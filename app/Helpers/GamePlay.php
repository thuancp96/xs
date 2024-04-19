<?php
namespace App\Helpers;

use App\Game;

class GamePlay
{
    public function GetGameList($locationId)
    {
        if (!is_numeric($locationId)) {
            return [];
        }
        return Game::where('active', 1)
            ->where('location_id', intval($locationId))
            ->select('id', 'name', 'odds', 'game_guide','game_code')
            ->orderBy('order')
            ->get();
    }
}