@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('content')
<div class="thanks__content">
    <div class="thanks__heading">
        <h2>お問い合わせありがとうございました</h2>
        <a href="/">
            <button class="thanks__button">HOME</button>
        </a>
    </div>
</div>
@endsection