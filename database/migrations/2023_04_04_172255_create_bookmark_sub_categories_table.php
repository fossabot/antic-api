<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmark_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bookmark_category_id');
            $table->string('name')->comment('分类名称');
            $table->tinyInteger('order')->comment('排序ID')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookmark_sub_categories');
    }
};