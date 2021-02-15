<template>
  <v-app>
    <v-app-bar app color="green darken-2">
      <div class="d-flex align-center">
        <v-img
          alt="Vuetify Logo"
          class="shrink mr-2"
          style="cursor: pointer"
          contain
          transition="scale-transition"
          width="40"
        />
      </div>

      <router-link to="/home" class="white--text mx-3">Home</router-link>

      <v-spacer />

      <router-link to="/brokerageNotes" class="white--text mx-3">Notas de corretagem</router-link>
      <router-link to="/brokers" class="white--text mx-3">Corretoras</router-link>
    </v-app-bar>

    <v-main>
      <v-container>
        <router-view></router-view>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import store from '@/store';

export default {
  name: "App",
  created() {
    // 'Loading prereqs in vuex store
    store.dispatch('asset/getAll');
    store.dispatch('broker/getAll');
    store.dispatch('brokerageNote/getAll');
    // 'End of loading prereqs in vuex store
  }
}
</script>

<style scoped>

</style>