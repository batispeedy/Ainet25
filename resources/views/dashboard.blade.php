<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-2xl mx-auto bg-white rounded shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Bem-vindo, {{ auth()->user()->name }}</h1>

        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2">Cartão Virtual</h2>

            @if(auth()->user()->card)
                <div class="p-4 border rounded bg-gray-50">
                    <p><strong>Número:</strong> {{ auth()->user()->card->card_number }}</p>
                    <p><strong>Saldo:</strong> {{ number_format(auth()->user()->card->balance, 2, ',', '.') }} €</p>
                </div>
            @else
                <p class="text-red-500">Este utilizador ainda não tem cartão associado.</p>
            @endif
        </div>

        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="text-red-600 hover:underline">Terminar Sessão</a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>

</body>
</html>
