<?php
/**
 * Regenerates LicenseGuard.php with the public key from this kit.
 * Run: php refresh-guard-key.php
 */
$pubLines = explode("\n", trim(file_get_contents(__DIR__ . '/public.pem')));
array_shift($pubLines); // remove header
array_pop($pubLines);   // remove footer
$b64 = implode("\n", $pubLines);

$stub = file_get_contents(__DIR__ . '/guard-template.stub');

// Build the const PUB_KEY as concatenation lines
$const = '';
foreach ($pubLines as $i => $line) {
    $const .= '        . "' . $line . '\\n"' . "\n";
}

$stub = str_replace(
    "%%PUBKEY%%",
    '"-----BEGIN PUBLIC KEY-----\\n"' . "\n" . rtrim($const, "\n") . "\n" . '        . "-----END PUBLIC KEY-----"',
    $stub
);
file_put_contents('C:/CrownCleanLaundry/app/app/Http/Middleware/LicenseGuard.php', $stub);
echo "LicenseGuard refreshed.\n";
