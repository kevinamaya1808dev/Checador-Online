@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-white mb-4">Panel de Administración</h2>
    
    <div class="card bg-dark border-secondary shadow">
        <div class="card-header bg-dark border-bottom border-secondary text-white">
            Gestión de Usuarios
        </div>
        <div class="card-body p-0">
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr class="text-secondary">
                        <th>Nombre</th><th>Email</th><th>Rol</th><th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <form action="{{ route('users.toggle', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $user->role == 'admin' ? 'btn-primary' : 'btn-outline-secondary' }}">
                                    {{ ucfirst($user->role) }}
                                </button>
                            </form>
                        </td>
                        <td class="text-end">
                            <form action="{{ route('users.delete', $user->id) }}" method="POST" onsubmit="return confirm('¿Seguro?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Borrar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection