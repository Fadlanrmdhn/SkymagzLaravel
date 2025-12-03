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
        if (!Schema::hasTable('magazines')){

            Schema::create('magazines', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->enum('type' ,['majalah','buku']);
                $table->text('description');
                $table->string('author');
                $table->string('publisher');
                $table->integer('price');
                $table->string('cover');
                $table->date('release_date');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('magazines');
    }
};
