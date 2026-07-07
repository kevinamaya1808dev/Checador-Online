import './bootstrap';

import './dashboard-clock';
import './dashboard-timers';
import './dashboard';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;
import { initTheme, toggleTheme } from './theme-switcher';

window.toggleTheme = toggleTheme; // Lo exponemos al objeto window para poder usarlo en los botones
initTheme(); // Inicializa al cargar


Alpine.start();