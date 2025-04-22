<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Card;
use App\Models\Operation;
use App\Models\Order;
use App\Services\Payment;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function confirm(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'nif' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'payment_type' => 'required|in:Visa,PayPal,MBWAY',
        ];

        switch ($request->payment_type) {
            case 'Visa':
                $rules['card_number'] = 'required|digits:16';
                $rules['cvc_code'] = 'required|digits:3';
                break;
            case 'PayPal':
                $rules['email_address'] = 'required|email';
                break;
            case 'MBWAY':
                $rules['phone_number'] = 'required|regex:/^9\d{8}$/';
                break;
        }

        $validated = $request->validate($rules);

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'O carrinho está vazio.');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $shipping = $total <= 50 ? 10 : ($total <= 100 ? 5 : 0);
        $totalWithShipping = $total + $shipping;

        $card = $user->card;
        if (!$card) {
            return back()->with('error', 'Cartão virtual não encontrado.');
        }

        if ($card->balance < $totalWithShipping) {
            return back()->with('error', 'Saldo insuficiente no cartão virtual.');
        }

        // Simular pagamento
        $reference = match ($request->payment_type) {
            'Visa' => ['card_number' => $request->card_number, 'cvc_code' => $request->cvc_code],
            'PayPal' => ['email_address' => $request->email_address],
            'MBWAY' => ['phone_number' => $request->phone_number],
        };

        if (!Payment::simulate($request->payment_type, $reference)) {
            return back()->with('error', 'O pagamento foi recusado. Verifica os dados.');
        }

        // Descontar saldo
        $card->balance -= $totalWithShipping;
        $card->save();

        // Registar operação
        Operation::create([
            'card_id' => $card->id,
            'type' => 'debit',
            'value' => $totalWithShipping,
            'date' => Carbon::now()->toDateString(),
            'debit_type' => 'purchase',
            'payment_type' => $request->payment_type,
            'payment_reference' => $reference[array_key_first($reference)],
        ]);

        // Registar encomenda
        Order::create([
            'user_id' => $user->id,
            'nif' => $request->nif,
            'delivery_address' => $request->address,
            'total_price' => $totalWithShipping,
            'status' => 'pago',
        ]);

        // Limpar carrinho
        session()->forget('cart');

        return redirect()->route('checkout.success');

    }
}
