@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white fw-bold mb-0">
            <i class="bi bi-people-fill text-info me-2"></i>Administración de Usuarios
        </h2>
    </div>

    <div class="card bg-dark border-secondary shadow-lg rounded-4">
        <div class="card-body p-0">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead>
                    <tr class="text-secondary" style="background-color: rgba(255,255,255,0.03);">
                        <th class="ps-4 py-3 fw-semibold text-uppercase small">Nombre</th>
                        <th class="py-3 fw-semibold text-uppercase small">Email</th>
                        <th class="py-3 fw-semibold text-uppercase small">Rol</th>
                        <th class="text-end pe-4 py-3 fw-semibold text-uppercase small">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $user)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-secondary bg-opacity-25 border border-secondary d-flex align-items-center justify-content-center text-info fw-bold"
                                     style="width:36px;height:36px;font-size:0.9rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="text-white">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="py-3">
                            <span class="text-info">{{ $user->email }}</span>
                        </td>
                        <td class="py-3">
                            <span class="badge rounded-pill px-3 py-2
                                @if(strtolower($user->role) === 'admin') text-bg-primary
                                @else text-bg-secondary
                                @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="text-end pe-4 py-3">
                            <button type="button"
                                    class="btn btn-sm btn-outline-info rounded-circle p-0 d-inline-flex align-items-center justify-content-center me-2"
                                    style="width:34px;height:34px;"
                                    title="Editar"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarUsuario"
                                    onclick="prepararEdicion('{{ $user->id }}', '{{ $user->name }}', '{{ $user->role }}')">
                                <i class="bi bi-pencil-fill"></i>
                            </button>

                            @if($user->id != 1)
                            <form action="{{ route('users.delete', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger rounded-circle p-0 d-inline-flex align-items-center justify-content-center"
                                        style="width:34px;height:34px;"
                                        title="Eliminar">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
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