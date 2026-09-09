<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LicenseGuard
{
    const PUB_KEY = "-----BEGIN PUBLIC KEY-----\n"
        . "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuwZOZfmyCVfihJK6ZnkQ\n"
        . "iIa2ECrFSXS1iw5L6N13ecvMK9rEQpepJ4TqId9J0rRrRawqlXvcz51xa+99+B/H\n"
        . "xiTZzdK1/GnScXgts7PYw+xhQN8jnaFu+rJYwHJzhzzP1bS6t7gugg4IHt6Nk3wA\n"
        . "s9M6g6/X0t57Naq17U2PJrJCZZyogt4xT62S4yCCYFxIeGNozFidxuxX2g8VL2tr\n"
        . "Y2FO4GsI1LpJIFbjVYoHgD9juepRYYZ3WUChBzFj5y8lc6EWwnWIjf5DWZVhW0um\n"
        . "jv8H65w/NN+PJV/nTr4upt9EsKvehXGbw1qu/9xycMyylGGZ30jg9w99VkyfFnb3\n"
        . "lQIDAQAB\n"
        . "-----END PUBLIC KEY-----";

    const GRACE_PERIOD_DAYS = 30;

    public static function machineId(): string
    {
        try {
            $out = @shell_exec('reg query "HKLM\\SOFTWARE\\Microsoft\\Cryptography" /v MachineGuid 2>nul');
            if ($out !== null && preg_match('/MachineGuid\s+REG_SZ\s+(\S+)/i', $out, $m)) {
                return strtoupper(md5('CC-' . trim($m[1])));
            }
        } catch (\Throwable $e) {
        }
        $alt = gethostname() . '|' . php_uname('n') . '|' . getenv('COMPUTERNAME');
        return strtoupper(md5('CC-' . $alt));
    }

    public static function licenseFile(): string
    {
        return storage_path('app/.ccl');
    }

    /**
     * Parse the .ccl file.
     * New format: TIMESTAMP.base64_SIGNATURE
     * Legacy format: plain base64_SIGNATURE (old activation, treated as permanent)
     */
    private static function parseLicenseFile(): ?array
    {
        $f = self::licenseFile();
        if (!is_file($f)) {
            return null;
        }
        $content = @file_get_contents($f);
        if ($content === false || $content === '') {
            return null;
        }
        $content = trim($content);

        if (preg_match('/^(\d+)\.(.+)$/', $content, $m)) {
            $expiry = (int) $m[1];
            $raw = base64_decode($m[2], true);
            if ($raw === false) {
                return null;
            }
            return ['expiry' => $expiry, 'raw' => $raw];
        }

        $raw = base64_decode($content, true);
        if ($raw === false) {
            return null;
        }
        return ['expiry' => 0, 'raw' => $raw];
    }

    private static function verifySignature(int $expiry, string $raw): bool
    {
        $machineId = self::machineId();
        $payload = $machineId . ':' . $expiry;
        return openssl_verify($payload, $raw, self::PUB_KEY) === 1;
    }

    public static function getLicenseInfo(): array
    {
        $license = self::parseLicenseFile();
        if ($license === null) {
            return ['status' => 'none', 'expiry' => 0, 'days_remaining' => 0];
        }

        $expiry = $license['expiry'];
        $raw = $license['raw'];

        if (!self::verifySignature($expiry, $raw)) {
            if (openssl_verify(self::machineId(), $raw, self::PUB_KEY) === 1) {
                return ['status' => 'active', 'expiry' => 0, 'days_remaining' => 99999];
            }
            return ['status' => 'invalid', 'expiry' => 0, 'days_remaining' => 0];
        }

        if ($expiry === 0) {
            return ['status' => 'active', 'expiry' => 0, 'days_remaining' => 99999];
        }

        $now = time();
        $daysRemaining = (int) ceil(($expiry - $now) / 86400);

        if ($now < $expiry) {
            return ['status' => 'active', 'expiry' => $expiry, 'days_remaining' => $daysRemaining];
        }

        $graceExpiry = $expiry + (self::GRACE_PERIOD_DAYS * 86400);
        if ($now < $graceExpiry) {
            $graceDays = (int) ceil(($graceExpiry - $now) / 86400);
            return ['status' => 'grace', 'expiry' => $expiry, 'days_remaining' => $daysRemaining, 'grace_days' => $graceDays];
        }

        return ['status' => 'locked', 'expiry' => $expiry, 'days_remaining' => $daysRemaining];
    }

    public static function activated(): bool
    {
        $info = self::getLicenseInfo();
        return in_array($info['status'], ['active', 'grace'], true);
    }

    public static function isActive(): bool
    {
        return self::getLicenseInfo()['status'] === 'active';
    }

    public static function isGracePeriod(): bool
    {
        return self::getLicenseInfo()['status'] === 'grace';
    }

    public static function isLocked(): bool
    {
        return self::getLicenseInfo()['status'] === 'locked';
    }

    public static function activate(string $code): bool
    {
        $code = trim($code);

        if (preg_match('/^(\d+)\.(.+)$/', $code, $m)) {
            $expiry = (int) $m[1];
            $raw = base64_decode($m[2], true);
            if ($raw === false) {
                return false;
            }
            if (!self::verifySignature($expiry, $raw)) {
                return false;
            }
            @file_put_contents(self::licenseFile(), $code, LOCK_EX);
            return self::activated();
        }

        $raw = base64_decode($code, true);
        if ($raw === false) {
            return false;
        }
        $machineId = self::machineId();
        if (openssl_verify($machineId, $raw, self::PUB_KEY) !== 1) {
            return false;
        }
        @file_put_contents(self::licenseFile(), base64_encode($raw), LOCK_EX);
        return self::activated();
    }

    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();
        if (in_array($path, ['license-activate', 'livewire/update', 'up'], true)) {
            return $next($request);
        }

        $info = self::getLicenseInfo();

        if (in_array($info['status'], ['none', 'invalid', 'locked'], true)) {
            return redirect('/license-activate');
        }

        if ($info['status'] === 'grace') {
            $request->attributes->set('license_grace', true);
            $request->attributes->set('license_grace_days', $info['grace_days'] ?? 0);
        }

        return $next($request);
    }
}
