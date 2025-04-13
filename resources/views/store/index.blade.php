<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Loja - Grocery Club</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('https://images.unsplash.com/photo-1506806732259-39c2d0268443?auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            font-family: 'Georgia', serif;
        }

        .overlay {
            background-color: rgba(255, 255, 255, 0.95);
        }

        .btn-rustic {
            background-color: #a47148;
            border: 1px solid #5c3b1e;
        }

        .btn-rustic:hover {
            background-color: #8a5e3b;
        }

        .navbar {
            background-color: #a47148;
        }
    </style>
</head>
<body class="overlay min-h-screen">

    <!-- Banner topo -->
    <nav class="navbar text-white p-4 shadow flex justify-between items-center">
        <a href="{{ url('/') }}" class="text-2xl font-bold">Grocery Club</a>

        <div class="flex gap-4">
            <a href="{{ route('store.index') }}" class="hover:underline">Loja</a>
            @auth
                <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                <a href="{{ route('cart.index') }}" class="hover:underline">Carrinho</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hover:underline">Sair</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hover:underline">Entrar</a>
                <a href="{{ route('register') }}" class="hover:underline">Registar</a>
            @endauth
        </div>
    </nav>

    <!-- Conteúdo principal -->
    <div class="max-w-6xl mx-auto p-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-8">Loja Gourmet</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded shadow p-4">
                    @if($product->photo)
                        <img src="{{ asset('storage/products/' . $product->photo) }}" alt="{{ $product->name }}" class="mb-4 w-full h-48 object-cover rounded">
                    @else
                        <div class="mb-4 w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400 rounded">
                            Sem imagem
                        </div>
                    @endif

                    <h2 class="text-xl font-semibold text-gray-900">{{ $product->name }}</h2>
                    <p class="text-gray-600 mb-2">{{ $product->description }}</p>
                    <p class="text-lg font-bold text-green-700">{{ number_format($product->price, 2, ',', '.') }} €</p>

                    @auth
                        <form method="POST" action="{{ route('cart.add', $product->id) }}">
                            @csrf
                            <button class="mt-3 w-full btn-rustic text-white px-4 py-2 rounded hover:shadow transition">
                                Adicionar ao Carrinho
                            </button>
                        </form>
                    @else
                        <p class="mt-4 text-sm text-gray-500">Autentica-te para adicionar ao carrinho</p>
                    @endauth
                </div>
            @endforeach
        </div>
    </div>

</body>
</html>
