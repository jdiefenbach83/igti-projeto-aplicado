<template>
    <div>
        <v-data-table
            :headers="headers"
            :items="brokers"
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
  import BrokerService from '../services/BrokerService';

  export default {
    name: "BrokersListing",
    data() {
      return {
        brokers: [],
        search: ''
      };
    },
    async created() {
      const brokers = await this.getBrokers();

      this.brokers = brokers.content;
    },
    methods: {
      async getBrokers() {
        try {
          return await BrokerService.getAll();
        } catch (error) {
          console.log(error);
        }
      },
      filterList (value, search, item) {
        return value != null &&
          search != null &&
          typeof value === 'string' &&
          value.toString().toLocaleUpperCase().indexOf(search.toLocaleUpperCase()) !== -1
      },
    },
    computed: {
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