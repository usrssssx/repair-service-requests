@extends('layouts.app')

@section('title', 'Создание заявки')

@section('content')
    <h1>Создание заявки</h1>
    <form method="POST" action="/requests">
        @csrf
        <div class="field">
            <label for="client_name" class="label">Имя клиента</label>
            <input type="text" id="client_name" name="client_name" value="{{ old('client_name') }}" class="input {{ $errors->has('client_name') ? 'field-error' : '' }}" required>
            @error('client_name')
                <div class="field-error-text">{{ $message }}</div>
            @enderror
        </div>
        <div class="field">
            <label for="phone" class="label">Телефон</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="input {{ $errors->has('phone') ? 'field-error' : '' }}" required>
            @error('phone')
                <div class="field-error-text">{{ $message }}</div>
            @enderror
        </div>
        <div class="field">
            <label for="address" class="label">Адрес</label>
            <input type="text" id="address" name="address" value="{{ old('address') }}" class="input {{ $errors->has('address') ? 'field-error' : '' }}" required>
            @error('address')
                <div class="field-error-text">{{ $message }}</div>
            @enderror
        </div>
        <div class="field">
            <label for="problem_text" class="label">Описание проблемы</label>
            <textarea id="problem_text" name="problem_text" class="textarea {{ $errors->has('problem_text') ? 'field-error' : '' }}" required>{{ old('problem_text') }}</textarea>
            @error('problem_text')
                <div class="field-error-text">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Создать</button>
    </form>
@endsection
