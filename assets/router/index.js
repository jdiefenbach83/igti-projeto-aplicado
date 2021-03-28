import Vue from 'vue';
import VueRouter from 'vue-router';
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

Vue.use(VueRouter);

export default new VueRouter({
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
    { path: '/home', component: Home },
    { path: '*', redirect: '/home' }
  ]
});