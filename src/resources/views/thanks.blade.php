@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('content')
<div class="thanks__content">
    <div class="thanks__heading">
        <h2>お問い合わせありがとうございました</h2>
        <div class="form__button">
            <a href="/">
                <button class="form__button-submit">HOME</button>
            </a>
        </div>
    </div>
</div>
@endsection