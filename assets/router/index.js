import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/Home";
import Broker from "../views/Broker";

Vue.use(VueRouter);

export default new VueRouter({
  mode: "history",
  routes: [
    { path: "/brokers", component: Broker },
    { path: "/home", component: Home },
    { path: "*", redirect: "/home" }
  ]
});