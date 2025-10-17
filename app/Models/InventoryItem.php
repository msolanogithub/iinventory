<?php

namespace Modules\Iinventory\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Imagina\Icore\Models\CoreModel;
use Modules\Ishoe\Models\Shoe;

class InventoryItem extends CoreModel
{

  protected $table = 'iinventory__inventory_items';
  public string $transformer = 'Modules\Iinventory\Transformers\InventoryItemTransformer';
  public string $repository = 'Modules\Iinventory\Repositories\InventoryItemRepository';
  public array $requestValidation = [
    'create' => 'Modules\Iinventory\Http\Requests\CreateInventoryItemRequest',
    'update' => 'Modules\Iinventory\Http\Requests\UpdateInventoryItemRequest',
  ];
  public array $modelRelations = [
    //eg. 'relationName' => 'belongsToMany/hasMany',
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
  protected $fillable = [
    'inventory_id',
    'shoe_id',
    'options',
    'quantity',
    'sizes'
  ];

  protected $casts = [
    'options' => 'array',
    'sizes' => 'array'
  ];

  public function inventory(): BelongsTo
  {
    return $this->belongsTo(Inventory::class, 'inventory_id');
  }

  public function shoe(): BelongsTo
  {
    return $this->belongsTo(Shoe::class);
  }
}
