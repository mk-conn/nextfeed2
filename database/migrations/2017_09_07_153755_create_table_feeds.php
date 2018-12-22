<?php

use App\Models\Feed;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableFeeds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            Feed::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->string('guid');
            $table->string('name');
            $table->integer('folder_id')
                  ->nullable();
            $table->integer('user_id');
            $table->string('description')
                  ->nullable();
            $table->string('feed_url');
            $table->string('site_url');
            $table->string('icon')
                  ->nullable();
            $table->string('logo')
                  ->nullable();
            $table->string('language')
                  ->nullable();
            $table->string('etag')
                  ->nullable();
            $table->string('auth_user')
                  ->nullable();
            $table->string('auth_password')
                  ->nullable();
            $table->smallInteger('order')
                  ->nullable();
            $table->smallInteger('articles_per_update')
                  ->nullable();
            $table->string('update_error')
                  ->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')
                  ->references('id')
                  ->on(User::TABLE)
                  ->onDelete('cascade');
            
            $table->foreign('folder_id')
                  ->references('id')
                  ->on(Folder::TABLE)
                  ->onDelete('cascade');
            
            $table->unique(['guid', 'user_id']);
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Feed::TABLE);
    }
}
