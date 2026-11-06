<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->index(['user_id', 'nombre']);
        });

        Schema::table('credenciales', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id_credencial')->constrained()->cascadeOnDelete();
            $table->index(['user_id', 'created_at']);
        });

        // Preserve legacy data only when ownership is unambiguous.
        if (DB::table('users')->count() === 1) {
            $ownerId = DB::table('users')->value('id');
            DB::table('categorias')->whereNull('user_id')->update(['user_id' => $ownerId]);
            DB::table('credenciales')->whereNull('user_id')->update(['user_id' => $ownerId]);
        }
    }

    public function down(): void
    {
        Schema::table('credenciales', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'created_at']);
            $table->dropConstrainedForeignId('user_id');
        });
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'nombre']);
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
