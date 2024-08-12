@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
<script src="{{ asset('/js/admin.js') }}"></script>

<!-- メッセージの表示 -->
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
    <div class="admin__heading">
        <h2>Admin</h2>
    </div>

    <form class="search-form" action="/admin" method="get">
        <div class="search-form__item" id="keyword">
            <input class="search-form__item-input" type="text" name="keyword" placeholder="名前やメールアドレスを入力してください" value="{{ request()->query('keyword') }}" />
        </div>

        <div class="search-form__item select_wrapper" id="gender">
            <select class="search-form__item-select" name=" gender">
                <option value="">性別</option>
                @foreach ($genders as $gender)
                <option type="text" value="{{ $loop->index + 1 }}" @if(request()->query('gender') == $loop->index + 1 ) selected @endif>{{ $gender }}</option>
                @endforeach
            </select>
        </div>

        <div class="search-form__item select_wrapper" id="category">
            <select class="search-form__item-select" name="category_id">
                <option value="">お問い合わせの種類</option>
                @foreach ($categories as $category)
                <option type="text" value="{{ $category['id'] }}" @if(request()->query('category_id')==$category['id']) selected @endif>{{ $category['content'] }}</option>
                @endforeach
            </select>
        </div>


        <div class="search-form__item" id="date">
            <input class="search-form__item-input" type="date" name="date" value="{{ request()->query('date') }}">
        </div>
        <div class="search-form__button">
            <button class="search-form__button-submit" type="submit">検索</button>
        </div>
        <div class="search-form__button">
            <button class="search-form__button-reset">リセット</button>
        </div>
    </form>

    <div class="admin-option">
        <form class="search-form" action="/download" method="get">
            <div class="search-form__button">
                <input type="hidden" name="keyword" value="{{ request()->query('keyword') }}">
                <input type="hidden" name="gender" value="{{ request()->query('gender') }}">
                <input type="hidden" name="category_id" value="{{ request()->query('category_id') }}">
                <input type="hidden" name="date" value="{{ request()->query('date') }}">
                <button class="search-form__button-download" type="submit">エクスポート</button>
            </div>
        </form>

        <!-- ページネーションのリンク -->
        <div class="admin-pageline">
            {{ $contacts->links('pagelink') }}
        </div>
    </div>

    <div class="admin-table">
        <table class="admin-table__inner">

            <tr class="admin-table__row">
                <th class="admin-form__item" id="name">
                    <p class="admin-form__item-heaer">お名前</p>
                </th>
                <th class="admin-form__item" id="gender">
                    <p class="admin-form__item-heaer">性別</p>
                </th>
                <th class="admin-form__item" id="email">
                    <p class="admin-form__item-heaer">メールアドレス</p>
                    </td>
                <th class="admin-form__item" id="category">
                    <p class="admin-form__item-heaer">お問い合わせの種類</p>
                </th>
                <th class="admin-form__item" id="detail">
                    <p class="admin-form__item-heaer"></p>
                </th>
            </tr>

            @foreach ($contacts as $contact)

            <tr class="admin-table__row">
                <td class="admin-form__item" id="name">
                    <p class="admin-form__item-p">{{ $contact['last_name'] }} {{ $contact['first_name'] }}</p>
                </td>
                <td class="admin-form__item" id="gender">
                    <p class="admin-form__item-p">{{ $genders[$contact['gender']-1] }}</p>
                </td>
                <td class="admin-form__item" id="email">
                    <p class="admin-form__item-p">{{ $contact['email'] }}</p>
                </td>
                <td class="admin-form__item" id="category">
                    <p class="admin-form__item-p">{{ $contact['category']['content'] }}</p>
                </td>
                <td class="admin-form__item" id="detail">
                    <div class="detail-form__button">
                        <button id="{{ $contact['id'] }}" class="modal-open detail-form__button-submit" type="button">
                            詳細
                        </button>
                        <x-modal>
                            <x-slot name="id">
                                {{ $contact['id'] }}
                            </x-slot>
                            <x-slot name="name">
                                {{ $contact['last_name'] }} {{ $contact['first_name'] }}
                            </x-slot>
                            <x-slot name="gender_content">
                                {{ $genders[$contact['gender']-1] }}
                            </x-slot>
                            <x-slot name="email">
                                {{ $contact['email'] }}
                            </x-slot>
                            <x-slot name="tel">
                                {{ $contact['tel'] }}
                            </x-slot>
                            <x-slot name="address">
                                {{ $contact['address'] }}
                            </x-slot>
                            <x-slot name="building">
                                {{ $contact['building'] }}
                            </x-slot>
                            <x-slot name="category_content">
                                {{ $contact['category']['content'] }}
                            </x-slot>
                            <x-slot name="detail">
                                {{ $contact['detail'] }}
                            </x-slot>
                        </x-modal>
                    </div>
                </td>
            </tr>
            @endforeach

        </table>
    </div>
</div>
@endsection