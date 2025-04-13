<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Registo - Grocery Club</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('https://images.unsplash.com/photo-1606788075761-1ec2ef302b25?auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            font-family: 'Georgia', serif;
        }

        .overlay {
            background-color: rgba(44, 30, 18, 0.88);
        }

        .btn-rustic {
            background-color: #a47148;
            border: 1px solid #5c3b1e;
        }

        .btn-rustic:hover {
            background-color: #8a5e3b;
        }

        label {
            font-weight: 600;
        }
    </style>
</head>
<body class="overlay min-h-screen flex items-center justify-center px-4">

    <div class="bg-white bg-opacity-95 p-8 rounded-xl shadow-xl max-w-xl w-full text-gray-800">
        <h2 class="text-3xl font-bold mb-6 text-center">Regista-te no Grocery Club</h2>

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

            <div class="mb-4">
                <label for="name">Nome</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full px-4 py-2 border rounded bg-white text-gray-800">
            </div>

            <div class="mb-4">
                <label for="gender">Género</label>
                <select id="gender" name="gender" required class="w-full px-4 py-2 border rounded bg-white text-gray-800">
                    <option value="">Seleciona</option>
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="nif">NIF</label>
                <input id="nif" type="text" name="nif" value="{{ old('nif') }}" required class="w-full px-4 py-2 border rounded bg-white text-gray-800">
            </div>

            <div class="mb-4">
                <label for="default_delivery_address">Morada de Entrega</label>
                <input id="default_delivery_address" type="text" name="default_delivery_address" value="{{ old('default_delivery_address') }}" required class="w-full px-4 py-2 border rounded bg-white text-gray-800">
            </div>

            <div class="mb-4">
                <label for="photo">Foto de Perfil</label>
                <input id="photo" type="file" name="photo" accept="image/*" class="w-full px-4 py-2 border rounded bg-white text-gray-800">
            </div>

            <div class="mb-4">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border rounded bg-white text-gray-800">
            </div>

            <div class="mb-4">
                <label for="password">Palavra-passe</label>
                <input id="password" type="password" name="password" required class="w-full px-4 py-2 border rounded bg-white text-gray-800">
            </div>

            <div class="mb-6">
                <label for="password_confirmation">Confirmar Palavra-passe</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required class="w-full px-4 py-2 border rounded bg-white text-gray-800">
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <a class="text-sm text-blue-600 hover:underline" href="{{ route('login') }}">
                    Já tens conta? Entrar
                </a>
                <button type="submit" class="btn-rustic w-full sm:w-auto px-6 py-2 text-white rounded text-lg">
                    Registar
                </button>
            </div>
        </form>
    </div>

</body>
</html>
