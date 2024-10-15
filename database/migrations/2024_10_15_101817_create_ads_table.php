<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('status', ['active', 'pending', 'archived']);
            $table->string('url');
            $table->unsignedInteger('views_count')->default(0);
            $table->decimal('cpm', 8, 2);
            $table->decimal('budget', 10, 2);
            $table->enum('button_text', ['Смотреть', 'Оставить заявку', 'Скачать', 'Подробнее']);
            $table->foreignId('campaign_id')->constrained('campaign')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ads');
    }
};
