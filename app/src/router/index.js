import {createRouter, createWebHistory} from 'vue-router';
import HomeView from '../views/HomeView.vue';
import AuthService from "@/modules/auth/services/authService.js";

const routes = [
    {
        path: '/',
        redirect: '/en'
    },
    {
        path: '/:lang',
        children: [
            {
                path: '',
                name: 'home',
                component: HomeView
            },
            {
                path: 'register',
                name: 'register',
                component: () => import('../views/auth/RegisterView.vue')
            },
            {
                path: 'login',
                name: 'login',
                component: () => import('../views/auth/LoginView.vue')
            },
            {
                path: 'budget/:month/:year',
                name: 'dashboard',
                component: () => import('../views/FrontDashboardView.vue'),
                beforeEnter: validateMonthAndYear
            },
            {
                path: 'budget/:month/:year/create',
                name: 'add-transaction',
                component: () => import('../views/ManageTransactionView.vue'),
                beforeEnter: validateMonthAndYear
            },
            {
                path: 'budget/:month/:year/edit/:id',
                name: 'edit-transaction',
                component: () => import('../views/ManageTransactionView.vue'),
                beforeEnter: validateMonthAndYear
            },
            {
                path: 'admin',
                name: 'admin',
                component: () => import('../views/AdminDashboardView.vue'),
                beforeEnter: validateUser
            },
            {
                path: 'admin/categories/create',
                name: 'create-category',
                component: () => import('../views/ManageCategoryView.vue'),
                beforeEnter: validateUser
            },
            {
                path: 'admin/categories/edit/:id',
                name: 'edit-category',
                component: () => import('../views/ManageCategoryView.vue'),
                beforeEnter: validateUser
            },
        ]
    },
    {
        path: '/:catchAll(.*)*',
        name: 'page-not-found',
        component: () => import('../views/PageNotFoundView.vue')
    }
];


const router = createRouter(
    {
        history: createWebHistory(import.meta.env.BASE_URL),
        routes: routes
    }
);

function validateMonthAndYear(to, from, next) {
    const month = parseInt(to.params.month);
    const year = parseInt(to.params.year);

    if (isNaN(month) || month < 1 || month > 12 || isNaN(year) || year < 2000 || year > 2100) {
        return next({name: 'page-not-found'});
    } else {
        next();
    }
}

function validateLanguage(to, from, next) {
    if (to.name === 'page-not-found') return next();

    const validLanguages = ['en', 'nl'];
    const lang = to.params.lang;

    if (!validLanguages.includes(lang)) {
        next({name: 'page-not-found'});
    } else {
        next();
    }
}


async function validateUser(to, from, next) {
    const authService = new AuthService();
    try {
        await authService.getUserProfile('en');
        if (authService.userIsAdmin()) {
            next();
        } else {
            next({name: 'page-not-found'});
        }
    }catch (e) {
        next({name: 'page-not-found'});
    }

}


router.beforeEach(validateLanguage);


export default router;
