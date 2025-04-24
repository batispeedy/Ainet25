<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Grocery Club</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('https://images.unsplash.com/photo-1567306226416-28f0efdc88ce?auto=format&fit=crop&w=1950&q=80');
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
    </style>
</head>

<body class="relative h-screen overflow-hidden text-white">

   
    <div class="absolute inset-0 overlay z-10"></div>

   
    <div class="relative z-20 flex items-center justify-center h-full">
        <div class="text-center px-6">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">Grocery Club</h1>
            <p class="text-xl md:text-2xl mb-10">Bem-vindo à tua loja de mercearia rústica e sofisticada.</p>

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="/login" class="btn-rustic px-6 py-3 rounded text-white font-semibold transition">
                    Entrar
                </a>
                <a href="/register" class="btn-rustic px-6 py-3 rounded text-white font-semibold transition">
                    Registar
                </a>
                <a href="{{ route('store.index') }}" class="btn-rustic px-6 py-3 rounded text-white font-semibold transition">
                    Entrar na Loja
                </a>
            </div>
        </div>
    </div>

</body>

</html>