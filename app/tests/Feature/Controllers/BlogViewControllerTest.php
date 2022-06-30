<?php
namespace Tests\Feature\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogViewControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test index*/
    public function ブログのトップページを開ける()
    {
        $blog1 = Blog::factory()->hasComments(1)->create();
        $blog2 = Blog::factory()->hasComments(3)->create();
        $blog3 = Blog::factory()->hasComments(2)->create();

        $response = $this->get('/');
        $response
            ->assertOk()
            ->assertSee($blog1->title)
            ->assertSee($blog2->title)
            ->assertSee($blog3->title)
            ->assertSee($blog1->user->name)
            ->assertSee($blog2->user->name)
            ->assertSee($blog3->user->name)
            ->assertSee('(1件のコメント)')
            ->assertSee('(3件のコメント)')
            ->assertSee('(2件のコメント)')
            ->assertSeeInOrder([$blog2->title, $blog3->title, $blog1->title]);
    }

    /* @test **/
    public function ブログ一覧で非公開のブログは表示されない()
    {
        Blog::factory()->create([
            'status' => Blog::CLOSED,
            'title' => 'ブログA'
        ]);

        Blog::factory()->create(['title' => 'ブログB']);
        Blog::factory()->create(['title' => 'ブログC']);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('ブログA')
            ->assertSee('ブログB')
            ->assertSee('ブログC');
    }

    /** @test show*/
    public function ブログの詳細画面が表示でき、コメントが古い順に表示される()
    {
        $this->withoutExceptionHandling();
        $blog = Blog::factory()->withCommentsData([
            ['name' => '太郎3', 'created_at' => now()->sub('3 days')],
            ['name' => '太郎2', 'created_at' => now()->sub('2 days')],
            ['name' => '太郎1', 'created_at' => now()->sub('1 days')],
        ])->create();

        $this->get('blogs/' . $blog->id)
            ->assertOk()
            ->assertSee($blog->title)
            ->assertSee($blog->user->name)
            ->assertSeeInOrder(['太郎3', '太郎2', '太郎1']);
    }

    /** @test show*/
    public function ブログで非公開のものは詳細画面で表示できない()
    {
        $blog = Blog::factory()->close()->create();

        $this->get('blogs/' . $blog->id)
            ->assertForbidden();
    }

    /**@test */
    public function クリスマスの日はメリークリスマス！と表示される()
    {
        $blog = Blog::factory()->create();
        Carbon::setTestNow('2022-12-24');
        $this->get('blogs/' . $blog->id)
            ->assertOk()
            ->assertDontSee('メリークリスマス！');

        Carbon::setTestNow('2022-12-25');
        $this->get('blogs/' . $blog->id)
            ->assertOk()
            ->assertSee('メリークリスマス！');
    }
}
