<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Perfil
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        @if(session('success'))
            <div class="mb-4 text-green-600 font-bold">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <x-label for="name" :value="'Nome'" />
                <x-input type="text" name="name" id="name" class="w-full" value="{{ old('name', $user->name) }}" required />
            </div>

            <div class="mb-4">
                <x-label for="gender" :value="'Género'" />
                <select name="gender" id="gender" class="w-full" required>
                    <option value="M" {{ $user->gender == 'M' ? 'selected' : '' }}>Masculino</option>
                    <option value="F" {{ $user->gender == 'F' ? 'selected' : '' }}>Feminino</option>
                </select>
            </div>

            <div class="mb-4">
                <x-label for="nif" :value="'NIF'" />
                <x-input type="text" name="nif" id="nif" class="w-full" value="{{ old('nif', $user->nif) }}" />
            </div>

            <div class="mb-4">
                <x-label for="default_delivery_address" :value="'Morada de Entrega'" />
                <x-input type="text" name="default_delivery_address" id="default_delivery_address" class="w-full" value="{{ old('default_delivery_address', $user->default_delivery_address) }}" />
            </div>

            <div class="mb-4">
                <x-label for="photo" :value="'Foto de Perfil'" />
                <input type="file" name="photo" id="photo" class="block mt-1" accept="image/*">
                @if($user->photo)
                    <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto de perfil" class="mt-2 w-24 h-24 rounded-full">
                @endif
            </div>

            <x-button>Guardar Alterações</x-button>
        </form>
    </div>
</x-app-layout>
