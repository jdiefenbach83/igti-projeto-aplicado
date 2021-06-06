<template>
  <div>
    <div class='d-flex align-center'>
      <h1>Corretoras</h1>
      <v-spacer />
      <v-btn
          color='green darken-2'
          class='mb-2 white--text'
          small
          @click="refreshBrokers()"
          :disabled="isLoadingBrokers"
      >
        <v-icon dark>
          mdi-refresh
        </v-icon>
      </v-btn>
    </div>
    <hr class='mb-3'/>
    <broker-listing />
  </div>
</template>

<script>
  import BrokerListing from "@/components/Broker/Listing";
  import store from "@/store";

  export default {
    name: "BrokerListingView",
    components: { BrokerListing },
    methods: {
      refreshBrokers() {
        store.dispatch('broker/getAll');
      },
    },
    computed: {
      isLoadingBrokers() {
        return this.$store.getters['broker/isLoading'];
      },
    },
  }
</script>

<style scoped>

</style>