<?php

namespace App\Http\Controllers\BaseLayout;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseLayout\BaseLayout as BaseLayoutResource;
use App\Http\Requests\BaseLayout\StoreBaseLayoutRequest;
use App\Http\Resources\BaseLayout\BaseLayoutCollection;
use App\Services\BaseLayout\BaseLayoutService;
use App\Http\Requests\PaginateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class BaseLayoutController extends Controller
{
    private $baseService;

    public function __construct(BaseLayoutService $baseService)
    {
        $this->middleware(['auth:sanctum'])->only(['store', 'update', 'destroy']);
        $this->baseService = $baseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PaginateRequest $request): \Illuminate\Http\Response | BaseLayoutCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $baseLayouts = $this->baseService->list($request);
            return new BaseLayoutCollection($baseLayouts);
        } catch (Exception  $exception) {
            return response()->json(['message' => $exception->getMessage()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): \Illuminate\Http\Response | BaseLayoutResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new BaseLayoutResource($this->baseService->findById($id));
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBaseLayoutRequest $request): \Illuminate\Http\Response | BaseLayoutResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new BaseLayoutResource($this->baseService->store($request));
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBaseLayoutRequest $request, int $id): \Illuminate\Http\Response | BaseLayoutResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new BaseLayoutResource($this->baseService->update($request, $id));
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    public function destroy(int $id): \Illuminate\Http\Response | \Illuminate\Http\JsonResponse | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->baseService->destroy($id);
            return response()->json([
                'message'   => 'Base layout deleted.'
            ], JsonResponse::HTTP_ACCEPTED);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
