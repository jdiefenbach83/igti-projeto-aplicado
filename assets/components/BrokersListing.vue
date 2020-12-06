<template>
    <div>
        <v-simple-table>
            <template v-slot:default>
                <thead>
                    <tr>
                        <th class="text-left">
                            C&oacute;digo
                        </th>
                        <th class="text-left">
                            Nome
                        </th>
                        <th class="text-left">
                            CNPJ
                        </th>
                        <th class="text-left">
                            Site
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="broker in brokers"
                        :key="broker.id"
                    >
                        <td>{{ broker.code }}</td>
                        <td>{{ broker.name }}</td>
                        <td>{{ broker.cnpj }}</td>
                        <td>{{ broker.site }}</td>
                    </tr>
                </tbody>
            </template>
        </v-simple-table>
    </div>
</template>

<script>
  import BrokerService from '../services/BrokerService';

  export default {
    name: "BrokersListing",
    data() {
      return {
        brokers: []
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
    }
  }
</script>

<style scoped>

</style>