<div x-data="{ showCart: false }">
    {{-- Header Bar --}}
    <div class="tw-w-full tw-bg-white tw-flex tw-justify-between tw-items-center tw-shadow-sm tw-px-3 tw-py-2">
        <div class="tw-flex tw-gap-2 tw-items-center">
            <img src="{{ asset('assets/images/laundry_icon.png') }}" alt="Logo" style="height: 32px; width: auto;">
            <div>
                <div class="tw-text-sm tw-font-bold">{{ getApplicationName() }}</div>
                <div class="tw-text-xs tw-text-gray-500">{{ $lang->data['self_service_order'] ?? 'Self-Service Order' }}</div>
            </div>
        </div>
        <div class="tw-flex tw-items-center tw-gap-2">
            <div class="tw-text-sm">{{ $lang->data['order'] ?? 'Order' }}: <span class="tw-font-bold">#{{ $order_id }}</span></div>
            @if($step == 2)
            <button @click="showCart = !showCart" class="btn btn-primary btn-sm lg:tw-hidden tw-relative">
                <i class="ri-shopping-cart-line"></i> {{ $lang->data['cart'] ?? 'Cart' }}
                @if(count($cart_items) > 0)
                <span class="badge bg-danger tw-absolute" style="top: -8px; right: -8px;">{{ count($cart_items) }}</span>
                @endif
            </button>
            @endif
        </div>
    </div>

    {{-- Progress Steps --}}
    <div class="tw-w-full tw-bg-white tw-border-t tw-py-2 tw-px-4">
        <div class="tw-flex tw-items-center tw-justify-center tw-gap-2">
            <div class="tw-flex tw-items-center tw-gap-1.5">
                <span class="badge {{ $step >= 1 ? 'bg-primary' : 'bg-secondary' }}" style="width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">1</span>
                <span class="tw-text-xs tw-font-medium tw-hidden sm:tw-inline">{{ $lang->data['details'] ?? 'Details' }}</span>
            </div>
            <div style="width: 40px; height: 2px; background: {{ $step >= 2 ? 'var(--bs-primary)' : '#dee2e6' }};"></div>
            <div class="tw-flex tw-items-center tw-gap-1.5">
                <span class="badge {{ $step >= 2 ? 'bg-primary' : 'bg-secondary' }}" style="width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">2</span>
                <span class="tw-text-xs tw-font-medium tw-hidden sm:tw-inline">{{ $lang->data['services'] ?? 'Services' }}</span>
            </div>
            <div style="width: 40px; height: 2px; background: {{ $step >= 3 ? '#198754' : '#dee2e6' }};"></div>
            <div class="tw-flex tw-items-center tw-gap-1.5">
                <span class="badge {{ $step >= 3 ? 'bg-success' : 'bg-secondary' }}" style="width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">3</span>
                <span class="tw-text-xs tw-font-medium tw-hidden sm:tw-inline">{{ $lang->data['done'] ?? 'Done' }}</span>
            </div>
        </div>
    </div>

    @if($step == 1)
    {{-- Step 1: Customer Details --}}
    <div class="tw-p-4">
        <div class="tw-max-w-lg tw-mx-auto">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="tw-text-center tw-mb-4">
                        <div style="width: 56px; height: 56px; background: #dbeafe; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px auto;">
                            <i class="ri-user-line" style="font-size: 24px; color: #2563eb;"></i>
                        </div>
                        <h5 class="tw-font-bold">{{ $lang->data['welcome'] ?? 'Welcome!' }}</h5>
                        <p class="tw-text-gray-500 tw-text-sm tw-mb-0">{{ $lang->data['enter_details_to_continue'] ?? 'Please enter your details to continue' }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ $lang->data['full_name'] ?? 'Full Name' }} <span class="text-danger">*</span></label>
                        <input type="text" wire:model="customer_name" class="form-control @error('customer_name') is-invalid @enderror"
                               placeholder="{{ $lang->data['enter_your_name'] ?? 'Enter your name' }}">
                        @error('customer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ $lang->data['phone_number'] ?? 'Phone Number' }} <span class="text-danger">*</span></label>
                        <input type="tel" wire:model="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror"
                               placeholder="{{ $lang->data['enter_phone'] ?? 'Enter your phone number' }}">
                        @error('customer_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ $lang->data['email'] ?? 'Email' }} <span class="text-muted">({{ $lang->data['optional'] ?? 'Optional' }})</span></label>
                        <input type="email" wire:model="customer_email" class="form-control"
                               placeholder="{{ $lang->data['enter_email'] ?? 'Enter your email' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ $lang->data['address'] ?? 'Address' }} <span class="text-muted">({{ $lang->data['optional'] ?? 'Optional' }})</span></label>
                        <textarea wire:model="customer_address" rows="2" class="form-control"
                                  placeholder="{{ $lang->data['enter_address'] ?? 'Enter your address' }}"></textarea>
                    </div>

                    <button wire:click="goToStep(2)" class="btn btn-primary w-100">
                        {{ $lang->data['continue_to_services'] ?? 'Continue to Services' }}
                        <i class="ri-arrow-right-line ms-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @elseif($step == 2)
    {{-- Step 2: Select Services --}}
    <div class="tw-w-full tw-flex lg:tw-flex-row tw-flex-col tw-relative" style="height: calc(100vh - 90px);">
        {{-- Services Section --}}
        <div class="lg:tw-w-1/2 tw-w-full tw-p-2 tw-bg-white tw-overflow-y-auto" style="height: calc(100vh - 90px);">
            {{-- Customer Info Summary --}}
            <div class="alert alert-light tw-flex tw-items-center tw-justify-between tw-mb-2 tw-py-2">
                <div class="tw-flex tw-items-center tw-gap-2">
                    <div style="width: 32px; height: 32px; background: var(--bs-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 12px;">
                        {{ strtoupper(substr($customer_name ?? 'C', 0, 1)) }}
                    </div>
                    <div>
                        <div class="tw-font-medium tw-text-sm">{{ $customer_name }}</div>
                        <div class="tw-text-xs tw-text-gray-500">{{ $customer_phone }}</div>
                    </div>
                </div>
                <button wire:click="goToStep(1)" class="btn btn-sm btn-outline-primary">
                    {{ $lang->data['edit'] ?? 'Edit' }}
                </button>
            </div>

            {{-- Search --}}
            <div class="input-group mb-2">
                <span class="input-group-text"><i class="ri-search-line"></i></span>
                <input type="text" class="form-control" wire:model.live="search_query"
                    placeholder="{{ $lang->data['search_here'] ?? 'Search Here' }}">
            </div>

            {{-- Services Grid --}}
            <div class="tw-grid tw-grid-cols-2 sm:tw-grid-cols-3 lg:tw-grid-cols-4 tw-gap-2">
                @foreach ($services as $item)
                <a type="button" class="text-decoration-none" data-bs-toggle="modal"
                    data-bs-target="#serviceTypeModal" wire:click="selectService({{ $item->id }})">
                    <div class="card mb-0 h-100">
                        <div class="card-body p-2 text-center">
                            <img src="{{ asset('assets/img/service-icons/' . $item->icon) }}"
                                style="height: 48px; width: 48px; object-fit: contain; margin: 0 auto 8px auto; display: block;"
                                onerror="this.src='{{ asset('assets/images/laundry_icon.png') }}'">
                            <div class="tw-text-xs tw-font-bold tw-truncate">{{ $item->service_name }}</div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- Addons Section --}}
            @if(count($addons) > 0)
            <div class="mt-3 pt-3 border-top">
                <h6 class="tw-text-sm tw-font-semibold tw-mb-2">{{ $lang->data['addons'] ?? 'Add-ons' }}</h6>
                <div class="tw-flex tw-flex-wrap tw-gap-1">
                    @foreach($addons as $addon)
                    <button wire:click="toggleAddon({{ $addon->id }})"
                            class="btn btn-sm {{ isset($selected_addons[$addon->id]) && $selected_addons[$addon->id] === true ? 'btn-primary' : 'btn-outline-secondary' }}">
                        {{ $addon->addon_name }} - {{ getFormattedCurrency($addon->addon_price) }}
                    </button>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Cart Section - Desktop --}}
        <div class="tw-hidden lg:tw-block lg:tw-w-1/2 tw-p-2 tw-bg-white tw-border-l tw-overflow-y-auto" style="height: calc(100vh - 90px);">
            <div class="tw-flex tw-flex-col tw-h-full">
                {{-- Cart Header --}}
                <div class="tw-flex tw-items-center tw-justify-between tw-mb-2">
                    <h6 class="tw-font-bold tw-m-0">
                        <i class="ri-shopping-cart-line me-1"></i>
                        {{ $lang->data['your_cart'] ?? 'Your Cart' }}
                        <span class="badge bg-primary ms-1">{{ count($cart_items) }}</span>
                    </h6>
                </div>

                {{-- Cart Items --}}
                <div class="tw-flex-1 tw-overflow-y-auto tw-mb-2">
                    @if(count($cart_items) == 0)
                    <div class="text-center py-5 text-muted">
                        <i class="ri-shopping-bag-line" style="font-size: 48px; opacity: 0.5;"></i>
                        <p class="mb-0 mt-2">{{ $lang->data['cart_empty'] ?? 'Your cart is empty' }}</p>
                        <p class="small mb-0">{{ $lang->data['select_service_to_start'] ?? 'Select a service to get started' }}</p>
                    </div>
                    @else
                    @foreach($cart_items as $key => $item)
                    <div class="card mb-2">
                        <div class="card-body p-2 tw-flex tw-items-center tw-justify-between">
                            <div class="tw-flex-1 tw-min-w-0 tw-mr-2">
                                <div class="tw-font-medium tw-text-sm tw-truncate">{{ $item['service_name'] }}</div>
                                <div class="tw-text-xs tw-text-gray-500">{{ $item['service_type_name'] }}</div>
                                <div class="tw-text-sm tw-text-primary tw-font-bold">{{ getFormattedCurrency($item['selling_price']) }}</div>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <button wire:click="decreaseQuantity('{{ $key }}')" class="btn btn-outline-secondary">
                                    <i class="ri-subtract-line"></i>
                                </button>
                                <span class="btn btn-light disabled">{{ $item['quantity'] }}</span>
                                <button wire:click="increaseQuantity('{{ $key }}')" class="btn btn-outline-secondary">
                                    <i class="ri-add-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    {{-- Selected Addons --}}
                    @php
                        $selectedAddonsList = [];
                        foreach($selected_addons as $addonId => $isSelected) {
                            if($isSelected === true) {
                                $addon = $addons->firstWhere('id', $addonId);
                                if($addon) $selectedAddonsList[] = $addon;
                            }
                        }
                    @endphp
                    @if(count($selectedAddonsList) > 0)
                    <div class="mt-2 pt-2 border-top">
                        <small class="text-muted">{{ $lang->data['addons'] ?? 'Add-ons' }}:</small>
                        @foreach($selectedAddonsList as $addon)
                        <div class="d-flex justify-content-between small">
                            <span>{{ $addon->addon_name }}</span>
                            <span>{{ getFormattedCurrency($addon->addon_price) }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @endif
                </div>

                {{-- Delivery Date & Instructions --}}
                <div class="border-top pt-2">
                    <div class="mb-2">
                        <label class="form-label small mb-1">{{ $lang->data['delivery_date'] ?? 'Delivery Date' }}</label>
                        <input type="date" wire:model="delivery_date" class="form-control form-control-sm" min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small mb-1">{{ $lang->data['special_instructions'] ?? 'Instructions' }}</label>
                        <textarea wire:model="special_instructions" rows="2" class="form-control form-control-sm"
                                  placeholder="{{ $lang->data['any_special_requests'] ?? 'Any requests...' }}"></textarea>
                    </div>
                </div>

                {{-- Totals --}}
                <div class="border-top pt-2">
                    <div class="d-flex justify-content-between small">
                        <span>{{ $lang->data['subtotal'] ?? 'Subtotal' }}</span>
                        <span>{{ getFormattedCurrency($sub_total) }}</span>
                    </div>
                    @if($addon_total > 0)
                    <div class="d-flex justify-content-between small">
                        <span>{{ $lang->data['addons'] ?? 'Add-ons' }}</span>
                        <span>{{ getFormattedCurrency($addon_total) }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between small">
                        <span>{{ $lang->data['tax'] ?? 'Tax' }} ({{ $tax_percent }}%)</span>
                        <span>{{ getFormattedCurrency($tax) }}</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold border-top pt-2 mt-2">
                        <span>{{ $lang->data['total'] ?? 'Total' }}</span>
                        <span class="text-primary">{{ getFormattedCurrency($total) }}</span>
                    </div>
                </div>

                {{-- Place Order Button --}}
                <button wire:click="placeOrder" class="btn btn-success w-100 mt-2" {{ count($cart_items) == 0 ? 'disabled' : '' }}>
                    <span wire:loading.remove wire:target="placeOrder">{{ $lang->data['place_order'] ?? 'Place Order' }}</span>
                    <span wire:loading wire:target="placeOrder">{{ $lang->data['processing'] ?? 'Processing...' }}</span>
                    <i class="ri-check-line ms-1" wire:loading.remove wire:target="placeOrder"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Cart Slide-out --}}
        <div x-show="showCart" 
             x-transition:enter="tw-transition tw-ease-out tw-duration-300"
             x-transition:enter-start="tw-translate-x-full"
             x-transition:enter-end="tw-translate-x-0"
             x-transition:leave="tw-transition tw-ease-in tw-duration-300"
             x-transition:leave-start="tw-translate-x-0"
             x-transition:leave-end="tw-translate-x-full"
             class="lg:tw-hidden tw-fixed tw-inset-y-0 tw-right-0 tw-w-full sm:tw-w-80 tw-bg-white tw-shadow-2xl tw-z-50 tw-overflow-y-auto"
             style="display: none;">
            <div class="p-3 d-flex flex-column h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">
                        <i class="ri-shopping-cart-line me-1"></i>
                        {{ $lang->data['your_cart'] ?? 'Your Cart' }}
                    </h6>
                    <button @click="showCart = false" class="btn btn-sm btn-light">
                        <i class="ri-close-line"></i>
                    </button>
                </div>

                {{-- Mobile Cart Items --}}
                <div class="flex-grow-1 overflow-auto mb-3">
                    @if(count($cart_items) == 0)
                    <div class="text-center py-5 text-muted">
                        <i class="ri-shopping-bag-line" style="font-size: 48px; opacity: 0.5;"></i>
                        <p class="mb-0 mt-2">{{ $lang->data['cart_empty'] ?? 'Your cart is empty' }}</p>
                    </div>
                    @else
                    @foreach($cart_items as $key => $item)
                    <div class="card mb-2">
                        <div class="card-body p-2 d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1 me-2" style="min-width: 0;">
                                <div class="fw-medium small text-truncate">{{ $item['service_name'] }}</div>
                                <div class="text-muted small">{{ $item['service_type_name'] }}</div>
                                <div class="text-primary fw-bold small">{{ getFormattedCurrency($item['selling_price']) }}</div>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <button wire:click="decreaseQuantity('{{ $key }}')" class="btn btn-outline-secondary">
                                    <i class="ri-subtract-line"></i>
                                </button>
                                <span class="btn btn-light disabled">{{ $item['quantity'] }}</span>
                                <button wire:click="increaseQuantity('{{ $key }}')" class="btn btn-outline-secondary">
                                    <i class="ri-add-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if(count($selectedAddonsList) > 0)
                    <div class="mt-2 pt-2 border-top">
                        <small class="text-muted">{{ $lang->data['addons'] ?? 'Add-ons' }}:</small>
                        @foreach($selectedAddonsList as $addon)
                        <div class="d-flex justify-content-between small">
                            <span>{{ $addon->addon_name }}</span>
                            <span>{{ getFormattedCurrency($addon->addon_price) }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @endif
                </div>

                {{-- Mobile Delivery & Instructions --}}
                <div class="border-top pt-3">
                    <div class="mb-2">
                        <label class="form-label small mb-1">{{ $lang->data['delivery_date'] ?? 'Delivery Date' }}</label>
                        <input type="date" wire:model="delivery_date" class="form-control form-control-sm" min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small mb-1">{{ $lang->data['special_instructions'] ?? 'Instructions' }}</label>
                        <textarea wire:model="special_instructions" rows="2" class="form-control form-control-sm"
                                  placeholder="{{ $lang->data['any_special_requests'] ?? 'Any requests...' }}"></textarea>
                    </div>
                </div>

                {{-- Mobile Totals --}}
                <div class="border-top pt-2">
                    <div class="d-flex justify-content-between small">
                        <span>{{ $lang->data['subtotal'] ?? 'Subtotal' }}</span>
                        <span>{{ getFormattedCurrency($sub_total) }}</span>
                    </div>
                    @if($addon_total > 0)
                    <div class="d-flex justify-content-between small">
                        <span>{{ $lang->data['addons'] ?? 'Add-ons' }}</span>
                        <span>{{ getFormattedCurrency($addon_total) }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between small">
                        <span>{{ $lang->data['tax'] ?? 'Tax' }} ({{ $tax_percent }}%)</span>
                        <span>{{ getFormattedCurrency($tax) }}</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold border-top pt-2 mt-2">
                        <span>{{ $lang->data['total'] ?? 'Total' }}</span>
                        <span class="text-primary">{{ getFormattedCurrency($total) }}</span>
                    </div>
                </div>

                <button wire:click="placeOrder" @click="showCart = false" class="btn btn-success w-100 mt-3" {{ count($cart_items) == 0 ? 'disabled' : '' }}>
                    <span wire:loading.remove wire:target="placeOrder">{{ $lang->data['place_order'] ?? 'Place Order' }}</span>
                    <span wire:loading wire:target="placeOrder">{{ $lang->data['processing'] ?? 'Processing...' }}</span>
                    <i class="ri-check-line ms-1" wire:loading.remove wire:target="placeOrder"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Overlay --}}
        <div x-show="showCart" @click="showCart = false"
             x-transition:enter="tw-transition tw-ease-out tw-duration-300"
             x-transition:enter-start="tw-opacity-0"
             x-transition:enter-end="tw-opacity-100"
             x-transition:leave="tw-transition tw-ease-in tw-duration-300"
             x-transition:leave-start="tw-opacity-100"
             x-transition:leave-end="tw-opacity-0"
             class="lg:tw-hidden tw-fixed tw-inset-0 tw-bg-black tw-bg-opacity-50 tw-z-40"
             style="display: none;"></div>
    </div>

    @else
    {{-- Step 3: Order Confirmation --}}
    <div class="tw-p-4">
        <div class="tw-max-w-lg tw-mx-auto">
            <div class="card shadow-sm">
                <div class="card-body p-4 text-center">
                    <div style="width: 64px; height: 64px; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                        <i class="ri-check-line" style="font-size: 32px; color: #16a34a;"></i>
                    </div>
                    
                    <h5 class="fw-bold mb-1">{{ $lang->data['order_placed'] ?? 'Order Placed Successfully!' }}</h5>
                    <p class="text-muted small mb-4">{{ $lang->data['order_confirmation_message'] ?? 'Thank you for your order. We will process it shortly.' }}</p>

                    @if($placed_order)
                    <div class="bg-light rounded p-3 text-start mb-3">
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-muted">{{ $lang->data['order_number'] ?? 'Order #' }}</span>
                            <span class="fw-bold text-primary">{{ $placed_order->order_number }}</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-muted">{{ $lang->data['customer'] ?? 'Customer' }}</span>
                            <span class="fw-medium">{{ $placed_order->customer_name }}</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-muted">{{ $lang->data['order_date'] ?? 'Order Date' }}</span>
                            <span class="fw-medium">{{ \Carbon\Carbon::parse($placed_order->order_date)->format('d M, Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-muted">{{ $lang->data['delivery_date'] ?? 'Delivery Date' }}</span>
                            <span class="fw-medium">{{ \Carbon\Carbon::parse($placed_order->delivery_date)->format('d M, Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2 mt-2">
                            <span class="text-muted small">{{ $lang->data['total_amount'] ?? 'Total' }}</span>
                            <span class="fw-bold">{{ getFormattedCurrency($placed_order->total) }}</span>
                        </div>
                    </div>

                    <div class="alert alert-info small text-start mb-3">
                        <i class="ri-information-line me-1"></i>
                        <strong>{{ $lang->data['note'] ?? 'Note' }}:</strong> 
                        {{ $lang->data['payment_note'] ?? 'Payment will be collected when you pick up your order or upon delivery.' }}
                    </div>
                    @endif

                    <button wire:click="startNewOrder" class="btn btn-primary w-100">
                        {{ $lang->data['place_another_order'] ?? 'Place Another Order' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Service Type Modal --}}
    <div class="modal fade" id="serviceTypeModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">
                        @if($service) {{ $service->service_name }} @endif
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($service && count($service_types) > 0)
                    <p class="text-muted small mb-3">{{ $lang->data['select_service_type'] ?? 'Select service type(s)' }}</p>
                    @foreach($service_types as $type)
                    <label class="d-flex align-items-center justify-content-between p-2 bg-light rounded mb-2 cursor-pointer {{ isset($selected_type[$type['id']]) && $selected_type[$type['id']] === true ? 'border border-primary' : '' }}" style="cursor: pointer;">
                        <div class="d-flex align-items-center gap-2">
                            <input type="checkbox" wire:model="selected_type.{{ $type['id'] }}" value="true" class="form-check-input m-0">
                            <span class="fw-medium small">{{ $type['service_type_name'] }}</span>
                        </div>
                        <span class="fw-bold text-primary small">{{ $type['formatted_price'] }}</span>
                    </label>
                    @endforeach
                    @error('service_error') <p class="text-danger small mt-2">{{ $message }}</p> @enderror
                    @else
                    <p class="text-muted text-center py-4">{{ $lang->data['no_service_types'] ?? 'No service types available' }}</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                    <button type="button" wire:click="addToCart" class="btn btn-primary">
                        {{ $lang->data['add_to_cart'] ?? 'Add to Cart' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
