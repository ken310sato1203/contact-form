<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\DatabaseSeeder;
use App\Models\Contact;

class LoginTest extends TestCase
{
    // テスト後にデータベースをリセット ※すべてのデータが消えるので注意
    use RefreshDatabase;
    // DBリセット後にSeederで初期データを登録
    protected string $seeder = DatabaseSeeder::class;

    // actingAsの引数に直接userを渡すと警告が出るためプライベート変数で渡す
    private $act_user;

    public function create_user()
    {
        $this->act_user = User::factory()->create();
    }

    public function test_login_redirect()
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }
    public function test_login_status()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_login_acting()
    {
        $this->create_user();
        $this->actingAs($this->act_user); // ログイン状態にする
        $this->assertAuthenticated(); // ログイン済みを確認
        $response = $this->get('/');
        $response->assertStatus(200); // リダイレクトされないことを確認
    }

    public function test_login_auth()
    {
        $password = '12345678';
        $user = User::factory()->create([
            'name' => 'John',
            'email' => 'test@example.com',
            'password' => Hash::make($password)
        ]);

        $response = $this->post('/login', [
            'email' => 'xxx@xxx',
            'password' => $password
        ]);
        $this->assertGuest(); // 未ログイン


        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'xxx'
        ]);
        $this->assertGuest(); // 未ログイン

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password
        ]);
        $response->assertSessionHasNoErrors();
        $this->assertAuthenticated(); // ログイン済み
    }

    public function test_login_validation()
    {
        $password = '12345678';
        $user = User::factory()->create([
            'name' => 'John',
            'email' => 'test@example.com',
            'password' => Hash::make($password)
        ]);

        $response = $this->post('/login', [
            'email' => '',
            'password' => ''
        ]);
        $response->assertInvalid(['email' => 'メールアドレスを入力してください']);
        $response->assertInvalid(['password' => 'パスワードを入力してください']);

        $response = $this->post('/login', [
            'email' => 'xxx@xxx',
            'password' => $password
        ]);
        $response->assertInvalid(['email' => 'メールアドレスまたはパスワードが間違っています。']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'xxx'
        ]);
        $response->assertInvalid(['email' => 'メールアドレスまたはパスワードが間違っています。']);
    }

    public function test_logout()
    {
        $this->create_user();
        $this->actingAs($this->act_user); // ログイン状態にする
        $this->assertAuthenticated(); // ログイン済みを確認
        $response = $this->get('/');
        $response->assertStatus(200); // リダイレクトされないことを確認

        $response = $this->post('/logout');
        $this->assertGuest(); // 未ログイン
    }
}
