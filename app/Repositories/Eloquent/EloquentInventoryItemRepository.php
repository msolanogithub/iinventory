<?php

namespace Modules\Iinventory\Repositories\Eloquent;

use Modules\Iinventory\Repositories\InventoryItemRepository;
use Imagina\Icore\Repositories\Eloquent\EloquentCoreRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EloquentInventoryItemRepository extends EloquentCoreRepository implements InventoryItemRepository
{
  /**
   * Filter names to replace
   * @var array
   */
  protected array $replaceFilters = [];

  /**
   * Relation names to replace
   * @var array
   */
  protected array $replaceSyncModelRelations = [];

  /**
   * Attribute to define default relations
   * all apply to index and show
   * index apply in the getItemsBy
   * show apply in the getItem
   * @var array
   */
  protected array $with = [/*all => [] ,index => [],show => []*/];

  /**
   * @param Builder $query
   * @param object $filter
   * @param object $params
   * @return Builder
   */
  public function filterQuery(Builder $query, object $filter, object $params): Builder
  {

    /**
     * Note: Add filter name to replaceFilters attribute before replace it
     *
     * Example filter Query
     * if (isset($filter->status)) $query->where('status', $filter->status);
     *
     */

    //Response
    return $query;
  }

  /**
   * @param Model $model
   * @param array $data
   * @return Model
   */
  public function syncModelRelations(Model $model, array $data): Model
  {
    //Get model relations data from model attributes
    //$modelRelationsData = ($model->modelRelations ?? []);

    /**
     * Note: Add relation name to replaceSyncModelRelations attribute before replace it
     *
     * Example to sync relations
     * if (array_key_exists(<relationName>, $data)){
     *    $model->setRelation(<relationName>, $model-><relationName>()->sync($data[<relationName>]));
     * }
     *
     */

    //Response
    return $model;
  }

  public function create(array $data): Model
  {
    $existItem = null;
    $items = $this->getItemsBy((object)[
      'filter' => (object)[
        'inventory_id' => $data['inventory_id'],
        'shoe_id' => $data['shoe_id'],
      ]
    ]);

    if ($items->count()) {
      $newOptions = array_map(function ($i) {
        return $i['id'];
      }, $data['options']);
      sort($newOptions);

      foreach ($items as $item) {
        $itemOptions = array_map(function ($i) {
          return $i['id'];
        }, $item->options);
        sort($itemOptions);
        if ($newOptions === $itemOptions) {
          $existItem = $item;
          break;
        };
      }
    }

    if ($existItem) return $existItem;
    return parent::create($data);
  }

  public function beforeUpdate(&$model, &$data): void
  {
    //Manage inventory movement
    if (isset($data['operation'])) {
      $operation = $data['operation'];
      $modelSizes = collect($model->sizes);
      $totalQuantity = 0;
      foreach ($data['sizes'] as $index => $entry) {
        $size = $entry['size'] ?? null;
        $qtyIncoming = isset($entry['quantity']) ? (int)$entry['quantity'] : 0;
        $existing = $modelSizes->where('size', $entry['size'])->first();
        $existingQty = $existing ? (int)$existing['quantity'] : 0;


        if ($operation === 'add') {
          $newQty = $existingQty + $qtyIncoming;
        } else {
          $newQty = $existingQty - $qtyIncoming;
          if ($newQty < 0) $newQty = 0;
        }

        $data['sizes'][$index]['quantity'] = $newQty;
        $totalQuantity += max(0, $newQty);
      }
      $data['quantity'] = $totalQuantity;
    }
  }
}
