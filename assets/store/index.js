import Vue from 'vue';
import Vuex from 'vuex';
import BrokerModule from './broker.module';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    broker: BrokerModule
  },
  state: {

  },
  mutations: {

  },
  getters: {

  },
  actions: {

  }
});