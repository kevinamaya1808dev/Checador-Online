document.addEventListener('DOMContentLoaded', () => {

    /*
    |--------------------------------------------------------------------------
    | Botones
    |--------------------------------------------------------------------------
    */

    const btnEntrada = document.getElementById('btnEntrada');
    const btnPausa = document.getElementById('btnPausa');
    const btnFinPausa = document.getElementById('btnReanudar');
    const btnSalida = document.getElementById('btnSalida');

    /*
    |--------------------------------------------------------------------------
    | Modal
    |--------------------------------------------------------------------------
    */

    const modal = document.getElementById('pauseModal');
    const btnCloseModal = document.getElementById('closePauseModal');
    const btnCancelPause = document.getElementById('cancelPause');
    const btnConfirmPause = document.getElementById('confirmPause');

    /*
    |--------------------------------------------------------------------------
    | Estado
    |--------------------------------------------------------------------------
    */

    const statusTitle = document.querySelector('[data-status-title]');
    const statusSubtitle = document.querySelector('[data-status-subtitle]');
    const statusDot = document.querySelector('[data-status-dot]');

    /*
    |--------------------------------------------------------------------------
    | Validación
    |--------------------------------------------------------------------------
    */

    if (!btnEntrada) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Estados
    |--------------------------------------------------------------------------
    */

    const STATES = {

        WAITING: 'waiting',

        WORKING: 'working',

        PAUSED: 'paused',

        FINISHED: 'finished'

    };

    let currentState = STATES.WAITING;

    /*
    |--------------------------------------------------------------------------
    | Mostrar/Ocultar Modal
    |--------------------------------------------------------------------------
    */

    function openModal() {

        modal.classList.remove('hidden');
        modal.classList.add('flex');

    }

    function closeModal() {

        modal.classList.add('hidden');
        modal.classList.remove('flex');

    }

    /*
    |--------------------------------------------------------------------------
    | Cambiar estado visual
    |--------------------------------------------------------------------------
    */

    function changeStatus(state) {

        if (!statusTitle) return;

        switch (state) {

            case STATES.WORKING:

                statusTitle.textContent = 'Trabajando';
                statusSubtitle.textContent = 'Tu jornada laboral está en curso.';

                statusDot.className =
                    'relative inline-flex h-4 w-4 rounded-full bg-green-400';

            break;

            case STATES.PAUSED:

                statusTitle.textContent = 'En pausa';
                statusSubtitle.textContent = 'Actualmente estás en una pausa.';

                statusDot.className =
                    'relative inline-flex h-4 w-4 rounded-full bg-yellow-400';

            break;

            case STATES.FINISHED:

                statusTitle.textContent = 'Turno finalizado';
                statusSubtitle.textContent = 'Has terminado tu jornada laboral.';

                statusDot.className =
                    'relative inline-flex h-4 w-4 rounded-full bg-red-400';

            break;

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Control botones
    |--------------------------------------------------------------------------
    */

    function updateButtons() {

        btnEntrada.disabled = true;
        btnPausa.disabled = true;
        btnFinPausa.disabled = true;
        btnSalida.disabled = true;

        switch (currentState) {

            case STATES.WAITING:

                btnEntrada.disabled = false;

            break;

            case STATES.WORKING:

                btnPausa.disabled = false;
                btnSalida.disabled = false;

            break;

            case STATES.PAUSED:

                btnFinPausa.disabled = false;

            break;

            case STATES.FINISHED:

            break;

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Entrada
    |--------------------------------------------------------------------------
    */

    btnEntrada.addEventListener('click', () => {

        currentState = STATES.WORKING;

        DashboardTimer.startWork();

        changeStatus(currentState);

        updateButtons();

    });

    /*
    |--------------------------------------------------------------------------
    | Pausa
    |--------------------------------------------------------------------------
    */

    btnPausa.addEventListener('click', () => {

        openModal();

    });

    /*
    |--------------------------------------------------------------------------
    | Confirmar pausa
    |--------------------------------------------------------------------------
    */

    btnConfirmPause.addEventListener('click', () => {

        currentState = STATES.PAUSED;

        DashboardTimer.startPause();

        changeStatus(currentState);

        updateButtons();

        closeModal();

    });

    /*
    |--------------------------------------------------------------------------
    | Fin pausa
    |--------------------------------------------------------------------------
    */

    btnFinPausa.addEventListener('click', () => {

        currentState = STATES.WORKING;

        DashboardTimer.finishPause();

        changeStatus(currentState);

        updateButtons();

    });

    /*
    |--------------------------------------------------------------------------
    | Salida
    |--------------------------------------------------------------------------
    */

    btnSalida.addEventListener('click', () => {

        currentState = STATES.FINISHED;

        DashboardTimer.stopWork();

        changeStatus(currentState);

        updateButtons();

    });

    /*
    |--------------------------------------------------------------------------
    | Cerrar modal
    |--------------------------------------------------------------------------
    */

    btnCloseModal.addEventListener('click', closeModal);

    btnCancelPause.addEventListener('click', closeModal);

    modal.addEventListener('click', (e) => {

        if (e.target === modal) {

            closeModal();

        }

    });

    /*
    |--------------------------------------------------------------------------
    | Inicializar
    |--------------------------------------------------------------------------
    */

    updateButtons();

});