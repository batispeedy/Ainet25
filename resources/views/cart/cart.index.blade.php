<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Carrinho</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Carrinho de Compras</h1>

        @if (session('success'))
            <div class="mb-4 text-green-600 font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if (empty($cart))
            <p>O teu carrinho está vazio.</p>
        @else
            <table class="w-full text-left border">
                <thead>
                    <tr>
                        <th class="p-2 border">Produto</th>
                        <th class="p-2 border">Preço</th>
                        <th class="p-2 border">Quantidade</th>
                        <th class="p-2 border">Subtotal</th>
                        <th class="p-2 border"></th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($cart as $id => $item)
                        @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                        <tr>
                            <td class="p-2 border">{{ $item['name'] }}</td>
                            <td class="p-2 border">{{ number_format($item['price'], 2, ',', '.') }} €</td>
                            <td class="p-2 border">{{ $item['quantity'] }}</td>
                            <td class="p-2 border">{{ number_format($subtotal, 2, ',', '.') }} €</td>
                            <td class="p-2 border">
                                <form method="POST" action="{{ route('cart.remove', $id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Remover</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-right font-bold p-2 border">Total:</td>
                        <td class="p-2 border font-bold">{{ number_format($total, 2, ',', '.') }} €</td>
                        <td class="p-2 border"></td>
                    </tr>
                </tbody>
            </table>

            <form method="POST" action="{{ route('cart.clear') }}" class="mt-4">
                @csrf
                <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">Limpar Carrinho</button>
            </form>
        @endif
    </div>

</body>
</html>
