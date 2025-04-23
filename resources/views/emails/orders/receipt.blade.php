<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo do Pedido #{{ $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .header { background-color: #f7f7f7; padding: 20px; text-align: center; }
        .content { margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #ddd; }
        th { background-color: #f0f0f0; }
        .total-line { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Recibo do Pedido #{{ $order->id }}</h1>
    </div>
    <div class="content">
        <p>Olá {{ $order->member->name }},</p>
        <p>Obrigado pela tua encomenda. Segue abaixo o resumo do teu pedido realizado em {{ $order->date->format('d/m/Y') }}:</p>

        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unit.</th>
                    <th>Desconto</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->unit_price, 2, ',', '.') }} €</td>
                        <td>{{ number_format($item->discount, 2, ',', '.') }} €</td>
                        <td>{{ number_format($item->subtotal, 2, ',', '.') }} €</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-line">
                    <td colspan="4" align="right">Subtotal:</td>
                    <td>{{ number_format($order->total_items, 2, ',', '.') }} €</td>
                </tr>
                <tr class="total-line">
                    <td colspan="4" align="right">Portes:</td>
                    <td>{{ number_format($order->shipping_cost, 2, ',', '.') }} €</td>
                </tr>
                <tr class="total-line">
                    <td colspan="4" align="right">Total:</td>
                    <td>{{ number_format($order->total, 2, ',', '.') }} €</td>
                </tr>
            </tfoot>
        </table>

        <p>Em anexo está o recibo em formato PDF.</p>
        <p>Para qualquer dúvida, visita a tua <a href="{{ route('profile.index') }}">página de perfil</a> ou contacta-nos.</p>
        <p>Cumprimentos,<br>Grocery Club</p>
    </div>
</body>
</html>