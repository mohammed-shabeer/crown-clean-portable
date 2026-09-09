<?php

namespace App\Livewire\CustomerOrder;

use Livewire\Component;
use App\Models\Addon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Service;
use App\Models\ServiceDetail;
use App\Models\ServiceType;
use App\Models\OrderAddonDetail;
use App\Models\Translation;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class PlaceOrder extends Component
{
    public $services;
    public $service_types;
    public $service;
    public $selected_type = [];
    public $addons;
    public $selected_addons = [];
    
    // Cart items
    public $cart_items = [];
    public $cart_addons = [];
    
    // Customer info
    public $customer_name;
    public $customer_phone;
    public $customer_email;
    public $customer_address;
    public $special_instructions;
    
    // Order details
    public $order_id;
    public $date;
    public $delivery_date;
    public $discount = 0;
    
    // Totals
    public $sub_total = 0;
    public $addon_total = 0;
    public $tax_percent = 0;
    public $tax = 0;
    public $total = 0;
    
    // UI State
    public $step = 1; // 1 = customer info, 2 = select services, 3 = confirmation
    public $order_placed = false;
    public $placed_order = null;
    
    public $lang;
    public $search_query;

    #[Layout('components.layouts.customer'), Title('Place Order')]
    public function render()
    {
        return view('livewire.customer-order.place-order');
    }

    public function mount()
    {
        $this->services = Service::where('is_active', 1)->latest()->get();
        $this->addons = Addon::where('is_active', 1)->latest()->get();
        $this->date = Carbon::today()->toDateString();
        $this->delivery_date = Carbon::today()->addDays(2)->toDateString();
        $this->tax_percent = getTaxPercentage();
        $this->generateOrderID();
        $this->service_types = collect();
        
        if (session()->has('selected_language')) {
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $this->lang = Translation::where('default', 1)->first();
        }
    }

    public function updated($name, $value)
    {
        if ($value == '') data_set($this, $name, null);
        
        if ($name == 'search_query' && $value != '') {
            $this->services = Service::where('is_active', 1)
                ->where('service_name', 'like', '%' . $value . '%')
                ->latest()->get();
        } elseif ($name == 'search_query' && $value == '') {
            $this->services = Service::where('is_active', 1)->latest()->get();
        }
        
        $this->calculateTotal();
    }

    public function generateOrderID()
    {
        $code_prefix = 'ORD-';
        $ordernumber = Order::orderBy('id', 'desc')->first();
        
        if ($ordernumber && $ordernumber->order_number != "") {
            $code = explode("-", $ordernumber->order_number);
            $new_code = $code[1] + 1;
            $new_code = str_pad($new_code, 4, "0", STR_PAD_LEFT);
            $this->order_id = $code_prefix . $new_code;
        } else {
            $this->order_id = $code_prefix . '0001';
        }
    }

    public function selectService($id)
    {
        $this->selected_type = [];
        $this->service = Service::where('id', $id)->first();
        $this->service_types = collect();
        
        if ($this->service) {
            $servicedetails = ServiceDetail::where('service_id', $id)->get();
            foreach ($servicedetails as $row) {
                $servicetype = ServiceType::where('id', $row->service_type_id)->first();
                if ($servicetype) {
                    $servicetype['price'] = $row->service_price;
                    $servicetype['formatted_price'] = getFormattedCurrency($row->service_price);
                    $this->service_types->push($servicetype->toArray());
                }
            }
        }
        
        if ($this->service_types && count($this->service_types) > 0) {
            $first = $this->service_types->first();
            if ($first) {
                $this->selected_type[$first['id']] = true;
            }
        }
    }

    public function addToCart()
    {
        if (!$this->service) {
            return;
        }

        $anySelected = false;
        foreach ($this->selected_type as $item) {
            if ($item === true) {
                $anySelected = true;
                break;
            }
        }

        if (!$anySelected) {
            $this->addError('service_error', 'Please select at least one service type');
            return;
        }

        $tax_type = getTaxType();
        
        foreach ($this->selected_type as $typeId => $value) {
            if ($value === true) {
                $serviceDetail = ServiceDetail::where('service_id', $this->service->id)
                    ->where('service_type_id', $typeId)
                    ->first();
                $serviceType = ServiceType::find($typeId);
                
                if ($serviceDetail && $serviceType) {
                    $price = $serviceDetail->service_price;
                    $selling_price = $price;
                    
                    if ($tax_type == 2) {
                        $price = $price * (100 / (100 + $this->tax_percent));
                    }
                    
                    $cartKey = $this->service->id . '_' . $typeId;
                    
                    if (isset($this->cart_items[$cartKey])) {
                        $this->cart_items[$cartKey]['quantity']++;
                    } else {
                        $this->cart_items[$cartKey] = [
                            'service_id' => $this->service->id,
                            'service_name' => $this->service->service_name,
                            'service_type_id' => $typeId,
                            'service_type_name' => $serviceType->service_type_name,
                            'price' => $price,
                            'selling_price' => $selling_price,
                            'quantity' => 1,
                            'icon' => $this->service->icon
                        ];
                    }
                }
            }
        }
        
        $this->selected_type = [];
        $this->service = null;
        $this->service_types = collect();
        $this->dispatch('closemodal');
        $this->calculateTotal();
    }

    public function increaseQuantity($key)
    {
        if (isset($this->cart_items[$key])) {
            $this->cart_items[$key]['quantity']++;
            $this->calculateTotal();
        }
    }

    public function decreaseQuantity($key)
    {
        if (isset($this->cart_items[$key])) {
            if ($this->cart_items[$key]['quantity'] > 1) {
                $this->cart_items[$key]['quantity']--;
            } else {
                unset($this->cart_items[$key]);
            }
            $this->calculateTotal();
        }
    }

    public function removeItem($key)
    {
        if (isset($this->cart_items[$key])) {
            unset($this->cart_items[$key]);
            $this->calculateTotal();
        }
    }

    public function toggleAddon($addonId)
    {
        if (isset($this->selected_addons[$addonId]) && $this->selected_addons[$addonId] === true) {
            $this->selected_addons[$addonId] = false;
        } else {
            $this->selected_addons[$addonId] = true;
        }
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->sub_total = 0;
        $this->addon_total = 0;
        $this->tax = 0;
        $this->total = 0;

        $tax_type = getTaxType();
        $itemtaxtotal2 = 0;
        $sub_total = 0;

        // Calculate cart items total
        foreach ($this->cart_items as $item) {
            $itemtaxtotal = 0;
            if ($tax_type == 2) {
                $itemtotallocal = ($item['selling_price'] * $item['quantity']) * (100 / (100 + $this->tax_percent ?? 0));
                $itemtaxtotal = ($item['selling_price'] * $item['quantity']) - $itemtotallocal;
                $itemtaxtotal2 += $itemtaxtotal;
                $sub_total += $itemtotallocal;
            } else {
                $itemtotallocal = ($item['selling_price'] * $item['quantity']);
                $itemtaxtotal = $itemtotallocal * $this->tax_percent / 100;
                $itemtaxtotal2 += $itemtaxtotal;
                $sub_total += $itemtotallocal;
            }
        }

        // Calculate addons total
        if ($this->selected_addons) {
            foreach ($this->selected_addons as $key => $value) {
                if ($value === true) {
                    $itemtaxtotal = 0;
                    $addon = Addon::where('id', $key)->first();
                    if ($addon) {
                        if ($tax_type == 2) {
                            $itemtotallocal = ($addon->addon_price) * (100 / (100 + $this->tax_percent ?? 0));
                            $itemtaxtotal = ($addon->addon_price) - $itemtotallocal;
                            $itemtaxtotal2 += $itemtaxtotal;
                            $sub_total += $itemtotallocal;
                            $this->addon_total += $itemtotallocal;
                        } else {
                            $itemtotallocal = ($addon->addon_price);
                            $itemtaxtotal = $itemtotallocal * $this->tax_percent / 100;
                            $itemtaxtotal2 += $itemtaxtotal;
                            $this->addon_total += $itemtotallocal;
                            $sub_total += $itemtotallocal;
                        }
                    }
                }
            }
        }

        $this->sub_total = $sub_total;
        $this->tax = $itemtaxtotal2;
        $this->total = ($this->sub_total + $itemtaxtotal2) - $this->discount;
        $this->total = round($this->total, 3, PHP_ROUND_HALF_UP);
    }

    public function goToStep($step)
    {
        // Going to step 2 (services) - validate customer info first
        if ($step == 2) {
            $this->validate([
                'customer_name' => 'required|min:2',
                'customer_phone' => 'required|min:10',
            ], [
                'customer_name.required' => 'Please enter your name',
                'customer_name.min' => 'Name must be at least 2 characters',
                'customer_phone.required' => 'Please enter your phone number',
                'customer_phone.min' => 'Please enter a valid phone number',
            ]);
        }
        
        // Going to step 3 (confirmation) - validate cart has items
        if ($step == 3 && count($this->cart_items) == 0) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Please add at least one service to your cart']);
            return;
        }
        $this->step = $step;
    }

    public function placeOrder()
    {
        $this->validate([
            'customer_name' => 'required|min:2',
            'customer_phone' => 'required|min:10',
        ], [
            'customer_name.required' => 'Please enter your name',
            'customer_phone.required' => 'Please enter your phone number',
            'customer_phone.min' => 'Please enter a valid phone number',
        ]);

        if (count($this->cart_items) == 0) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Your cart is empty']);
            return;
        }

        $this->generateOrderID();
        $this->calculateTotal();

        // Check if customer exists, if not create one
        $customer = Customer::where('phone', $this->customer_phone)->first();
        if (!$customer) {
            $customer = Customer::create([
                'name' => $this->customer_name,
                'phone' => $this->customer_phone,
                'email' => $this->customer_email,
                'address' => $this->customer_address,
                'is_active' => 1,
            ]);
        }

        // Create the order
        $order = Order::create([
            'order_number' => $this->order_id,
            'customer_id' => $customer->id,
            'customer_name' => $this->customer_name,
            'phone_number' => $this->customer_phone,
            'order_date' => Carbon::now()->toDateTimeString(),
            'delivery_date' => Carbon::parse($this->delivery_date)->toDateTimeString(),
            'sub_total' => $this->sub_total,
            'addon_total' => $this->addon_total,
            'discount' => $this->discount ?? 0,
            'tax_percentage' => $this->tax_percent,
            'tax_amount' => $this->tax,
            'tax_type' => getTaxType(),
            'taxable_amount' => $this->sub_total,
            'total' => $this->total,
            'note' => $this->special_instructions,
            'status' => 0, // Pending
            'order_type' => 2, // 2 = Customer self-order (online)
            'created_by' => null, // No staff member - customer placed
            'financial_year_id' => getFinancialYearId()
        ]);

        // Create order details
        foreach ($this->cart_items as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'service_id' => $item['service_id'],
                'service_name' => $item['service_type_name'],
                'service_quantity' => $item['quantity'],
                'service_detail_total' => $item['selling_price'] * $item['quantity'],
                'service_price' => $item['selling_price'],
                'color_code' => '',
            ]);
        }

        // Create addon details
        if ($this->selected_addons) {
            foreach ($this->selected_addons as $key => $value) {
                if ($value === true) {
                    $addon = Addon::where('id', $key)->first();
                    if ($addon) {
                        OrderAddonDetail::create([
                            'order_id' => $order->id,
                            'addon_id' => $addon->id,
                            'addon_name' => $addon->addon_name,
                            'addon_price' => $addon->addon_price,
                        ]);
                    }
                }
            }
        }

        // Send SMS notification if enabled
        if ($customer) {
            try {
                sendOrderCreateSMS($order->id, $customer->id);
            } catch (\Exception $e) {
                // SMS failed, but order is placed - don't break the flow
            }
        }

        $this->placed_order = $order;
        $this->order_placed = true;
        $this->step = 3;

        $this->dispatch('alert', ['type' => 'success', 'message' => 'Your order has been placed successfully!']);
    }

    public function startNewOrder()
    {
        $this->reset([
            'cart_items', 'cart_addons', 'selected_addons', 'customer_name', 
            'customer_phone', 'customer_email', 'customer_address', 
            'special_instructions', 'sub_total', 'addon_total', 'tax', 
            'total', 'step', 'order_placed', 'placed_order'
        ]);
        
        $this->step = 1;
        $this->services = Service::where('is_active', 1)->latest()->get();
        $this->date = Carbon::today()->toDateString();
        $this->delivery_date = Carbon::today()->addDays(2)->toDateString();
        $this->generateOrderID();
    }
}
