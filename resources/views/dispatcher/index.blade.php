@extends('layouts.app')

@section('title', 'Панель диспетчера')

@section('content')
    @php
        $actionLabels = [
            'create' => 'создание',
            'assign' => 'назначение',
            'cancel' => 'отмена',
            'take' => 'взятие',
            'done' => 'завершение',
        ];
    @endphp
    <h1>Панель диспетчера</h1>

    <form method="GET" action="/dispatcher/requests" class="field">
        <label for="status" class="label">Фильтр по статусу</label>
        <div class="actions">
            <select name="status" id="status" class="select">
                <option value="">Все</option>
                @foreach ($statuses as $value)
                    <option value="{{ $value }}" @selected($status === $value)>{{ $value }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Применить</button>
            <a href="/dispatcher/requests" class="link-pill">Сбросить</a>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Клиент</th>
                <th>Контакты</th>
                <th>Проблема</th>
                <th>Статус</th>
                <th>Мастер</th>
                <th>Назначить</th>
                <th>Отмена</th>
                <th>События</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($requests as $request)
                @php
                    $statusValue = $request->status instanceof \App\Enums\RequestStatus ? $request->status->value : $request->status;
                @endphp
                <tr>
                    <td data-label="ID">{{ $request->id }}</td>
                    <td data-label="Клиент">{{ $request->client_name }}</td>
                    <td data-label="Контакты">
                        {{ $request->phone }}
                        <div class="text-muted">{{ $request->address }}</div>
                    </td>
                    <td data-label="Проблема">{{ $request->problem_text }}</td>
                    <td data-label="Статус">
                        <span class="status-badge status-{{ $statusValue }}">{{ $statusValue }}</span>
                    </td>
                    <td data-label="Мастер">{{ $request->assignee?->name ?? '—' }}</td>
                    <td data-label="Назначить">
                        @if ($request->status === \App\Enums\RequestStatus::New)
                            <form method="POST" action="/dispatcher/requests/{{ $request->id }}/assign">
                                @csrf
                                @method('PATCH')
                                <div class="field">
                                    <select name="assigned_to" class="select {{ $errors->has('assigned_to') ? 'field-error' : '' }}" required>
                                        <option value="">-- мастер --</option>
                                        @foreach ($masters as $master)
                                            <option value="{{ $master->id }}">{{ $master->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('assigned_to')
                                        <div class="field-error-text">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Назначить</button>
                            </form>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td data-label="Отмена">
                        @if (!in_array($request->status, [\App\Enums\RequestStatus::Done, \App\Enums\RequestStatus::Canceled], true))
                            <form method="POST" action="/dispatcher/requests/{{ $request->id }}/cancel" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger">Отменить</button>
                            </form>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td data-label="События">
                        @if ($request->events->isNotEmpty())
                            <ul class="timeline">
                                @foreach ($request->events->take(5) as $event)
                                    <li class="timeline-item">
                                        <span class="timeline-dot"></span>
                                        <span>
                                            {{ $event->created_at?->format('d.m H:i') }} —
                                            {{ $actionLabels[$event->action] ?? $event->action }}
                                            @if ($event->actor)
                                                ({{ $event->actor->name }})
                                            @endif
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">Заявок нет.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
