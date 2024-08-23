import { createApp } from 'vue';
import App from './App.vue';
import '@/assets/main.css';
import router from '@/router';
import "@/assets/styles.scss";
import * as bootstrap from "bootstrap";
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faLeftLong } from '@fortawesome/free-solid-svg-icons';
import { faRightLong } from '@fortawesome/free-solid-svg-icons';
import { faEye } from '@fortawesome/free-solid-svg-icons';
import { faEyeSlash } from '@fortawesome/free-solid-svg-icons';
import { faCaretDown } from '@fortawesome/free-solid-svg-icons';
import { faCircleCheck } from '@fortawesome/free-regular-svg-icons';
import { faCircleCheck as faCircleCheckSolid } from '@fortawesome/free-solid-svg-icons';
import { faCloudArrowUp } from '@fortawesome/free-solid-svg-icons';
import { faCheck } from '@fortawesome/free-solid-svg-icons';
import { faTrashCan } from '@fortawesome/free-solid-svg-icons';
import { faFileWord } from '@fortawesome/free-solid-svg-icons';
import { faDownload } from '@fortawesome/free-solid-svg-icons';

library.add(faLeftLong);
library.add(faRightLong);
library.add(faEye);
library.add(faEyeSlash);
library.add(faCaretDown);
library.add(faCircleCheck);
library.add(faCircleCheckSolid);
library.add(faCloudArrowUp);
library.add(faCheck);
library.add(faTrashCan);
library.add(faFileWord);
library.add(faDownload);
const app = createApp(App);
app.use(router);
app.component("font-awesome-icon", FontAwesomeIcon);
app.mount('#app');
