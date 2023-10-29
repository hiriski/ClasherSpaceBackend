<?php

namespace App\Http\Controllers\BaseLayout;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseLayout\RequestParamsGetBaseLayout;
use App\Http\Requests\BaseLayout\StoreBaseLayoutRequest;
use App\Http\Resources\BaseLayout\BaseLayout;
use App\Http\Resources\BaseLayout\BaseLayoutCollection;
use App\Services\BaseLayoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    public function index(RequestParamsGetBaseLayout $request)
    {
        $items = $this->baseService->findAll($request->all());
        return new BaseLayoutCollection($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBaseLayoutRequest $request)
    {
        $params = $request->only([
            // base field model
            'name',
            'link',
            'description',
            'townHallLevel',
            'builderHallLevel',
            'baseType',
            'imageUrls',
            'markedAsWarBase',

            // additional field
            'categoryIds',
            'tagIds',
        ]);
        try {
            $item = $this->baseService->create($params);
            return response()->json(new BaseLayout($item), JsonResponse::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json([
                'message'   => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $item = $this->baseService->findById($id);
            return new BaseLayout($item);
        } catch (\Exception $exception) {
            return response()->json([
                'message'   => $exception->getMessage(),
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $params = $request->only([
            // base field model
            'name',
            'link',
            'description',
            'townHallLevel',
            'builderHallLevel',
            'baseType',
            'imageUrls',
            'markedAsWarBase',

            // additional field
            'categoryIds',
            'tagIds',
        ]);
        try {
            $item = $this->baseService->update($params, $id);
            return response()->json(new BaseLayout($item), JsonResponse::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'message'   => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $result = $this->baseService->delete($id);
            if ($result) {
                return response()->json([
                    'message'   => 'Item deleted.'
                ]);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'message'   => $exception->getMessage(),
            ]);
        }
    }
}
