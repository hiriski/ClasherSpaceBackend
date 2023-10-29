<?php

namespace App\Services;

use App\Models\BaseLayout;
use Exception;

class BaseLayoutService
{

  /**
   * find all items
   */
  public function findAll($params)
  {
    $data = BaseLayout::orderBy('createdAt', 'desc')->where('baseType', $params['baseType'])->with([
      'categories', 'tags', 'user'
    ]);
    $perPage = isset($params['perPage']) ? (int) $params['perPage'] : 12;

    // search by query
    if (isset($params['query'])) {
      $searchTerm = $params['query'];
      $data = $data->where(function ($q) use ($searchTerm) {
        $q->where('name', 'LIKE', '%' . $searchTerm . '%')->orWhere('description', 'like', '%' . $searchTerm . '%');
      });
    }

    // search by town hall level
    if (isset($params['townHallLevel'])) {
      $data->where('townHallLevel', $params['townHallLevel']);
    }

    // search by builder hall level
    if (isset($params['builderHallLevel'])) {
      $data->where('builderHallLevel', $params['builderHallLevel']);
    }

    // filter by category
    if (isset($params['categoryId'])) {
      $categoryId = $params['categoryId'];
      $data->whereHas('categories', function ($query) use ($categoryId) {
        $query->where('categoryId', $categoryId);
      });
    }

    $data = $data->paginate($perPage);

    return $data;
  }

  /**
   * find by id
   */
  public function findById(string $id)
  {
    return BaseLayout::with(['categories', 'tags', 'user'])->findOrFail($id);
  }

  /**
   * create item
   */
  public function create($params)
  {
    $data = BaseLayout::create(array_merge($params, [
      'userId'      => auth()->id(),
      'imageUrls'   => isset($params['imageUrls']) ? implode(",", $params['imageUrls']) : null,
    ]));

    // attach categories
    if (isset($params['categoryIds'])) {
      $data->categories()->attach($params['categoryIds']);
    }

    // attach tag
    if (isset($params['tagIds'])) {
      $data->tags()->attach($params['tagIds']);
    }

    return $data->fresh();
  }

  /**
   * update item
   */
  public function update(array $params, int $id)
  {
    $data = BaseLayout::findOrFail($id);
    $params['imageUrls'] = isset($params['imageUrls']) && count($params['imageUrls']) > 0 ? implode(",", $params['imageUrls']) : null;
    $data->update($params);

    if (isset($params['categoryIds']) && count($params['categoryIds']) > 0) {
      $this->syncCategories([
        'baseLayoutId'  => $id,
        'categoryIds'   => $params['categoryIds']
      ]);
    }

    if (isset($params['tagIds']) && count($params['tagIds']) > 0) {
      $this->syncTags([
        'baseLayoutId'  => $id,
        'tagIds'        => $params['tagIds']
      ]);
    }

    return $data;
  }

  /**
   * delete item
   */
  public function delete(int $id)
  {
    try {
      $data = BaseLayout::findOrFail($id);
      if ($data) {
        $data->delete();
        return true;
      } else {
        return false;
      }
    } catch (Exception $_) {
      return false;
    }
  }


  /**
   * sync item categories
   */
  public function syncCategories(array $params)
  {
    try {
      $data = BaseLayout::findOrFail($params['baseLayoutId']);
      if ($data && count($params['categoryIds']) > 0) {
        $data->categories()->sync($params['categoryIds']);
      }
      return true;
    } catch (Exception $_) {
      return false;
    }
  }

  /**
   * sync item tags
   */
  public function syncTags(array $params)
  {
    try {
      $data = BaseLayout::findOrFail($params['baseLayoutId']);
      if ($data && count($params['tagIds']) > 0) {
        $data->tags()->sync($params['tagIds']);
      }
      return true;
    } catch (Exception $_) {
      return false;
    }
  }
}
