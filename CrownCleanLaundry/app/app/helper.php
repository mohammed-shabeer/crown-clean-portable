<?php
/* get expense category type */

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client;

function getExpenseCategoryType($type)
{
    if (session()->has('selected_language')) {
        $lang = \App\Models\Translation::where('id', session()->get('selected_language'))->first();
    } else {
        $lang = \App\Models\Translation::where('default', 1)->first();
    }
    if ($lang) {
        switch ($type) {
            case 1:
                return $lang->data['asset'] ?? 'Asset';
            case 2:
                return  $lang->data['liability'] ?? 'Liability';
            default:
                return '';
        }
    }
    switch ($type) {
        case 1:
            return 'Asset';
        case 2:
            return 'Liability';
        default:
            return '';
    }
}
/* get payment mode */
function getpaymentMode($type)
{
    if (session()->has('selected_language')) {
        $lang = \App\Models\Translation::where('id', session()->get('selected_language'))->first();
    } else {
        $lang = \App\Models\Translation::where('default', 1)->first();
    }
    if ($lang) {
        switch ($type) {
            case 1:
                return $lang->data['cash'] ?? 'CASH';
            case 2:
                return $lang->data['card'] ?? 'CARD';
            case 3:
                return $lang->data['benefit_pay'] ?? 'BENEFIT PAY';
            default:
                return '';
        }
    } else {
    switch ($type) {
        case 1:
            return 'CASH';
        case 2:
            return 'CARD';
        case 3:
            return 'BENEFIT PAY';
        default:
            return '';
    }
}
}
/* get financial year */
function getFinancialYearId()
{
    $settings = new App\Models\MasterSettings();
    $site = $settings->siteData();
    if (isset($site['default_financial_year'])) {
        $year_id = (($site['default_financial_year']) && ($site['default_financial_year'] != "")) ? $site['default_financial_year'] : '';
        return $year_id;
    }
    return null;
}
/* get Currency */
function getCurrency()
{
    $settings = new App\Models\MasterSettings();
    $site = $settings->siteData();
    if (isset($site['default_currency'])) {
        $currency = (($site['default_currency']) && ($site['default_currency'] != "")) ? $site['default_currency'] : '$';
        return $currency;
    }
    return '$';
}
/* get Tax percentage */
if(!function_exists('getTaxPercentage'))
{
    function getTaxPercentage()
    {
        $settings = new App\Models\MasterSettings();
        $site = $settings->siteData();
        if(isset($site['default_tax_percentage']))
        {
            $currency = (($site['default_tax_percentage']) && ($site['default_tax_percentage'] !=""))? $site['default_tax_percentage'] : 0;
            return $currency;
        }
        return 0;
    }
}



/* get order status */
function getOrderStatus($status, $preventlang = null)
{
    if (session()->has('selected_language')) {
        $lang = \App\Models\Translation::where('id', session()->get('selected_language'))->first();
    } else {
        $lang = \App\Models\Translation::where('default', 1)->first();
    }
    if ($lang == null || $preventlang) {
        switch ($status) {
            case -1:
                return 'All Orders';
            case 0:
                return 'Pending';
            case 1:
                return 'Processing';
            case 2:
                return 'Ready To Deliver';
            case 3:
                return 'Delivered';
            case 4:
                return 'Returned';
        }
    } else {
        switch ($status) {
            case -1:
                return 'All Orders';
            case 0:
                return $lang->data['pending'] ?? 'Pending';
            case 1:
                return $lang->data['processing'] ?? 'Processing';
            case 2:
                return $lang->data['ready_to_deliver'] ?? 'Ready To Deliver';
            case 3:
                return $lang->data['delivered'] ?? 'Delivered';
            case 4:
                return $lang->data['returned'] ?? 'Returned';
        }
    }
}
/* get order status wit color */
function getOrderStatusWithColor($status)
{
    switch ($status) {
        case 0:
            return 'today-task-pending';
        case 1:
            return 'today-task-processing';
        case 2:
            return 'today-task-ready';
        case 3:
            return 'today-task-delivered';
        case 4:
            return 'today-task-returned';
    }
}
/* get order status with color for change status screen */
function getOrderStatusWithColorKan($status)
{
    switch ($status) {
        case 0:
            return 'scrum-task-pending';
        case 1:
            return 'scrum-task-processing';
        case 2:
            return 'scrum-task-ready';
    }
}
/* get priner type */
function getPrinterType()
{
    $settings = new App\Models\MasterSettings();
    $site = $settings->siteData();
    if (isset($site['default_printer'])) {
        $printerType = (($site['default_printer']) && ($site['default_printer'] != "")) ? $site['default_printer'] : 1;
        return $printerType;
    }
    return 1;
}

