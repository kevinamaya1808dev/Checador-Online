@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
        <h2 class="text-white fw-bold mb-0">
            <i class="bi bi-people-fill text-info me-2"></i>Administración de Usuarios
        </h2>
        @include('admin.modals.registrar-becario')
    </div>

    <div class="card bg-dark border-secondary shadow-lg rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
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
                                    <div class="rounded-circle bg-secondary bg-opacity-25 border border-secondary d-flex align-items-center justify-content-center text-info fw-bold user-avatar"
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
                                <div class="d-flex justify-content-end gap-2 flex-nowrap">
                                    <button type="button"
                                            class="btn btn-sm rounded-circle p-0 d-inline-flex align-items-center justify-content-center action-btn action-btn-edit"
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
                                                class="btn btn-sm rounded-circle p-0 d-inline-flex align-items-center justify-content-center action-btn action-btn-delete"
                                                style="width:34px;height:34px;"
                                                title="Eliminar">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.modals.editar-usuario')

<style>
    .action-btn {
        width: 36px !important;
        height: 36px !important;
        border: none !important;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        color: #fff !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
    }

    .action-btn-edit {
        background: linear-gradient(135deg, #22d3ee, #0891b2) !important;
        box-shadow: 0 2px 8px rgba(8, 145, 178, 0.45);
    }

    .action-btn-edit:hover,
    .action-btn-edit:focus {
        color: #fff !important;
        transform: translateY(-3px) scale(1.06);
        box-shadow: 0 6px 16px rgba(8, 145, 178, 0.6);
        filter: brightness(1.1);
    }

    .action-btn-delete {
        background: linear-gradient(135deg, #f87171, #dc2626) !important;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.45);
    }

    .action-btn-delete:hover,
    .action-btn-delete:focus {
        color: #fff !important;
        transform: translateY(-3px) scale(1.06);
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.6);
        filter: brightness(1.1);
    }

    .action-btn:active {
        transform: translateY(0) scale(0.96);
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        h2.fw-bold {
            font-size: 1.25rem;
        }

        .table-dark td,
        .table-dark th {
            font-size: 0.8rem;
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }

        .user-avatar {
            width: 28px !important;
            height: 28px !important;
            font-size: 0.75rem !important;
        }

        .action-btn {
            width: 28px !important;
            height: 28px !important;
            font-size: 0.75rem;
        }

        .badge.rounded-pill {
            font-size: 0.7rem;
            padding: 0.35rem 0.6rem !important;
        }
    }
</style>

@endsection