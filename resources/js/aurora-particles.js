export function initAuroraParticles() {
    const canvas = document.getElementById('aurora-stars');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    let particles = [];
    let width, height;

    function isDark() {
        return document.documentElement.classList.contains('dark');
    }

    function resize() {
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
    }

    function createParticles() {
        const count = Math.floor((width * height) / 12000);
        particles = Array.from({ length: count }, () => ({
            x: Math.random() * width,
            y: Math.random() * height,
            radius: Math.random() * 1.3 + 0.3,
            baseAlpha: Math.random() * 0.5 + 0.15,
            phase: Math.random() * Math.PI * 2,
            speed: Math.random() * 0.015 + 0.005,
            hue: [270, 190, 150][Math.floor(Math.random() * 3)], // morado, cian, verde
        }));
    }

    function draw(time) {
        ctx.clearRect(0, 0, width, height);
        const dark = isDark();

        for (const p of particles) {
            const twinkle = Math.sin(time * p.speed + p.phase) * 0.5 + 0.5;
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);

            ctx.fillStyle = dark
                ? `rgba(220, 235, 255, ${p.baseAlpha * twinkle})`
                : `hsla(${p.hue}, 70%, 55%, ${p.baseAlpha * twinkle * 0.5})`;

            ctx.fill();
        }
        requestAnimationFrame(draw);
    }

    resize();
    createParticles();
    requestAnimationFrame(draw);

    window.addEventListener('resize', () => {
        resize();
        createParticles();
    });
}