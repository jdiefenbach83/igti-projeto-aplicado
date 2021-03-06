<template>
    <div>
        <v-data-table
            :headers="headers"
            :items="brokers"
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
                ></v-text-field>
            </template>
        </v-data-table>
    </div>
</template>

<script>
  export default {
    name: "BrokerListing",
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
      brokers() {
        return this.$store.getters["broker/brokers"];
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
            text: 'Nome',
            align: 'start',
            sortable: true,
            value: 'name',
          },
          {
            text: 'CNPJ',
            align: 'start',
            sortable: true,
            value: 'cnpj',
          },
          {
            text: 'Site',
            align: 'start',
            sortable: true,
            value: 'site',
          },
        ]
      }
    }
  }
</script>

<style scoped>

</style>