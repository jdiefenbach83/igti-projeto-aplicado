import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../store/index';
import Login from '@/views/Security/Login';
import Home from '@/views/Home';
import Asset from '@/views/Asset/Listing';
import Broker from '@/views/Broker/Listing';
import BrokerageNoteListing from '@/views/BrokerageNote/Listing';
import BrokerageNoteAdd from '@/views/BrokerageNote/Add';
import BrokerageNoteEdit from '@/views/BrokerageNote/Edit';
import Company from '@/views/Company/Listing';
import OperationListing from '@/views/Operation/Listing';
import OperationAdd from '@/views/Operation/Add';
import OperationEdit from '@/views/Operation/Edit';
import PositionListing from '@/views/Position/Listing';
import ConsolidationListing from '@/views/Consolidation/Listing';
import TaxListing from '@/views/Tax/Listing';
import GoodListing from '@/views/Good/Listing';
import ExemptListing from '@/views/Exempt/Listing';

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  routes: [
    { name: 'AssetListing', path: '/assets', component: Asset },
    { name: 'BrokerListing', path: '/brokers', component: Broker },
    { name: 'BrokerageNoteListing', path: '/brokerageNotes', component: BrokerageNoteListing },
    { name: 'BrokerageNoteAdd', path: '/brokerageNotes/add', component: BrokerageNoteAdd },
    { name: 'BrokerageNoteEdit', path: '/brokerageNotes/edit/:id', component: BrokerageNoteEdit },
    { name: 'CompanyListing', path: '/companies', component: Company },
    { name: 'OperationListing', path: '/brokerageNotes/:id/operations', component: OperationListing },
    { name: 'OperationAdd', path: '/brokerageNotes/:brokerageNoteId/operations/add', component: OperationAdd },
    { name: 'OperationEdit', path: '/brokerageNotes/:brokerageNoteId/operations/edit/:operationId', component: OperationEdit },
    { name: 'PositionListing', path: '/positions', component: PositionListing },
    { name: 'ConsolidationListing', path: '/consolidations', component: ConsolidationListing },
    { name: 'TaxListing', path: '/taxes', component: TaxListing },
    { name: 'GoodListing', path: '/goods', component: GoodListing },
    { name: 'ExemptListing', path: '/exempts', component: ExemptListing },
    { name: 'Login', path: '/login', component: Login },
    { path: '/home', component: Home },
    { path: '*', redirect: '/home' }
  ],
});

router.beforeEach((to, from, next) => {
  const restoreLogin = async () => {
    await store.dispatch('security/restoreLogin');
  };

  restoreLogin();

  const isAuthenticated = store.getters['security/isAuthenticated'];
  const publicPages = ['/login'];
  const authRequired = !publicPages.includes(to.path);

  if (authRequired && !isAuthenticated) {
    next('/login');
  } else {
    next();
  }
});

export default router;