<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Invoice')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {

            .no-print {
                display: none !important;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        body {
            font-family: 'sans-serif';
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto max-w-2xl bg-white p-8 shadow-md">
        @yield('content')
    </div>
</body>

</html>
