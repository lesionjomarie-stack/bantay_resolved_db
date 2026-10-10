<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('category');
            $table->string('location');
            $table->string('contact_number');
            $table->text('description');
            $table->string('photo')->nullable();
            $table->string('status')->default('Pending'); // Pending, In Progress, Resolved, Rejected
            $table->text('admin_remarks')->nullable();
            $table->integer('backed_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
