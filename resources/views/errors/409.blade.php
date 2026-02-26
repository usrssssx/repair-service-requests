@extends('layouts.app')

@section('title', 'Конфликт')

@section('content')
    <h1>409 — Конфликт</h1>
    <p>{{ $message ?? 'Операция не выполнена.' }}</p>
    <p><a href="/master/requests" class="btn btn-secondary">Вернуться к списку</a></p>
@endsection
