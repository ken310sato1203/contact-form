<link rel="stylesheet" href="{{ asset('css/modal.css') }}">

<div class="modal-content" id="modal-content-{{ $id }}">


    <span class="round_btn" id="modal-close"></span>

    <div class="modal__content">
        <!-- <div class="modal__heading">
            <h2>お問い合わせ詳細</h2>
        </div> -->
        <div class="modal-table">
            <table class="modal-table__inner">
                <tr class="modal-table__row">
                    <th class="modal-table__header">お名前</th>
                    <td class="modal-table__text">
                        <input type="text" name="name" value="{{ $name }}" readonly />
                    </td>
                </tr>
                <tr class="modal-table__row">
                    <th class="modal-table__header">性別</th>
                    <td class="modal-table__text">
                        <input type="text" name="gender_content" value="{{ $gender_content }}" readonly />
                    </td>
                </tr>
                <tr class="modal-table__row">
                    <th class="modal-table__header">メールアドレス</th>
                    <td class="modal-table__text">
                        <input type="email" name="email" value="{{ $email }}" readonly />
                    </td>
                </tr>
                <tr class="modal-table__row">
                    <th class="modal-table__header">電話番号</th>
                    <td class="modal-table__text">
                        <input type="tel" name="tel" value="{{ $tel }}" readonly />
                    </td>
                </tr>
                <tr class="modal-table__row">
                    <th class="modal-table__header">住所</th>
                    <td class="modal-table__text">
                        <input type="text" name="address" value="{{ $address }}" readonly />
                    </td>
                </tr>
                <tr class="modal-table__row">
                    <th class="modal-table__header">建物名</th>
                    <td class="modal-table__text">
                        <input type="text" name="building" value="{{ $building }}" readonly />
                    </td>
                </tr>
                <tr class="modal-table__row">
                    <th class="modal-table__header">お問い合わせの種類</th>
                    <td class="modal-table__text">
                        <input type="text" name="category_content" value="{{ $category_content }}" readonly />
                    </td>
                </tr>
                <tr class="modal-table__row">
                    <th class="modal-table__header">お問い合わせ内容</th>
                    <td class="modal-table__text">
                        {{ $detail }}
                    </td>
                </tr>
            </table>
        </div>

        <form class="delete-form" action="/delete" method="post">
            @method('DELETE')
            @csrf
            <div class="form__button">
                <input type="hidden" name="id" value="{{ $id }}">
                <input type="hidden" name="keyword" value="{{ request()->query('keyword') }}">
                <input type="hidden" name="gender" value="{{ request()->query('gender') }}">
                <input type="hidden" name="category_id" value="{{ request()->query('category_id') }}">
                <input type="hidden" name="date" value="{{ request()->query('date') }}">
                <button class="form__button-submit" type="submit">削除</button>
            </div>
        </form>
    </div>

    <div>