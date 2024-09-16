<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ContactRequest;

class TestRequestTest extends TestCase
{

    /**
     * @group validation
     * @dataProvider provideTestData
     */
    public function testValidation($input, $expectedMessages)
    {
        $request = new ContactRequest();
        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($input, $request->rules(), $request->messages());
        $this->assertEquals($expectedMessages, $validator->messages()->getMessages());
    }

    /**
     * バリデーションテスト用データプロバイダー
     * 1要素目: 入力値
     * 2要素目: バリデーションの結果（true:成功/false:失敗）
     */
    public function provideTestData()
    {
        return [
            '1_ok' => [
                [
                    'last_name' => 'Smith',
                    'first_name' => 'John',
                    'gender' => '1',
                    'tel1' => '11111',
                    'tel2' => '22222',
                    'tel3' => '33333',
                    'email' => 'test@example.com',
                    'address' => '東京都',
                    'category_id' => '1',
                    'detail' => 'お問い合わせ内容'
                ],
                []
            ],
            '2_ng_required' => [
                [
                    'last_name' => '',
                    'first_name' => '',
                    'gender' => '',
                    'tel1' => '',
                    'tel2' => '',
                    'tel3' => '',
                    'email' => '',
                    'address' => '',
                    'category_id' => '',
                    'detail' => ''
                ],
                [
                    'last_name' => ['姓を入力してください'],
                    'first_name' => ['名を入力してください'],
                    'gender' => ['性別を選択してください'],
                    'tel1' => ['電話番号を入力してください'],
                    'tel2' => ['電話番号を入力してください'],
                    'tel3' => ['電話番号を入力してください'],
                    'email' => ['メールアドレスを入力してください'],
                    'address' => ['住所を入力してください'],
                    'category_id' => ['お問い合わせの種類を選択してください'],
                    'detail' => ['お問い合わせ内容を入力してください'],
                ]
            ],
            '3_ng_max' => [
                [
                    'last_name' => '１１１１１１１１１１２２２２２２２２２２３３３３３３３３３３４４４４４４４４４４５５５５５５５５５５６６６６６６６６６６７７７７７７７７７７８８８８８８８８８８９９９９９９９９９９００００００００００１１１１１１１１１１２２２２２２２２２２３３３３３３３３３３４４４４４４４４４４５５５５５５５５５５６６６６６６６６６６７７７７７７７７７７８８８８８８８８８８９９９９９９９９９９００００００００００１１１１１１１１１１２２２２２２２２２２３３３３３３３３３３４４４４４４４４４４５５５５５５５５５５６６６６６x',
                    'first_name' =>
                    '１１１１１１１１１１２２２２２２２２２２３３３３３３３３３３４４４４４４４４４４５５５５５５５５５５６６６６６６６６６６７７７７７７７７７７８８８８８８８８８８９９９９９９９９９９００００００００００１１１１１１１１１１２２２２２２２２２２３３３３３３３３３３４４４４４４４４４４５５５５５５５５５５６６６６６６６６６６７７７７７７７７７７８８８８８８８８８８９９９９９９９９９９００００００００００１１１１１１１１１１２２２２２２２２２２３３３３３３３３３３４４４４４４４４４４５５５５５５５５５５６６６６６x',
                    'gender' => '1',
                    'tel1' => '11111',
                    'tel2' => '22222',
                    'tel3' => '33333',
                    'email' => '１１１１１１１１１１２２２２２２２２２２３３３３３３３３３３４４４４４４４４４４５５５５５５５５５５６６６６６６６６６６７７７７７７７７７７８８８８８８８８８８９９９９９９９９９９００００００００００１１１１１１１１１１２２２２２２２２２２３３３３３３３３３３４４４４４４４４４４５５５５５５５５５５６６６６６６６６６６７７７７７７７７７７８８８８８８８８８８９９９９９９９９９９００００００００００１１１１１１１１１１２２２２２２２２２２３３３３３３３３３３４４４４４４４４４４５５５５５５５５５５６６６６６x',
                    'address' => '東京都',
                    'category_id' => '1',
                    'detail' => '１１１１１１１１１１２２２２２２２２２２３３３３３３３３３３４４４４４４４４４４５５５５５５５５５５６６６６６６６６６６７７７７７７７７７７８８８８８８８８８８９９９９９９９９９９００００００００００１１１１１１１１１１２２２２２２２２２２x'
                ],
                [
                    'last_name' => ['姓を255文字以下で入力してください'],
                    'first_name' => ['名を255文字以下で入力してください'],
                    'email' => [
                        'メールアドレスはメール形式で入力してください',
                        'メールアドレスを255文字以下で入力してください',
                    ],
                    'detail' => ['お問合せ内容は120文字以内で入力してください'],
                ]
            ],
            '4_ng_tel' => [
                [
                    'last_name' => 'Smith',
                    'first_name' => 'John',
                    'gender' => '1',
                    'tel1' => 'aaaaaa',
                    'tel2' => 'bbbbbb',
                    'tel3' => 'cccccc',
                    'email' => 'test@example.com',
                    'address' => '東京都',
                    'category_id' => '1',
                    'detail' => 'お問い合わせ内容'
                ],
                [
                    'tel1' => [
                        '電話番号は半角数字で入力してください',
                        '電話番号は5桁までの数字で入力してください'
                    ],
                    'tel2' => [
                        '電話番号は半角数字で入力してください',
                        '電話番号は5桁までの数字で入力してください'
                    ],
                    'tel3' => [
                        '電話番号は半角数字で入力してください',
                        '電話番号は5桁までの数字で入力してください'
                    ],
                ]
            ],
            '5_ng_email' => [
                [
                    'last_name' => 'Smith',
                    'first_name' => 'John',
                    'gender' => '1',
                    'tel1' => '11111',
                    'tel2' => '22222',
                    'tel3' => '33333',
                    'email' => 'xxxx',
                    'address' => '東京都',
                    'category_id' => '1',
                    'detail' => 'お問い合わせ内容'
                ],
                [
                    'email' => [
                        'メールアドレスはメール形式で入力してください',
                    ],
                ]
            ],
        ];
    }
}
