@extends('layouts.app')

@section('title', 'Loja - Grocery Club')

@section('content')
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
</style>

<div class="overlay p-8 max-w-7xl mx-auto rounded">


    <div class="flex justify-between items-center mb-6">
        <h1 class="text-4xl font-bold text-gray-800">Loja Gourmet</h1>
    </div>

   
    <div class="overflow-x-auto mb-8">
        <div class="flex gap-4">
            @foreach($categories as $category)
                <a href="{{ route('store.index', ['category' => $category->id]) }}" class="text-center group min-w-[120px]">
                    <div class="bg-white rounded shadow hover:shadow-lg transition overflow-hidden w-32">
                        @if ($category->image && file_exists(public_path('storage/categories/' . $category->image)))
                            <img src="{{ asset('storage/categories/' . $category->image) }}" alt="{{ $category->name }}"
                                class="w-full h-24 object-cover">
                        @else
                            <img src="{{ asset('storage/categories/category_no_image.png') }}" alt="Sem imagem"
                                class="w-full h-24 object-cover">
                        @endif
                        <p class="py-2 font-semibold text-gray-800 text-sm group-hover:text-black truncate px-2">
                            {{ $category->name }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>


    <form method="GET" class="mb-6 bg-white p-4 rounded shadow flex flex-col md:flex-row gap-4 items-center">
        <select name="category" class="border rounded p-2 w-full md:w-auto">
            <option value="">Todas as Categorias</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <select name="sort" class="border rounded p-2 w-full md:w-auto">
            <option value="">Ordenar por</option>
            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nome</option>
            <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Preço</option>
        </select>

        <button class="btn-rustic px-4 py-2 text-white rounded transition w-full md:w-auto">
            Filtrar
        </button>
    </form>


    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($products as $product)
            <div class="bg-white rounded shadow p-4">
                @if ($product->photo && file_exists(public_path('storage/products/' . $product->photo)))
                    <img src="{{ asset('storage/products/' . $product->photo) }}" alt="{{ $product->name }}"
                        class="mb-4 w-full h-48 object-cover rounded">
                @else
                    <img src="{{ asset('storage/products/product_no_image.png') }}" alt="Sem imagem"
                        class="mb-4 w-full h-48 object-cover rounded">
                @endif

                <h2 class="text-xl font-semibold text-gray-900">{{ $product->name }}</h2>
                <p class="text-gray-600 mb-2">{{ $product->description }}</p>

                {{-- **Aqui fica a secção de desconto** --}}
                @if ($product->discount && $product->discount_min_qty)
                    <p class="text-sm text-red-700 font-medium">
                        Desconto de {{ number_format($product->discount,2,',','.') }} € a partir de {{ $product->discount_min_qty }} unidades
                    </p>
                    <p class="text-lg font-bold text-gray-500 line-through">
                        {{ number_format($product->price, 2, ',', '.') }} €
                    </p>
                    <p class="text-lg font-bold text-red-600">
                        {{ number_format($product->price - $product->discount, 2, ',', '.') }} €
                    </p>
                @else
                    <p class="text-lg font-bold text-green-700">
                        {{ number_format($product->price, 2, ',', '.') }} €
                    </p>
                @endif

                <form method="POST" action="{{ route('cart.add', $product->id) }}" class="mt-3">
                    @csrf
                    <div class="flex items-center gap-2">
                        <input type="number" name="quantity" value="1" min="1"
                            class="w-20 border border-gray-300 rounded px-2 py-1 text-gray-800" required>
                        <button type="submit"
                            class="btn-rustic text-white px-4 py-2 rounded hover:shadow transition flex-1">
                            Adicionar ao Carrinho
                        </button>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
