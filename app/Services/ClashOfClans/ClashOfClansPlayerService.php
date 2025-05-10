<?php

namespace App\Services\ClashOfClans;

use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\ClashOfClansPlayer;
use Carbon\Carbon;

class ClashOfClansPlayerService extends ClashOfClansApiService
{

  /**
   * @throws Exception 
   */
  public function findAll()
  {
    try {
      $perPage = 20;
      $players = ClashOfClansPlayer::orderBy('createdAt', 'desc')->paginate($perPage);
      return $players;
    } catch (Exception $exception) {
      throw new Exception($exception->getMessage());
    }
  }

  /**
   * @throws Exception
   */
  public function getPlayerInformation(string $playerTag)
  {
    try {
      $player = $this->get("/players/%23$playerTag");
      $player = ClashOfClansPlayer::updateOrCreate(
        [
          'playerTag' => $playerTag,
        ],
        [
          'data'      => json_encode($player),
          'syncedAt'  => Carbon::now(),
        ]
      );
      return $player->fresh();
    } catch (Exception $exception) {
      Log::info($exception->getMessage());
      throw new Exception($exception->getMessage());
    }
  }
}
