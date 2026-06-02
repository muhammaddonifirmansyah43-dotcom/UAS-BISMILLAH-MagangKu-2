<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internships', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('company_id');
            $table->uuid('created_by');

            $table->string('title');
            $table->enum('type', ['Magang', 'PKL']);
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->string('location');
            $table->string('registration_url')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->date('open_date')->nullable();
            $table->date('close_date')->nullable();

            $table->timestamps();

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internships');
    }
};