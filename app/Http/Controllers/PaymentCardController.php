<?php

namespace App\Http\Controllers;

use App\Models\PaymentCard;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentCardController extends ApiController
{
    public function index(User $user)
    {
        $cards = $user->paymentCards()->get();
        return $this->showAll($cards);
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'cardHolderName' => ['required'],
            'cardNumber' => ['required', 'unique:payment_cards,number'],
            'month' => ['required'],
            'year' => ['required']
        ]);

        $card = $user->paymentCards()->create([
            'holder_name' => $request->get('cardHolderName'),
            'number' => $request->get('cardNumber'),
            'expiry_month' => $request->get('month'),
            'expiry_year' => $request->get('year')
        ]);

        return $this->showMessage('Card saved.');
    }

    public function destroy(User $user, PaymentCard $card)
    {
        if (!$this->confirmCardBelongsToUser($user, $card)) {
            return $this->errorResponse('Sorry this payment card does not belong you.');
        }

        $card->delete();

        return $this->showOne($card);
    }

    private function confirmCardBelongsToUser(User $user, PaymentCard $card)
    {
        $isUserCard = false;

        $user->paymentCards()->get()->each(function ($userCard) use ($card, &$isUserCard) {
            if ($userCard->id === $card->id) {
                $isUserCard = true;
            }
        });

        return $isUserCard;
    }
}
