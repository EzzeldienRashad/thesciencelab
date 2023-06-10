import './assets/main.css';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import "@/assets/styles.scss";
import * as bootstrap from "bootstrap";
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faLeftLong } from '@fortawesome/free-solid-svg-icons';

library.add(faLeftLong);
const app = createApp(App);
app.use(router);
app.component("font-awesome-icon", FontAwesomeIcon);
app.mount('#app');
