<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Operation;
use App\Models\Order;
use App\Models\Card;
use App\Models\User;
use App\Services\Payment;

class ProfileController extends Controller
{
    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var Card|null $card */
        $card = Card::find($user->id);

        $transactions = $card
            ? Operation::where('card_id', $card->id)->latest()->get()
            : collect();

        $orders = Order::where('member_id', $user->id)->latest()->get();

        return view('profile.edit', compact('transactions', 'orders', 'card'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name'                      => 'required|string|max:255',
            'nif'                       => 'nullable|string|max:20',
            'default_delivery_address'  => 'nullable|string|max:255',
            'photo'                     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name                     = $request->name;
        $user->nif                      = $request->nif;
        $user->default_delivery_address = $request->default_delivery_address;

        if ($request->hasFile('photo')) {
            $filename = 'user_'.$user->id.'.'.$request->photo->extension();
            $request->photo->storeAs('public/users', $filename);
            $user->photo = $filename;
        }

        /** @var User $user */
        $user->save();

        return back()->with('success', 'Perfil atualizado com sucesso.');
    }

    public function destroy(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        Auth::logout();

        /** @var User $user */
        $user->delete();

        return redirect()->route('home')->with('success', 'Conta eliminada com sucesso.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|confirmed|min:8',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->password = Hash::make($request->password);

        /** @var User $user */
        $user->save();

        return back()->with('success', 'Palavra-passe alterada com sucesso!');
    }

    public function topup(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $rules = [
            'value'        => 'required|numeric|min:1',
            'payment_type' => 'required|in:Visa,PayPal,MBWAY',
        ];

        switch ($request->payment_type) {
            case 'Visa':
                $rules['card_number'] = 'required|digits:16';
                $rules['cvc_code']    = 'required|digits:3';
                break;
            case 'PayPal':
                $rules['email_address'] = 'required|email';
                break;
            case 'MBWAY':
                $rules['phone_number']  = ['required','regex:/^9\\d{8}$/'];
                break;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'cartao');
        }

        $reference = match ($request->payment_type) {
            'Visa'   => ['card_number'=>$request->card_number,'cvc_code'=>$request->cvc_code],
            'PayPal' => ['email_address'=>$request->email_address],
            'MBWAY'  => ['phone_number'=>$request->phone_number],
        };

        if (! Payment::simulate($request->payment_type, $reference)) {
            return back()
                ->with('error', 'Pagamento falhou. Verifica os dados.')
                ->withInput()
                ->with('active_tab', 'cartao');
        }

        /** @var Card|null $card */
        $card = Card::find($user->id);
        if (! $card) {
            return back()->with('error', 'Cartão não encontrado.')
                         ->with('active_tab','cartao');
        }

        $card->balance += $request->value;
        $card->save();

        Operation::create([
            'card_id'          => $card->id,
            'type'             => 'credit',
            'value'            => $request->value,
            'date'             => Carbon::now()->toDateString(),
            'credit_type'      => 'payment',
            'payment_type'     => $request->payment_type,
            'payment_reference'=> $this->extractReference($request),
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Saldo carregado com sucesso!')
            ->with('active_tab', 'cartao');
    }

    private function extractReference(Request $request): string
    {
        return match ($request->payment_type) {
            'Visa'   => $request->card_number,
            'PayPal' => $request->email_address,
            'MBWAY'  => $request->phone_number,
            default  => '',
        };
    }
}
