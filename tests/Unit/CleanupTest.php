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
     * @throws \Exception
     */
    public function testCleanupRespectsFeedSettings()
    {
        $this->mockFeedReader();

        $feed = $this->createFeed(
            null, null, [
            'settings' => [
                'articles' => [
                    'keep'    => 3,
                    'cleanup' => [
                        'keepUnread' => true
                    ]
                ]
            ]
        ]);
        $now = Carbon::create();
        $before = $now->subDays(4);

        $this->createArticle(
            $feed, [
            'updated_date' => $before,
            'read'         => true
        ], 5);
        $this->createArticle(
            $feed, [
            'updated_date' => $before,
            'read'         => false
        ], 5);
        $this->createArticle($feed, ['read' => false], 30);
        $count = $feed->cleanup();
        $this->assertEquals(5, $count);

        $feed->articles()
             ->delete();

        $feed->settings = [
            'articles' => [
                'keep'    => 3,
                'cleanup' => [
                    'keepUnread' => false
                ]
            ]
        ];
        $feed->save();
        $this->createArticle($feed, ['read' => false, 'updated_date' => $before], 10);
        $this->createArticle($feed, ['read' => true, 'updated_date' => $before], 10);
        $count = $feed->cleanup();
        $this->assertEquals(20, $count);
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

        $this->createArticle(
            $feed, [
            'updated_at' => $before,
            'read'       => true
        ], 10);
        $this->createArticle($feed, [], 30);

        Artisan::call('feed:cleanup', ['--days' => 20]);

        $articles = $feed->articles->all();
        $this->assertCount(30, $articles);
    }
}
