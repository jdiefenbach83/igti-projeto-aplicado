import Vue from 'vue';
import Vuex from 'vuex';
import BrokerModule from './broker.module';
import BrokerageNoteModule from './brokerageNote.module';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    broker: BrokerModule,
    brokerageNote: BrokerageNoteModule,
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