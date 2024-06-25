<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create ( 'reports', function (Blueprint $table){
            $table->id();
            $table->int('id_User');
            $table->int('id_Post');
            $table->string('reason');
            $table->timestamps();
            // $table->FOREIGN KEY('id_Post') -> REFERENCES posts('id');
            // $table->FOREIGN KEY ('id_User') REFERENCES users('id');
            $table->foreign('id_User')->references('id')->on('user'); // Liên kết `id_User` với `id` của bảng `users`
            $table->foreign('id_Post')->references('id')->on('post'); // Liên kết `id_User` với `id` của bảng `users`

    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');

    }
};
