import Vue from 'vue';
import Vuex from 'vuex';
import AssetModule from './asset.module';
import BrokerModule from './broker.module';
import BrokerageNoteModule from './brokerageNote.module';
import CompanyModule from './company.module';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    asset: AssetModule,
    broker: BrokerModule,
    brokerageNote: BrokerageNoteModule,
    company: CompanyModule,
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