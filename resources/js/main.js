import { createApp } from 'vue';
import AdminRouter from './router/admin';
import AdminLayout from './layouts/AdminLayout.vue';

const app = createApp({});

app.component('AdminLayout', AdminLayout);

app.use(AdminRouter);

app.mount('#admin-app');
