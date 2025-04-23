<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Grocery Club')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Georgia', serif;
        }

        .navbar {
            background-color: #a47148;
        }

        .navbar a {
            color: #fff;
        }

        .navbar a:hover {
            color: #f3f3f3;
            text-decoration: underline;
        }

        .username {
            color: #fff;
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- NAVBAR -->
    <nav class="navbar shadow px-6 py-4 flex justify-between items-center">
        <a href="{{ route('home') }}" class="text-2xl font-bold">Grocery Club</a>

        <div class="space-x-4 flex items-center">
            <a href="{{ route('cart.index') }}">🛒 Carrinho</a>

            @auth
            <span class="username mr-3">Olá, {{ Auth::user()->name }}</span>
            <a href="{{ route('dashboard') }}">Dashboard</a>

            @if(Auth::user()->type === 'board')
            <div class="relative group inline-block">
                <button class="text-white bg-yellow-800 px-3 py-2 rounded hover:bg-yellow-700 transition">
                    ⚙️ Configurações
                </button>

                <!-- Submenu oculto por defeito, aparece ao pairar sobre o grupo -->
                <div
                    class="hidden group-hover:block absolute right-0 mt-2 w-48 bg-white rounded shadow-lg z-10">
                    <a
                        href="{{ route('categories.index') }}"
                        class="block px-4 py-2 text-black hover:bg-gray-100">Categorias</a>
                    <a
                        href="{{ route('products.index') }}"
                        class="block px-4 py-2 text-black hover:bg-gray-100">Produtos</a>
                    <a
                        href="{{ route('shipping_costs.index') }}"
                        class="block px-4 py-2 text-black hover:bg-gray-100">Portes</a>
                    <a
                        href="{{ route('stats.index') }}"
                        class="block px-4 py-2 text-black hover:bg-gray-100">Estatísticas</a>
                </div>
            </div>
            @endif
            <a href="{{ route('profile.edit') }}">Perfil</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit">Sair</button>
            </form>
            @else
            <a href="{{ route('login') }}">Entrar</a>
            <a href="{{ route('register') }}">Registar</a>
            <a href="{{ route('store.index') }}">Loja</a>
            @endauth
        </div>
    </nav>

    <!-- CONTEÚDO -->
    <main class="flex-grow px-6 py-8">
        @yield('content')
    </main>

</body>

</html>