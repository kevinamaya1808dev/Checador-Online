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