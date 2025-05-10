<?php

namespace App\Http\Controllers\ClashOfClans;


use App\Http\Controllers\Controller;
use App\Http\Resources\ClashOfClans\ClashOfClansPlayer;
use App\Http\Resources\ClashOfClans\ClashOfClansPlayerCollection;
use App\Services\ClashOfClans\ClashOfClansPlayerService;
use Illuminate\Http\JsonResponse;
use Exception;

class ClashOfClansPlayerController extends Controller
{
    private ClashOfClansPlayerService $playerService;

    public function __construct(ClashOfClansPlayerService $playerService)
    {
        $this->playerService = $playerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $players = $this->playerService->findAll();
            return new ClashOfClansPlayerCollection($players);
        } catch (Exception  $exception) {
            return response()->json([
                'message'   => $exception->getMessage(),
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $playerTag)
    {
        try {
            $player = $this->playerService->getPlayerInformation($playerTag);
            return new ClashOfClansPlayer($player);
        } catch (Exception  $exception) {
            return response([
                'message'   => $exception->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
