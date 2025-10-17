<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('iinventory__inventory_items', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->increments('id');
      // Your fields...
      $table->unsignedInteger('inventory_id');
      $table->integer('shoe_id')->unsigned();
      $table->json('options');
      $table->integer('quantity')->unsigned()->default(0);
      $table->json('sizes');
      // Foreign keys
      $table->foreign('inventory_id')->references('id')->on('iinventory__inventories')->onDelete('cascade');
      $table->foreign('shoe_id')->references('id')->on('ishoe__shoes')->onDelete('cascade');
      // Audit fields
      $table->timestamps();
      $table->auditStamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('iinventory__inventory_items', function (Blueprint $table) {
      $table->dropForeign(['inventory_id']);
      $table->dropForeign(['shoe_id']);
    });
    Schema::dropIfExists('iinventory__inventory_items');
  }
};
