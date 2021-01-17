import Vue from 'vue';
import App from './App';
import store from './store'
import router from './router';
import vuetify from './plugins/vuetify';

Vue.use(vuetify);

new Vue({
  components: { App },
  template: '<App/>',
  store,
  router,
  vuetify
}).$mount("#app");

console.log('Initializing vuex store...');
console.log(store.state.teste);
console.log('Ready!');