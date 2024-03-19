import './bootstrap';
import '../css/app.css';

import {createInertiaApp} from '@inertiajs/svelte';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => resolvePageComponent(`./Pages/${name}.svelte`, import.meta.glob('./Pages/**/*.svelte'), { eager: true }),
  setup({ el, App, props }) {
    new App({ target: el, props, hydrate: true })
  },
  progress: {
    color: '#4B5563',
  },
});
