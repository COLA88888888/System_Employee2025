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
import { usePermissionStore } from './stores/permission';
import { useStaff } from './Staff/AuthLogin';

const pinia = createPinia();
app.use(pinia);

// Restore Auth & Permissions State
const staffStore = useStaff();
const permissionStore = usePermissionStore();

const token = localStorage.getItem('web_token');
const userStr = localStorage.getItem('web_user');

// main.js (ສ່ວນ Restore State)
if (token && userStr) {
    try {
        const user = JSON.parse(userStr);
        staffStore.setToken(token);
        staffStore.setUser(user);

        // --- ຈຸດທີ່ປັບປຸງໃໝ່ ---
        // 1. ດຶງສິດຈາກ role_relation ກ່ອນ, ຖ້າບໍ່ມີໃຫ້ເບິ່ງທີ່ user.permissions
        let perms = [];
        if (user.role_relation && user.role_relation.permissions) {
            perms = user.role_relation.permissions;
        } else if (user.permissions) {
            perms = user.permissions;
        }

        // 2. ຖ້າ perms ເປັນ string (JSON string) ໃຫ້ parse ມັນອອກມາ
        if (typeof perms === 'string') {
            try {
                perms = JSON.parse(perms);
            } catch (e) {
                perms = [];
            }
        }

        // 3. ເຊັດຄ່າໃຫ້ Store
        if (Array.isArray(perms)) {
            permissionStore.setPermissions(perms);
        }

        // 4. ເຊັດ Role ID ແລະ Name
        const rName = user.role_relation?.name || user.role || 'employee';
        const rId = user.role_relation?.id || user.role_id || null;
        
        permissionStore.setRole(rName);
        permissionStore.setRoleId(rId);

        console.log("Restored Permissions:", perms); // ເບິ່ງໃນ Console ວ່າຂໍ້ມູນມາບໍ່
    } catch (e) {
        console.error('Error restoring state:', e);
    }
}

app.config.globalProperties.$can = (key) => usePermissionStore().has(key);
app.use(Router);
app.component('SidebarMenu', SidebarMenu);

// Add global properties for settings
app.config.globalProperties.$appSettings = appSettings;

app.mount('#app');