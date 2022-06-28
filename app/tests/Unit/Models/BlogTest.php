<?php
namespace Tests\Unit\Models;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function userリレーションを返す()
    {
        $blog = Blog::factory()->create();

        $this->assertInstanceOf(User::class, $blog->user);
    }

    /** @test */
    public function commentsリレーションを返す()
    {
        $blog = Blog::factory()->create();
        $this->assertInstanceOf(Collection::class, $blog->comments);
    }

    /** @test */
    public function ブログの公開・非公開のスコープ()
    {
        $blog1 = Blog::factory()->create([
            'status' => Blog::CLOSED,
            'title' => 'ブログA'
        ]);

        $blog2 = Blog::factory()->create(['title' => 'ブログB']);
        $blog3 = Blog::factory()->create(['title' => 'ブログC']);

        $blogs = Blog::onlyOpen()->get();

        $this->assertFalse($blogs->contains($blog1));
        $this->assertTrue($blogs->contains($blog2));
        $this->assertTrue($blogs->contains($blog3));
    }
}
