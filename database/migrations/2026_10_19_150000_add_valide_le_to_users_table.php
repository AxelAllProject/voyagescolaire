<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Null tant qu'un administrateur n'a pas validé le compte créé par inscription.
            $table->timestamp('valide_le')->nullable()->after('role');
        });

        // Les comptes existants ont été créés par un administrateur : ils sont déjà validés.
        DB::table('users')->update(['valide_le' => now()]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('valide_le');
        });
    }
};
