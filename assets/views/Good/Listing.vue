<template>
  <div>
    <div class='d-flex align-center'>
      <h1>Bens e direitos</h1>
      <v-spacer />
      <v-btn
        color='green darken-2'
        class='mb-2 white--text'
        small
        @click="refreshGoods()"
        :disabled="isLoadingGoods"
      >
        <v-icon dark>
          mdi-refresh
        </v-icon>
      </v-btn>
    </div>
    <hr class='mb-3'/>
    <good-listing />
  </div>
</template>

<script>
import GoodListing from "@/components/Good/Listing";
import store from "@/store";

export default {
  name: 'GoodListingView',
  components: { GoodListing },
  methods: {
    refreshGoods() {
      store.dispatch('good/getAll');
    },
  },
  computed: {
    isLoadingGoods() {
      return this.$store.getters['good/isLoading'];
    },
  },
}
</script>
