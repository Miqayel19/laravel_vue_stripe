<?php


namespace App\Http\Services;

use App\Http\Contracts\CardInterface;
use App\Card;

class CardService implements CardInterface
{
    public function __construct(Card $card)
    {
        $this->card = $card;
    }
    public function index()
    {
        return Card::all();
    }
    public function show($id)
    {
        return  Card::where('id',$id)->first();
    }
    public function create($credentials)
    {
        return $this->card->create($credentials);
    }

    public function update($credentials,$id)
    {
        return $this->card->where('id', $id)->update($credentials);
    }
    public function delete($id)
    {
        return $this->card->where('id', $id)->delete();
    }

}
