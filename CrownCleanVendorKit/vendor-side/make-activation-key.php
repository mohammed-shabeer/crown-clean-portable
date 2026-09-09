<?php
/**
 * VENDOR-SIDE ACTIVATION KEY GENERATOR — Crown Clean Laundry
 * ============================================================
 * KEEP THIS FILE + private.pem OUTSIDE THE CUSTOMER DELIVERY.
 * Never ship private.pem to the customer.
 *
 * Usage:  php make-activation-key.php <SYSTEM_ID>
 * Output: a base64 activation code the customer pastes into the app.
 */

if ($argc < 2) {
    echo "Usage: php make-activation-key.php <SYSTEM_ID>\n";
    exit(1);
}

$systemId = strtoupper(trim($argv[1]));
if (!preg_match('/^[A-F0-9]{32}$/', $systemId)) {
    echo "ERROR: SYSTEM_ID must be the 32-hex-char value the app displays (e.g. 1A2B3C4D5E6F77889900AABBCCDDEEFF).\n";
    exit(1);
}

$keyFile = __DIR__ . '/private.pem';
if (!is_file($keyFile)) {
    echo "ERROR: private.pem not found next to this script.\n";
    exit(1);
}

$priv = file_get_contents($keyFile);
$res = openssl_get_privatekey($priv);
if ($priv === false) {
    echo "ERROR: cannot read private key.\n";
    exit(1);
}

if (openssl_sign($systemId, $signature, $res) === false) {
    echo "ERROR: signing failed: " . openssl_error_string() . "\n";
    exit(1);
}

echo "SYSTEM ID      : {$systemId}\n";
echo "ACTIVATION CODE:\n";
echo base64_encode($signature) . "\n";
