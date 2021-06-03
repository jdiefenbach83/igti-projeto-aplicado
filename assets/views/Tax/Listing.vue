<template>
  <div>
    <div class='d-flex align-center'>
      <h1>DARF</h1>
      <v-spacer />
      <v-btn
        color='green darken-2'
        class='mb-2 white--text'
        smalla
        @click="refreshConsolidations()"
        :disabled="isLoadingConsolidations"
      >
        <v-icon dark>
          mdi-refresh
        </v-icon>
      </v-btn>
    </div>
    <hr class='mb-3'/>
    <tax-listing />
  </div>
</template>

<script>
import TaxListing from "@/components/Tax/Listing";
import store from "@/store";

export default {
  name: "TaxListingView",
  components: { TaxListing },
  methods: {
    refreshConsolidations() {
      store.dispatch('consolidation/getAll');
    },
  },
  computed: {
    isLoadingConsolidations() {
      return this.$store.getters["consolidation/isLoading"];
    },
  },
}
</script>

<style scoped>

</style>