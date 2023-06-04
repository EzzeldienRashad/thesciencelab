import {createApp} from "vue";
import App from "./App.vue";
import "@/assets/main.css";
import router from "@/router/index.js";
import {library} from '@fortawesome/fontawesome-svg-core';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {faRightLong} from "@fortawesome/free-solid-svg-icons";

library.add(faRightLong);

const app = createApp(App);
app.use(router);
app.component("font-awesome-icon", FontAwesomeIcon);
app.mount("#app");