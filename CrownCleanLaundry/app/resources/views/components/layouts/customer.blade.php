<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if(isRTL() == true) dir="rtl" @endif>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/laundry_icon.png') }}" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link href="{{ asset('assets/plugins/toastr.min.css') }}" rel="stylesheet" />
    @vite('resources/css/app.css')
    <title>{{ $title ?? 'Place Order' }} - {{ getApplicationName() }}</title>
    <x-theme-component/>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            background-color: #f5f6fa;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .customer-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 0;
        }
        .service-card {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .service-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .service-card.selected {
            border-color: var(--primary-color) !important;
            background: rgba(var(--primary-color-rgb), 0.1);
        }
        /* Mobile-first responsive styles */
        @media (max-width: 768px) {
            .modal-dialog {
                margin: 10px;
                max-width: calc(100% - 20px);
            }
        }
        /* Fix input styles for mobile */
        input, textarea, select {
            font-size: 16px !important;
        }
        [wire\:loading][wire\:loading], [wire\:loading\.delay][wire\:loading\.delay], [wire\:loading\.inline-block][wire\:loading\.inline-block], [wire\:loading\.inline][wire\:loading\.inline], [wire\:loading\.block][wire\:loading\.block], [wire\:loading\.flex][wire\:loading\.flex], [wire\:loading\.table][wire\:loading\.table], [wire\:loading\.grid][wire\:loading\.grid], [wire\:loading\.inline-flex][wire\:loading\.inline-flex] { display: none; }
        [wire\:offline][wire\:offline] { display: none; }
        [wire\:dirty]:not(textarea):not(input):not(select) { display: none; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="tw-text-sm">
    <div class="customer-container">
        {{ $slot }}
    </div>

    <script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('vendor/livewire/livewire.min.js') }}" data-csrf="{{ csrf_token() }}" data-update-uri="{{ url('/livewire/update') }}"></script>

    <script>
        "use strict";
        document.addEventListener('livewire:init', () => {
            Livewire.on('closemodal', (event) => {
                $('.modal').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').removeAttr('style');
            });

            Livewire.on('alert', (event) => {
                toastr[event[0].type](event[0].message, 
                event[0].title ?? ''), toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                }
            });

            Livewire.on('reloadpage', (event) => {
                window.location.reload();
            });
        });
    </script>

    @stack('js')
</body>
</html>
