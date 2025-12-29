import { createRouter, createWebHistory } from "vue-router";

import Login from '../Pages/Login.vue';
import Register from '../Pages/Register.vue';
import Dashboard from '../Pages/Dashboard.vue';
import Attendance from "../Pages/Attendance.vue";
import Payroll from "../Pages/Payroll.vue";
import Employee from "../Pages/Employee.vue";
import Position from "../Pages/Position.vue";
import N404 from "../Pages/N404.vue";
import Leave from '../Pages/Leave.vue';
import BreakType from '../Pages/BreakType.vue';
import Department from "../Pages/Department.vue";
import Report from "../Pages/Report.vue";
import User from "../Pages/User.vue";
import Roles from "../Pages/Roles.vue";
import Notifications from "../Pages/Notifications.vue";
import { useStaff } from '../Staff/AuthLogin';

import { usePermissionStore } from '../stores/permission';

//  ສ້າງ middleware ສຳລັບການເຂົ້າໄປໃນ router ສໍາລັບ Vue.js

const AuthMiddleware = (to, from, next) => {
    // ດຶງ token ຈາກ localStorage    => ເປັນຖານຂໍ້ມູນຂະໜາດນ້ອຍທີ່ຢູ່ໃນ browser
    const user = localStorage.getItem('web_user');
    const token = localStorage.getItem('web_token');
    const authStaff = useStaff();
    const permissionStore = usePermissionStore();

    // ຖ້າບໍ່ມີ token ຫຼື user ກໍ່ເປັນບໍ່ອະນຸຍາດ

    if (token) {
        let userData = user;
        try {
            userData = JSON.parse(user);
        } catch (e) {
            console.error("Failed to parse user data", e);
        }

        authStaff.setToken(token);
        authStaff.setUser(userData);
        
        if (userData && userData.permissions) {
            permissionStore.setPermissions(userData.permissions);
        }
        next();
    }

    else {
        // ໃຫ້ User ແລ່ນໄປໜ້າ login ອໍໂຕ້
        next({
            path: '/login',
            replace: true,
        })
    }
}

const routes = [
    {
        path: '/',
        name: 'home',
        meta: {
            middleware: [AuthMiddleware]
        }
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            middleware: [AuthMiddleware],
            permission: 'dashboard.view'
        }
    },
    {
        path: '/attendance',
        name: 'attendance',
        component: Attendance,
        meta: {
            middleware: [AuthMiddleware],
            permission: 'attendance.view'
        }
    },
    {
        path: '/payroll',
        name: 'payroll',
        component: Payroll,
        meta: {
            middleware: [AuthMiddleware],
            permission: 'payroll.view'
        }
    },
    {
        path: '/employee',
        name: 'employee',
        component: Employee,
        meta: {
            middleware: [AuthMiddleware],
            permission: 'employee.view'
        }
    },
    {
        path: '/position',
        name: 'position',
        component: Position,
        meta: {
            middleware: [AuthMiddleware],
            permission: 'settings.edit'
        }
    },
    {
        path: '/leave',
        name: 'leave',
        component: Leave,
        meta: {
            middleware: [AuthMiddleware],
            permission: 'leave.view'
        }
    },
    {
        path: '/break_type',
        name: 'break_type',
        component: BreakType,
        meta: {
            middleware: [AuthMiddleware],
            permission: 'settings.edit'
        }
    },
    {
        path: '/department',
        name: 'department',
        component: Department,
        meta: {
            middleware: [AuthMiddleware],
            permission: 'settings.edit'
        }
    },
    {
        path: '/report',
        name: 'report',
        component: Report,
        meta: {
            middleware: [AuthMiddleware],
            permission: ['report.history', 'report.employee', 'report.attendance', 'report.leave', 'report.payroll']
        }
    },
    {
        path: '/user',
        name: 'user',
        component: User,
        meta: {
            middleware: [AuthMiddleware],
            permission: 'user.view'
        }
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/register',
        name: 'register',
        component: Register
    },
    {
        path: '/roles',
        name: 'roles',
        component: Roles,
        meta: {
            middleware: [AuthMiddleware],
            permission: 'role.manage'
        }
    },
    {
        path: '/notifications',
        name: 'notifications',
        component: Notifications,
        meta: {
            middleware: [AuthMiddleware]
        }
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'N404',
        component: N404
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
    scrollBehavior() {
        return { top: 0 }
    }
});

//  ການເພີ່ມ middleware ສໍາລັບ router
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('web_token');
    const permissionStore = usePermissionStore();

    // 1. Handle Authentication Requirements
    if (to.meta.middleware && to.meta.middleware.includes(AuthMiddleware)) {
        if (!token) {
            return next({ path: '/login', replace: true });
        }
    }

    // 2. Dynamic Landing Page (Redirect from '/' or '/login' for logged-in users)
    if (to.path === '/' || (to.path === '/login' && token)) {
        if (token) {
            const allowedRoutes = [
                { path: '/dashboard', perm: 'dashboard.view' },
                { path: '/employee', perm: 'employee.view' },
                { path: '/attendance', perm: 'attendance.view' },
                { path: '/payroll', perm: 'payroll.view' },
                { path: '/leave', perm: 'leave.view' }
            ];

            const firstAllowed = allowedRoutes.find(r => permissionStore.has(r.perm));
            return next({ path: firstAllowed ? firstAllowed.path : '/dashboard', replace: true });
        }
        if (to.path === '/') {
            return next({ path: '/login', replace: true });
        }
    }

    // 3. Specific Route Permission Protection
    if (to.meta.permission) {
        const requiredPerms = Array.isArray(to.meta.permission) ? to.meta.permission : [to.meta.permission];
        const hasAccess = requiredPerms.some(p => permissionStore.has(p));
        
        if (!hasAccess) {
            console.warn(`Access denied to ${to.path}. Missing:`, to.meta.permission);
            return next({ name: 'N404' }); 
        }
    }

    // 4. Default Allow
    next();
});

export default router;