<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Verificação de Email - Grocery Club</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('https://images.unsplash.com/photo-1606788075761-1ec2ef302b25?auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            font-family: 'Georgia', serif;
        }
        .overlay { background-color: rgba(44, 30, 18, 0.88); }
        .btn-rustic { background-color: #a47148; border: 1px solid #5c3b1e; }
        .btn-rustic:hover { background-color: #8a5e3b; }
        label { font-weight: 600; }
    </style>
</head>
<body class="overlay min-h-screen flex items-center justify-center px-4">
    <div class="bg-white bg-opacity-95 p-8 rounded-xl shadow-xl max-w-xl w-full text-gray-800">
        <h2 class="text-3xl font-bold mb-6 text-center text-yellow-800">Verificação de Email</h2>

        <p class="mb-4 text-gray-700">
            Bem-vindo ao <span class="font-semibold text-gray-900">Grocery Club</span>! Para continuares, verifica o teu email clicando no link que te enviámos.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-6">
                Enviámos um novo link de verificação para o teu email.
            </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
            <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                @csrf
                <button type="submit" class="btn-rustic w-full py-2 text-white rounded-xl shadow">
                    Reenviar Email de Verificação
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-100">
                    Sair
                </button>
            </form>
        </div>
    </div>
</body>
</html>
