<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->decimal('sr_artist_first_session', 10, 2)->nullable()->after('promo_price');
            $table->decimal('master_artist_first_session', 10, 2)->nullable()->after('sr_artist_first_session');
            $table->decimal('sr_artist_second_session', 10, 2)->nullable()->after('master_artist_first_session');
            $table->decimal('master_artist_second_session', 10, 2)->nullable()->after('sr_artist_second_session');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'sr_artist_first_session',
                'master_artist_first_session',
                'sr_artist_second_session',
                'master_artist_second_session',
            ]);
        });
    }
};
