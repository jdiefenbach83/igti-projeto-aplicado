import Vue from "vue";
import App from "./App";
import router from "./router";
import vuetify from './plugins/vuetify';

Vue.use(vuetify);

new Vue({
  components: { App },
  template: "<App/>",
  router,
  vuetify
}).$mount("#app");