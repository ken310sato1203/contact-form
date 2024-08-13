<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\DatabaseSeeder;

class ContactTest extends TestCase
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

    public function setup(): void
    {
        parent::setUp();
        $this->create_user();
        $this->actingAs($this->act_user); // ログイン状態にする
    }

    public function test_contact_store()
    {
        $contact = [
            'category_id' => '1',
            'first_name' => 'John',
            'last_name' => 'Smith',
            'gender' => '1',
            'email' => 'test@example.com',
            'tel' => '11122223333',
            'address' => '東京都',
            'building' => 'マンション',
            'detail' => 'お問い合わせ内容'
        ];

        $response = $this->post('/thanks', $contact);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $this->assertDatabaseHas('contacts', $contact);
    }

    public function test_contact_confirm()
    {
        $contact = [
            'category_id' => '1',
            'first_name' => 'John',
            'last_name' => 'Smith',
            'gender' => '1',
            'email' => 'test@example.com',
            'tel1' => '111',
            'tel2' => '2222',
            'tel3' => '3333',
            'address' => '東京都',
            'building' => 'マンション',
            'detail' => 'お問い合わせ内容'
        ];

        $response = $this->post('/confirm', $contact);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $response
            ->assertSee('商品のお届けについて') // category_id=1
            ->assertSee('男性'); // gender=1
    }

    public function test_contact_validation()
    {
        $response = $this->post('/confirm', [
            'category_id' => '',
            'first_name' => '',
            'last_name' => '',
            'gender' => '',
            'email' => '',
            'tel1' => '',
            'tel2' => '',
            'tel3' => '',
            'address' => '',
            'building' => '',
            'detail' => ''
        ]);
        $response->assertInvalid(['category_id' => 'お問い合わせの種類を選択してください']);
        $response->assertInvalid(['first_name' => '名を入力してください']);
        $response->assertInvalid(['last_name' => '姓を入力してください']);
        $response->assertInvalid(['gender' => '性別を選択してください']);
        $response->assertInvalid(['email' => 'メールアドレスを入力してください']);
        $response->assertInvalid(['tel1' => '電話番号を入力してください']);
        $response->assertInvalid(['tel2' => '電話番号を入力してください']);
        $response->assertInvalid(['tel3' => '電話番号を入力してください']);
        $response->assertInvalid(['address' => '住所を入力してください']);
        $response->assertInvalid(['detail' => 'お問い合わせ内容を入力してください']);

        $response = $this->post('/confirm', [
            'category_id' => '1',
            'first_name' => 'John',
            'last_name' => 'Smith',
            'gender' => '1',
            'email' => 'xxx',
            'tel1' => '111',
            'tel2' => '2222',
            'tel3' => '3333',
            'address' => '東京都',
            'building' => 'マンション',
            'detail' => 'お問い合わせ内容'
        ]);
        $response->assertInvalid(['email' => 'メールアドレスはメール形式で入力してください']);

        $response = $this->post('/confirm', [
            'category_id' => '1',
            'first_name' => 'John',
            'last_name' => 'Smith',
            'gender' => '1',
            'email' => 'test@example.com',
            'tel1' => 'xxx',
            'tel2' => '2222',
            'tel3' => '3333',
            'address' => '東京都',
            'building' => 'マンション',
            'detail' => 'お問い合わせ内容'
        ]);
        $response->assertInvalid(['tel1' => '電話番号は半角数字で入力してください']);

        $response = $this->post('/confirm', [
            'category_id' => '1',
            'first_name' => 'John',
            'last_name' => 'Smith',
            'gender' => '1',
            'email' => 'test@example.com',
            'tel1' => '111',
            'tel2' => '2222',
            'tel3' => '3333',
            'address' => '東京都',
            'building' => 'マンション',
            'detail' => '１１１１１１１１１１２２２２２２２２２２３３３３３３３３３３４４４４４４４４４４５５５５５５５５５５６６６６６６６６６６７７７７７７７７７７８８８８８８８８８８９９９９９９９９９９００００００００００１１１１１１１１１１２２２２２２２２２２x'
        ]);
        $response->assertInvalid(['detail' => 'お問合せ内容は120文字以内で入力してください']);
    }
}
