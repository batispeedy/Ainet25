<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

        $transactions = Operation::where('user_id', $user->id)->latest()->get();
        $orders = Order::where('user_id', $user->id)->latest()->get();
        $card = Card::find($user->id); // ou $user->card se a relação estiver configurada

        return view('profile.edit', compact('transactions', 'orders', 'card'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'nif' => 'nullable|string|max:20',
            'default_delivery_address' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        $user->nif = $request->nif;
        $user->default_delivery_address = $request->default_delivery_address;

        if ($request->hasFile('photo')) {
            $filename = 'user_' . $user->id . '.' . $request->photo->extension();
            $request->photo->storeAs('public/users', $filename);
            $user->photo = $filename;
        }

        $user->save();

        return back()->with('success', 'Perfil atualizado com sucesso.');
    }

    public function updatePassword(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Palavra-passe alterada com sucesso!');
    }

    public function destroy(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        Auth::logout();
        $user->delete(); // soft delete (porque o modelo usa SoftDeletes)

        return redirect('/')->with('success', 'Conta eliminada com sucesso.');
    }

    public function topup(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'value' => 'required|numeric|min:1',
            'payment_type' => 'required|in:Visa,PayPal,MBWAY',
        ]);

        $success = Payment::simulate($request->payment_type);

        if (!$success) {
            return back()->with('error', 'Pagamento falhou. Verifica os dados e tenta novamente.');
        }

        $card = Card::find($user->id);
        if (!$card) {
            return back()->with('error', 'Cartão virtual não encontrado.');
        }

        $card->balance += $request->value;
        $card->save();

        Operation::create([
            'user_id' => $user->id,
            'type' => 'topup',
            'value' => $request->value,
            'payment_type' => $request->payment_type,
        ]);

        return back()->with('success', 'Saldo carregado com sucesso!');
    }
}
