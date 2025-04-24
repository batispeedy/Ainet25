<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Card;
use App\Models\Operation;

class CardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();
        $card = Card::findOrFail($user->id);
        return view('card.show', compact('card'));
    }

    public function history()
    {
        $user = Auth::user();
        $card = Card::findOrFail($user->id);
        $operations = Operation::where('card_id', $card->id)
                               ->orderByDesc('date')
                               ->paginate(15);
        return view('card.history', compact('card', 'operations'));
    }
}
