import './bootstrap';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;
import { initTheme, toggleTheme } from './theme-switcher';

window.toggleTheme = toggleTheme; // Lo exponemos al objeto window para poder usarlo en los botones
initTheme(); // Inicializa al cargar