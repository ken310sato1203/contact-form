<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\DatabaseSeeder;
use App\Models\Contact;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\ResultSet;

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

    public function test_contact_store_back()
    {
        $contact = [
            'back' => 'back'
        ];

        $response = $this->post('/thanks', $contact);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/');
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

    public function test_download()
    {
        $contact = Contact::all()->sortBy('id');

        $response = $this->get('/download');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $stream = mb_convert_encoding($response->streamedContent(), 'UTF-8', 'SJIS');
        $csvReader = Reader::createFromString($stream);
        $csvReader->setHeaderOffset(0);

        $stmt = new Statement();
        $func = function (array $recordA, array $recordB) {
            return $recordA['contacts.id'] <=> $recordB['contacts.id'];
        };
        $csvRecords = $stmt->orderBy($func)->process($csvReader);

        $cnt = 0;
        foreach ($csvRecords as $csvRow) {
            $this->assertEquals($csvRow['contacts.id'], $contact[$cnt]['id']);
            $this->assertEquals($csvRow['contacts.last_name'], $contact[$cnt]['last_name']);
            $this->assertEquals($csvRow['contacts.first_name'], $contact[$cnt]['first_name']);

            if ($contact[$cnt]['gender'] === 1) {
                $gender = '男性';
            } else if ($contact[$cnt]['gender'] === 2) {
                $gender = '女性';
            } else if ($contact[$cnt]['gender'] === 3) {
                $gender = 'その他';
            }
            $this->assertEquals($csvRow['contacts.gender'], $gender);

            $this->assertEquals($csvRow['contacts.email'], $contact[$cnt]['email']);
            $this->assertEquals($csvRow['contacts.tel'], $contact[$cnt]['tel']);
            $this->assertEquals($csvRow['contacts.address'], $contact[$cnt]['address']);
            $this->assertEquals($csvRow['contacts.building'], $contact[$cnt]['building']);

            if ($contact[$cnt]['category_id'] === 1) {
                $category = '商品のお届けについて';
            } else if ($contact[$cnt]['category_id'] === 2) {
                $category = '商品の交換について';
            } else if ($contact[$cnt]['category_id'] === 3) {
                $category = '商品トラブル';
            } else if ($contact[$cnt]['category_id'] === 4) {
                $category = 'ショップへのお問い合わせ';
            } else if ($contact[$cnt]['category_id'] === 5) {
                $category = 'その他';
            }
            $this->assertEquals($csvRow['categories.content'], $category);

            $this->assertEquals($csvRow['contacts.detail'], $contact[$cnt]['detail']);

            $cnt++;
        }
    }
}
