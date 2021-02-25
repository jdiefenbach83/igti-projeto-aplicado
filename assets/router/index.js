import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '@/views/Home';
import Broker from '@/views/Broker/Listing';
import BrokerageNoteListing from '@/views/BrokerageNote/Listing';
import BrokerageNoteAdd from '@/views/BrokerageNote/Add';
import BrokerageNoteEdit from '@/views/BrokerageNote/Edit';
import OperationListing from '@/views/Operation/Listing';
import OperationAdd from '@/views/Operation/Add';

Vue.use(VueRouter);

export default new VueRouter({
  mode: 'history',
  routes: [
    { name: 'BrokerListing', path: '/brokers', component: Broker },
    { name: 'BrokerageNoteListing', path: '/brokerageNotes', component: BrokerageNoteListing },
    { name: 'BrokerageNoteAdd', path: '/brokerageNotes/add', component: BrokerageNoteAdd },
    { name: 'BrokerageNoteEdit', path: '/brokerageNotes/edit/:id', component: BrokerageNoteEdit },
    { name: 'OperationListing', path: '/brokerageNotes/:id/operations', component: OperationListing },
    { name: 'OperationAdd', path: '/brokerageNotes/:brokerageNoteId/operations/add', component: OperationAdd },
    { path: '/home', component: Home },
    { path: '*', redirect: '/home' }
  ]
});