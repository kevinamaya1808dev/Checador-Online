document.addEventListener('DOMContentLoaded', () => {


    /*
    |--------------------------------------------------------------------------
    | Elementos del reloj
    |--------------------------------------------------------------------------
    */

    const hourHand = document.getElementById('hour-hand');
    const minuteHand = document.getElementById('minute-hand');
    const secondHand = document.getElementById('second-hand');

    const digitalClock = document.getElementById('digital-clock');
    const currentDate = document.getElementById('current-date');


    /*
    |--------------------------------------------------------------------------
    | Validación
    |--------------------------------------------------------------------------
    */

    if (
        !hourHand ||
        !minuteHand ||
        !secondHand ||
        !digitalClock ||
        !currentDate
    ) {
        return;
    }



    /*
    |--------------------------------------------------------------------------
    | Formato de fecha español
    |--------------------------------------------------------------------------
    */

    const days = [
        'Domingo',
        'Lunes',
        'Martes',
        'Miércoles',
        'Jueves',
        'Viernes',
        'Sábado'
    ];


    const months = [
        'Enero',
        'Febrero',
        'Marzo',
        'Abril',
        'Mayo',
        'Junio',
        'Julio',
        'Agosto',
        'Septiembre',
        'Octubre',
        'Noviembre',
        'Diciembre'
    ];



    /*
    |--------------------------------------------------------------------------
    | Actualización del reloj
    |--------------------------------------------------------------------------
    */

    function updateClock(){

        const now = new Date();


        /*
        |--------------------------------------------------------------------------
        | Tiempo
        |--------------------------------------------------------------------------
        */

        const seconds = now.getSeconds();

        const minutes = now.getMinutes();

        const hours = now.getHours();



        /*
        |--------------------------------------------------------------------------
        | Grados de rotación
        |--------------------------------------------------------------------------
        */

        const secondDegrees = seconds * 6;


        const minuteDegrees =
            (minutes * 6) +
            (seconds * 0.1);



        const hourDegrees =
            (hours % 12) * 30 +
            (minutes * 0.5);



        /*
        |--------------------------------------------------------------------------
        | Aplicar movimiento
        |--------------------------------------------------------------------------
        */

        secondHand.style.transform =
            `translateX(-50%) rotate(${secondDegrees}deg)`;


        minuteHand.style.transform =
            `translateX(-50%) rotate(${minuteDegrees}deg)`;


        hourHand.style.transform =
            `translateX(-50%) rotate(${hourDegrees}deg)`;



        /*
        |--------------------------------------------------------------------------
        | Hora digital
        |--------------------------------------------------------------------------
        */

        const formattedHours =
            String(hours).padStart(2,'0');


        const formattedMinutes =
            String(minutes).padStart(2,'0');


        const formattedSeconds =
            String(seconds).padStart(2,'0');



        digitalClock.textContent =
            `${formattedHours}:${formattedMinutes}:${formattedSeconds}`;



        /*
        |--------------------------------------------------------------------------
        | Fecha
        |--------------------------------------------------------------------------
        */

        currentDate.textContent =
            `${days[now.getDay()]}, 
            ${now.getDate()} de 
            ${months[now.getMonth()]} de 
            ${now.getFullYear()}`;

    }



    /*
    |--------------------------------------------------------------------------
    | Animación suave
    |--------------------------------------------------------------------------
    */

    secondHand.style.transition =
        'transform 0.2s cubic-bezier(.4,2.3,.3,1)';


    minuteHand.style.transition =
        'transform 0.3s ease';


    hourHand.style.transition =
        'transform 0.3s ease';



    /*
    |--------------------------------------------------------------------------
    | Inicio
    |--------------------------------------------------------------------------
    */

    updateClock();


    setInterval(
        updateClock,
        1000
    );


});