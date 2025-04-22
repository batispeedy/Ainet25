<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'gender' => ['required', 'in:M,F'],
        'nif' => ['nullable', 'string', 'max:20'],
        'default_delivery_address' => ['nullable', 'string', 'max:255'],
        'photo' => ['nullable', 'image', 'max:2048'],
    ]);

    $data = $request->only(['name', 'email', 'gender', 'nif', 'default_delivery_address']);
    $data['password'] = Hash::make($request->password);
    $data['type'] = 'pending_member';

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('users', 'public');
    }

    $user = User::create($data);

    // Cria o cartão virtual
    do {
        $cardNumber = rand(100000, 999999);
    } while (\App\Models\Card::where('card_number', $cardNumber)->exists());

    $user->card()->create([
        'card_number' => $cardNumber,
        'balance' => 0.00,
    ]);

    event(new Registered($user));

    Auth::login($user);

    return redirect(route('dashboard', absolute: false));
}
}