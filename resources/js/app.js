import './bootstrap';

import { createApp } from 'vue';
import App from './App.vue';
import Router from './Router';
import SidebarMenu from './Components/SidebarMenu.vue';
import { createPinia } from 'pinia';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import Cleave from 'vue-cleave-component';
import Pagination from './Components/Pagination.vue';
import VueFlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import { Lao } from './locales/lao';
import _, { at } from 'lodash';
import { PerfectScrollbarPlugin } from 'vue3-perfect-scrollbar';
import 'vue3-perfect-scrollbar/style.css';
import { createI18n } from "vue-i18n";
import en from './locale/en.json';
import la from './locale/la.json';
import ch from './locale/ch.json';
import th from './locale/th.json';
import { formatDate } from './utils/formatDate'

// ກຳນົດພາສາ
const i18n = createI18n({
    locale: 'la', // ກຳນົດພາສາເປັນລາວ
    messages: {
        en: en,
        ch: ch,
        th: th,
        la: la,
    },
});

// Load saved settings from localStorage
const savedSettings = localStorage.getItem('app_settings');
let appSettings = {
    theme: 'light',
    language: 'la',
    autoSave: true
};

if (savedSettings) {
    try {
        appSettings = JSON.parse(savedSettings);
    } catch (e) {
        console.error('Failed to parse saved settings', e);
    }
}

// Apply theme
if (appSettings.theme === 'dark') {
    document.body.classList.add('dark-theme');
}

// Apply language
i18n.global.locale.value = appSettings.language;

const app = createApp(App);
app.config.globalProperties.$formatDate = formatDate;
app.component('Pagination',Pagination);
app.use(i18n);
app.use(Cleave);
app.use(VueSweetalert2);
app.use(PerfectScrollbarPlugin);
// Register flatpickr as a component (vue-flatpickr-component exports a component)
app.component('FlatPickr', VueFlatPickr);

// ກຳນົດພາສາລາວເປັນຄ່າເລີ່ມຕົ້ນສໍາລັບ Flatpickr
import flatpickr from 'flatpickr';
flatpickr.localize(Lao);
const pinia = createPinia();
app.use(pinia);
app.use(Router);
app.component('SidebarMenu', SidebarMenu);

// Add global properties for settings
app.config.globalProperties.$appSettings = appSettings;

app.mount('#app');