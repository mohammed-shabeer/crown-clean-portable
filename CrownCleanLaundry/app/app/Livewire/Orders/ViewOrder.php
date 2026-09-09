<?php

namespace App\Livewire\Orders;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Customer;
use App\Models\MasterSettings;
use App\Models\Order;
use App\Models\OrderAddonDetail;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Translation;
use Illuminate\Support\Facades\Auth;

class ViewOrder extends Component
{
    public $order,$orderdetails,$orderaddons,$lang,$balance,$total,$customer,$payments,$sitename,$address,$phone,$paid_amount,$payment_type,$zipcode,$tax_number,$store_email,$notes;
    public $current_delivery_date;
    
    // Discount properties for payment modal
    public $modal_discount = 0;
    public $sub_total = 0;
    public $tax_percent = 0;
    #[Title('View Order')]
    public function render()
    {
        return view('livewire.orders.view-order');
    }

    /* process before mount */
    public function mount($id)
    {
        if(!\Illuminate\Support\Facades\Gate::allows('order_view')){
            abort(404);
        }
        if(Auth::user()->user_type==1)
        {  $this->order = Order::where('id',$id)->first();
            if($this->order) {
                $this->current_delivery_date = \Carbon\Carbon::parse($this->order->delivery_date)->toDateString();
            }
        } else {
            $this->order = Order::where('created_by',Auth::user()->id)->where('id',$id)->first();
            if($this->order) {
                $this->current_delivery_date = \Carbon\Carbon::parse($this->order->delivery_date)->toDateString();
                }
        }
        if(!$this->order)
        {
            abort(404);
        }
        $this->customer = Customer::where('id',$this->order->customer_id)->first();
        $this->orderaddons = OrderAddonDetail::where('order_id',$this->order->id)->get();
        $this->orderdetails = OrderDetail::where('order_id',$this->order->id)->get();
        $this->payments = Payment::where('order_id',$this->order->id)->get();
        $settings = new MasterSettings();
        $site = $settings->siteData();
        if(isset($site['default_application_name']))
        {   /* if site  has default application name */
            $sitename = (($site['default_application_name']) && ($site['default_application_name'] !=""))? $site['default_application_name'] : 'Laundry Box';
            $this->sitename = $sitename;
        }
        if(isset($site['default_phone_number']))
        {  /* if site has default phone number */
            $phone = (($site['default_phone_number']) && ($site['default_phone_number'] !=""))? $site['default_phone_number'] : '123456789';
            $this->phone = $phone;
        }
        if(isset($site['default_address']))
        {
            /* if site has default address */
            $address = (($site['default_address']) && ($site['default_address'] !=""))? $site['default_address'] : 'Address';
            $this->address = $address;
        }
        if(isset($site['default_zip_code']))
        {   /* if site has default zip code */
            $zipcode = (($site['default_zip_code']) && ($site['default_zip_code'] !=""))? $site['default_zip_code'] : 'ZipCode';
            $this->zipcode = $zipcode;
        }
        if(isset($site['store_tax_number']))
        {   /* if site has store tax number */
            $tax_number = (($site['store_tax_number']) && ($site['store_tax_number'] !=""))? $site['store_tax_number'] : 'Tax Number';
            $this->tax_number = $tax_number;
        }
        if(isset($site['store_email']))
        {   /* if site has store email */
            $store_email = (($site['store_email']) && ($site['store_email'] !=""))? $site['store_email'] : 'store@store.com';
            $this->store_email = $store_email;
        }
        $this->balance = $this->order->total -  Payment::where('order_id',$this->order->id)->sum('received_amount');
        $this->paid_amount = $this->balance;

        // Initialize discount properties
        $this->modal_discount = $this->order->discount ?? 0;
        $this->sub_total = $this->order->sub_total ?? 0;
        $this->tax_percent = $this->order->tax_percentage ?? getTaxPercentage();

        if(session()->has('selected_language'))
        {   /* session has selected language */
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        }
        else{
            $this->lang = Translation::where('default',1)->first();
        }
    }

    #[Computed()]
    public function calculatedTotal()
    {
        if (!$this->order) {
            return 0;
        }
        $taxableAmount = $this->sub_total - $this->modal_discount;
        if ($taxableAmount < 0) {
            $taxableAmount = 0;
        }

        $taxType = getTaxType();
        if ($taxType == 'exclusive') {
            $newTax = ($taxableAmount * $this->tax_percent) / 100;
        } else {
            $newTax = ($taxableAmount * $this->tax_percent) / (100 + $this->tax_percent);
        }

        return $taxableAmount + ($taxType == 'exclusive' ? $newTax : 0);
    }

    /* add the payment */
    public function addPayment()
    {
        if($this->order->status == 4)
        {
            return 0;
        }
        $this->validate([
            'paid_amount'   => 'required',
            'payment_type'  => 'required',
        ]);

        // Update order with new discount and recalculated total if discount changed
        if ($this->modal_discount != $this->order->discount) {
            $taxableAmount = $this->sub_total - $this->modal_discount;
            if ($taxableAmount < 0) {
                $taxableAmount = 0;
            }
            $taxType = getTaxType();
            if ($taxType == 'exclusive') {
                $newTax = ($taxableAmount * $this->tax_percent) / 100;
            } else {
                $newTax = ($taxableAmount * $this->tax_percent) / (100 + $this->tax_percent);
            }
            $newTotal = $taxableAmount + ($taxType == 'exclusive' ? $newTax : 0);

            $this->order->update([
                'discount' => $this->modal_discount,
                'tax_amount' => $newTax,
                'total' => $newTotal,
            ]);
            $this->order->refresh();
        }

        // Recalculate balance with updated total
        $this->balance = $this->order->total - Payment::where('order_id', $this->order->id)->sum('received_amount');

        /* if paid amount > balance */
        if($this->paid_amount > $this->balance)
        {
            $this->addError('payment_type','Amount cannot be greater than balance');
            return 0;
        }
        Payment::create([
            'payment_date'  => \Carbon\Carbon::today(),
            'customer_id'   => $this->customer->id ?? null,
            'customer_name' => $this->customer->name ?? null,
            'payment_note'  => $this->notes,
            'order_id'  => $this->order->id,
            'payment_type'  => $this->payment_type,
            'financial_year_id' => getFinancialYearId(),
            'received_amount'   => $this->paid_amount,
            'created_by'    => Auth::user()->id,
        ]);
        $this->payments = Payment::where('order_id',$this->order->id)->get();
        $this->balance = $this->order->total -  Payment::where('order_id',$this->order->id)->sum('received_amount');
        $this->paid_amount = $this->balance;
        $this->notes = '';
        $this->payment_type = '';
        $this->dispatch('closemodal');
        $this->dispatch(
            'alert', ['type' => 'success',  'message' => 'Payment Successfully Added!']);
    }
    /* change the status */
    public function changeStatus($status)
    {
        $this->order->status = $status;
        
        $this->order->save();
        $message = sendOrderStatusChangeSMS($this->order->id,$status);
        if($message)
        {
            $this->dispatch(
                'alert', ['type' => 'error',  'message' => $message,'title'=>'SMS Error']);
        }
        $this->dispatch(
            'alert', ['type' => 'success',  'message' => 'Status Successfully Updated!']);
    }

    public function changeDeliveryDate(){
        if($this->order) {
            $this->order->delivery_date = $this->current_delivery_date;
            $this->order->save();
            $this->emit('closemodal');
            $this->dispatch(
                'alert', ['type' => 'success',  'message' => 'Delivery date Updated!']);
        }

    }
}
