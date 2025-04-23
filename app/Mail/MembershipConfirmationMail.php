<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class MembershipConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var User */
    public $user;

    /**
     * Construtor recebe o utilizador que acabou de pagar.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this
            ->subject('Confirmação de Inscrição no Grocery Club')
            ->view('emails.membership.confirmation')
            ->with([
                'userName' => $this->user->name,
                'fee'      => number_format($this->user->card->balance, 2, ',', '.'),
            ]);
    }
}
