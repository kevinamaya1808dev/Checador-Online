<style>

    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(14px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .entrada {
        opacity: 0;
        animation-name: fadeInUp;
        animation-duration: .6s;
        animation-timing-function: cubic-bezier(.16,.84,.44,1);
        animation-fill-mode: forwards;
    }

    @keyframes anilloPulso {
        0% { box-shadow: 0 0 0 0 rgba(255,255,255,.18); }
        70% { box-shadow: 0 0 0 10px rgba(255,255,255,0); }
        100% { box-shadow: 0 0 0 0 rgba(255,255,255,0); }
    }

    .anillo-vivo {
        animation: anilloPulso 2.2s ease-out infinite;
    }

</style>