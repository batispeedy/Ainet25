<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;
use App\Models\Card;
use App\Models\Operation;
use App\Models\User;
use App\Services\Payment;
use App\Mail\MembershipConfirmationMail;

class MembershipFeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show membership fee payment form.
     */
    public function show()
    {
        $setting = Setting::first();
        $fee = $setting ? $setting->membership_fee : 0;

        return view('membership.pay', compact('fee'));
    }

    /**
     * Process membership fee payment.
     */
    public function pay(Request $request)
    {
        // Validate payment type
        $request->validate([
            'payment_type' => 'required|in:Visa,PayPal,MBWAY',
        ]);

        // Get fee
        $fee = Setting::first()->membership_fee;

        // Dynamic rules based on payment type
        $rules = ['payment_type' => 'required|in:Visa,PayPal,MBWAY'];
        if ($request->payment_type === 'Visa') {
            $rules['card_number'] = 'required|digits:16';
            $rules['cvc_code']    = 'required|digits:3';
        } elseif ($request->payment_type === 'PayPal') {
            $rules['email_address'] = 'required|email';
        } else {
            $rules['phone_number']  = ['required','regex:/^9\d{8}$/'];
        }
        $request->validate($rules);

        // Simulate payment
        $referenceData = match ($request->payment_type) {
            'Visa'   => ['card_number'=>$request->card_number,'cvc_code'=>$request->cvc_code],
            'PayPal' => ['email_address'=>$request->email_address],
            'MBWAY'  => ['phone_number'=>$request->phone_number],
        };

        if (! Payment::simulate($request->payment_type, $referenceData)) {
            return back()
                ->with('error', 'Pagamento falhou. Verifica os dados.')
                ->withInput();
        }

        $user = Auth::user();

        DB::transaction(function () use ($user, $fee) {
            /** @var User $user */
            /** @var Card $card */

            // Create or retrieve card
            $card = Card::firstOrCreate(
                ['id' => $user->id],
                ['card_number' => random_int(100000, 999999), 'balance' => 0]
            );

            // Record debit operation
            Operation::create([
                'card_id'    => $card->id,
                'type'       => 'debit',
                'debit_type' => 'membership_fee',
                'value'      => $fee,
                'date'       => now()->toDateString(),
            ]);

            // Update card balance
            $card->balance -= $fee;
            $card->save();

            // Update user type
            $user->type = 'member';
            $user->save();

            // Send confirmation email
            Mail::to($user->email)
                ->send(new MembershipConfirmationMail($user));
        });

        return redirect()
            ->route('membership.confirmation')
            ->with('success', 'Quota paga com sucesso! Bem-vindo ao Grocery Club.');
    }
}
