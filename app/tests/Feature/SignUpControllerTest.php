<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
* @see App\Http\Controllers\SignUpController;
*/
class SignUpControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * index
     */
    public function ユーザー登録画面を開ける()
    {
        $this->withoutExceptionHandling();
        $this->get('signup')
            ->assertOk();
    }

    /**
     * @test store
     */
    public function ユーザー登録できる()
    {
        // データ検証
        // DBに保存
        // ログインさせてからマイページにリダイレクト
        $validData = [
            'name' => '太郎',
            'email' => 'aaa@bbb.ccc',
            'password' => 'P@ssw0rd'
        ];

        $this->post('signup', $validData)
            ->assertOk();

        unset($validData['password']);
        $this->assertDatabaseHas('users', $validData);

        // パスワードの検証
        $user = User::firstWhere($validData);
        $this->assertTrue(Hash::check('P@ssw0rd', $user->password));
    }
}
