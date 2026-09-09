<?php
/**
 * VENDOR-SIDE ACTIVATION KEY GENERATOR — Crown Clean Laundry
 * ============================================================
 * KEEP THIS FILE + private.pem OUTSIDE THE CUSTOMER DELIVERY.
 *
 * Usage:
 *   php make-activation-key.php <SYSTEM_ID>                    # 30-day key (default)
 *   php make-activation-key.php <SYSTEM_ID> --days 90          # 90-day key
 *   php make-activation-key.php <SYSTEM_ID> --days 0           # permanent key (no expiry)
 *
 * Output format: TIMESTAMP.base64(RSA-SHA1-SIGN("SYSTEM_ID:TIMESTAMP"))
 * The launcher writes this to .ccl. LicenseGuard reads the timestamp
 * from the file and verifies the signature — no brute-force needed.
 */

if ($argc < 2) {
    echo "Usage: php make-activation-key.php <SYSTEM_ID> [--days N]\n";
    exit(1);
}

$systemId = strtoupper(trim($argv[1]));
if (!preg_match('/^[A-F0-9]{32}$/', $systemId)) {
    echo "ERROR: SYSTEM_ID must be 32 hex chars.\n";
    exit(1);
}

$days = 30;
for ($i = 2; $i < $argc; $i++) {
    if ($argv[$i] === '--days' && isset($argv[$i + 1])) {
        $days = max(0, (int) $argv[$i + 1]);
    }
}

$keyFile = __DIR__ . '/private.pem';
if (!is_file($keyFile)) {
    echo "ERROR: private.pem not found.\n";
    exit(1);
}

$priv = file_get_contents($keyFile);
$res = openssl_get_privatekey($priv);

$expiryTimestamp = $days > 0 ? time() + ($days * 86400) : 0;
$payload = $systemId . ':' . $expiryTimestamp;

if (openssl_sign($payload, $signature, $res) === false) {
    echo "ERROR: signing failed: " . openssl_error_string() . "\n";
    exit(1);
}

$activationCode = $expiryTimestamp . '.' . base64_encode($signature);

echo "SYSTEM ID      : {$systemId}\n";
if ($days > 0) {
    echo "EXPIRES        : " . date('Y-m-d H:i:s', $expiryTimestamp) . " ({$days} days)\n";
} else {
    echo "EXPIRES        : NEVER (permanent license)\n";
}
echo "ACTIVATION CODE:\n";
echo $activationCode . "\n";
