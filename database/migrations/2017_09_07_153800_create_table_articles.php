<?php

use App\Models\Article;
use App\Models\Feed;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableArticles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Article::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->string('guid');
            $table->integer('feed_id');
            $table->mediumText('title');
            $table->string('author', 100);
            $table->string('language', 20)
                  ->nullable();
            $table->dateTimeTz('publish_date')
                  ->nullable();
            $table->dateTimeTz('updated_date')
                  ->nullable();
            $table->longText('content')
                  ->nullable();
            $table->longText('description')
                  ->nullable();
            $table->string('url');
            $table->json('categories')
                  ->nullable();
            $table->timestamps();

//            $table->softDeletes();

            $table->foreign('feed_id')
                  ->references('id')
                  ->on(Feed::TABLE)
                  ->onDelete('cascade');

            $table->unique(['guid', 'feed_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Article::TABLE);
    }
}
