@extends('layouts.app')

@section('title', 'Вход')

@section('content')
    <h1>Вход</h1>
    <p class="text-muted">Выберите пользователя из сидов.</p>
    <form method="POST" action="/login">
        @csrf
        <div class="field">
            <label for="user_id" class="label">Пользователь</label>
            <select name="user_id" id="user_id" class="select {{ $errors->has('user_id') ? 'field-error' : '' }}" required>
                <option value="">-- выберите --</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                @endforeach
            </select>
            @error('user_id')
                <div class="field-error-text">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Войти</button>
    </form>
@endsection
