<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Grocery Club</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-image: url('https://images.unsplash.com/photo-1505250469679-203ad9ced0cb?auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            font-family: 'Georgia', serif;
        }

        .overlay {
            background-color: rgba(44, 30, 18, 0.8);
        }

        .btn-rustic {
            background-color: #a47148;
            border: 1px solid #5c3b1e;
        }

        .btn-rustic:hover {
            background-color: #8a5e3b;
        }

        .slogan {
            font-family: 'Brush Script MT', cursive;
        }
    </style>
</head>
<body class="relative text-white">

    <!-- Overlay -->
    <div class="absolute inset-0 overlay z-10"></div>

    <!-- Conteúdo -->
    <div class="relative z-20">

        <!-- Hero -->
        <div class="flex items-center justify-center h-screen flex-col text-center px-4">
            <h1 class="text-6xl font-bold mb-2">Grocery Club</h1>
            <p class="slogan text-3xl mb-6">"Sabores autênticos, tradição em cada detalhe."</p>

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="/login" class="btn-rustic px-6 py-3 rounded text-white font-semibold transition">
                    <i class="fas fa-sign-in-alt mr-2"></i> Entrar
                </a>
                <a href="/register" class="btn-rustic px-6 py-3 rounded text-white font-semibold transition">
                    <i class="fas fa-user-plus mr-2"></i> Registar
                </a>
                <a href="{{ route('store.index') }}" class="btn-rustic px-6 py-3 rounded text-white font-semibold transition">
                    <i class="fas fa-store mr-2"></i> Entrar na Loja
                </a>
            </div>
        </div>

        <!-- A nossa história -->
        <section class="bg-yellow-50 text-gray-800 px-6 py-16">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl font-bold mb-4">A Nossa História</h2>
                <p class="text-lg leading-relaxed">
                    No Grocery Club acreditamos que a mercearia é mais do que produtos — é cultura, é memória, é sabor. Nascemos da paixão pela autenticidade e pela arte de bem comer.
                </p>
            </div>
        </section>

        <!-- Destaques -->
        <section class="bg-white text-gray-900 px-6 py-16">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-3xl font-bold text-center mb-12">Produtos em Destaque</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-yellow-100 rounded shadow p-6 text-center">
                        <img src="https://images.unsplash.com/photo-1585238342028-3b47cde8f7c2?auto=format&fit=crop&w=400&q=80" class="w-full h-40 object-cover rounded mb-4" alt="Queijo Artesanal">
                        <h3 class="text-xl font-semibold">Queijo Artesanal</h3>
                        <p class="mt-2 text-sm">Maturado em adegas de pedra, com sabor intenso.</p>
                    </div>
                    <div class="bg-yellow-100 rounded shadow p-6 text-center">
                        <img src="https://images.unsplash.com/photo-1589307004969-4a202ec9a1a0?auto=format&fit=crop&w=400&q=80" class="w-full h-40 object-cover rounded mb-4" alt="Pão Rústico">
                        <h3 class="text-xl font-semibold">Pão Rústico</h3>
                        <p class="mt-2 text-sm">Feito em forno de lenha, crocante por fora, macio por dentro.</p>
                    </div>
