<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->text('alasan_penolakan')->nullable()->after('catatan');
            $table->string('foto_pengembalian')->nullable()->after('tanggal_kembali');
            $table->text('catatan_pengembalian')->nullable()->after('foto_pengembalian');
        });
    }

    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn(['alasan_penolakan', 'foto_pengembalian', 'catatan_pengembalian']);
        });
    }
};