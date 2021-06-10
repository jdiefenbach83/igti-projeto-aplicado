<template>
  <div>
    <div class='d-flex align-center'>
      <h1>Posições</h1>
      <v-spacer />
      <v-btn
        color='green darken-2'
        class='mb-2 white--text'
        small
        @click="refreshPositions()"
        :disabled="isLoadingPositions"
      >
        <v-icon dark>
          mdi-refresh
        </v-icon>
      </v-btn>
    </div>
    <hr class='mb-3'/>
    <position-listing />
  </div>
</template>

<script>
  import PositionListing from "@/components/Position/Listing";
  import store from "@/store";
  export default {
    name: "PositionListingView",
    components: { PositionListing },
    methods: {
      refreshPositions() {
        store.dispatch('position/getAll');
      },
    },
    computed: {
      isLoadingPositions() {
        return this.$store.getters["position/isLoading"];
      },
    },
  }
</script>
