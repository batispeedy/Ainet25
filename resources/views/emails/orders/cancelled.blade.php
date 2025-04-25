<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancelamento da Encomenda #{{ $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .header { background-color: #f7f7f7; padding: 20px; text-align: center; }
        .content { margin: 20px; }
        .reason { background-color: #ffe5e5; padding: 10px; border: 1px solid #ffcccc; margin-top: 20px; }
        a { color: #1a73e8; text-decoration: none; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Encomenda #{{ $order->id }} Cancelada</h1>
    </div>
    <div class="content">
        <p>Olá {{ $order->member->name }},</p>
        <p>Lamentamos informar que a tua encomenda <strong>#{{ $order->id }}</strong>, realizada em {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}, foi cancelada pelo administrador.</p>

        <div class="reason">
            <p><strong>Motivo do Cancelamento:</strong></p>
            <p>{{ $reason }}</p>
        </div>

        <p>Se tiveres alguma dúvida, visita a tua <a href="{{ route('profile.edit') }}">página de perfil</a> ou contacta-nos.</p>
        <p>Cumprimentos,<br>Grocery Club</p>
    </div>
</body>
</html>
