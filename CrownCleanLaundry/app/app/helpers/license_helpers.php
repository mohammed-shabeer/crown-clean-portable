<?php

if (!function_exists('licenseStatus')) {
    function licenseStatus(): string
    {
        return \App\Http\Middleware\LicenseGuard::getLicenseInfo()['status'];
    }
}

if (!function_exists('licenseIsActive')) {
    function licenseIsActive(): bool
    {
        return \App\Http\Middleware\LicenseGuard::isActive();
    }
}

if (!function_exists('licenseIsGrace')) {
    function licenseIsGrace(): bool
    {
        return \App\Http\Middleware\LicenseGuard::isGracePeriod();
    }
}

if (!function_exists('canAccessReports')) {
    function canAccessReports(): bool
    {
        return \App\Http\Middleware\LicenseGuard::isActive();
    }
}

if (!function_exists('canExport')) {
    function canExport(): bool
    {
        return \App\Http\Middleware\LicenseGuard::isActive();
    }
}
