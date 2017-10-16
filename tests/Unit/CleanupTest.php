<?php

namespace Tests\Unit;


use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Tests\Traits\FeedReaderMock;
use Tests\Traits\ModelFactoryTrait;

/**
 * Class CleanupTest
 *
 * @package Tests\Unit
 */
class CleanupTest extends TestCase
{
    use DatabaseMigrations, ModelFactoryTrait, FeedReaderMock;

    /**
     *
     */
    public function setup()
    {
        parent::setUp();
    }

    /**
     *
     */
    public function testCleanupRespectsFeedSettings()
    {
        $this->mockFeedReader();

        $feed = $this->createFeed(null, null, ['settings' => ['articles' => ['keep' => 3]]]);
        $now = Carbon::create();
        $before = $now->subDays(4);

        $this->createArticle($feed, [
            'updated_at' => $before,
            'read'       => true
        ], 10);
        $this->createArticle($feed, [], 30);

        $count = $feed->cleanup();
        $this->assertEquals(10, $count);
    }

    /**
     *
     */
    public function testCleanupFromConsole()
    {
        $this->mockFeedReader();

        $feed = $this->createFeed();
        $now = Carbon::create();
        $before = $now->subDays(23);

        $this->createArticle($feed, [
            'updated_at' => $before,
            'read'       => true
        ], 10);
        $this->createArticle($feed, [], 30);

        Artisan::call('feed:cleanup', ['--days' => 20]);

        $articles = $feed->articles->all();
        $this->assertCount(30, $articles);
    }
}
