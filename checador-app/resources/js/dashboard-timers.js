document.addEventListener('DOMContentLoaded', () => {

    /*
    |--------------------------------------------------------------------------
    | Elementos
    |--------------------------------------------------------------------------
    */

    const workTimer = document.getElementById('workTimer');
    const pauseTimer = document.getElementById('pauseTimer');

    if (!workTimer || !pauseTimer) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Variables
    |--------------------------------------------------------------------------
    */

    let workSeconds = 0;
    let pauseSeconds = 0;

    let working = false;
    let paused = false;

    /*
    |--------------------------------------------------------------------------
    | Formato HH:MM:SS
    |--------------------------------------------------------------------------
    */

    function formatTime(seconds) {

        const hrs = Math.floor(seconds / 3600);
        const min = Math.floor((seconds % 3600) / 60);
        const sec = seconds % 60;

        return [
            String(hrs).padStart(2, '0'),
            String(min).padStart(2, '0'),
            String(sec).padStart(2, '0')
        ].join(':');
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar pantalla
    |--------------------------------------------------------------------------
    */

    function render() {

        workTimer.textContent = formatTime(workSeconds);
        pauseTimer.textContent = formatTime(pauseSeconds);

    }

    /*
    |--------------------------------------------------------------------------
    | Intervalo principal
    |--------------------------------------------------------------------------
    */

    setInterval(() => {

        if (working) {
            workSeconds++;
        }

        if (paused) {
            pauseSeconds++;
        }

        render();

    }, 1000);

    /*
    |--------------------------------------------------------------------------
    | API pública
    |--------------------------------------------------------------------------
    */

    window.DashboardTimer = {

        startWork() {

            working = true;
            paused = false;

        },

        stopWork() {

            working = false;

        },

        startPause() {

            paused = true;
            working = false;

        },

        finishPause() {

            paused = false;
            working = true;

        },

        reset() {

            working = false;
            paused = false;

            workSeconds = 0;
            pauseSeconds = 0;

            render();

        },

        setWork(seconds) {

            workSeconds = seconds;
            render();

        },

        setPause(seconds) {

            pauseSeconds = seconds;
            render();

        }

    };

    render();

});