<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('priority')->default(0);
            $table->boolean('active')->default(0);
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('brand_id');
            $table->string('access_type_code', 1)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('region_id')->references('id')->on('regions');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('access_type_code')->references('code')->on('access_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
