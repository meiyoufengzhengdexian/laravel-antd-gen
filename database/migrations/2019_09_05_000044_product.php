<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Product extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function(Blueprint $blueprint){
            $blueprint->bigIncrements('id')->autoIncrement();
            $blueprint->string('name')->default('')->comment('产品名称');
            $blueprint->decimal('price')->default(0)->comment('产品价格');
            $blueprint->integer('work_off')->default(0)->comment('已售出');
            $blueprint->string('bar_code')->default('')->comment('条码');
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('product');
    }
}
