<?php
declare(strict_types=1);

namespace App\Responses\AutoWash;

class Card
{
    public readonly int $no_seq;
    public readonly string $cd_card_corp;
    public readonly string $card_corp;
    public readonly string $cd_payment_card;
    public readonly string $no_card;
    public readonly string $no_card_user;
    public readonly string $nm_card;
    public readonly string $yn_main_card;
    public readonly string $yn_credit;

    public function __construct(array $card)
    {
        $this->no_seq = data_get($card, 'no_seq');
        $this->cd_card_corp = data_get($card, 'cd_card_corp');
        $this->card_corp = data_get($card, 'card_corp');
        $this->cd_payment_card = data_get($card, 'cd_payment_card');
        $this->no_card = data_get($card, 'no_card');
        $this->no_card_user = data_get($card, 'no_card_user');
        $this->nm_card = data_get($card, 'nm_card');
        $this->yn_main_card = data_get($card, 'yn_main_card');
        $this->yn_credit = data_get($card, 'yn_credit');
    }
}
