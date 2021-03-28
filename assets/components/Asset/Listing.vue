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
    computed: {
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