<x-guest-layout>
  <x-authentication-card>
    <x-slot name="logo">
      <x-authentication-card-logo />
    </x-slot>

    <x-validation-errors class="mb-4" />

    @session('status')
      <div class="mb-4 text-sm font-medium text-green-600 dark:text-green-400">
        {{ $value }}
      </div>
    @endsession

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div>
        <x-label for="email" value="{{ __('Email atau Nomor Telepon') }}" />
        <x-input id="email" class="mt-1 block w-full rounded-lg" type="text" name="email" :value="old('email')" required
          autofocus autocomplete="username" />
      </div>

      <div class="mt-4">
        <x-label for="password" value="{{ __('Kata Sandi') }}" />
        <x-input id="password" class="mt-1 block w-full rounded-lg" type="password" name="password" required
          autocomplete="current-password" />
      </div>

      <div class="mt-4 flex items-center justify-between">
        <label for="remember_me" class="flex items-center">
          <x-checkbox id="remember_me" name="remember" checked />
          <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Ingat saya') }}</span>
        </label>

        @if (Route::has('password.request'))
          <a class="text-sm text-indigo-600 hover:text-indigo-500 no-underline"
            href="{{ route('password.request') }}">
            {{ __('Lupa sandi?') }}
          </a>
        @endif
      </div>

      {{-- Bagian Button: Diubah menjadi stack (atas-bawah) sesuai gambar --}}
      <div class="mt-6 flex flex-col gap-3">
        <x-button class="w-full justify-center py-3 bg-indigo-600 rounded-lg">
          {{ __('MASUK SEKARANG') }}
        </x-button>

        @if (Route::has('register'))
          <a href="{{ route('register') }}" class="w-full">
            <x-secondary-button class="w-full justify-center py-3 rounded-lg" type="button">
              {{ __('Daftar Akun Baru') }}
            </x-secondary-button>
          </a>
        @endif
      </div>
    </form>
  </x-authentication-card>
</x-guest-layout>