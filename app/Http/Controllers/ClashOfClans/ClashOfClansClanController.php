<?php

namespace App\Http\Controllers\ClashOfClans;


use App\Http\Controllers\Controller;
use App\Http\Requests\SearchClanRequest;
use App\Http\Resources\ClashOfClans\ClashOfClansClan;
use App\Http\Resources\ClashOfClans\ClashOfClansClanCollection;
use App\Services\ClashOfClans\ClashOfClansClanService;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Request;

class ClashOfClansClanController extends Controller
{
    private ClashOfClansClanService $clanService;

    public function __construct(ClashOfClansClanService $clanService)
    {
        $this->clanService = $clanService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $clans = $this->clanService->findAll();
            return new ClashOfClansClanCollection($clans);
        } catch (Exception  $exception) {
            return response()->json([
                'message'   => $exception->getMessage(),
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Search clan
     */
    public function search(SearchClanRequest $request)
    {
        try {
            $clans = $this->clanService->search($request);
            return isset($clans['items']) ?  $clans['items'] : [];
        } catch (Exception  $exception) {
            return response()->json([
                'message'   => $exception->getMessage(),
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $clanTag)
    {
        try {
            $clan = $this->clanService->getClanInformation($clanTag);
            return new ClashOfClansClan($clan);
        } catch (Exception  $exception) {
            return response([
                'message'   => $exception->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
