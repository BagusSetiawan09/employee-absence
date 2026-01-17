<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  @livewireStyles
</head>

<body class="font-sans antialiased"
      style="background-image: url('{{ asset('image/background.jpg') }}');
             background-size: cover;
             background-position: center;
             background-repeat: no-repeat;
             background-attachment: fixed;">

  <div class="font-sans text-gray-900 min-h-screen flex flex-col justify-center items-center backdrop-blur-sm"
       style="background-color: rgba(255, 255, 255, 0.5);">

    <div class="absolute right-4 top-4">
      <x-theme-toggle x-data />
    </div>

    <div class="w-full">
        {{ $slot }}
    </div>

  </div>

  <x-sigsegv-core-dumped />

  @livewireScripts
</body>

</html>