/* get favicon */
function getFavIcon()
{
    $settings = new App\Models\MasterSettings();
    $site = $settings->siteData();
    if (isset($site['default_favicon']) && file_exists(public_path($site['default_favicon']))) {
        $favicon = (($site['default_favicon']) && ($site['default_favicon'] != "")) ? $site['default_favicon'] : 'assets/img/favicon.png';
        return $favicon;
    }
    return asset('assets/img/logo-ct.png');
}


/* get getAppliation Name */
function getApplicationName()
{
    $settings = new App\Models\MasterSettings();
    $site = $settings->siteData();
    if (isset($site['default_application_name'])) {
        $favicon = (($site['default_application_name']) && ($site['default_application_name'] != "")) ? $site['default_application_name'] : 'Laundry Box';
        return $favicon;
    }
    return 'Laundry Box';
}


/* get site logo */
function getSiteLogo()
{
    $settings = new App\Models\MasterSettings();
    $site = $settings->siteData();
    if (isset($site['default_logo']) && file_exists(public_path($site['default_logo']))) {
        $favicon = (($site['default_logo']) && ($site['default_logo'] != "")) ? $site['default_logo'] : 'assets/img/logo-ct.png';
        return $favicon;
    }
    return asset('assets/img/logo-ct.png');
}

//Checks if Selected language is RTL
function isRTL()
{
    if (session()->has('selected_language')) {
        $lang = \App\Models\Translation::where('id', session()->get('selected_language'))->first();
        if ($lang) {
            if ($lang->is_rtl) {
                return true;
            }
        }
    }
    return false;
}

function getCountryCode()
{
    $settings = new App\Models\MasterSettings();
    $site = $settings->siteData();
    if (isset($site['country_code']) && $site['country_code'] != '') {
        return '+'.$site['country_code'];
    }
    return '+91';
}

function smsOrderDeliveredOnly()
{
    $settings = new App\Models\MasterSettings();
    $site = $settings->siteData();
    if (isset($site['sms_delivered_only']) && $site['sms_delivered_only'] == 1) {
        return true;
    }
    return false;
}

function smsOrderReadyToDeliverOnly()
{
    $settings = new App\Models\MasterSettings();
    $site = $settings->siteData();
    if (isset($site['sms_ready_to_deliver_only']) && $site['sms_ready_to_deliver_only'] == 1) {
        return true;
    }
    return false;
}


function isSMSEnabled()
{
    $settings = new App\Models\MasterSettings();
    $site = $settings->siteData();
    if (isset($site['sms_enabled']) && ($site['sms_enabled'] == 1)) {
        return true;
    }
    return false;
}

function sendOrderCreateSMS($order, $to)
{

    if (isSMSEnabled() == true) {
        $settings = new App\Models\MasterSettings();
        $site = $settings->siteData();
        $messageerror = null;
        try {
            $myorder = Order::find($order);
            if (smsOrderDeliveredOnly() && smsOrderReadyToDeliverOnly()) {
                return;
            }
            if (smsOrderDeliveredOnly()) {
                return;
            }
            if (smsOrderReadyToDeliverOnly()) {
                return;
            }

            $account_sid = (($site['sms_account_sid']) && ($site['sms_account_sid'] != "")) ? $site['sms_account_sid'] : '';
            $auth_token = (($site['sms_auth_token']) && ($site['sms_auth_token'] != "")) ? $site['sms_auth_token'] : '';
            $twilio_number = (($site['sms_twilio_number']) && ($site['sms_twilio_number'] != "")) ? $site['sms_twilio_number'] : '';

            $client = new Client($account_sid, $auth_token);
            $customer = Customer::find($to);
            if ($customer) {
                $phoneInt = (int)$customer->phone;
                $message = getFormatedTextSMS($order, 1);
                $client->messages->create(
                    getCountryCode() . $phoneInt,
                    ['from' => $twilio_number, 'body' => $message]
                );
            }
        } catch (\Exception $e) {
            $messageerror = $e->getMessage();
            if ($e->getCode() == 21211) {
                $messageerror = 'Could not send SMS,Because the phone number is invalid';
            }
        }
        return $messageerror;
    }
}

