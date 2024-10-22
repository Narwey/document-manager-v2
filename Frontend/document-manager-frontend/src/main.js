import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia';
import App from './App.vue'
import routes from "./router/routes"
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import './index.css'

const pinia = createPinia();
createApp(App).use(VueSweetalert2).use(pinia).use(routes).mount('#app');
