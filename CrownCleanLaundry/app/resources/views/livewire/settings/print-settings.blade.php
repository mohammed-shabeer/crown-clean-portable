<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">{{ $lang->data['print_settings'] ?? 'Print Settings' }}</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    {{ $lang->data['dashboard'] ?? 'Dashboard' }}
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">{{ $lang->data['print_settings'] ?? 'Print Settings' }}</li>
        </ul>
    </div>

    <div class="row gy-4">
        <div class="col-lg-8">
            <!-- Printer Configuration Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <iconify-icon icon="mdi:printer-settings" class="text-xl me-2"></iconify-icon>
                        {{ $lang->data['printer_configuration'] ?? 'Printer Configuration' }}
                    </h6>
                    <p class="text-sm text-secondary-light mb-0">{{ $lang->data['printer_config_desc'] ?? 'Configure your printer settings for automatic printing' }}</p>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Printer Selection -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">{{ $lang->data['printer_name'] ?? 'Printer Name' }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" wire:model="printer_name" 
                                    placeholder="{{ $lang->data['enter_printer_name'] ?? 'Enter printer name or select from detected' }}" id="printerNameInput">
                                <button class="btn btn-outline-primary" type="button" onclick="detectPrinters()">
                                    <iconify-icon icon="mdi:magnify" class="text-lg"></iconify-icon>
                                    {{ $lang->data['detect'] ?? 'Detect' }}
                                </button>
                            </div>
                            <small class="text-secondary-light">{{ $lang->data['printer_name_hint'] ?? 'Leave empty to use system default printer' }}</small>
                            <div id="detectedPrinters" class="mt-2" style="display: none;">
                                <label class="form-label text-sm">{{ $lang->data['detected_printers'] ?? 'Detected Printers' }}:</label>
                                <select class="form-select form-select-sm" id="printerSelect" onchange="selectPrinter(this.value)">
                                    <option value="">{{ $lang->data['select_printer'] ?? 'Select a printer...' }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Paper Size -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">{{ $lang->data['paper_size'] ?? 'Paper Size' }}</label>
                            <select class="form-select" wire:model.live="paper_size">
                                <option value="A4">A4 (210 × 297 mm)</option>
                                <option value="A5">A5 (148 × 210 mm)</option>
                                <option value="Letter">Letter (216 × 279 mm)</option>
                                <option value="Legal">Legal (216 × 356 mm)</option>
                                <option value="80mm">{{ $lang->data['thermal_80mm'] ?? 'Thermal 80mm' }} (80 × 297 mm)</option>
                                <option value="58mm">{{ $lang->data['thermal_58mm'] ?? 'Thermal 58mm' }} (58 × 297 mm)</option>
                                <option value="custom">{{ $lang->data['custom_size'] ?? 'Custom Size' }}</option>
                            </select>
                        </div>

                        <!-- Custom Paper Size -->
                        @if($paper_size === 'custom')
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">{{ $lang->data['paper_width'] ?? 'Paper Width' }} (mm)</label>
                            <input type="number" class="form-control" wire:model="paper_width" min="20" max="500">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">{{ $lang->data['paper_height'] ?? 'Paper Height' }} (mm)</label>
                            <input type="number" class="form-control" wire:model="paper_height" min="20" max="1000">
                        </div>
                        @endif

                        <!-- Orientation -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">{{ $lang->data['orientation'] ?? 'Orientation' }}</label>
                            <select class="form-select" wire:model="orientation">
                                <option value="portrait">{{ $lang->data['portrait'] ?? 'Portrait' }}</option>
                                <option value="landscape">{{ $lang->data['landscape'] ?? 'Landscape' }}</option>
                            </select>
                        </div>

                        <!-- Color Mode -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">{{ $lang->data['color_mode'] ?? 'Color Mode' }}</label>
                            <select class="form-select" wire:model="color_mode">
                                <option value="monochrome">{{ $lang->data['monochrome'] ?? 'Black & White' }}</option>
                                <option value="color">{{ $lang->data['color'] ?? 'Color' }}</option>
                            </select>
                        </div>

                        <!-- Margins -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">{{ $lang->data['margins'] ?? 'Margins' }} (mm)</label>
                            <div class="row g-2">
                                <div class="col-3">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">{{ $lang->data['top'] ?? 'Top' }}</span>
                                        <input type="number" class="form-control" wire:model="margin_top" min="0" max="50">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">{{ $lang->data['bottom'] ?? 'Bottom' }}</span>
                                        <input type="number" class="form-control" wire:model="margin_bottom" min="0" max="50">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">{{ $lang->data['left'] ?? 'Left' }}</span>
                                        <input type="number" class="form-control" wire:model="margin_left" min="0" max="50">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">{{ $lang->data['right'] ?? 'Right' }}</span>
                                        <input type="number" class="form-control" wire:model="margin_right" min="0" max="50">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Copies -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">{{ $lang->data['copies'] ?? 'Copies' }}</label>
                            <input type="number" class="form-control" wire:model="copies" min="1" max="10">
                        </div>

                        <!-- Scale -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">{{ $lang->data['scale'] ?? 'Scale' }} (%)</label>
                            <input type="number" class="form-control" wire:model="scale" min="50" max="200">
                        </div>

                        <!-- Auto Print Toggle -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold d-block">{{ $lang->data['auto_print'] ?? 'Auto Print' }}</label>
                            <div class="form-switch switch-primary mt-2">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                    wire:model="auto_print" id="toggleAutoPrint">
                                <label class="form-check-label ms-2" for="toggleAutoPrint">
                                    {{ $lang->data['skip_print_dialog'] ?? 'Skip print dialog' }}
                                </label>
                            </div>
                        </div>

                        <!-- Print Header/Footer Toggle -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between p-3 border radius-8 bg-base">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="w-40-px h-40-px bg-info-100 text-info-600 d-flex justify-content-center align-items-center radius-8">
                                        <iconify-icon icon="mdi:page-layout-header-footer" class="text-xl"></iconify-icon>
                                    </div>
                                    <div>
                                        <h6 class="text-md mb-0">{{ $lang->data['header_footer'] ?? 'Headers & Footers' }}</h6>
                                        <span class="text-sm text-secondary-light">{{ $lang->data['print_page_info'] ?? 'Print page URL and date' }}</span>
                                    </div>
                                </div>
                                <div class="form-switch switch-info">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                        wire:model="print_header_footer" id="toggleHeaderFooter">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Reset Button -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between p-3 border radius-8 bg-base h-100">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="w-40-px h-40-px bg-warning-100 text-warning-600 d-flex justify-content-center align-items-center radius-8">
                                        <iconify-icon icon="mdi:restore" class="text-xl"></iconify-icon>
                                    </div>
                                    <div>
                                        <h6 class="text-md mb-0">{{ $lang->data['reset_settings'] ?? 'Reset Settings' }}</h6>
                                        <span class="text-sm text-secondary-light">{{ $lang->data['reset_to_default'] ?? 'Reset to default values' }}</span>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-warning btn-sm" wire:click="resetPrinterSettings">
                                    <iconify-icon icon="mdi:restore" class="text-lg"></iconify-icon>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Info Alert -->
                    <div class="alert alert-info mt-3 mb-0 d-flex align-items-start gap-3">
                        <iconify-icon icon="mdi:information" class="text-xl flex-shrink-0 mt-1"></iconify-icon>
                        <div>
                            <strong>{{ $lang->data['note'] ?? 'Note' }}:</strong>
                            {{ $lang->data['auto_print_note'] ?? 'When "Auto Print" is enabled, bills will print directly without showing the print dialog. Make sure your printer is properly configured. For thermal printers, select the appropriate paper size (80mm or 58mm).' }}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">{{ $lang->data['bill_elements'] ?? 'Bill Elements' }}</h6>
                    <p class="text-sm text-secondary-light mb-0">{{ $lang->data['toggle_bill_elements_desc'] ?? 'Toggle which elements appear on printed bills' }}</p>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Addon Toggle -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between p-3 border radius-8 bg-base">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="w-40-px h-40-px bg-primary-100 text-primary-600 d-flex justify-content-center align-items-center radius-8">
                                        <iconify-icon icon="mdi:package-variant" class="text-xl"></iconify-icon>
                                    </div>
                                    <div>
                                        <h6 class="text-md mb-0">{{ $lang->data['addon'] ?? 'Addon' }}</h6>
                                        <span class="text-sm text-secondary-light">{{ $lang->data['show_addon_on_bill'] ?? 'Show addon on bill' }}</span>
                                    </div>
                                </div>
                                <div class="form-switch switch-primary">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                        wire:model.live="print_show_addon" id="toggleAddon">
                                </div>
                            </div>
                        </div>

                        <!-- Sub Total Toggle -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between p-3 border radius-8 bg-base">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="w-40-px h-40-px bg-success-100 text-success-600 d-flex justify-content-center align-items-center radius-8">
                                        <iconify-icon icon="mdi:calculator" class="text-xl"></iconify-icon>
                                    </div>
                                    <div>
                                        <h6 class="text-md mb-0">{{ $lang->data['sub_total'] ?? 'Sub Total' }}</h6>
                                        <span class="text-sm text-secondary-light">{{ $lang->data['show_subtotal_on_bill'] ?? 'Show sub total on bill' }}</span>
                                    </div>
                                </div>
                                <div class="form-switch switch-success">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                        wire:model.live="print_show_subtotal" id="toggleSubtotal">
                                </div>
                            </div>
                        </div>

                        <!-- Notes Toggle -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between p-3 border radius-8 bg-base">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="w-40-px h-40-px bg-info-100 text-info-600 d-flex justify-content-center align-items-center radius-8">
                                        <iconify-icon icon="mdi:note-text" class="text-xl"></iconify-icon>
                                    </div>
                                    <div>
                                        <h6 class="text-md mb-0">{{ $lang->data['notes'] ?? 'Notes' }}</h6>
                                        <span class="text-sm text-secondary-light">{{ $lang->data['show_notes_on_bill'] ?? 'Show notes on bill' }}</span>
                                    </div>
                                </div>
                                <div class="form-switch switch-info">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                        wire:model.live="print_show_notes" id="toggleNotes">
                                </div>
                            </div>
                        </div>

                        <!-- Tax Toggle -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between p-3 border radius-8 bg-base">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="w-40-px h-40-px bg-warning-100 text-warning-600 d-flex justify-content-center align-items-center radius-8">
                                        <iconify-icon icon="mdi:percent" class="text-xl"></iconify-icon>
                                    </div>
                                    <div>
                                        <h6 class="text-md mb-0">{{ $lang->data['tax'] ?? 'Tax' }}</h6>
                                        <span class="text-sm text-secondary-light">{{ $lang->data['show_tax_on_bill'] ?? 'Show tax on bill' }}</span>
                                    </div>
                                </div>
                                <div class="form-switch switch-warning">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                        wire:model.live="print_show_tax" id="toggleTax">
                                </div>
                            </div>
                        </div>

                        <!-- Discount Toggle -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between p-3 border radius-8 bg-base">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="w-40-px h-40-px bg-danger-100 text-danger-600 d-flex justify-content-center align-items-center radius-8">
                                        <iconify-icon icon="mdi:tag-outline" class="text-xl"></iconify-icon>
                                    </div>
                                    <div>
                                        <h6 class="text-md mb-0">{{ $lang->data['discount'] ?? 'Discount' }}</h6>
                                        <span class="text-sm text-secondary-light">{{ $lang->data['show_discount_on_bill'] ?? 'Show discount on bill' }}</span>
                                    </div>
                                </div>
                                <div class="form-switch switch-danger">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                        wire:model.live="print_show_discount" id="toggleDiscount">
                                </div>
                            </div>
                        </div>

                        <!-- Gross Total Toggle -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between p-3 border radius-8 bg-base">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="w-40-px h-40-px bg-purple-100 text-purple-600 d-flex justify-content-center align-items-center radius-8">
                                        <iconify-icon icon="mdi:cash-multiple" class="text-xl"></iconify-icon>
                                    </div>
                                    <div>
                                        <h6 class="text-md mb-0">{{ $lang->data['gross_total'] ?? 'Gross Total' }}</h6>
                                        <span class="text-sm text-secondary-light">{{ $lang->data['show_gross_total_on_bill'] ?? 'Show gross total on bill' }}</span>
                                    </div>
                                </div>
                                <div class="form-switch switch-primary">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                        wire:model.live="print_show_gross_total" id="toggleGrossTotal">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Font Size Settings -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">{{ $lang->data['font_settings'] ?? 'Font Settings' }}</h6>
                    <p class="text-sm text-secondary-light mb-0">{{ $lang->data['font_settings_desc'] ?? 'Customize the font size for printed bills' }}</p>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">{{ $lang->data['font_size'] ?? 'Font Size' }} (px)</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="range" class="form-range flex-grow-1" min="10" max="24" step="1" 
                                    wire:model.live="print_font_size" id="fontSizeRange">
                                <span class="badge bg-primary-100 text-primary-600 px-16 py-8 radius-4">
                                    {{ $print_font_size }}px
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">{{ $lang->data['preview'] ?? 'Preview' }}</label>
                            <div class="border radius-8 p-3 bg-neutral-50">
                                <p class="mb-0" style="font-size: {{ $print_font_size }}px;">
                                    {{ $lang->data['sample_text'] ?? 'This is how your bill text will appear' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button wire:click="save" class="btn btn-primary px-32">
                    <iconify-icon icon="mdi:content-save" class="text-lg me-1"></iconify-icon>
                    {{ $lang->data['save_settings'] ?? 'Save Settings' }}
                </button>
            </div>
        </div>

        <!-- Preview Panel -->
        <div class="col-lg-4">
            <div class="card position-sticky" style="top: 100px;">
                <div class="card-header">
                    <h6 class="card-title mb-0">{{ $lang->data['bill_preview'] ?? 'Bill Preview' }}</h6>
                </div>
                <div class="card-body">
                    <div class="border radius-8 p-3 bg-white" style="font-size: {{ $print_font_size }}px;">
                        <div class="text-center mb-3">
                            <h6 class="fw-bold">{{ getApplicationName() }}</h6>
                            <small class="text-secondary-light">{{ $lang->data['tax_invoice'] ?? 'Tax Invoice' }}</small>
                        </div>
                        <hr>
                        <div class="mb-2">
                            <small><strong>{{ $lang->data['order'] ?? 'Order' }} #:</strong> ORD-0001</small><br>
                            <small><strong>{{ $lang->data['date'] ?? 'Date' }}:</strong> {{ now()->format('l, d/m/Y h:i A') }}</small>
                        </div>
                        <hr>
                        <div class="mb-2">
                            <small>1x Service Item - {{ getCurrency() }} 10.000</small>
                        </div>
                        <hr>
                        @if($print_show_subtotal)
                        <div class="d-flex justify-content-between mb-1">
                            <small>{{ $lang->data['sub_total'] ?? 'Sub Total' }}</small>
                            <small>{{ getCurrency() }} 10.000</small>
                        </div>
                        @endif
                        @if($print_show_addon)
                        <div class="d-flex justify-content-between mb-1">
                            <small>{{ $lang->data['addon'] ?? 'Addon' }}</small>
                            <small>{{ getCurrency() }} 2.000</small>
                        </div>
                        @endif
                        @if($print_show_discount)
                        <div class="d-flex justify-content-between mb-1">
                            <small>{{ $lang->data['discount'] ?? 'Discount' }}</small>
                            <small>{{ getCurrency() }} 1.000</small>
                        </div>
                        @endif
                        @if($print_show_tax)
                        <div class="d-flex justify-content-between mb-1">
                            <small>{{ $lang->data['tax'] ?? 'Tax' }} (10%)</small>
                            <small>{{ getCurrency() }} 1.100</small>
                        </div>
                        @endif
                        @if($print_show_gross_total)
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <small>{{ $lang->data['gross_total'] ?? 'Gross Total' }}</small>
                            <small>{{ getCurrency() }} 12.100</small>
                        </div>
                        @endif
                        @if($print_show_notes)
                        <hr>
                        <div class="mb-1">
                            <small><strong>{{ $lang->data['notes'] ?? 'Notes' }}:</strong> Sample note text</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Store detected printers in localStorage for persistence
    let detectedPrinters = JSON.parse(localStorage.getItem('detectedPrinters') || '[]');
    
    // Function to detect printers (simulated - browser cannot directly enumerate printers)
    function detectPrinters() {
        const detectBtn = document.querySelector('[onclick="detectPrinters()"]');
        const printerSelect = document.getElementById('printerSelect');
        const detectedPrintersDiv = document.getElementById('detectedPrinters');
        
        // Show loading state
        detectBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> {{ $lang->data["detecting"] ?? "Detecting..." }}';
        detectBtn.disabled = true;
        
        // Note: Browsers cannot directly enumerate printers due to security restrictions
        // We provide a workaround using common printer detection methods
        
        setTimeout(() => {
            // Reset button
            detectBtn.innerHTML = '<iconify-icon icon="mdi:magnify" class="text-lg"></iconify-icon> {{ $lang->data["detect"] ?? "Detect" }}';
            detectBtn.disabled = false;
            
            // Show the printer selection area
            detectedPrintersDiv.style.display = 'block';
            
            // Clear existing options
            printerSelect.innerHTML = '<option value="">{{ $lang->data["select_printer"] ?? "Select a printer..." }}</option>';
            
            // Add common printer options based on saved printers and common names
            const commonPrinters = [
                { name: 'Default System Printer', value: '' },
                { name: 'Microsoft Print to PDF', value: 'Microsoft Print to PDF' },
                ...detectedPrinters.map(p => ({ name: p, value: p }))
            ];
            
            // Add option to add custom printer
            const optgroup = document.createElement('optgroup');
            optgroup.label = '{{ $lang->data["available_printers"] ?? "Available Printers" }}';
            
            commonPrinters.forEach(printer => {
                const option = document.createElement('option');
                option.value = printer.value;
                option.textContent = printer.name;
                optgroup.appendChild(option);
            });
            
            printerSelect.appendChild(optgroup);
            
            // Add custom printer option
            const customOptgroup = document.createElement('optgroup');
            customOptgroup.label = '{{ $lang->data["custom"] ?? "Custom" }}';
            const customOption = document.createElement('option');
            customOption.value = '__custom__';
            customOption.textContent = '{{ $lang->data["add_custom_printer"] ?? "+ Add Custom Printer" }}';
            customOptgroup.appendChild(customOption);
            printerSelect.appendChild(customOptgroup);
            
            // Show info alert
            showAlert('info', '{{ $lang->data["printer_detect_info"] ?? "Printer detection is limited in browsers. Enter your printer name manually if not listed, or use the system print dialog to see all available printers." }}');
        }, 1000);
    }
    
    // Function to select a printer
    function selectPrinter(value) {
        const printerInput = document.getElementById('printerNameInput');
        
        if (value === '__custom__') {
            // Prompt for custom printer name
            const customName = prompt('{{ $lang->data["enter_printer_name_prompt"] ?? "Enter your printer name exactly as it appears in system settings:" }}');
            if (customName && customName.trim()) {
                printerInput.value = customName.trim();
                // Save to detected printers for future use
                if (!detectedPrinters.includes(customName.trim())) {
                    detectedPrinters.push(customName.trim());
                    localStorage.setItem('detectedPrinters', JSON.stringify(detectedPrinters));
                }
                // Trigger Livewire update
                printerInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
        } else {
            printerInput.value = value;
            // Trigger Livewire update
            printerInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }
    
    // Show alert function
    function showAlert(type, message) {
        // Use existing alert system if available
        if (typeof Livewire !== 'undefined') {
            Livewire.dispatch('alert', { type: type, message: message });
        } else {
            alert(message);
        }
    }
    
    // Test print function
    function testPrint() {
        const printerConfig = {
            printerName: document.getElementById('printerNameInput')?.value || '',
            paperSize: '{{ $paper_size }}',
            orientation: '{{ $orientation }}',
            copies: {{ $copies }},
            autoPrint: {{ $auto_print ? 'true' : 'false' }}
        };
        
        // Create a test print content
        const testContent = `
            <div style="padding: 20px; font-family: Arial, sans-serif;">
                <h2 style="text-align: center;">Print Test</h2>
                <p style="text-align: center;">This is a test print from {{ getApplicationName() }}</p>
                <hr>
                <p><strong>Printer:</strong> ${printerConfig.printerName || 'Default'}</p>
                <p><strong>Paper Size:</strong> ${printerConfig.paperSize}</p>
                <p><strong>Orientation:</strong> ${printerConfig.orientation}</p>
                <p><strong>Copies:</strong> ${printerConfig.copies}</p>
                <hr>
                <p style="text-align: center; font-size: 12px;">If you can read this, your printer is working correctly!</p>
            </div>
        `;
        
        // Open print window
        const printWindow = window.open('', '_blank', 'width=400,height=600');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Print Test</title>
                <style>
                    @page {
                        size: ${printerConfig.paperSize} ${printerConfig.orientation};
                        margin: 10mm;
                    }
                    @media print {
                        body { margin: 0; }
                    }
                </style>
            </head>
            <body>${testContent}</body>
            </html>
        `);
        printWindow.document.close();
        
        if (printerConfig.autoPrint) {
            printWindow.onload = function() {
                printWindow.print();
                printWindow.onafterprint = function() {
                    printWindow.close();
                };
            };
        } else {
            printWindow.print();
        }
    }
</script>