function sendOrderStatusChangeSMS($order, $to_status)
{
    if (isSMSEnabled() == true) {
        $settings = new App\Models\MasterSettings();
        $site = $settings->siteData();
        $messageerror = null;
        try {
            $myorder = Order::find($order);
            if (smsOrderDeliveredOnly() && smsOrderReadyToDeliverOnly()) {
                if ($myorder->status != 3 && $myorder->status != 2) {
                    return;
                }
            }
            if (smsOrderDeliveredOnly() && (!smsOrderReadyToDeliverOnly())) {
                if (smsOrderDeliveredOnly() && $myorder->status != 3) {
                    return;
                }
            }
            if ((!smsOrderDeliveredOnly()) && (smsOrderReadyToDeliverOnly())) {
                if (smsOrderReadyToDeliverOnly() && $myorder->status != 2) {
                    return;
                }
            }
            $account_sid = (($site['sms_account_sid']) && ($site['sms_account_sid'] != "")) ? $site['sms_account_sid'] : '';
            $auth_token = (($site['sms_auth_token']) && ($site['sms_auth_token'] != "")) ? $site['sms_auth_token'] : '';
            $twilio_number = (($site['sms_twilio_number']) && ($site['sms_twilio_number'] != "")) ? $site['sms_twilio_number'] : '';
            $client = new Client($account_sid, $auth_token);
            $customer = Customer::find($myorder->customer_id);
            if ($customer) {
                if ($to_status == 2) {
                    $message = getFormatedTextSMS($order, 3);
                } else {
                    $message = getFormatedTextSMS($order, 2);
                }
                $phoneInt = (int)$customer->phone;
                $client->messages->create(
                    getCountryCode() . $phoneInt,
                    ['from' => $twilio_number, 'body' => $message]
                );
            }
        } catch (\Exception $e) {
            $messageerror = $e->getMessage();
            if ($e->getCode() == 21211) {
                $messageerror = 'Could not send SMS,Because the phone number is invalid';
            }
        }
        return $messageerror;
    }
}

//get formatted currency
function getFormattedCurrency($value)
{
    $settings = new App\Models\MasterSettings();
    $site = $settings->siteData();
    $symbol = $site['default_currency'] ?? '$';
    $alignment = $site['default_currency_alignment'] ?? 1;
    $value = number_format($value, 3);
    if ($alignment == 1) {
        return $symbol . ' ' . $value;
    }
    return $value . ' ' . $symbol;
}


