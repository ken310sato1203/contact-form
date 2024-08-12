@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endsection

@section('content')
<div class="contact-form__content">
    <div class="contact-form__heading">
        <h2>Contact</h2>
    </div>

    <div class="form__group">
        <form class="form" action="/confirm" method="post" novalidate>
            @csrf
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">お名前</span>
                    <sup class="form__label--required">※</sup>
                </div>
                <div class="form__group-content">
                    <div class="form__group-content-name">
                        <div>
                            <div class="form__input--text-name">
                                <input type="text" name="last_name" placeholder="例：山田" value="{{ old('last_name') }}" />
                            </div>
                            <div class="form__error">
                                @error('last_name')
                                {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div>
                            <div class="form__input--text-name">
                                <input type="text" name="first_name" placeholder="例：太郎" value="{{ old('first_name') }}" />
                            </div>
                            <div class="form__error">
                                @error('first_name')
                                {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">性別</span>
                    <sup class="form__label--required">※</sup>
                </div>
                <div class="form__group-content">
                    <div class="form__group-content-name">
                        @foreach ($genders as $gender)
                        <div>
                            <div class="form__input--text-gender">
                                <input type="radio" name="gender" value="{{ $loop->index + 1 }}" {{ old('gender') == $loop->index + 1 ? 'checked' : '' }}> {{ $gender }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="form__error">
                        @error('gender')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">メールアドレス</span>
                    <sup class="form__label--required">※</sup>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="email" pattern="^[a-zA-Z0-9]+$" name="email" placeholder="例：test@example.com" value="{{ old('email') }}" />
                    </div>
                    <div class="form__error">
                        @error('email')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">電話番号</span>
                    <sup class="form__label--required">※</sup>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text-tel">
                        <input size="5" type="tel" name="tel1" placeholder="080" value="{{ old('tel1') }}" /> - <input size=" 7" type="tel" name="tel2" placeholder="1234" value="{{ old('tel2') }}" /> - <input size=" 7" type="tel" name="tel3" placeholder="5678" value="{{ old('tel3') }}" />
                    </div>
                    <div class=" form__error">
                        @if ($errors->has('tel1') || $errors->has('tel2') || $errors->has('tel3'))
                        @foreach (array_unique([$errors->first('tel1'),$errors->first('tel2'),$errors->first('tel3')]) as $error)
                        <p>{{ $error }}</p>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">住所</span>
                    <sup class="form__label--required">※</sup>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="text" name="address" placeholder="例：東京都渋谷区千駄ヶ谷1-2-3" value="{{ old('address') }}" />
                    </div>
                    <div class="form__error">
                        @error('address')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">建物名</span>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="text" name="building" placeholder="例：千駄ヶ谷マンション101" value="{{ old('building') }}" />
                    </div>
                    <div class="form__error">
                        @error('building')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">お問い合わせの種類</span>
                    <sup class="form__label--required">※</sup>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text-category select_wrapper">
                        <select name="category_id">
                            <option value="">選択してください</option>
                            @foreach ($categories as $category)
                            <option type="text" value="{{ $category['id'] }}" @if($category['id']===(int)old('category_id')) selected @endif>{{ $category['content'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form__error">
                        @error('category_id')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">お問い合わせ内容</span>
                    <sup class="form__label--required">※</sup>
                </div>
                <div class="form__group-content">
                    <div class="form__input--textarea">
                        <textarea name="detail" placeholder="お問い合わせ内容をご記載ください">{{ old('detail') }}</textarea>
                    </div>
                    <div class="form__error">
                        @error('detail')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form__button">
                <button class="form__button-submit" type="submit">確認画面</button>
            </div>

        </form>
    </div>

</div>
@endsection