<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AlphaRule;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'last_name' => ['required', 'max:255'],
            'first_name' => ['required', 'max:255'],
            'gender' => ['required'],
            'tel1' => ['required', 'regex:/^[0-9]+$/', 'max:5'],
            'tel2' => ['required', 'regex:/^[0-9]+$/', 'max:5'],
            'tel3' => ['required', 'regex:/^[0-9]+$/', 'max:5'],
            'email' => ['required', 'email:filter', 'max:255'],
            'address' => ['required', 'max:255'],
            'category_id' => ['required', 'max:255'],
            'detail' => ['required', 'max:120'],
        ];
    }

    public function messages()
    {
        return [
            'last_name.required' => '姓を入力してください',
            'last_name.string' => '姓を文字列で入力してください',
            'last_name.max' => '姓を255文字以下で入力してください',
            'first_name.required' => '名を入力してください',
            'first_name.string' => '名を文字列で入力してください',
            'first_name.max' => '名を255文字以下で入力してください',
            'gender.required' => '性別を選択してください',
            'tel1.required' => '電話番号を入力してください',
            'tel2.required' => '電話番号を入力してください',
            'tel3.required' => '電話番号を入力してください',
            'tel1.regex' => '電話番号は半角数字で入力してください',
            'tel2.regex' => '電話番号は半角数字で入力してください',
            'tel3.regex' => '電話番号は半角数字で入力してください',
            'tel1.max' => '電話番号は5桁までの数字で入力してください',
            'tel2.max' => '電話番号は5桁までの数字で入力してください',
            'tel3.max' => '電話番号は5桁までの数字で入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.regex' => 'メールアドレスを半角英数記号で入力してください',
            'email.email' => 'メールアドレスはメール形式で入力してください',
            'email.max' => 'メールアドレスを255文字以下で入力してください',
            // 'tel.required' => '電話番号を入力してください',
            // 'tel.numeric' => '電話番号を数値で入力してください',
            // 'tel.digits_between' => '電話番号を10桁から11桁の間で入力してください',
            'category_id.required' => 'お問い合わせの種類を選択してください',
            'detail.required' => 'お問い合わせ内容を入力してください',
            'detail.max' => 'お問合せ内容は120文字以内で入力してください',
        ];
    }
}
