<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="companies"
      :items-per-page="5"
      item-key="id"
      class="elevation-1"
      :search="search"
      :custom-filter="filterList"
      :loading="isLoadingCompanies"
      loading-text="Carregando..."
      sear
    >
      <template v-slot:top>
        <v-text-field
          v-model="search"
          label="Pesquisa"
          class="mx-4"
        />
      </template>
    </v-data-table>
  </div>
</template>

<script>
  export default {
    name: "CompanyListing",
    data() {
      return {
        search: ''
      };
    },
    methods: {
      filterList (value, search, item) {
        return value != null &&
          search != null &&
          typeof value === 'string' &&
          value.toString().toLocaleUpperCase().indexOf(search.toLocaleUpperCase()) !== -1
      },
    },
    async created() {
      const hasCompanies = this.$store.getters['company/hasCompanies'];

      if (!hasCompanies) {
        await this.$store.dispatch('company/getAll');
      }
    },
    computed: {
      isLoadingCompanies() {
        return this.$store.getters['company/isLoading'];
      },
      companies() {
        return this.$store.getters["company/companies"];
      },
      headers () {
        return [
          {
            text: 'CNPJ',
            align: 'start',
            sortable: true,
            value: 'cnpj',
          },
          {
            text: 'Nome',
            align: 'start',
            sortable: true,
            value: 'name',
          },
        ]
      }
    }
  }
</script>

<style scoped>

</style>