<div class="tw-overflow-x-clip" x-data="posFunction">
    <div class="tw-w-full tw-bg-white tw-flex tw-justify-between tw-items-center ">
        <div class="tw-flex tw-gap-2 tw-px-3 tw-py-2">
            <a href="{{ route('orders') }}" class="no-underline">
                <button
                    class="bg-primary-600 tw-text-white tw-text-xs radius-8 px-20 tw-py-2 d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                        stroke="currentColor" class="tw-size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    <span>{{ $lang->data['back'] ?? 'Back' }}</span>
                </button>
            </a>
            <template x-if="detached">
                <button
                    class="tw-px-2 tw-py-1.5 bg-primary-600 tw-w-fit tw-rounded-md tw-text-white tw-flex tw-items-center tw-gap-1.5 tw-border-0 tw-shadow-md"
                    @click="shown = !shown">
                    <template x-if="!shown">
                        <div class="tw-flex  tw-items-center tw-gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="tw-size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                            <span class="text-sm ">{{ $lang->data['cart'] ?? 'Cart' }}</span>
                        </div>
                    </template>
                    <template x-if="shown">
                        <div class="tw-flex tw-items-center tw-gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="tw-size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>

                            <span class="text-sm ">{{ $lang->data['products'] ?? 'Products' }}</span>
                        </div>
                    </template>
                </button>
            </template>
        </div>
        <button type="button" data-theme-toggle
            class="w-40-px h-40-px bg-neutral-200 rounded-circle tw-hidden justify-content-center align-items-center"></button>
    </div>

    <div class="tw-w-[100%] tw-h-full tw-flex lg:tw-flex-row tw-flex-col  tw-relative tw-mt-0.5">
        <div class="tw-lg:w-1/2 tw-w-full tw-flex-col tw-h-[calc(100vh-4.0rem)]  tw-p-2 tw-bg-white p-16">
            <div class="tw-flex tw-flex-col">
                <div class="icon-field has-validation">
                    <span class="icon tw-translate-y-[2px]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-search" viewBox="0 0 16 16">
                            <path
                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                        </svg>
                    </span>
                    <input type="text" class="form-control" wire:model.live="search_query"
                        placeholder="{{ $lang->data['search_here'] ?? 'Search Here' }}" required="">
                </div>
                <div
                    class="tw-w-full tw-h-[calc(100vh-9rem)] tw-overflow-y-scroll custom-scroll tw-mt-2 tw-flex tw-p-0.5">
                    <div class="tw-grid tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-2 tw-h-fit tw-w-full">
                        @foreach ($services as $item)
                            <a type="button" class=" hover:tw-translate-y-1" data-bs-toggle="modal"
                                data-bs-target="#servicetype" wire:click="selectService({{ $item->id }})">
                                <div class="card bg-neutral-100">
                                    <div
                                        class="card-body tw-flex tw-items-center tw-justify-center tw-flex-col tw-rounded-md  tw-overflow-clip tw-ring-1 tw-ring-neutral-200">
                                        <img src="{{ asset('assets/img/service-icons/' . $item->icon) }}"
                                            class="tw-h-24 tw-w-24 tw-object-center tw-rounded-md tw-py-2">
                                        <div
                                            class="tw-px-2 tw-py-1.5  tw-w-full tw-flex tw-justify-center tw-items-center">
                                            <div class="tw-text-sm tw-text-center tw-truncate tw-font-bold tw-w-[90%] ">
                                                {{ $item->service_name }}</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class=" tw-h-[calc(100vh-4rem)]  tw-bg-white p-16"
            :class="shown && detached ? 'tw-absolute tw-inset-0 tw-w-full' :
                ' tw-hidden lg:tw-block lg:tw-w-1/2 tw-w-full tw-shrink-0 '">
            <div class="tw-flex tw-items-center tw-gap-8 tw-w-full">
                <div class="tw-flex tw-min-w-fit tw-shrink tw-flex-col" x-data="{}">
                    <div class="tw-text-sm">{{ $lang->data['order'] ?? 'Order' }} : <span
                            class="tw-font-bold">#{{ $order_id }}</span></div>
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <div class="tw-text-sm tw-relative">
                            {{ $lang->data['date'] ?? 'Date' }} : <span
                                class="tw-font-bold">{{ $date }}</span>
                            <input type="date" wire:model.live="date" name=""
                                class="tw-opacity-0 tw-absolute tw-pointer-events-none" x-ref="date">
                        </div>

                        <button @click="$refs.date.showPicker()"
                            class="tw-px-2 tw-py-1 bg-primary-600 tw-rounded-md tw-text-white tw-flex tw-items-center tw-gap-1.5 tw-border-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor"
                                class="bi bi-calendar3" viewBox="0 0 16 16">
                                <path
                                    d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857z" />
                                <path
                                    d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                            </svg>
                        </button>
                    </div>

                    <div class="tw-flex tw-items-center tw-gap-2">
                        <div class="tw-text-sm tw-relative">
                            {{ $lang->data['delivery_date'] ?? 'Delivery Date' }} : <span
                                class="tw-font-bold">{{ $delivery_date }}</span>
                            <input type="date" wire:model.live="delivery_date" name=""
                                class="tw-opacity-0 tw-absolute tw-pointer-events-none" x-ref="delivery_date">
                        </div>

                        <button @click="$refs.delivery_date.showPicker()"
                            class="tw-px-2 tw-py-1 bg-primary-600 tw-rounded-md tw-text-white tw-flex tw-items-center tw-gap-1.5 tw-border-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                fill="currentColor" class="bi bi-calendar3" viewBox="0 0 16 16">
                                <path
                                    d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857z" />
                                <path
                                    d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                            </svg>
                        </button>
                    </div>

                </div>
                <div class="tw-flex tw-items-center tw-gap-2 tw-w-full tw-shrink">
                    <div class="icon-field  tw-relative tw-w-full tw-items-center">
                        <span class="icon -tw-translate-y-[2px]">
                            <iconify-icon icon="f7:person"></iconify-icon>
                        </span>
                        <input type="text"
                            class="form-control @error('paid_amount_customer') is-invalid   @enderror"
                            placeholder="@if (!$selected_customer) {{ $lang->data['select_a_customer'] ?? 'Select A Customer' }} @else {{ $selected_customer->name }} @endif"
                            required="" wire:model.live="customer_query">
                        @if ($customers && count($customers) > 0)
                            <div
                                class="tw-absolute tw-top-[100%] tw-left-0 tw-w-full tw-z-20 tw-shadow-md tw-bg-white tw-rounded-lg ">
                                @foreach ($customers as $row)
                                    <li class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900"
                                        wire:click="selectCustomer({{ $row->id }})">{{ $row->name }} -
                                        {{ $row->phone }}</li>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @can('customer_create')
                        <button type="button" data-bs-toggle="modal" data-bs-target="#addcustomer"
                            class="tw-px-4 tw-py-3 bg-primary-600 tw-rounded-md tw-text-white tw-flex tw-items-center tw-gap-1.5 tw-border-0 tw-shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-person-fill-add" viewBox="0 0 16 16">
                                <path
                                    d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                <path
                                    d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4" />
                            </svg>
                        </button>
                    @endcan
                </div>
            </div>
            <div
                class="tw-w-full   tw-flex tw-flex-col tw-mt-4 tw-rounded-lg tw-overflow-clip tw-border @error('error') tw-border-red-500 @else tw-border-neutral-200 dark:tw-border-[#1b2431] @enderror tw-border-solid">
                <div class="tw-flex tw-flex-col lg:tw-w-full tw-overflow-x-auto">
                    <div class="tw-flex tw-flex-col lg:tw-w-full tw-w-[100rem] ">
                        <div class="tw-flex tw-flex-col  tw-overflow-x-auto tw-w-full tw-shrink-0">
                            <table class="tw-w-full tw-text-xs tw-shrink-0 tw-h-fit ">
                                <thead class="tw-bg-[#e9ecef] dark:tw-bg-[#1b2431]">
                                    <tr>
                                        <th class="tw-py-2 tw-px-2 tw-text-xs tw-w-[10rem] lg:tw-w-[10%] tw-text-left">
                                            {{ $lang->data['service'] ?? 'Service' }}</th>
                                        <th
                                            class="tw-py-2 tw-px-1 tw-text-xs tw-w-[10rem] lg:tw-w-[15%] tw-text-center">
                                            {{ $lang->data['color'] ?? 'Color' }}</th>
                                        <th
                                            class="tw-py-2 tw-px-1 tw-text-xs tw-w-[10rem] lg:tw-w-[15%] tw-text-center">
                                            {{ $lang->data['price'] ?? 'Price' }}</th>
                                        <th
                                            class="tw-py-2 tw-px-1 tw-text-xs tw-w-[10rem] lg:tw-w-[15%] tw-text-center">
                                            {{ $lang->data['rate'] ?? 'Rate' }}</th>
                                        <th
                                            class="tw-py-2 tw-px-1 tw-text-xs tw-w-[10rem] lg:tw-w-[15%] tw-text-center">
                                            {{ $lang->data['qty'] ?? 'QTY' }}</th>

                                        <th
                                            class="tw-py-2 tw-px-1 tw-text-xs tw-w-[10rem] lg:tw-w-[10%] tw-text-center">
                                            {{ $lang->data['tax'] ?? 'Tax  ' }} ({{ $tax_percent }}%)</th>
                                        <th
                                            class="tw-py-2 tw-px-1 tw-text-xs tw-w-[10rem] lg:tw-w-[10%] tw-text-center">
                                            {{ $lang->data['total'] ?? 'Total' }}</th>
                                        <th
                                            class="tw-py-2 tw-px-1 tw-text-xs tw-w-[10rem] lg:tw-w-[5%] tw-text-center">
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <div
                            class="tw-flex tw-h-[calc(100dvh-23rem)] tw-overflow-y-auto tw-overflow-x-auto tw-w-full tw-shrink-0">
                            <table class="  tw-w-full tw-text-xs tw-shrink-0  tw-h-fit">
                                <tbody>
                                    @php
                                        $currentcount = 0;
                                    @endphp
                                    @foreach ($selservices as $key => $item)
                                        <tr
                                            class="tw-border-b tw-border-neutral-200 dark:tw-border-neutral-800/50 tw-border-solid">
                                            <td class="tw-py-2 tw-px-2 lg:tw-w-[10%] tw-w-[10rem] tw-text-left">
                                                <div class="tw-flex tw-flex-col ">
                                                    @php
                                                        $serviceinline = null;
                                                        if (isset($item['service'])) {
                                                            $serviceinline = \App\Models\Service::where(
                                                                'id',
                                                                $item['service'],
                                                            )->first();
                                                        }
                                                        if (isset($item['service_type'])) {
                                                            $servicetypeinline = \App\Models\ServiceType::where(
                                                                'id',
                                                                $item['service_type'],
                                                            )->first();
                                                        }
                                                        $currentcount++;
                                                        $itemtaxtotal = 0;
                                                        $itemtotal = 0;
                                                        $localrate = 0;
                                                        if (getTaxType() == 2) {
                                                            $localrate =
                                                                $selling_price[$key] *
                                                                (100 / (100 + $tax_percent ?? 0));
                                                            $itemtotallocal =
                                                                $selling_price[$key] *
                                                                $quantity[$key] *
                                                                (100 / (100 + $tax_percent ?? 0));
                                                            $itemtaxtotal =
                                                                $selling_price[$key] * $quantity[$key] -
                                                                    $itemtotallocal ??
                                                                0;
                                                            $itemtotal = $selling_price[$key] * $quantity[$key];
                                                        } else {
                                                            $itemtotallocal = $selling_price[$key] * $quantity[$key];
                                                            $localrate = $selling_price[$key];
                                                            $itemtaxtotal = ($itemtotallocal * $tax_percent) / 100;
                                                            $itemtotal = $itemtotallocal + $itemtaxtotal;
                                                        }
                                                    @endphp
                                                    <div class="tw-text-xs tw-font-semibold tw-flex tw-items-center tw-gap-1">
                                                        {{ $serviceinline->service_name }}
                                                        @if(isset($is_express[$key]) && $is_express[$key])
                                                        <span class="tw-bg-orange-500 tw-text-white tw-text-[10px] tw-px-1 tw-py-0.5 tw-rounded tw-font-bold">EXPRESS</span>
                                                        @endif
                                                    </div>
                                                    <input type="text" 
                                                        wire:model.live.debounce.500ms="service_names.{{ $key }}"
                                                        class="tw-text-xs tw-font-normal text-primary-600 tw-ring-1 tw-ring-neutral-300 tw-bg-white tw-px-1 tw-py-0.5 tw-w-full focus:tw-ring-primary-500 tw-rounded"
                                                        placeholder="{{ $servicetypeinline->service_type_name }}"
                                                        value="{{ $service_names[$key] ?? $servicetypeinline->service_type_name }}">
                                                </div>
                                            </td>
                                            <td class="tw-py-2 tw-px-1 lg:tw-w-[15%] tw-w-[10rem]  tw-text-center ">
                                                <div
                                                    class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center">
                                                    <input type="color" name=""
                                                        pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
                                                        class="tw-w-10 tw-h-6"
                                                        wire:model.live="colors.{{ $key }}"
                                                        wire:change="changeColor({{ $key }})">
                                                </div>
                                            </td>
                                            <td class="tw-py-2 tw-px-1 lg:tw-w-[15%] tw-w-[10rem]  tw-text-center">
                                                <div
                                                    class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center">
                                                    <input type="text" name=""
                                                        wire:model.live="selling_price.{{ $key }}"
                                                        id=""
                                                        class="tw-ring-1 tw-px-1 tw-py-0.5 tw-rounded-md tw-w-[4rem]">
                                                </div>
                                            </td>
                                            <td class="tw-py-2 tw-px-1 lg:tw-w-[15%] tw-w-[10rem]  tw-text-center">
                                                <div
                                                    class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center">
                                                    {{ getFormattedCurrency($localrate ?? 0) }}
                                                </div>
                                            </td>
                                            <td class="tw-py-2 tw-px-1 lg:tw-w-[15%] tw-w-[10rem]  tw-text-center">
                                                <div
                                                    class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center">
                                                    <div
                                                        class="tw-flex tw-items-center tw-gap-1 tw-justify-center tw-text-sm">
                                                        <button type="button" wire:click="decrementQty({{ $key }})"
                                                            class="tw-w-6 tw-h-6 tw-flex tw-items-center tw-justify-center tw-bg-neutral-200 hover:tw-bg-neutral-300 tw-rounded tw-border-0 tw-text-neutral-700 tw-font-bold">
                                                            -
                                                        </button>
                                                        <input type="number" min="1" 
                                                            wire:model.live.debounce.300ms="quantity.{{ $key }}"
                                                            class="tw-w-10 tw-text-center tw-ring-1 tw-ring-neutral-300 tw-px-1 tw-py-0.5 tw-rounded-md focus:tw-ring-primary-500 tw-text-sm"
                                                            value="{{ $quantity[$key] }}">
                                                        <button type="button" wire:click="incrementQty({{ $key }})"
                                                            class="tw-w-6 tw-h-6 tw-flex tw-items-center tw-justify-center tw-bg-neutral-200 hover:tw-bg-neutral-300 tw-rounded tw-border-0 tw-text-neutral-700 tw-font-bold">
                                                            +
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tw-py-2 tw-px-1 lg:tw-w-[10%] tw-w-[10rem] tw-text-center">
                                                <div
                                                    class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center">
                                                    {{ getFormattedCurrency($itemtaxtotal ?? 0) }}
                                                </div>
                                            </td>
                                            <td class="tw-py-2 tw-px-1 lg:tw-w-[10%] tw-w-[10rem] tw-text-center">
                                                <div
                                                    class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center">
                                                    {{ getFormattedCurrency($itemtotal ?? 0) }}
                                                </div>
                                            </td>
                                            <td class="tw-py-2 tw-px-1 lg:tw-w-[5%] tw-w-[10rem] tw-text-center">
                                                <div
                                                    class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center">
                                                    <button wire:click="removeItem({{ $key }})"
                                                        class="tw-px-2 tw-py-1 tw-bg-red-500 tw-rounded-md tw-text-white tw-flex tw-items-center tw-gap-1.5 tw-border-0 tw-shadow-md">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" class="bi bi-trash"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                            <path
                                                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div
                    class="tw-mt-4 tw-flex tw-justify-between tw-text-sm  tw-p-2 tw-border-t dark:tw-border-[#1b2431] tw-border-dashed tw-border-neutral-200 tw-border-b-0 tw-border-l-0 tw-border-r-0">
                    <div class="tw-flex tw-flex-col tw-gap-2">
                        <!-- WhatsApp Bill Checkbox -->
                        <div class="tw-flex tw-items-center tw-gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#25D366" viewBox="0 0 16 16">
                                <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                            </svg>
                            <span>{{ $lang->data['send_bill_whatsapp'] ?? 'Send bill to WhatsApp' }}</span>
                            <button type="button" wire:click="toggleWhatsApp" 
                                style="width: 22px; height: 22px; border: 2px solid {{ $send_whatsapp ? '#25D366' : '#ccc' }}; background: {{ $send_whatsapp ? '#25D366' : '#fff' }}; border-radius: 4px; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0;">
                                @if($send_whatsapp)
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#fff" viewBox="0 0 16 16">
                                    <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                </svg>
                                @endif
                            </button>
                        </div>
                        <div class="tw-flex tw-items-center tw-gap-2">
                            <div class="tw-flex tw-items-center tw-gap-2">
                                {{ $lang->data['addon'] ?? 'Addon' }} <button data-bs-toggle="modal"
                                    data-bs-target="#addons"
                                    class="tw-px-1 tw-py-1  tw-rounded-md  tw-flex tw-items-center tw-gap-1.5 tw-border-0 tw-shadow-md @if ($selected_addons && count($selected_addons) > 0) bg-primary-600 tw-text-white @else tw-border tw-border-solid tw-bg-transparent tw-border-neutral-400 @endif">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        fill="currentColor" class="bi bi-box-fill" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.004-.001.274-.11a.75.75 0 0 1 .558 0l.274.11.004.001zm-1.374.527L8 5.962 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339Z" />
                                    </svg>
                                </button>
                                :
                            </div>
                            <div class="tw-font-bold"> {{ getFormattedCurrency($addon_total) }}</div>
                        </div>
                        <div class="tw-flex tw-items-center tw-gap-2">
                            <div class="">{{ $lang->data['sub_total'] ?? 'Sub Total' }} :</div>
                            <div class="tw-font-bold">{{ getFormattedCurrency($sub_total) }}</div>
                        </div>
                        <div class="tw-flex tw-items-center  tw-gap-2">
                            <div class="tw-flex tw-items-center tw-gap-2">
                                {{ $lang->data['notes'] ?? 'Notes' }} : <button data-bs-toggle="modal"
                                    data-bs-target="#notesModal"
                                    class="tw-px-1 tw-py-1  tw-rounded-md  tw-flex tw-items-center tw-gap-1.5 tw-border-0 tw-shadow-md @if ($payment_notes && $payment_notes != '') bg-primary-600 tw-text-white @else tw-border tw-border-solid tw-bg-transparent tw-border-neutral-400 @endif">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="tw-flex tw-flex-col tw-gap-2">
                        <div class="tw-flex tw-items-end tw-justify-end tw-gap-2">
                            <div class="">{{ $lang->data['tax'] ?? 'Tax' }}
                                ({{ getTaxPercentage() }}%) :</div>
                            <div class="tw-font-bold"> {{ getFormattedCurrency($tax) }} </div>
                        </div>
                        <div class="tw-flex tw-items-end tw-justify-end tw-gap-2">
                            <div class="tw-flex tw-items-center tw-gap-2">
                                {{ $lang->data['discount'] ?? 'Discount' }}
                                <button data-bs-toggle="modal" data-bs-target="#discount"
                                    class="tw-px-1 tw-py-1  tw-rounded-md  tw-flex tw-items-center tw-gap-1.5 tw-border-0 tw-shadow-md @if ($discount && $discount > 0) bg-primary-600 tw-text-white @else tw-border tw-border-solid tw-bg-transparent tw-border-neutral-400 @endif">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        fill="currentColor" class="bi bi-tag-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M2 1a1 1 0 0 0-1 1v4.586a1 1 0 0 0 .293.707l7 7a1 1 0 0 0 1.414 0l4.586-4.586a1 1 0 0 0 0-1.414l-7-7A1 1 0 0 0 6.586 1zm4 3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                                    </svg>
                                </button>
                                :
                            </div>
                            <div class="tw-font-bold">{{ getFormattedCurrency($discount) }}</div>
                        </div>
                        <div class="tw-flex tw-items-center  tw-justify-end tw-gap-2">
                            <div class="">{{ $lang->data['gross_total'] ?? 'Gross Total' }} :</div>
                            <div class="tw-font-extrabold"> {{ getFormattedCurrency($total) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Inline Payment Type Selection -->
            <div class="tw-flex tw-items-center tw-gap-2 tw-mt-1 tw-p-2 tw-w-full" x-data="{ showPaymentTypes: false }">
                <div class="tw-flex tw-items-center tw-gap-2 tw-w-full">
                    <!-- Payment Type Dropdown -->
                    <div class="tw-relative tw-flex-1">
                        <button @click="showPaymentTypes = !showPaymentTypes" type="button"
                            class="tw-px-3 tw-py-2.5 tw-h-full bg-success-600 tw-rounded-md tw-text-white tw-flex tw-items-center tw-justify-between tw-gap-2 tw-w-full tw-border-0 tw-shadow-md">
                            <span class="tw-flex tw-items-center tw-gap-2">
                                @if($payment_type == 1)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1H1zm0 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1zm2 2h2v2H3V6zm4 0h6v1H7V6z"/>
                                    </svg>
                                    {{ $lang->data['cash'] ?? 'Cash' }}
                                @elseif($payment_type == 2)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7z"/>
                                        <path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1z"/>
                                    </svg>
                                    {{ $lang->data['card'] ?? 'Card' }}
                                @elseif($payment_type == 3)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h6zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H5z"/>
                                        <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                    </svg>
                                    {{ $lang->data['benefit_pay'] ?? 'Benefit Pay' }}
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1H1zm0 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1zm2 2h2v2H3V6zm4 0h6v1H7V6z"/>
                                    </svg>
                                    {{ $lang->data['payment_type'] ?? 'Payment Type' }}
                                @endif
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="tw-transition-transform" :class="{ 'tw-rotate-180': showPaymentTypes }" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </button>
                        <!-- Dropdown Options (Opens Upward) -->
                        <div x-show="showPaymentTypes" @click.away="showPaymentTypes = false" x-transition
                            class="tw-absolute tw-left-0 tw-w-full tw-bg-white tw-rounded-md tw-shadow-lg tw-border tw-border-neutral-200 tw-z-50"
                            style="bottom: 100%; margin-bottom: 4px;">
                            <button type="button" wire:click="$set('payment_type', 1)" @click="showPaymentTypes = false"
                                class="tw-w-full tw-px-4 tw-py-2.5 tw-flex tw-items-center tw-gap-2 hover:tw-bg-neutral-100 tw-border-0 tw-bg-transparent tw-text-left @if($payment_type == 1) tw-bg-primary-50 tw-text-primary-600 @endif">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1H1zm0 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1zm2 2h2v2H3V6zm4 0h6v1H7V6z"/>
                                </svg>
                                {{ $lang->data['cash'] ?? 'Cash' }}
                            </button>
                            <button type="button" wire:click="$set('payment_type', 2)" @click="showPaymentTypes = false"
                                class="tw-w-full tw-px-4 tw-py-2.5 tw-flex tw-items-center tw-gap-2 hover:tw-bg-neutral-100 tw-border-0 tw-bg-transparent tw-text-left @if($payment_type == 2) tw-bg-primary-50 tw-text-primary-600 @endif">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7z"/>
                                    <path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1z"/>
                                </svg>
                                {{ $lang->data['card'] ?? 'Card' }}
                            </button>
                            <button type="button" wire:click="$set('payment_type', 3)" @click="showPaymentTypes = false"
                                class="tw-w-full tw-px-4 tw-py-2.5 tw-flex tw-items-center tw-gap-2 hover:tw-bg-neutral-100 tw-border-0 tw-bg-transparent tw-text-left @if($payment_type == 3) tw-bg-primary-50 tw-text-primary-600 @endif">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h6zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H5z"/>
                                    <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                </svg>
                                {{ $lang->data['benefit_pay'] ?? 'Benefit Pay' }}
                            </button>
                        </div>
                    </div>
                    <!-- Quick Pay Button -->
                    <button
                        class="tw-px-3 tw-py-2.5 tw-h-full bg-info-600 tw-rounded-md tw-text-white tw-flex tw-items-center tw-gap-1.5 tw-flex-1 tw-justify-center tw-border-0 tw-shadow-md"
                        wire:click.prevent="save('cash')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499L12.136.326zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484L5.562 3zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-13z"/>
                        </svg>
                        <span>{{ $lang->data['quick_pay'] ?? 'Quick Pay' }}</span>
                    </button>
                    <!-- More Options / Payment Details -->
                    <button
                        class="tw-px-3 tw-py-2.5 tw-h-full bg-primary-600 tw-rounded-md tw-text-white tw-flex tw-items-center tw-gap-1.5 tw-flex-1 tw-justify-center tw-border-0 tw-shadow-md"
                        data-bs-toggle="modal" data-bs-target="#payment">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4V.5zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2zm-3.5-7h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5z"/>
                        </svg>
                        <span>{{ $lang->data['details'] ?? 'Details' }}</span>
                    </button>
                    <!-- Save & Print -->
                    <button
                        class="tw-px-3 tw-py-2.5 tw-h-full bg-success-700 tw-rounded-md tw-text-white tw-flex tw-items-center tw-gap-1.5 tw-flex-1 tw-justify-center tw-border-0 tw-shadow-md"
                        wire:click.prevent="save">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                            <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
                        </svg>
                        <span>{{ $lang->data['save_print'] ?? 'Save & Print' }}</span>
                    </button>
                    <!-- Clear Button -->
                    <button
                        class="tw-px-3 tw-py-2.5 tw-bg-red-500 tw-rounded-md tw-text-white tw-h-full tw-flex tw-items-center tw-gap-1.5 tw-border-0 tw-shadow-md"
                        wire:click.prevent="clearAll">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                            <path
                                d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41m-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9" />
                            <path fill-rule="evenodd"
                                d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5 5 0 0 0 8 3M3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade " id="servicetype" tabindex="-1" role="dialog" aria-labelledby="servicetype"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-md modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title text-md" id="exampleModalLabel">
                        {{ $lang->data['select_service_type'] ?? 'Select Service Type' }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24">
                    <div class="row">
                        @foreach ($service_types as $item)
                            <div class="col-12 mb-20">
                                <div class="tw-flex tw-items-center tw-justify-between">
                                    <div class="d-flex align-items-center gap-10 fw-medium text-lg">
                                        <div class="form-check style-check d-flex align-items-center">
                                            <input class="form-check-input radius-4 border border-neutral-500"
                                                type="radio" id="test{{ $item['id'] }}" name="service_type_radio"
                                                value="{{ $item['id'] }}"
                                                wire:click="selectServiceType({{ $item['id'] }})"
                                                @if(isset($selected_type[$item['id']]) && $selected_type[$item['id']]) checked @endif>
                                        </div>
                                        <label for="test{{ $item['id'] }}"
                                            class="form-label fw-medium text-primary-light mb-0">{{ $item['service_type_name'] }}</label>
                                    </div>
                                    <div class="">{{ $item['price'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Express Service Option -->
                    <div class="border-top pt-3 mt-3">
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <div class="d-flex align-items-center gap-10 fw-medium text-lg">
                                <div class="form-check form-switch switch-warning d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                        id="expressService" wire:model.live="is_express_service">
                                </div>
                                <label for="expressService" class="form-label fw-medium text-warning-600 mb-0">
                                    <iconify-icon icon="mdi:lightning-bolt" class="text-warning-600 me-1"></iconify-icon>
                                    {{ $lang->data['express_service'] ?? 'Express Service' }} (×2)
                                </label>
                            </div>
                            <div class="text-warning-600 fw-medium">{{ $lang->data['double_price'] ?? 'Double Price' }}</div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start justify-content-end gap-3 mt-24">
                        <button type="button" data-bs-dismiss="modal"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8">
                            <span>{{ $lang->data['cancel'] ?? 'Cancel' }}</span>
                        </button>
                        <button type="submit" wire:click.prevent="addItem"
                            class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8">
                            <span>{{ $lang->data['save'] ?? 'Save' }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="notesModal" tabindex="-1" role="dialog" aria-labelledby="notesModal"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title text-md" id="exampleModalLabel">
                        {{ $lang->data['notes_remarks'] ?? 'Notes / Remarks' }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24">
                    <div class="tw-flex tw-gap-2 tw-flex-col">
                        <div class="">
                            {{ $lang->data['notes_remarks'] ?? 'Notes / Remarks' }}
                        </div>
                        <textarea rows="3" type="number" name="" id="" wire:model.live="payment_notes"
                            class=" form-control" placeholder="{{ $lang->data['enter_notes'] ?? 'Enter Notes' }}"></textarea>
                    </div>

                    <div class="d-flex align-items-start justify-content-end gap-3 mt-24">
                        <button data-bs-dismiss="modal"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8">
                            {{ $lang->data['close'] ?? 'Close' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade " id="discount" tabindex="-1" role="dialog" aria-labelledby="discount"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title text-md" id="exampleModalLabel">
                        {{ $lang->data['discount'] ?? 'Discount' }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24">
                    <div class="tw-flex tw-gap-2 tw-flex-col">
                        <div class="">
                            {{ $lang->data['discount'] ?? 'Discount' }}
                        </div>
                        <input type="number" name="" id="" wire:model.live="discount"
                            class=" form-control" placeholder="{{ $lang->data['enter_amount'] ?? 'Enter Amount' }}">
                    </div>
                    <div class="d-flex align-items-start justify-content-end gap-3 mt-24">
                        <button data-bs-dismiss="modal"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8">
                            {{ $lang->data['close'] ?? 'Close' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade " id="addons" tabindex="-1" role="dialog" aria-labelledby="discount"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title text-md" id="exampleModalLabel">{{ $lang->data['addons'] ?? 'Addons' }}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24">
                    @foreach ($addons as $row)
                        <div class="col-12 mb-20 tw-flex tw-justify-between tw-items-center">
                            <div class="d-flex align-items-center gap-10 fw-medium text-lg">
                                <div class="form-check style-check d-flex align-items-center">
                                    <input class="form-check-input radius-4 border border-neutral-500" type="checkbox"
                                        name="addon" id="addon{{ $row->id }}"
                                        wire:model.live="selected_addons.{{ $row->id }}">
                                </div>
                                <label for="addon{{ $row->id }}"
                                    class="form-label fw-medium  text-primary-light mb-0">{{ $row->addon_name }}</label>
                            </div>
                            <div class="text-primary">{{ getFormattedCurrency($row->addon_price) }}</div>
                        </div>
                    @endforeach
                    @if (count($addons) == 0)
                        <div class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center">
                            <div class="">No addons were found!.</div>
                        </div>
                    @endif
                    <div class="d-flex align-items-start justify-content-end gap-3 mt-24">
                        <button data-bs-dismiss="modal"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8">
                            {{ $lang->data['close'] ?? 'Close' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade " id="payment" tabindex="-1" role="dialog" aria-labelledby="payment"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content modal-content-lg radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title text-md" id="exampleModalLabel">
                        {{ $lang->data['payments'] ?? 'Payments' }}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24">
                    <div class="">
                        <ul>

                            <li class="d-flex align-items-center gap-1 tw-justify-between text-sm">
                                <span class="text-md fw-semibold text-primary-light">
                                    {{ $lang->data['balance'] ?? 'Balance' }} :</span>
                                <span class="text-secondary-light fw-medium"> {{ getFormattedCurrency($this?->currentBalance) }}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 tw-mb-6 tw-mt-4">
                        <hr>
                    </div>
                    <div class="col-12 tw-my-6">
                        @if(count($payments) > 0)
                        <table class="table basic-border-table mb-0 tw-w-full tw-text-xs">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Payment Type </th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $key => $item)
                                    <tr>
                                        <td>
                                            {{$key + 1}}
                                        </td>
                                        <td class="text-primary">{{getFormattedCurrency($item['amount'])}}</td>
                                        <td> {{ getpaymentMode($item['payment_type']) }}</td>
                                        <td>
                                            <button wire:click="removePayment({{$key}})" type="button" class="remove-item-button bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium tw-size-6 d-flex justify-content-center align-items-center rounded-circle"> 
                                                <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="tw-py-16">
                            <div class="text-center tw-text-xs">{{$lang->data['no_payment'] ?? 'No payments were added, Add a payment to show it here.'}}</div>
                        </div>
                        @endif
                    </div>
                    <div class="col-12 tw-my-6">
                        <hr>
                    </div>
                    <div class="row mb-20 ">
                        <div class="col-6 ">
                            <label for="name"
                                class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['paid_amount'] ?? 'Paid Amount' }}
                                <span class="text-danger">*</span></label>
                            <input type="text" class="form-control radius-8"
                                placeholder="{{ $lang->data['enter_amount'] ?? 'Enter Amount' }}"
                                wire:model="payment_amount">
                            @error('payment_amount')
                                <span class="error text-danger tw-text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-6 ">
                            <label for="name"
                                class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['payment_type'] ?? 'Payment Type' }}
                                <span class="text-danger">*</span></label>
                            <select class="form-select radius-8" wire:model="payment_type">
                                <option value="">
                                    {{ $lang->data['choose_payment_type'] ?? 'Choose Payment Type' }}
                                </option>
                                <option class="select-box" value="1">
                                    💵 {{ $lang->data['cash'] ?? 'Cash' }}
                                </option>
                                <option class="select-box" value="2">
                                    💳 {{ $lang->data['card'] ?? 'Card' }}
                                </option>
                                <option class="select-box" value="3">
                                    📱 {{ $lang->data['benefit_pay'] ?? 'Benefit Pay' }}
                                </option>
                            </select>
                            @error('payment_type')
                                <span class="error text-danger tw-text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-20 ">
                        <div class="col-6 ">
                            <label for="name"
                                class="form-label fw-semibold text-primary-light text-sm mb-8">{{ $lang->data['notes'] ?? 'Notes' }}
                                </label>
                            <input type="text" class="form-control radius-8"
                                placeholder="{{ $lang->data['notes'] ?? 'Notes' }}"
                                wire:model="notes">
                            @error('notes')
                                <span class="error text-danger tw-text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-6">
                            <button
                                class="tw-px-2 col-6 tw-text-xs tw-justify-center tw-font-semibold tw-py-3 tw-mt-[30px]  bg-success-600 tw-rounded-md tw-text-white tw-flex tw-items-center tw-gap-1.5 tw-w-full tw-border-0 tw-shadow-md "
                                wire:click="add_payment">
                                <span>{{ $lang->data['add_payment'] ?? 'Add Payment' }}</span>
                            </button>
                        </div>
                        
                    </div>
                  
                   

                    <div class="modal-footer tw-mt-12">
                        <button
                        class="tw-justify-center tw-font-semibold tw-py-2 tw-h-full bg-primary-600 tw-rounded-md tw-text-white tw-flex tw-items-center tw-gap-1.5 tw-px-12 tw-border-0 tw-shadow-md "
                        wire:click.prevent="save">
                        <span>{{ $lang->data['save_print'] ?? 'Save & Print' }}</span>
                    </button></div>
                </div>
            </div>
        </div>
    </div>

        <div class="modal fade " id="addcustomer" tabindex="-1" role="dialog" aria-labelledby="addcustomerLabel"
            aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title fw-600" id="addcustomerLabel">
                            {{ $lang->data['add_customer'] ?? 'Add Customer' }}
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{ $lang->data['customer_name'] ?? 'Customer Name' }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" required class="form-control"
                                        placeholder="{{ $lang->data['enter_customer_name'] ?? 'Enter Customer Name' }}"
                                        wire:model="customer_name">
                                    @error('customer_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{ $lang->data['phone_number'] ?? 'Phone Number' }}
                                        <span class="text-danger">*</span></label>
                                    <input type="text" required class="form-control"
                                        placeholder="{{ $lang->data['enter_phone_number'] ?? 'Enter Phone Number' }}"
                                        wire:model="customer_phone">
                                    @error('customer_phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{ $lang->data['email'] ?? 'Email' }}</label>
                                    <input type="text" class="form-control"
                                        placeholder="{{ $lang->data['enter_email'] ?? 'Enter Email' }}"
                                        wire:model="email">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{ $lang->data['tax_number'] ?? 'Tax Number' }}</label>
                                    <input type="text" class="form-control"
                                        placeholder="{{ $lang->data['enter_tax_number'] ?? 'Enter Tax Number' }}"
                                        wire:model="tax_no">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">{{ $lang->data['address'] ?? 'Address' }}</label>
                                    <textarea type="text" class="form-control" placeholder="{{ $lang->data['enter_address'] ?? 'Enter Address' }}"
                                        wire:model="address"></textarea>
                                </div>
                                <div class="col-md-12 mb-1">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="employee" checked
                                            wire:model="is_active">
                                        <label class="form-check-label"
                                            for="employee">{{ $lang->data['is_active'] ?? 'Is Active' }} ?</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                            <button type="button" class="btn btn-primary"
                                wire:click.prevent="createCustomer()">{{ $lang->data['save'] ?? 'Save' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script wire:ignore>
            function posFunction() {
                return {
                    detached: false,
                    shown: false,
                    init() {
                        if (window.innerWidth < 1024) {
                            this.detached = true;
                        } else {
                            this.detached = false;
                        }
                        window.addEventListener('resize', (e) => {
                            if (window.innerWidth < 1024) {
                                this.detached = true;
                            } else {
                                this.detached = false;
                            }
                        })

                        this.$wire.on('reloadpage', orderId => {
                            if (this.$wire.order) {
                                window.location.href = '{{ url('admin/orders/') }}';
                            } else {
                                window.location.reload();

                            }
                        })
                        this.$wire.on('printPageOrder', orderId => {
                            var $id = orderId;
                            window.open(
                                '{{ url('admin/orders/print') }}' + '/' + $id,
                                '_blank'
                            );
                            window.onfocus = function() {
                                setTimeout(function() {

                                    window.location.href = '{{ url('admin/orders/') }}';

                                }, 1000);
                            }
                        })
                        this.$wire.on('printPage', orderId => {
                            var $id = orderId;
                            window.open(
                                '{{ url('admin/orders/print') }}' + '/' + $id,
                                '_blank'
                            );
                            window.onfocus = function() {
                                setTimeout(function() {
                                    window.location.reload()
                                }, 1000);
                            }
                        })
                    },
                }
            }
        </script>
        <livewire:components.check-financial-year-component />
    </div>
</div>
