<template>
  <div>
    <div class='d-flex align-center'>
      <h1>Renda variável</h1>
      <v-spacer />
      <v-btn
        color='green darken-2'
        class='mb-2 white--text'
        small
      >
        <!--        @click="refreshConsolidations()"-->
        <!--        :disabled="isLoadingConsolidations"-->
        <v-icon dark>
          mdi-refresh
        </v-icon>
      </v-btn>
    </div>
    <hr class='mb-3'/>
    <consolidation-listing />
  </div>
</template>

<script>
import ConsolidationListing from "@/components/Consolidation/Listing";

export default {
  name: "ConsolidationListingView",
  components: { ConsolidationListing },
}
</script>

<style scoped>

</style>