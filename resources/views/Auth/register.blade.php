<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Registo</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0">
        <div>
            <h1 class="text-2xl font-bold">Grocery Club — Registo</h1>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            @if ($errors->any())
                <div class="mb-4 text-sm text-red-600">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf

                <!-- Nome -->
                <div>
                    <label for="name" class="block font-medium text-sm text-gray-700">Nome</label>
                    <input id="name" class="block mt-1 w-full border rounded" type="text" name="name" value="{{ old('name') }}" required autofocus />
                </div>

                <!-- Género -->
                <div class="mt-4">
                    <label for="gender" class="block font-medium text-sm text-gray-700">Género</label>
                    <select id="gender" name="gender" class="block mt-1 w-full border rounded" required>
                        <option value="M">Masculino</option>
                        <option value="F">Feminino</option>
                    </select>
                </div>

                <!-- NIF -->
                <div class="mt-4">
                    <label for="nif" class="block font-medium text-sm text-gray-700">NIF</label>
                    <input id="nif" class="block mt-1 w-full border rounded" type="text" name="nif" value="{{ old('nif') }}" />
                </div>

                <!-- Morada -->
                <div class="mt-4">
                    <label for="default_delivery_address" class="block font-medium text-sm text-gray-700">Morada de Entrega</label>
                    <input id="default_delivery_address" class="block mt-1 w-full border rounded" type="text" name="default_delivery_address" value="{{ old('default_delivery_address') }}" />
                </div>

                <!-- Foto -->
                <div class="mt-4">
                    <label for="photo" class="block font-medium text-sm text-gray-700">Foto de Perfil</label>
                    <input id="photo" class="block mt-1 w-full border rounded" type="file" name="photo" accept="image/*">
                </div>

                <!-- Email -->
                <div class="mt-4">
                    <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                    <input id="email" class="block mt-1 w-full border rounded" type="email" name="email" value="{{ old('email') }}" required />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                    <input id="password" class="block mt-1 w-full border rounded" type="password" name="password" required autocomplete="new-password" />
                </div>

                <!-- Confirmar Password -->
                <div class="mt-4">
                    <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirmar Password</label>
                    <input id="password_confirmation" class="block mt-1 w-full border rounded" type="password" name="password_confirmation" required />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                        Já tens conta?
                    </a>

                    <button class="ml-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        Registar
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
