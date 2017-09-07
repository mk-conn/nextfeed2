<?php

use App\Models\Article;
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
            $table->string('name');
            $table->integer('feed_id');
            $table->string('body');
            $table->string('url');
            $table->string('feed_location');
            $table->string('icon');
            $table->timestamps();

            $table->softDeletes();

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