function getFormatedTextSMS($order, $type)
{
    $myorder = Order::find($order);
    $settings = new App\Models\MasterSettings();
    $site = $settings->siteData();
    $string = null;
    if ($type == 1) {
        if (isset($site['sms_createorder']) && $site['sms_createorder'] != '') {
            $string = $site['sms_createorder'] ?? 'Hi <name> An Order #<order_number> was created and will be delivered on <delivery_date> Your Order Total is <total>.';
        } else {
            $string = 'Hi <name> An Order #<order_number> was created and will be delivered on <delivery_date> Your Order Total is <total>.';
        }
    } else {
        if (isset($site['sms_statuschange']) && $site['sms_statuschange'] != '') {
            $string = $site['sms_statuschange'] ?? 'Hi <name> Your Order #<order_number> status has been changed to <status> on <current_time>';
        } else {
            $string =  'Hi <name> Your Order #<order_number> status has been changed to <status> on <current_time>';
        }
    }

    $replacer = [
        '<name>' => 'Customer Name',
        '<order_date>' => 'Order Date',
        '<delivery_date>' => 'Delivery Date',
        '<no_of_products>' => 'No Of Products',
        '<total>' => 'Total',
        '<discount>' => 'Discount',
        '<paid>' => 'Paid Amount',
        '<status>'  => 'Status',
        '<order_number>'    => 'Order Number',
        '<current_time>'    => 'Current Time'
    ];
    $count = \App\Models\OrderDetail::where('order_id', $order)->count();
    $paid = \App\Models\Payment::where('order_id', $order)->sum('received_amount');
    $replacement = [
        $myorder->customer_name,
        \Carbon\Carbon::parse($myorder->order_date)->format('d/m/Y'),
        \Carbon\Carbon::parse($myorder->delivery_date)->format('d/m/Y'),
        $count,
        getCurrency() . number_format($myorder->total, 2),
        getCurrency() . number_format($myorder->discount, 2),
        getCurrency() . number_format($paid, 2),
        getOrderStatus($myorder->status),
        $myorder->order_number,
        \Carbon\Carbon::now()->format('d/m/Y h:i A')
    ];
    return str_replace(array_keys($replacer), array_values($replacement), $string);
}

if(!function_exists('getTaxType'))
{
    function getTaxType()
    {
        $settings = new App\Models\MasterSettings();
        $site = $settings->siteData();
        if(isset($site['default_tax_mode']))
        {
            $tax_type = (($site['default_tax_mode']) && ($site['default_tax_mode'] !=""))? $site['default_tax_mode'] : 1;
            return $tax_type;
        }
        return 1;
    }
}

/**
 * Get order type label
 * 1 = POS Order (created by staff)
 * 2 = Customer Self-Order (via QR/link)
 */
if(!function_exists('getOrderType'))
{
    function getOrderType($type)
    {
        if (session()->has('selected_language')) {
            $lang = \App\Models\Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $lang = \App\Models\Translation::where('default', 1)->first();
        }
        
        switch ($type) {
            case 1:
                return $lang->data['pos_order'] ?? 'POS Order';
            case 2:
                return $lang->data['online_order'] ?? 'Online Order';
            default:
                return $lang->data['pos_order'] ?? 'POS Order';
        }
    }
}

/**
 * Get order type badge class
 */
if(!function_exists('getOrderTypeBadgeClass'))
{
    function getOrderTypeBadgeClass($type)
    {
        switch ($type) {
            case 1:
                return 'bg-primary-100 text-primary-600';
            case 2:
                return 'bg-purple-100 text-purple-600';
            default:
                return 'bg-primary-100 text-primary-600';
        }
    }
}

/**
 * Get print settings
 */
if(!function_exists('getPrintSettings'))
{
    function getPrintSettings()
    {
        $settings = new App\Models\MasterSettings();
        $site = $settings->siteData();
        
        return [
            'show_addon' => isset($site['print_show_addon']) ? (bool)$site['print_show_addon'] : true,
            'show_subtotal' => isset($site['print_show_subtotal']) ? (bool)$site['print_show_subtotal'] : true,
            'show_notes' => isset($site['print_show_notes']) ? (bool)$site['print_show_notes'] : true,
            'show_tax' => isset($site['print_show_tax']) ? (bool)$site['print_show_tax'] : true,
            'show_discount' => isset($site['print_show_discount']) ? (bool)$site['print_show_discount'] : true,
            'show_gross_total' => isset($site['print_show_gross_total']) ? (bool)$site['print_show_gross_total'] : true,
            'font_size' => isset($site['print_font_size']) ? (int)$site['print_font_size'] : 14,
        ];
    }
}

/**
 * Get print setting value
 */
if(!function_exists('getPrintSetting'))
{
    function getPrintSetting($key, $default = true)
    {
        $settings = new App\Models\MasterSettings();
        $site = $settings->siteData();
        $settingKey = 'print_' . $key;
        
        if (isset($site[$settingKey])) {
            if ($key === 'font_size') {
                return (int)$site[$settingKey];
            }
            return (bool)$site[$settingKey];
        }
        
        return $default;
    }
}

