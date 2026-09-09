<?php

namespace App\Livewire\Components;

use Livewire\Component;

class LicenseGraceBanner extends Component
{
    public function render()
    {
        $info = \App\Http\Middleware\LicenseGuard::getLicenseInfo();

        if ($info['status'] !== 'grace') {
            return '';
        }

        $graceDays = $info['grace_days'] ?? 0;
        $expiryDate = $info['expiry'] > 0 ? date('Y-m-d', $info['expiry']) : '';

        return <<<'BLADE'
        <div style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white; padding: 12px 20px; text-align: center; font-weight: 500; position: sticky; top: 0; z-index: 9999;">
            <span>⚠️ License expired on {{ $expiryDate }}. Contact vendor to renew. Features restricted — {{ $graceDays }} days until full lock.</span>
        </div>
        BLADE;
    }
}
