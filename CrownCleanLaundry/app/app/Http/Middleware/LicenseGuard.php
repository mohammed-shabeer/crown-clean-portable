<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Application access guard.
 */
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

    public static function activated(): bool
    {
        $f = self::licenseFile();
        if (!is_file($f)) {
            return false;
        }
        $sig64 = @file_get_contents($f);
        if ($sig64 === false || $sig64 === '') {
            return false;
        }
        $raw = base64_decode($sig64, true);
        if ($raw === false) {
            return false;
        }
        return openssl_verify(self::machineId(), $raw, self::PUB_KEY) === 1;
    }

    public static function activate(string $sig64): bool
    {
        $raw = base64_decode(trim($sig64), true);
        if ($raw === false) {
            return false;
        }
        if (openssl_verify(self::machineId(), $raw, self::PUB_KEY) !== 1) {
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
        if (!self::activated()) {
            return redirect('/license-activate');
        }
        return $next($request);
    }
}