/**
 * Get printer configuration settings
 */
if(!function_exists('getPrinterConfig'))
{
    function getPrinterConfig()
    {
        $settings = new App\Models\MasterSettings();
        $site = $settings->siteData();
        
        // Get paper size dimensions
        $paperSize = $site['paper_size'] ?? 'A4';
        $paperDimensions = getPaperDimensions($paperSize, $site);
        
        return [
            'printer_name' => $site['printer_name'] ?? '',
            'paper_size' => $paperSize,
            'paper_width' => $paperDimensions['width'],
            'paper_height' => $paperDimensions['height'],
            'orientation' => $site['print_orientation'] ?? 'portrait',
            'margin_top' => isset($site['margin_top']) ? (int)$site['margin_top'] : 10,
            'margin_bottom' => isset($site['margin_bottom']) ? (int)$site['margin_bottom'] : 10,
            'margin_left' => isset($site['margin_left']) ? (int)$site['margin_left'] : 10,
            'margin_right' => isset($site['margin_right']) ? (int)$site['margin_right'] : 10,
            'copies' => isset($site['print_copies']) ? (int)$site['print_copies'] : 1,
            'auto_print' => isset($site['auto_print']) ? (bool)$site['auto_print'] : false,
            'print_header_footer' => isset($site['print_header_footer']) ? (bool)$site['print_header_footer'] : false,
            'color_mode' => $site['color_mode'] ?? 'monochrome',
            'scale' => isset($site['print_scale']) ? (int)$site['print_scale'] : 100,
        ];
    }
}

/**
 * Get paper dimensions based on paper size
 */
if(!function_exists('getPaperDimensions'))
{
    function getPaperDimensions($paperSize, $site = null)
    {
        $dimensions = [
            'A4' => ['width' => 210, 'height' => 297],
            'A5' => ['width' => 148, 'height' => 210],
            'Letter' => ['width' => 216, 'height' => 279],
            'Legal' => ['width' => 216, 'height' => 356],
            '80mm' => ['width' => 80, 'height' => 297],
            '58mm' => ['width' => 58, 'height' => 297],
        ];
        
        if ($paperSize === 'custom' && $site) {
            return [
                'width' => isset($site['paper_width']) ? (int)$site['paper_width'] : 210,
                'height' => isset($site['paper_height']) ? (int)$site['paper_height'] : 297
            ];
        }
        
        return $dimensions[$paperSize] ?? $dimensions['A4'];
    }
}

/**
 * Generate CSS @page rules for print settings
 */
if(!function_exists('getPrintPageCSS'))
{
    function getPrintPageCSS()
    {
        $config = getPrinterConfig();
        
        $css = "@page {\n";
        
        // Paper size
        if ($config['paper_size'] === 'custom' || in_array($config['paper_size'], ['80mm', '58mm'])) {
            $css .= "    size: {$config['paper_width']}mm {$config['paper_height']}mm;\n";
        } else {
            $css .= "    size: {$config['paper_size']} {$config['orientation']};\n";
        }
        
        // Margins
        $css .= "    margin: {$config['margin_top']}mm {$config['margin_right']}mm {$config['margin_bottom']}mm {$config['margin_left']}mm;\n";
        
        $css .= "}\n";
        
        // Add print media query styles
        $css .= "@media print {\n";
        $css .= "    body {\n";
        $css .= "        -webkit-print-color-adjust: exact !important;\n";
        $css .= "        print-color-adjust: exact !important;\n";
        $css .= "        color-adjust: exact !important;\n";
        $css .= "    }\n";
        
        // Scale
        if ($config['scale'] != 100) {
            $scale = $config['scale'] / 100;
            $css .= "    html {\n";
            $css .= "        transform: scale({$scale});\n";
            $css .= "        transform-origin: top left;\n";
            $css .= "    }\n";
        }
        
        $css .= "}\n";
        
        return $css;
    }
}

/**
 * Get JavaScript print configuration object
 */
