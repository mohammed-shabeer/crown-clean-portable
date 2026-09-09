<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\MasterSettings;
use App\Models\Translation;
use Livewire\Attributes\Title;

class PrintSettings extends Component
{
    // Bill Element Settings
    public $print_show_addon = true;
    public $print_show_subtotal = true;
    public $print_show_notes = true;
    public $print_show_tax = true;
    public $print_show_discount = true;
    public $print_show_gross_total = true;
    public $print_font_size = 14;
    
    // Printer Configuration Settings
    public $printer_name = '';
    public $paper_size = 'A4';
    public $paper_width = 210; // mm for custom size
    public $paper_height = 297; // mm for custom size
    public $orientation = 'portrait';
    public $margin_top = 10; // mm
    public $margin_bottom = 10; // mm
    public $margin_left = 10; // mm
    public $margin_right = 10; // mm
    public $copies = 1;
    public $auto_print = false; // Skip print dialog
    public $print_header_footer = false;
    public $color_mode = 'monochrome'; // monochrome or color
    public $scale = 100; // percentage
    
    public $lang;
    public $availablePrinters = [];

    #[Title('Print Settings')]
    public function render()
    {
        return view('livewire.settings.print-settings');
    }

    public function mount()
    {
        if(!\Illuminate\Support\Facades\Gate::allows('setting_view')){
            abort(404);
        }
        
        $this->loadSettings();
        
        if (session()->has('selected_language')) {
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $this->lang = Translation::where('default', 1)->first();
        }
    }

    public function loadSettings()
    {
        $settings = new MasterSettings();
        $site = $settings->siteData();

        // Bill Element Settings
        $this->print_show_addon = isset($site['print_show_addon']) ? (bool)$site['print_show_addon'] : true;
        $this->print_show_subtotal = isset($site['print_show_subtotal']) ? (bool)$site['print_show_subtotal'] : true;
        $this->print_show_notes = isset($site['print_show_notes']) ? (bool)$site['print_show_notes'] : true;
        $this->print_show_tax = isset($site['print_show_tax']) ? (bool)$site['print_show_tax'] : true;
        $this->print_show_discount = isset($site['print_show_discount']) ? (bool)$site['print_show_discount'] : true;
        $this->print_show_gross_total = isset($site['print_show_gross_total']) ? (bool)$site['print_show_gross_total'] : true;
        $this->print_font_size = isset($site['print_font_size']) ? (int)$site['print_font_size'] : 14;
        
        // Printer Configuration Settings
        $this->printer_name = $site['printer_name'] ?? '';
        $this->paper_size = $site['paper_size'] ?? 'A4';
        $this->paper_width = isset($site['paper_width']) ? (int)$site['paper_width'] : 210;
        $this->paper_height = isset($site['paper_height']) ? (int)$site['paper_height'] : 297;
        $this->orientation = $site['print_orientation'] ?? 'portrait';
        $this->margin_top = isset($site['margin_top']) ? (int)$site['margin_top'] : 10;
        $this->margin_bottom = isset($site['margin_bottom']) ? (int)$site['margin_bottom'] : 10;
        $this->margin_left = isset($site['margin_left']) ? (int)$site['margin_left'] : 10;
        $this->margin_right = isset($site['margin_right']) ? (int)$site['margin_right'] : 10;
        $this->copies = isset($site['print_copies']) ? (int)$site['print_copies'] : 1;
        $this->auto_print = isset($site['auto_print']) ? (bool)$site['auto_print'] : false;
        $this->print_header_footer = isset($site['print_header_footer']) ? (bool)$site['print_header_footer'] : false;
        $this->color_mode = $site['color_mode'] ?? 'monochrome';
        $this->scale = isset($site['print_scale']) ? (int)$site['print_scale'] : 100;
    }

    public function save()
    {
        $settingsToSave = [
            // Bill Element Settings
            'print_show_addon' => $this->print_show_addon ? '1' : '0',
            'print_show_subtotal' => $this->print_show_subtotal ? '1' : '0',
            'print_show_notes' => $this->print_show_notes ? '1' : '0',
            'print_show_tax' => $this->print_show_tax ? '1' : '0',
            'print_show_discount' => $this->print_show_discount ? '1' : '0',
            'print_show_gross_total' => $this->print_show_gross_total ? '1' : '0',
            'print_font_size' => (string)$this->print_font_size,
            
            // Printer Configuration Settings
            'printer_name' => $this->printer_name,
            'paper_size' => $this->paper_size,
            'paper_width' => (string)$this->paper_width,
            'paper_height' => (string)$this->paper_height,
            'print_orientation' => $this->orientation,
            'margin_top' => (string)$this->margin_top,
            'margin_bottom' => (string)$this->margin_bottom,
            'margin_left' => (string)$this->margin_left,
            'margin_right' => (string)$this->margin_right,
            'print_copies' => (string)$this->copies,
            'auto_print' => $this->auto_print ? '1' : '0',
            'print_header_footer' => $this->print_header_footer ? '1' : '0',
            'color_mode' => $this->color_mode,
            'print_scale' => (string)$this->scale,
        ];

        foreach ($settingsToSave as $title => $value) {
            MasterSettings::updateOrCreate(
                ['master_title' => $title],
                ['master_value' => $value]
            );
        }

        $this->dispatch(
            'alert',
            ['type' => 'success', 'message' => $this->lang->data['settings_saved'] ?? 'Print settings saved successfully!']
        );
    }

    public function toggleSetting($setting)
    {
        $this->{$setting} = !$this->{$setting};
    }
    
    public function savePrinterName($printerName)
    {
        $this->printer_name = $printerName;
    }
    
    public function resetPrinterSettings()
    {
        $this->printer_name = '';
        $this->paper_size = 'A4';
        $this->paper_width = 210;
        $this->paper_height = 297;
        $this->orientation = 'portrait';
        $this->margin_top = 10;
        $this->margin_bottom = 10;
        $this->margin_left = 10;
        $this->margin_right = 10;
        $this->copies = 1;
        $this->auto_print = false;
        $this->print_header_footer = false;
        $this->color_mode = 'monochrome';
        $this->scale = 100;
    }
}
