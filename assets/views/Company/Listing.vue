<template>
  <div>
    <div class='d-flex align-center'>
      <h1>Empresas</h1>
      <v-spacer />
      <v-btn
        color='green darken-2'
        class='mb-2 white--text'
        small
        @click="refreshCompanies()"
        :disabled="isLoadingCompanies"
      >
        <v-icon dark>
          mdi-refresh
        </v-icon>
      </v-btn>
    </div>
    <hr class='mb-3'/>
    <company-listing />
  </div>
</template>

<script>
  import CompanyListing from '@/components/Company/Listing';
  import store from "@/store";

  export default {
    name: 'CompanyListingView',
    components: { CompanyListing },
    methods: {
      refreshCompanies() {
        store.dispatch('company/getAll');
      },
    },
    computed: {
      isLoadingCompanies() {
        return this.$store.getters['company/isLoading'];
      },
    },
  }
</script>
