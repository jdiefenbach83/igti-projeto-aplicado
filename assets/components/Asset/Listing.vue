<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="assets"
      :items-per-page="5"
      item-key="id"
      class="elevation-1"
      :search="search"
      :custom-filter="filterList"
      :loading="isLoadingAssets"
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
    name: "AssetListing",
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

      const hasAssets = this.$store.getters['asset/hasAssets'];

      if (!hasAssets) {
        await this.$store.dispatch('asset/getAll');
      }
    },
    computed: {
      isLoadingAssets() {
        return this.$store.getters['asset/isLoading'];
      },
      assets() {
        const assets = this.$store.getters["asset/assets"];

        return assets.map(asset => {
          const company = this.$store.getters["company/getById"](asset.company_id);

          return {
            ...asset,
            company: company?.name,
          }
        });
      },
      headers () {
        return [
          {
            text: 'Código',
            align: 'start',
            sortable: true,
            value: 'code',
          },
          {
            text: 'Tipo',
            align: 'start',
            sortable: true,
            value: 'type',
          },
          {
            text: 'Empresa',
            align: 'start',
            sortable: true,
            value: 'company',
          },
        ]
      }
    }
  }
</script>
