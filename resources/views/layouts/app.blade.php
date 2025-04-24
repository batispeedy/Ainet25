<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Grocery Club')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Georgia', serif; }
    .navbar { background-color: #a47148; }
    .navbar a { color: #fff; }
    .navbar a:hover { color: #f3f3f3; text-decoration: underline; }
    .username { color: #fff; font-weight: bold; }
    
    #settings-menu a { color: #000 !important; }
  </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">


  <nav class="navbar shadow px-6 py-4 flex justify-between items-center relative">
    <a href="{{ route('home') }}" class="text-2xl font-bold">Grocery Club</a>

    <div class="space-x-4 flex items-center">
      <a href="{{ route('cart.index') }}">🛒 Carrinho</a>

      @auth
        <span class="username mr-3">Olá, {{ Auth::user()->name }}</span>
        <a href="{{ route('dashboard') }}">Dashboard</a>

        @can('manage-settings')
         
          <div class="relative inline-block">
            <button id="settings-toggle"
                    class="text-white bg-yellow-800 px-3 py-2 rounded hover:bg-yellow-700 transition">
              ⚙️ Configurações
            </button>
            <div id="settings-menu"
                 class="hidden absolute right-0 mt-2 w-48 bg-white rounded shadow-lg z-20">
              <a href="{{ route('settings.categories.index') }}"    class="block px-4 py-2 hover:bg-gray-100">Categorias</a>
              <a href="{{ route('settings.products.index') }}"      class="block px-4 py-2 hover:bg-gray-100">Produtos</a>
              <a href="{{ route('settings.shipping_costs.index') }}"class="block px-4 py-2 hover:bg-gray-100">Portes</a>
              <a href="{{ route('settings.users.index') }}"         class="block px-4 py-2 hover:bg-gray-100">Utilizadores</a>
              <a href="{{ route('stats.index') }}"                  class="block px-4 py-2 hover:bg-gray-100">Estatísticas</a>
            </div>
          </div>
        @endcan

        @can('manage-orders')
        
          <a href="{{ route('admin.orders.index') }}">🛠️ Gestão de Encomendas</a>
        @endcan

        @can('manage-inventory')
         
          <a href="{{ route('inventory.index') }}">📦 Inventário</a>
        
          <a href="{{ route('supply_orders.index') }}">🔄 Ordens de Reposição</a>
        @endcan

        {{-- Apenas membros veem suas próprias estatísticas --}}
        @can('view-personal-stats')
          <a href="{{ route('stats.personal') }}" class="px-4 py-2 hover:underline">
            📊 Minhas Estatísticas
          </a>
        @endcan

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

  <main class="flex-grow px-6 py-8">
    @yield('content')
  </main>

  @auth
    @can('manage-settings')
      <script>
        document.addEventListener('DOMContentLoaded', function(){
          const toggle = document.getElementById('settings-toggle');
          const menu   = document.getElementById('settings-menu');
          toggle.addEventListener('click', e => {
            e.stopPropagation();
            menu.classList.toggle('hidden');
          });
          document.addEventListener('click', () => menu.classList.add('hidden'));
        });
      </script>
    @endcan
  @endauth

</body>
</html>
