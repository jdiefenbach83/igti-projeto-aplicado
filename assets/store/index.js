import Vue from 'vue';
import Vuex from 'vuex';
import AssetModule from './asset.module';
import BrokerModule from './broker.module';
import BrokerageNoteModule from './brokerageNote.module';
import CompanyModule from './company.module';
import PositionModule from './position.module';
import ConsolidationModule from './consolidation.module';
import GoodModule from './good.module';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    asset: AssetModule,
    broker: BrokerModule,
    brokerageNote: BrokerageNoteModule,
    company: CompanyModule,
    position: PositionModule,
    consolidation: ConsolidationModule,
    good: GoodModule,
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