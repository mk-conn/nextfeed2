<?php

use App\Models\Article;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class AlterArticlesMakeSearchable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $dbDefault = config('database.default');
        $key = "database.connections.{$dbDefault}.driver";
        $tableName = 'article_index';

        Schema::create($tableName, function ($table) {
            $table->increments('id');
            $table->integer('user_id')
                  ->unsigned();
        });

        if (config($key) === 'pgsql') {
            DB::statement("ALTER TABLE {$tableName} ADD searchable tsvector NULL");
            DB::statement("CREATE INDEX {$tableName}_searchable_index ON {$tableName} USING GIN (searchable)");

            Artisan::call('scout:import', ['model' => Article::class]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_index');
    }
}
