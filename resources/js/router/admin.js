import { createRouter, createWebHistory } from 'vue-router';

import AdminLogin from '../pages/admin/AdminLogin.vue';
import AdminDashboard from '../pages/admin/AdminDashboard.vue';
import AdminUsersIndex from '../pages/admin/AdminUsersIndex.vue';
import AdminUsersEdit from '../pages/admin/AdminUsersEdit.vue';
import AdminOrdersIndex from '../pages/admin/AdminOrdersIndex.vue';
import AdminOrdersShow from '../pages/admin/AdminOrdersShow.vue';
import AdminPrescriptionsIndex from '../pages/admin/AdminPrescriptionsIndex.vue';
import AdminPrescriptionsShow from '../pages/admin/AdminPrescriptionsShow.vue';
import AdminBlogs from '../pages/admin/AdminBlogs.vue';
import AdminSupport from '../pages/admin/AdminSupport.vue';
import AdminProductsIndex from '../pages/admin/Products/Index.vue';

const routes = [
  {
    path: '/admin/login',
    name: 'admin.login',
    component: AdminLogin,
    meta: { guest: true }
  },
  {
    path: '/admin/dashboard',
    name: 'admin.dashboard',
    component: AdminDashboard,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/users',
    name: 'admin.users',
    component: AdminUsersIndex,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/users/:id/edit',
    name: 'admin.users.edit',
    component: AdminUsersEdit,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/orders',
    name: 'admin.orders',
    component: AdminOrdersIndex,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/orders/:id',
    name: 'admin.orders.show',
    component: AdminOrdersShow,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/products',
    name: 'admin.products',
    component: AdminProductsIndex,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/prescriptions',
    name: 'admin.prescriptions',
    component: AdminPrescriptionsIndex,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/prescriptions/:id',
    name: 'admin.prescriptions.show',
    component: AdminPrescriptionsShow,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/blogs',
    name: 'admin.blogs',
    component: AdminBlogs,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/support',
    name: 'admin.support',
    component: AdminSupport,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/:catchAll(.*)',
    redirect: '/admin/dashboard'
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const isLoggedIn = !!localStorage.getItem('admin_logged_in');

  if (to.meta.requiresAuth && !isLoggedIn) {
    next({ path: '/admin/login' });
  } else if (to.meta.guest && isLoggedIn) {
    next({ path: '/admin/dashboard' });
  } else {
    next();
  }
});

export default router;
