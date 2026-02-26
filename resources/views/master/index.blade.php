@extends('layouts.app')

@section('title', 'Панель мастера')

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
    <h1>Панель мастера</h1>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Клиент</th>
                <th>Контакты</th>
                <th>Проблема</th>
                <th>Статус</th>
                <th>Действия</th>
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
                    <td data-label="Действия">
                        @if ($request->status === \App\Enums\RequestStatus::Assigned)
                            <form method="POST" action="/master/requests/{{ $request->id }}/take" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-primary">Take</button>
                            </form>
                        @elseif ($request->status === \App\Enums\RequestStatus::InProgress)
                            <form method="POST" action="/master/requests/{{ $request->id }}/done" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-primary">Done</button>
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
                    <td colspan="7">Назначенных заявок нет.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
