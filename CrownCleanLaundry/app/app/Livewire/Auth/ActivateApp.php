<?php

namespace App\Livewire\Auth;

use App\Http\Middleware\LicenseGuard;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class ActivateApp extends Component
{
    public $machine_id = '';
    public $activation_code = '';
    public $activated = false;

    #[Layout('components.layouts.base'), Title('Activate')]
    public function render()
    {
        $this->machine_id = LicenseGuard::machineId();
        $this->activated = LicenseGuard::activated();
        return view('livewire.auth.activate-app');
    }

    public function activate()
    {
        $this->validate([
            'activation_code' => 'required|string',
        ]);
        if (LicenseGuard::activate($this->activation_code)) {
            return $this->redirect('/');
        }
        $this->addError('activation_code', 'Invalid activation code for this system.');
    }
}
