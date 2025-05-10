<?php

namespace App\Services\ClashOfClans;

use App\Http\Requests\SearchClanRequest;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\ClashOfClansClan;
use Carbon\Carbon;

class ClashOfClansClanService extends ClashOfClansApiService
{

  /**
   * @throws Exception 
   */
  public function findAll()
  {
    try {
      $perPage = 20;
      $clans = ClashOfClansClan::orderBy('createdAt', 'desc')->paginate($perPage);
      return $clans;
    } catch (Exception $exception) {
      throw new Exception($exception->getMessage());
    }
  }

  /**
   * @throws Exception 
   */
  public function search(SearchClanRequest $request)
  {
    try {
      $result = $this->get("/clans", $request->all());
      return $result;
    } catch (Exception $exception) {
      throw new Exception($exception->getMessage());
    }
  }

  /**
   * @throws Exception
   */
  public function getClanInformation(string $clanTag)
  {
    try {
      $clan = $this->get("/clans/%23$clanTag");
      $clan = ClashOfClansClan::updateOrCreate(
        [
          'clanTag' => $clanTag,
        ],
        [
          'data'      => json_encode($clan),
          'syncedAt'  => Carbon::now(),
        ]
      );
      return $clan->fresh();
    } catch (Exception $exception) {
      Log::info($exception->getMessage());
      throw new Exception($exception->getMessage());
    }
  }
}
