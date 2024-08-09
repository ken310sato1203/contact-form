@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
<div class="admin__alert">
    @if (session('message'))
    <div class="admin__alert--success">{{ session('message') }}</div>
    @endif @if ($errors->any())
    <div class="admin__alert--danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
<div class="admin__content">
    <form class="search-form" action="/search" method="get">
        <div class="search-form__item">
            <input class="search-form__item-input" type="text" name="keyword" placeholder="名前やメールアドレスを入力してください" value="{{ request()->query('keyword') }}" />
        </div>

        <div class="search-form__item">
            <select name="gender">
                <option value="">性別</option>
                @foreach ($genders as $gender)
                <option type="text" value="{{ $loop->index + 1 }}" @if(request()->query('gender') == $loop->index + 1 ) selected @endif>{{ $gender }}</option>
                @endforeach
            </select>
        </div>

        <div class="search-form__item">
            <select name="category_id">
                <option value="">お問い合わせの種類</option>
                @foreach ($categories as $category)
                <option type="text" value="{{ $category['id'] }}" @if(request()->query('category_id')==$category['id']) selected @endif>{{ $category['content'] }}</option>
                @endforeach
            </select>
        </div>


        <div class="search-form__item">
            <input type="date" name="date" value="{{ request()->query('date') }}">
        </div>
        <div class="search-form__button">
            <button class="search-form__button-submit" type="submit">検索</button>
        </div>
        <div class="search-form__button">
            <button class="search-form__button-submit" type="reset">リセット</button>
        </div>
    </form>
    <div class="admin-table">
        <table class="admin-table__inner">
            <tr class="admin-table__row">
                <th class="admin-table__header">
                    <span class="admin-table__header-span">お名前</span>
                    <span class="admin-table__header-span">性別</span>
                    <span class="admin-table__header-span">メールアドレス</span>
                    <span class="admin-table__header-span">お問い合わせの種類</span>
                    <span class="admin-table__header-span"></span>
                </th>
            </tr>
            @foreach ($contacts as $contact)
            <tr class="admin-table__row">
                <td class="admin-table__item">
                    <form class="update-form" action="/todos/update" method="post">
                        @method('PATCH') @csrf
                        <div class="update-form__item">
                            <input class="update-form__item-input" type="text" name="content" value="{{ $contact['last_name'] }} {{ $contact['first_name'] }}" />
                        </div>
                        <div class="update-form__item">
                            <p class="update-form__item-p">{{ $genders[$contact['gender']-1] }}</p>
                        </div>
                        <div class="update-form__item">
                            <p class="update-form__item-p">{{ $contact['email'] }}</p>
                        </div>
                        <div class="update-form__item">
                            <p class="update-form__item-p">{{ $contact['category']['content'] }}</p>
                        </div>
                        <div class="update-form__item">
                            <div class="update-form__button">
                                <button class="update-form__button-submit" type="submit">
                                    詳細
                                </button>
                            </div>
                        </div>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection