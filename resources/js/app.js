import './bootstrap';
import Alpine from 'alpinejs';
import './dashboard-clock';
import './dashboard-timers';
import './dashboard';
import { initTheme, toggleTheme } from './theme-switcher';

window.toggleTheme = toggleTheme;
initTheme();
window.Alpine = Alpine;

Alpine.start();

import { initAuroraParticles } from './aurora-particles';

// ...donde ya tienes tu código de inicialización, junto a Alpine.start() por ejemplo:
initAuroraParticles();