import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '@/views/Home';
import Broker from '@/views/Broker';
import BrokerageNoteListing from '@/views/BrokerageNote/Listing';
import BrokerageNoteAdd from '@/views/BrokerageNote/Add';
import BrokerageNoteEdit from '@/views/BrokerageNote/Edit';
import BrokerageNoteOperations from '@/views/BrokerageNote/Operations';

Vue.use(VueRouter);

export default new VueRouter({
  mode: 'history',
  routes: [
    { name: 'BrokerageNoteListing', path: '/brokerageNotes', component: BrokerageNoteListing },
    { name: 'BrokerageNoteAdd', path: '/brokerageNotes/add', component: BrokerageNoteAdd },
    { name: 'BrokerageNoteEdit', path: '/brokerageNotes/edit/:id', component: BrokerageNoteEdit },
    { name: 'BrokerageNoteOperations', path: '/brokerageNotes/:id/operations', component: BrokerageNoteOperations },
    { path: '/brokers', component: Broker },
    { path: '/home', component: Home },
    { path: '*', redirect: '/home' }
  ]
});