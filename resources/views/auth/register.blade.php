<x-guest-layout>
  <x-authentication-card>
    <x-slot name="logo">
      <x-authentication-card-logo />
    </x-slot>

    <x-validation-errors class="mb-4" />

    <form method="POST" action="{{ route('register') }}">
      @csrf

      {{-- Nama Lengkap --}}
      <div>
        <x-label for="name" value="{{ __('Nama Lengkap') }}" />
        <x-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus
          autocomplete="name" />
      </div>

      {{-- NIM (Wajib untuk Anak Magang) --}}
      <div class="mt-4">
        <x-label for="nim" value="{{ __('NIM (Nomor Induk Mahasiswa)') }}" />
        <x-input id="nim" class="mt-1 block w-full" type="text" name="nim" :value="old('nim')" required
          autocomplete="nim" />
      </div>

      {{-- Universitas --}}
      <div class="mt-4">
        <x-label for="university" value="{{ __('Universitas / Asal Instansi') }}" />
        <x-input id="university" class="mt-1 block w-full" type="text" name="university" :value="old('university')" required
          autocomplete="university" />
      </div>

      {{-- Email --}}
      <div class="mt-4">
        <x-label for="email" value="{{ __('Email') }}" />
        <x-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required
          autocomplete="username" />
      </div>

      {{-- Nomor Telepon --}}
      <div class="mt-4">
        <x-label for="phone" value="{{ __('Nomor Telepon') }}" />
        <x-input id="phone" class="mt-1 block w-full" type="number" name="phone" :value="old('phone')" required
          autocomplete="tel" />
      </div>

      {{-- Baris Tanggal Magang (Grid 2 Kolom) --}}
      <div class="mt-4 grid grid-cols-2 gap-4">
        <div>
          <x-label for="start_date" value="{{ __('Tanggal Mulai Magang') }}" />
          <x-input id="start_date" class="mt-1 block w-full" type="date" name="start_date" :value="old('start_date')" required />
        </div>
        <div>
          <x-label for="end_date" value="{{ __('Tanggal Berakhir Magang') }}" />
          <x-input id="end_date" class="mt-1 block w-full" type="date" name="end_date" :value="old('end_date')" required />
        </div>
      </div>

      {{-- Jenis Kelamin --}}
      <div class="mt-4">
        <x-label for="gender" value="{{ __('Jenis Kelamin') }}" />
        <x-select id="gender" class="mt-1 block w-full" name="gender" required>
          <option disabled selected>{{ __('Pilih Jenis Kelamin') }}</option>
          <option value="male" @selected(old('gender') == 'male')>
            {{ __('Laki-laki') }}
          </option>
          <option value="female" @selected(old('gender') == 'female')>
            {{ __('Perempuan') }}
          </option>
        </x-select>
      </div>

      {{-- Alamat --}}
      <div class="mt-4">
        <x-label for="address" value="{{ __('Alamat Lengkap') }}" />
        <x-textarea id="address" class="mt-1 block w-full" name="address" required>{{ old('address') }}</x-textarea>
      </div>

      {{-- Kota --}}
      <div class="mt-4">
        <x-label for="city" value="{{ __('Kota') }}" />
        <x-input id="city" class="mt-1 block w-full" type="text" name="city" :value="old('city')" required
          autocomplete="city" />
      </div>

      {{-- Password --}}
      <div class="mt-4">
        <x-label for="password" value="{{ __('Kata Sandi') }}" />
        <x-input id="password" class="mt-1 block w-full" type="password" name="password" required
          autocomplete="new-password" />
      </div>

      {{-- Konfirmasi Password --}}
      <div class="mt-4">
        <x-label for="password_confirmation" value="{{ __('Konfirmasi Kata Sandi') }}" />
        <x-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation"
          required autocomplete="new-password" />
      </div>

      @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
        <div class="mt-4">
          <x-label for="terms">
            <div class="flex items-center">
              <x-checkbox name="terms" id="terms" required />
              <div class="ms-2">
                {!! __('Saya setuju dengan :terms_of_service dan :privacy_policy', [
                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md">'.__('Ketentuan Layanan').'</a>',
                    'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md">'.__('Kebijakan Privasi').'</a>',
                ]) !!}
              </div>
            </div>
          </x-label>
        </div>
      @endif

      <div class="mt-6 flex flex-col gap-3">
        <x-button class="w-full justify-center py-3">
          {{ __('DAFTAR SEKARANG') }}
        </x-button>

        <div class="text-center">
            <a class="text-sm text-gray-600 underline hover:text-gray-900 rounded-md" href="{{ route('login') }}">
                {{ __('Sudah terdaftar?') }}
            </a>
        </div>
      </div>
    </form>
  </x-authentication-card>
</x-guest-layout>