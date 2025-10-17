<?php

namespace Modules\Iinventory\Models;

use Imagina\Icore\Models\CoreModel;

class Inventory extends CoreModel
{
  protected $table = 'iinventory__inventories';
  public string $transformer = 'Modules\Iinventory\Transformers\InventoryTransformer';
  public string $repository = 'Modules\Iinventory\Repositories\InventoryRepository';
  public array $requestValidation = [
    'create' => 'Modules\Iinventory\Http\Requests\CreateInventoryRequest',
    'update' => 'Modules\Iinventory\Http\Requests\UpdateInventoryRequest',
  ];
  public array $modelRelations = [
    //eg. 'relationName' => 'belongsToMany/hasMany',
    'items' => 'hasMany',
    'inTransactions' => 'hasMany',
    'outTransactions' => 'hasMany',
  ];
  //Instance external/internal events to dispatch with extraData
  public array $dispatchesEventsWithBindings = [
    //eg. ['path' => 'path/module/event', 'extraData' => [/*...optional*/]]
    'created' => [],
    'creating' => [],
    'updated' => [],
    'updating' => [],
    'deleting' => [],
    'deleted' => []
  ];

  protected $fillable = ['id','title', 'quantity'];

  public function items()
  {
    return $this->hasMany(InventoryItem::class, 'inventory_id');
  }

  public function inTransactions()
  {
    return $this->hasMany(InventoryTransaction::class, 'to_inventory_id');
  }

  public function outTransactions()
  {
    return $this->hasMany(InventoryTransaction::class, 'from_inventory_id');
  }
}