if(!function_exists('getPrintJSConfig'))
{
    function getPrintJSConfig()
    {
        $config = getPrinterConfig();
        return json_encode($config);
    }
}

/**
 * Send WhatsApp message using Twilio
 */
if(!function_exists('sendWhatsAppBill'))
{
    function sendWhatsAppBill($orderId, $customerId)
    {
        if (!isSMSEnabled()) {
            return 'SMS/WhatsApp is not enabled';
        }
        
        $settings = new App\Models\MasterSettings();
        $site = $settings->siteData();
        $messageerror = null;
        
        try {
            $order = App\Models\Order::find($orderId);
            $customer = App\Models\Customer::find($customerId);
            
            if (!$customer || !$customer->phone) {
                return 'Customer phone number not found';
            }
            
            $account_sid = $site['sms_account_sid'] ?? '';
            $auth_token = $site['sms_auth_token'] ?? '';
            $twilio_number = $site['sms_twilio_number'] ?? '';
            
            if (empty($account_sid) || empty($auth_token) || empty($twilio_number)) {
                return 'Twilio credentials not configured';
            }
            
            $client = new Twilio\Rest\Client($account_sid, $auth_token);
            
            // Build bill message
            $message = buildWhatsAppBillMessage($order, $customer);
            
            $phoneInt = (int)$customer->phone;
            $toNumber = 'whatsapp:' . getCountryCode() . $phoneInt;
            $fromNumber = 'whatsapp:' . $twilio_number;
            
            $client->messages->create(
                $toNumber,
                [
                    'from' => $fromNumber,
                    'body' => $message
                ]
            );
            
        } catch (\Exception $e) {
            $messageerror = $e->getMessage();
        }
        
        return $messageerror;
    }
}

/**
 * Build WhatsApp bill message based on print settings
 */
if(!function_exists('buildWhatsAppBillMessage'))
{
    function buildWhatsAppBillMessage($order, $customer)
    {
        $printSettings = getPrintSettings();
        $siteName = getApplicationName();
        
        $message = "📋 *{$siteName}*\n";
        $message .= "━━━━━━━━━━━━━━━\n";
        $message .= "*Order #:* {$order->order_number}\n";
        $message .= "*Date:* " . \Carbon\Carbon::parse($order->order_date)->format('l, d/m/Y h:i A') . "\n";
        $message .= "*Delivery:* " . \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') . "\n";
        $message .= "━━━━━━━━━━━━━━━\n";
        
        // Order items
        $orderDetails = App\Models\OrderDetail::where('order_id', $order->id)->get();
        foreach ($orderDetails as $item) {
            $service = App\Models\Service::find($item->service_id);
            $message .= "• {$service->service_name} [{$item->service_name}]\n";
            $message .= "  {$item->service_quantity} x " . getFormattedCurrency($item->service_price) . " = " . getFormattedCurrency($item->service_detail_total) . "\n";
        }
        
        $message .= "━━━━━━━━━━━━━━━\n";
        
        // Conditional elements based on print settings
        if ($printSettings['show_subtotal']) {
            $message .= "*Sub Total:* " . getFormattedCurrency($order->sub_total) . "\n";
        }
        
        if ($printSettings['show_addon'] && $order->addon_total > 0) {
            $message .= "*Addon:* " . getFormattedCurrency($order->addon_total) . "\n";
        }
        
        if ($printSettings['show_discount'] && $order->discount > 0) {
            $message .= "*Discount:* " . getFormattedCurrency($order->discount) . "\n";
        }
        
        if ($printSettings['show_tax']) {
            $message .= "*Tax ({$order->tax_percentage}%):* " . getFormattedCurrency($order->tax_amount) . "\n";
        }
        
        if ($printSettings['show_gross_total']) {
            $message .= "━━━━━━━━━━━━━━━\n";
            $message .= "*TOTAL:* " . getFormattedCurrency($order->total) . "\n";
        }
        
        if ($printSettings['show_notes'] && $order->note) {
            $message .= "\n*Notes:* {$order->note}\n";
        }
        
        $message .= "\nThank you for your business! 🙏";
        
        return $message;
    }
}