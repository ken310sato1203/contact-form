<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\DatabaseSeeder;
use \Datetime;

use function PHPUnit\Framework\assertEquals;

class AdminTest extends TestCase
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

    public function test_admin_contact_search()
    {
        $target = Contact::factory()->create([
            'category_id' => '1',
            'first_name' => 'John',
            'last_name' => 'Smith',
            'gender' => '1',
            'email' => 'test@example.com',
            'tel' => '11122223333',
            'address' => '東京都',
            'building' => 'マンション',
            'detail' => 'お問い合わせ内容'
        ]);

        $condition =
            '?keyword=John'
            . '&gender=2'
            . '&category_id=1'
            . '&date=' . substr($target['created_at'], 0, 10);

        $response = $this->get('/admin' . $condition);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $response->assertViewHas('contacts');
        $contacts = $response->original['contacts'];
        $this->assertEquals(0, count($contacts)); // 検索結果0件

        $condition =
            '?keyword=John'
            . '&gender=1'
            . '&category_id=2'
            . '&date=' . substr($target['created_at'], 0, 10);

        $response = $this->get('/admin' . $condition);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $response->assertViewHas('contacts');
        $contacts = $response->original['contacts'];
        $this->assertEquals(0, count($contacts)); // 検索結果0件

        $condition =
            '?keyword=John'
            . '&gender=1'
            . '&category_id=1'
            . '&date=xxx';

        $response = $this->get('/admin' . $condition);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $response->assertViewHas('contacts');
        $contacts = $response->original['contacts'];
        $this->assertEquals(0, count($contacts)); // 検索結果0件

        $condition =
            '?keyword=John'
            . '&gender=1'
            . '&category_id=1'
            . '&date=' . substr($target['created_at'], 0, 10);

        $response = $this->get('/admin' . $condition);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $response->assertViewHas('contacts');
        $contacts = $response->original['contacts'];
        $this->assertEquals(1, count($contacts));
        $this->assertEquals($target['category_id'], $contacts[0]['category_id']);
        $this->assertEquals($target['first_name'], $contacts[0]['first_name']);
        $this->assertEquals($target['last_name'], $contacts[0]['last_name']);
        $this->assertEquals($target['gender'], $contacts[0]['gender']);
        $this->assertEquals($target['email'], $contacts[0]['email']);
        $this->assertEquals($target['tel'], $contacts[0]['tel']);
        $this->assertEquals($target['address'], $contacts[0]['address']);
        $this->assertEquals($target['building'], $contacts[0]['building']);
        $this->assertEquals($target['detail'], $contacts[0]['detail']);
    }

    public function test_admin_contact_delete()
    {
        $target = Contact::factory()->create([
            'category_id' => '1',
            'first_name' => 'John',
            'last_name' => 'Smith',
            'gender' => '1',
            'email' => 'test@example.com',
            'tel' => '11122223333',
            'address' => '東京都',
            'building' => 'マンション',
            'detail' => 'お問い合わせ内容'
        ]);

        $response = $this->delete('/delete', ['id' => $target['id']]);
        $response->assertSessionHasNoErrors();
        $this->assertDeleted('contacts', ['id' => $target['id']]);
        $response->assertRedirect('/admin?keyword=&gender=&category_id=&date=');
    }
}
