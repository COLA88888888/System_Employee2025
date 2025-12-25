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
        redirect: '/dashboard',
        meta: {
            middleware: [AuthMiddleware]
        }
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            middleware: [AuthMiddleware]
        }
    },
    {
        path: '/attendance',
        name: 'attendance',
        component: Attendance,
        meta: {
            middleware: [AuthMiddleware]
        }
    },
    {
        path: '/payroll',
        name: 'payroll',
        component: Payroll,
        meta: {
            middleware: [AuthMiddleware]
        }
    },
    {
        path: '/employee',
        name: 'employee',
        component: Employee,
        meta: {
            middleware: [AuthMiddleware]
        }
    },
    {
        path: '/position',
        name: 'position',
        component: Position,
        meta: {
            middleware: [AuthMiddleware]
        }
    },
    {
        path: '/leave',
        name: 'leave',
        component: Leave,
        meta: {
            middleware: [AuthMiddleware]
        }
    },
    {
        path: '/break_type',
        name: 'break_type',
        component: BreakType,
        meta: {
            middleware: [AuthMiddleware]
        }
    },
    {
        path: '/department',
        name: 'department',
        component: Department,
        meta: {
            middleware: [AuthMiddleware]
        }
    },
    {
        path: '/report',
        name: 'report',
        component: Report,
        meta: {
            middleware: [AuthMiddleware]
        }
    },
    {
        path: '/user',
        name: 'user',
        component: User,
        meta: {
            middleware: [AuthMiddleware]
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

    if (to.meta.middleware) {
        to.meta.middleware.forEach(middleware => {
            middleware(to, from, next);
        });
    }

    else {
        if (to.path === '/login' || to.path === '/') {
            if (token) {
                next({
                    path: '/dashboard',
                    replace: true,
                });
            }
            else {
                next();
            }
        }
        else {
            next();
        }
    }
})

export default router;