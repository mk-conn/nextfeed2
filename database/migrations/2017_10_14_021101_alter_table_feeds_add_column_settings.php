<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableFeedsAddColumnSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(App\Models\Feed::TABLE, function (Blueprint $table) {
            $table->json('settings')
                  ->nullable();
        });

        Schema::table(App\Models\Feed::TABLE, function (Blueprint $table) {
            $table->dropColumn('articles_per_update');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(App\Models\Feed::TABLE, function (Blueprint $table) {
            $table->dropColumn('settings');
        });

        Schema::table(App\Models\Feed::TABLE, function (Blueprint $table) {
            $table->smallInteger('articles_per_update')
                  ->nullable();
        });
    }
}
