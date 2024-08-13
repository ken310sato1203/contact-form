<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\DatabaseSeeder;

class RegisterTest extends TestCase
{
    // テスト後にデータベースをリセット ※すべてのデータが消えるので注意
    use RefreshDatabase;
    // DBリセット後にSeederで初期データを登録
    protected string $seeder = DatabaseSeeder::class;

    public function test_register_status()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_register_store()
    {
        $name = ['name' => 'John'];
        $email = ['email' => 'test@example.com'];
        $password = ['password' => '12345678'];

        $response = $this->post('/register', array_merge(
            $name,
            $email,
            $password
        ));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/');
        $this->assertGuest(); // 未ログイン

        $this->assertDatabaseHas('users', array_merge(
            $name,
            $email,
        ));
    }

    public function test_register_validation()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => '',
            'password' => ''
        ]);
        $response->assertInvalid(['name' => 'お名前を入力してください']);
        $response->assertInvalid(['email' => 'メールアドレスを入力してください']);
        $response->assertInvalid(['password' => 'パスワードを入力してください']);

        $response = $this->post('/register', [
            'name' => 'John',
            'email' => 'xxx',
            'password' => '12345678'
        ]);
        $response->assertInvalid(['email' => 'メールアドレスは「ユーザー名@ドメイン」形式で入力してください']);

        $response = $this->post('/register', [
            'name' => 'John',
            'email' => 'xxx@xxx',
            'password' => '12345678'
        ]);
        $response->assertInvalid(['email' => 'メールアドレスは「ユーザー名@ドメイン」形式で入力してください']);

        $response = $this->post('/register', [
            'name' => 'John',
            'email' => 'あああ@example.com',
            'password' => '12345678'
        ]);
        $response->assertInvalid(['email' => 'メールアドレスは「ユーザー名@ドメイン」形式で入力してください']);

        $response = $this->post('/register', [
            'name' => 'John',
            'email' => 'test@example.com',
            'password' => 'xxx'
        ]);
        $response->assertInvalid(['password' => 'パスワードは、8文字以上にしてください。']);
    }
}
