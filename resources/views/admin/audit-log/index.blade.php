@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Logs de Auditoria</h2>
    <form method="GET" action="{{ route('admin.audit-log.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-2">
                <label for="action">Ação:</label>
                <select name="action" id="action" class="form-control">
                    <option value="">Todas</option>
                    <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Criado</option>
                    <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Atualizado</option>
                    <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Excluído</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="model">Modelo:</label>
                <input type="text" name="model" id="model" class="form-control" value="{{ request('model') }}">
            </div>
            <div class="col-md-3">
                <label for="user_id">Usuário:</label>
                <select name="user_id" id="user_id" class="form-control">
                    <option value="">Todos</option>
                    @foreach($users as $id => $name)
                        <option value="{{ $id }}" {{ request('user_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="start_date">Data inicial:</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-2">
                <label for="end_date">Data final:</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Data</th>
                <th>Ação</th>
                <th>Modelo</th>
                <th>ID</th>
                <th>Usuário</th>
                <th>IP</th>
                <th>Alterações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->model }}</td>
                    <td>{{ $log->model_id }}</td>
                    <td>{{ $log->user->name ?? '-' }}</td>
                    <td>{{ $log->ip_address }}</td>
                    <td>
                        <button class="btn btn-sm btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#log-{{ $log->id }}">Ver</button>
                        <div class="collapse" id="log-{{ $log->id }}">
                            @php
                                $changes = json_decode($log->changes, true);
                            @endphp
                            @if(is_array($changes))
                                <table class="table table-sm table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>Campo</th>
                                            <th>Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($changes as $campo => $valor)
                                            <tr>
                                                <td>{{ $campo }}</td>
                                                <td>{{ is_array($valor) ? json_encode($valor) : $valor }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <pre>{{ $log->changes }}</pre>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $logs->links() }}
</div>
@endsection
