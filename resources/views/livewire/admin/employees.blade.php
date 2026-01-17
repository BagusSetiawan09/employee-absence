<div>
  <div class="mb-4 flex-col items-center gap-5 sm:flex-row md:flex md:justify-between lg:mr-4">
    <h3 class="mb-4 text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200 md:mb-0">
      Data Magang
    </h3>
    <x-button wire:click="showCreating">
      <x-heroicon-o-plus class="mr-2 h-4 w-4" /> Tambah Magang
    </x-button>
  </div>
  <div class="mb-1 text-sm dark:text-white">Filter:</div>
  <div class="mb-4 grid grid-cols-3 flex-wrap items-center gap-5 md:gap-8 lg:flex">
    <x-select id="division" wire:model.live="division">
      <option value="">{{ __('Select Division') }}</option>
      @foreach (App\Models\Division::all() as $_division)
        <option value="{{ $_division->id }}" {{ $_division->id == $division ? 'selected' : '' }}>
          {{ $_division->name }}
        </option>
      @endforeach
    </x-select>

    <div class="col-span-3 flex items-center gap-2 lg:col-span-1">
      <x-input type="text" class="w-full lg:w-72" name="search" id="seacrh" wire:model="search"
        placeholder="{{ __('Search') }}" />
      <div class="flex gap-2">
        <x-button class="flex justify-center sm:w-32" type="button" wire:click="$refresh" wire:loading.attr="disabled">
          {{ __('Search') }}
        </x-button>
        @if ($search)
          <x-secondary-button class="flex justify-center sm:w-32" type="button" wire:click="$set('search', '')"
            wire:loading.attr="disabled">
            {{ __('Reset') }}
          </x-secondary-button>
        @endif
      </div>
    </div>
  </div>
  <div class="overflow-x-scroll">
    <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
      <thead class="bg-gray-50 dark:bg-gray-900">
        <tr>
          <th scope="col" class="relative px-2 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300">
            No.
          </th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            {{ __('Name') }}
          </th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            {{ __('NIM') }}
          </th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            {{ __('Email') }}
          </th>
          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
            {{ __('Phone Number') }}
          </th>
          <th scope="col" class="hidden px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 sm:table-cell">
            {{ __('City') }}
          </th>
          <th scope="col" class="relative px-6 py-3">
            <span class="sr-only">Actions</span>
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
        @php
          $class = 'cursor-pointer group-hover:bg-gray-100 dark:group-hover:bg-gray-700';
        @endphp
        @foreach ($users as $user)
          @php
            $wireClick = "wire:click=show('$user->id')";
          @endphp
          <tr wire:key="{{ $user->id }}" class="group">
            <td class="{{ $class }} p-2 text-center text-sm font-medium text-gray-900 dark:text-white" {{ $wireClick }}>
              {{ $loop->iteration }}
            </td>
            <td class="{{ $class }} px-6 py-4 text-sm font-medium text-gray-900 dark:text-white" {{ $wireClick }}>
              {{ $user->name }}
            </td>
            {{-- Menampilkan data NIM --}}
            <td class="{{ $class }} px-6 py-4 text-sm font-medium text-gray-900 dark:text-white" {{ $wireClick }}>
              {{ $user->nim }}
            </td>
            <td class="{{ $class }} px-6 py-4 text-sm font-medium text-gray-900 dark:text-white" {{ $wireClick }}>
              {{ $user->email }}
            </td>
            <td class="{{ $class }} px-6 py-4 text-sm font-medium text-gray-900 dark:text-white" {{ $wireClick }}>
              {{ $user->phone }}
            </td>
            <td class="{{ $class }} hidden px-6 py-4 text-sm font-medium text-gray-900 dark:text-white sm:table-cell" {{ $wireClick }}>
              {{ $user->city }}
            </td>
            <td class="relative flex justify-end gap-2 px-6 py-4">
              <x-button wire:click="edit('{{ $user->id }}')">
                Edit
              </x-button>
              <x-danger-button wire:click="confirmDeletion('{{ $user->id }}', '{{ $user->name }}')">
                Delete
              </x-danger-button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-3">
    {{ $users->links() }}
  </div>

  <x-confirmation-modal wire:model="confirmingDeletion">
    <x-slot name="title">Hapus Magang</x-slot>
    <x-slot name="content">Apakah Anda yakin ingin menghapus <b>{{ $deleteName }}</b>?</x-slot>
    <x-slot name="footer">
      <x-secondary-button wire:click="$toggle('confirmingDeletion')" wire:loading.attr="disabled">{{ __('Cancel') }}</x-secondary-button>
      <x-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">{{ __('Confirm') }}</x-danger-button>
    </x-slot>
  </x-confirmation-modal>

  {{-- Modal Tambah --}}
  <x-dialog-modal wire:model="creating">
    <x-slot name="title">Magang Baru</x-slot>
    <form wire:submit="create">
      <x-slot name="content">
        {{-- Bagian Photo Profil Tetap Sama --}}
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
          <div x-data="{ photoName: null, photoPreview: null }" class="col-span-6 sm:col-span-4">
            <input type="file" id="photo" class="hidden" wire:model.live="form.photo" x-ref="photo" x-on:change="photoName = $refs.photo.files[0].name; const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL($refs.photo.files[0]);" />
            <x-label for="photo" value="{{ __('Photo') }}" />
            <div class="mt-2 h-20 w-20 rounded-full outline outline-gray-400" x-show="! photoPreview"></div>
            <div class="mt-2" x-show="photoPreview" style="display: none;"><span class="block h-20 w-20 rounded-full bg-cover bg-center bg-no-repeat" x-bind:style="'background-image: url(\'' + photoPreview + '\');'"></span></div>
            <x-secondary-button class="me-2 mt-2" type="button" x-on:click.prevent="$refs.photo.click()">{{ __('Select A New Photo') }}</x-secondary-button>
            @error('form.photo') <x-input-error for="form.photo" message="{{ $message }}" class="mt-2" /> @enderror
          </div>
        @endif
        <div class="mt-4">
          <x-label for="name">Nama Magang</x-label>
          <x-input id="name" class="mt-1 block w-full" type="text" wire:model="form.name" />
          @error('form.name') <x-input-error for="form.name" class="mt-2" message="{{ $message }}" /> @enderror
        </div>
        <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:gap-3">
          <div class="w-full">
            <x-label for="email">{{ __('Email') }}</x-label>
            <x-input id="email" class="mt-1 block w-full" type="email" wire:model="form.email" required />
          </div>
          <div class="w-full">
            <x-label for="nim">NIM</x-label>
            <x-input id="nim" class="mt-1 block w-full" type="text" wire:model="form.nim" required />
          </div>
        </div>
      </x-slot>
      <x-slot name="footer">
        <x-secondary-button wire:click="$toggle('creating')" wire:loading.attr="disabled">{{ __('Cancel') }}</x-secondary-button>
        <x-button class="ml-2" wire:click="create" wire:loading.attr="disabled">{{ __('Confirm') }}</x-button>
      </x-slot>
    </form>
  </x-dialog-modal>

  {{-- Modal Edit dan Detail juga disesuaikan dengan menghapus Job Title/Education --}}
</div>