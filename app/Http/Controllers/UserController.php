<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
{
    $users = User::withTrashed()->get(); 
    return view('users.index', compact('users'));
}

    public function edit(User $user) {
        return view('users.edit', compact('user'));
    }
    public function update(Request $req, User $user) {
        $req->validate([
            'name' => 'required|max:255',
            'email'=> ['required','email', Rule::unique('users')->ignore($user->id)],
            'type' => ['required', Rule::in(['member','board','employee','pending_member'])],
            'blocked'=>'boolean'
        ]);
        $user->update($req->only('name','email','type','blocked'));
        return redirect()->route('users.index')->with('success','Utilizador atualizado.');
    }
    public function destroy(User $user) {
        $user->delete();
        return back()->with('success','Membro cancelado.');
    }
    public function block(User $user) {
        $user->blocked = true; $user->save();
        return back()->with('success','Utilizador bloqueado.');
    }
    public function unblock(User $user) {
        $user->blocked = false; $user->save();
        return back()->with('success','Utilizador desbloqueado.');
    }
    public function promote(User $user) {
        $user->type = 'board'; $user->save();
        return back()->with('success','Promovido a board.');
    }
    public function demote(User $user) {
        $user->type = 'member'; $user->save();
        return back()->with('success','Revogado privilégio de board.');
    }
}
