<template>
  <div>
    <div class='d-flex align-center'>
      <h1>Rendimentos isentos e não tributáveis</h1>
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
    <exempt-listing />
  </div>
</template>

<script>
import ExemptListing from "@/components/Exempt/Listing";
import store from "@/store";

export default {
  name: "ExemptListingView",
  components: { ExemptListing },
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
