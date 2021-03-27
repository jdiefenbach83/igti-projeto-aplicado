import Vue from 'vue';
import Vuex from 'vuex';
import AssetModule from './asset.module';
import BrokerModule from './broker.module';
import BrokerageNoteModule from './brokerageNote.module';
import CompanyModule from './company.module';
import PositionModule from './position.module';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    asset: AssetModule,
    broker: BrokerModule,
    brokerageNote: BrokerageNoteModule,
    company: CompanyModule,
    position: PositionModule,
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