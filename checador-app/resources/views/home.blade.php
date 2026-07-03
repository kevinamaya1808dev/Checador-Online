@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="text-white fw-bold mb-4">Administración de Usuarios</h2>
    
    <div class="card bg-dark border-secondary shadow">
        <div class="card-body p-0">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead>
                    <tr class="text-secondary">
                        <th class="ps-4">Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $user)
                    <tr>
                        <td class="ps-4">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-info me-2" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEditarUsuario"
                                    onclick="prepararEdicion('{{ $user->id }}', '{{ $user->name }}', '{{ $user->role }}')">
                                Editar
                            </button>
                            
                            @if($user->id != 1)
                            <form action="{{ route('users.delete', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('admin.modals.editar-usuario')

@endsection