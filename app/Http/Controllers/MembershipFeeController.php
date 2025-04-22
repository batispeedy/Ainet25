<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Setting;
use App\Models\Card;
use App\Models\Operation;
use App\Services\Payment;

class MembershipFeeController extends Controller
{
    public function show()
    {
        $settings = Setting::first();
        $fee = $settings ? $settings->membership_fee : 100;

        return view('membership.show', compact('fee'));
    }

    public function pay(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Já é membro
        if ($user->type === 'member') {
            return redirect()->route('profile.edit')->with('success', 'Já és membro do clube.');
        }
 
        // Apenas pending_member pode pagar
        if ($user->type !== 'pending_member') {
            return redirect()->route('profile.edit')->with('error', 'Não tens permissão para pagar a quota.');
        }

        // Validação do pagamento
        $request->validate([
            'payment_type' => 'required|in:Visa,PayPal,MBWAY',
            'card_number' => 'required_if:payment_type,Visa|digits:16',
            'cvc_code' => 'required_if:payment_type,Visa|digits:3',
            'email_address' => 'required_if:payment_type,PayPal|email',
            'phone_number' => 'required_if:payment_type,MBWAY|regex:/^9\d{8}$/',
        ]);

        $reference = match ($request->payment_type) {
            'Visa' => ['card_number' => $request->card_number, 'cvc_code' => $request->cvc_code],
            'PayPal' => ['email_address' => $request->email_address],
            'MBWAY' => ['phone_number' => $request->phone_number],
        };

        if (!Payment::simulate($request->payment_type, $reference)) {
            return back()->with('error', 'O pagamento falhou. Verifica os dados e tenta novamente.');
        }

        $card = $user->card;
        if (!$card) {
            return back()->with('error', 'Cartão virtual não encontrado.');
        }

        $fee = Setting::first()->membership_fee ?? 100;

        if ($card->balance < $fee) {
            return back()->with('error', 'Saldo insuficiente no cartão virtual.');
        }

        // Debitar e registar operação
        $card->balance -= $fee;
        $card->save();

        Operation::create([
            'card_id' => $card->id,
            'type' => 'debit',
            'value' => $fee,
            'date' => now()->toDateString(),
            'debit_type' => 'membership_fee',
            'payment_type' => $request->payment_type,
            'payment_reference' => $request->payment_type === 'Visa' ? $request->card_number
                : ($request->payment_type === 'PayPal' ? $request->email_address : $request->phone_number),
        ]);

        // Atualizar tipo de utilizador
        $user->type = 'member';
        $user->save();

        return redirect()->route('membership.confirmation');
    }
}
