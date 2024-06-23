<?php

namespace App\Services\BaseLayout;

use App\Http\Requests\BaseLayout\StoreBaseLayoutRequest;
use App\Models\BaseLayout as BaseLayoutModel;
use App\Http\Requests\PaginateRequest;
use Illuminate\Support\Facades\DB;
use Exception;

class BaseLayoutService
{

    public $baseLayout;

    protected $filters = [
        'name',
        'description',
        'userId',
        'categoryId',
        'townHallLevel',
        'builderHallLevel',
        'isWarBase',
        'baseType',
        'except'
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests       = $request->all();
            $method         = $request->get('paginate', 0) ? 'paginate' : 'get';
            $perPage        = $request->get('paginate', 0) ? $request->get('perPage', 10) : '*';
            $orderColumn    = $request->get('orderColumn') ?? 'id';
            $orderType      = $request->get('orderType') ?? 'desc';

            $result = BaseLayoutModel::with('user', 'categories', 'tags')->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->filters)) {
                        if ($key == "except") {
                            $explodes = explode('|', $request);
                            if (count($explodes)) {
                                foreach ($explodes as $explode) {
                                    $query->where('id', '!=', $explode);
                                }
                            }
                        } else {
                            if ($key == "categoryId") {
                                $query->whereHas('categories', function ($queryC) use ($request) {
                                    $queryC->where('categoryId', $request);
                                });
                            } else {
                                $query->where($key, 'like', '%' . $request . '%');
                            }
                        }
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $perPage
            );
            return $result;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function findById(int $id): BaseLayoutModel
    {
        try {
            return BaseLayoutModel::with('user', 'categories', 'tags')->findOrFail($id);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }


    /**
     * @throws Exception
     */
    public function store(StoreBaseLayoutRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $this->baseLayout = BaseLayoutModel::create(
                    array_merge($request->validated(), [
                        'userId'      => auth()->id(),
                        'imageUrls'   => isset($request->imageUrls) ? implode(",", $request->imageUrls) : null,
                    ])
                );

                // attach categories
                if ($request->categoryIds) {
                    $this->baseLayout->categories()->attach($request->categoryIds);
                }

                // attach tag
                if ($request->tagIds) {
                    $this->baseLayout->tags()->attach($request->tagIds);
                }
            });
            return $this->baseLayout->fresh();
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function update(StoreBaseLayoutRequest $request, int $id): BaseLayoutModel
    {
        try {
            $baseLayout = BaseLayoutModel::findOrFail($id);
            DB::transaction(function () use ($request, $baseLayout) {
                $baseLayout->update(
                    array_merge($request->validated(), [
                        'imageUrls'   => isset($request->imageUrls) ? implode(",", $request->imageUrls) : null,
                    ])
                );

                if (count($request->categoryIds) == 0) {
                    $baseLayout->categories()->detach();
                } else {
                    $baseLayout->categories()->sync($request->categoryIds);
                }

                if (count($request->tagIds) == 0) {
                    $baseLayout->tags()->detach();
                } else {
                    $baseLayout->tags()->sync($request->tagIds);
                }
            });
            return $baseLayout->fresh();
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id)
    {
        try {
            $baseLayout = BaseLayoutModel::findOrFail($id);
            DB::transaction(function () use ($baseLayout) {
                $baseLayout->categories()->detach();
                $baseLayout->tags()->detach();
                $baseLayout->delete();
            });
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function syncCategories(int $id, array $categoryIds)
    {
        try {
            $baseLayout = BaseLayoutModel::findOrFail($id);
            if ($baseLayout && count($categoryIds) > 0) {
                $baseLayout->categories()->sync($categoryIds);
            }
            return $baseLayout->fresh();
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function syncTags(int $id, array $tagIds)
    {
        try {
            $baseLayout = BaseLayoutModel::findOrFail($id);
            if ($baseLayout && count($tagIds) > 0) {
                $baseLayout->tags()->sync($tagIds);
            }
            return $baseLayout->fresh();
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
