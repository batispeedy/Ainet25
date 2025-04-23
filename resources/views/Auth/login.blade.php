<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Entrar - Grocery Club</title>
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
            background-color: rgba(44, 30, 18, 0.85);
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
<body class="overlay min-h-screen flex items-center justify-center">
    <div class="bg-white bg-opacity-95 p-8 rounded-xl shadow-xl max-w-md w-full text-gray-800">
        <h2 class="text-3xl font-bold mb-6 text-center">Entrar no Grocery Club</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1" for="email">Email</label>
                <input
                  type="email"
                  name="email"
                  id="email"
                  value="{{ old('email') }}"
                  required
                  autofocus
                  class="w-full px-4 py-2 border rounded bg-white text-gray-800 @error('email') border-red-500 @enderror"
                />
                @error('email')
                  <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-2">
                <label class="block text-sm font-semibold mb-1" for="password">Palavra-passe</label>
                <input
                  type="password"
                  name="password"
                  id="password"
                  required
                  class="w-full px-4 py-2 border rounded bg-white text-gray-800 @error('password') border-red-500 @enderror"
                />
                @error('password')
                  <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6 text-right">
                @if (Route::has('password.request'))
                  <a
                    href="{{ route('password.request') }}"
                    class="text-sm text-blue-600 hover:underline"
                  >Esqueceu a palavra-passe?</a>
                @endif
            </div>
            <button type="submit" class="btn-rustic w-full text-white py-2 rounded text-lg">Entrar</button>
        </form>
        <p class="mt-6 text-sm text-center">
            Ainda não tens conta?
            <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Regista-te</a>
        </p>
    </div>
</body>
</html>
