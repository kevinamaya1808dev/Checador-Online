<?php
// app/Support/EstadoTurnoPresenter.php
namespace App\Support;

class EstadoTurnoPresenter
{
    public string $estado;
    public bool $gating;
    public array $info;
    public array $banner;
    public bool $puedeEntrada;
    public bool $puedePausar;
    public bool $puedeReanudar;
    public bool $puedeSalir;

    private const INFO = [
        'inactivo'   => ['label' => 'Esperando turno', 'desc' => 'Aún no has registrado tu entrada.', 'texto' => 'text-slate-600 dark:text-slate-300', 'icon' => 'bi-hourglass-split'],
        'trabajando' => ['label' => 'Trabajando', 'desc' => 'Tu turno está activo.', 'texto' => 'text-green-700 dark:text-green-400', 'icon' => 'bi-briefcase-fill'],
        'pausado'    => ['label' => 'En pausa', 'desc' => 'Descanso en curso.', 'texto' => 'text-amber-700 dark:text-amber-400', 'icon' => 'bi-cup-hot'],
        'terminado'  => ['label' => 'Turno finalizado', 'desc' => 'Registro guardado correctamente.', 'texto' => 'text-red-700 dark:text-red-400', 'icon' => 'bi-flag-fill'],
    ];

    private const BANNER = [
        'inactivo'   => ['wash' => 'from-slate-500/10', 'border' => 'border-slate-500/20', 'iconBg' => 'bg-gradient-to-br from-slate-500 to-slate-700', 'iconSh' => 'shadow-[0_8px_20px_-4px_rgba(100,116,139,0.5)]', 'dot' => 'bg-slate-400', 'pulse' => false],
        'trabajando' => ['wash' => 'from-green-500/10', 'border' => 'border-green-500/25', 'iconBg' => 'bg-gradient-to-br from-green-500 to-green-700', 'iconSh' => 'shadow-[0_8px_20px_-4px_rgba(34,197,94,0.55)]', 'dot' => 'bg-green-400', 'pulse' => true],
        'pausado'    => ['wash' => 'from-amber-500/10', 'border' => 'border-amber-500/25', 'iconBg' => 'bg-gradient-to-br from-amber-500 to-amber-700', 'iconSh' => 'shadow-[0_8px_20px_-4px_rgba(217,119,6,0.55)]', 'dot' => 'bg-amber-400', 'pulse' => true],
        'terminado'  => ['wash' => 'from-red-500/10', 'border' => 'border-red-500/25', 'iconBg' => 'bg-gradient-to-br from-red-500 to-red-700', 'iconSh' => 'shadow-[0_8px_20px_-4px_rgba(220,38,38,0.5)]', 'dot' => 'bg-red-400', 'pulse' => false],
    ];

    public function __construct(?string $estadoRaw)
    {
        $this->gating = ! is_null($estadoRaw);
        $this->estado = $estadoRaw ?? 'inactivo';

        $this->info = self::INFO[$this->estado] ?? [
            'label' => ucfirst($this->estado), 'desc' => '', 'texto' => 'text-slate-600 dark:text-slate-300', 'icon' => 'bi-question-circle',
        ];

        $this->banner = self::BANNER[$this->estado] ?? [
            'wash' => 'from-slate-500/10', 'border' => 'border-slate-500/20',
            'iconBg' => 'bg-gradient-to-br from-slate-500 to-slate-700',
            'iconSh' => 'shadow-[0_8px_20px_-4px_rgba(100,116,139,0.5)]',
            'dot' => 'bg-slate-400', 'pulse' => false,
        ];

        $this->puedeEntrada  = ! $this->gating || $this->estado === 'inactivo';
        $this->puedePausar   = ! $this->gating || $this->estado === 'trabajando';
        $this->puedeReanudar = ! $this->gating || $this->estado === 'pausado';
        $this->puedeSalir    = ! $this->gating || $this->estado === 'trabajando';
    }

    public function destacar(string $accion): bool
    {
        $mapa = [
            'entrada'  => $this->puedeEntrada,
            'pausar'   => $this->puedePausar,
            'reanudar' => $this->puedeReanudar,
            'salir'    => $this->puedeSalir,
        ];

        return $this->gating && ($mapa[$accion] ?? false);
    }
}