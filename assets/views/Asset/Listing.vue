<template>
  <div>
    <div class='d-flex align-center'>
      <h1>Ativos</h1>
      <v-spacer />
      <v-btn
        color='green darken-2'
        class='mb-2 white--text'
        small
        @click="refreshAssets()"
        :disabled="isLoadingAssets"
      >
        <v-icon dark>
          mdi-refresh
        </v-icon>
      </v-btn>
    </div>
    <hr class='mb-3'/>
    <asset-listing />
  </div>
</template>

<script>
  import AssetListing from '@/components/Asset/Listing';
  import store from "@/store";

  export default {
    name: 'AssetListingView',
    components: { AssetListing },
    methods: {
      refreshAssets() {
        store.dispatch('asset/getAll');
      },
    },
    computed: {
      isLoadingAssets() {
        return this.$store.getters['asset/isLoading'];
      },
    },
  }
</script>
