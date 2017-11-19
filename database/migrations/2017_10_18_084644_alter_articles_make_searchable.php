<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
//        $dbDefault = config('database.default');
//        $key = "database.connections.{$dbDefault}.driver";
//        $tableName = 'article_index';
//
//        Schema::create($tableName, function ($table) {
//            $table->increments('id');
//            $table->integer('user_id')
//                  ->unsigned();
//        });
//
//        if (config($key) === 'pgsql') {
//            DB::statement("ALTER TABLE {$tableName} ADD searchable tsvector NULL");
//            DB::statement("CREATE INDEX {$tableName}_searchable_index ON {$tableName} USING GIN (searchable)");
//
//            Artisan::call('scout:import', ['model' => Article::class]);
//        }


        $dbDefault = config('database.default');
        $key = "database.connections.{$dbDefault}.driver";
        $tableName = Article::TABLE;

        if (config($key) === 'pgsql') {

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->unsignedInteger('user_id')
                      ->nullable();

                DB::statement("ALTER TABLE {$tableName} ADD searchable tsvector NULL");
                DB::statement("CREATE INDEX {$tableName}_searchable_index ON {$tableName} USING GIN (searchable)");

            });

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $articles = Article::all();
                /** @var Article $article */
                foreach ($articles as $article) {
                    $user = $article->feed->user;
                    $article->user()
                            ->associate($user);
                    $article->save();
                }

                $table->unsignedInteger('user_id')
                      ->nullable(false)
                      ->change();

                $table->foreign('user_id')
                      ->references('id')
                      ->on(User::TABLE)
                      ->onDelete('cascade');
            });

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExists('article_index');
    }
}
