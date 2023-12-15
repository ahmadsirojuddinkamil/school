<?php

namespace Modules\News\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_news_is_displayed(): void
    {
        $respone = $this->get('/');
        $respone->assertStatus(200);
        $respone->assertViewIs('news::pages.news.index');
    }
}
