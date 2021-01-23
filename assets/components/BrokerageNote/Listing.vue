<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="brokerageNotes"
      item-key="id"
      class="elevation-1"
      :search="search"
      :custom-filter="filterList"
      sear
      :loading="isLoadingBrokerageNotes"
      loading-text="Carregando..."
    >
      <template v-slot:top>
        <v-text-field
          v-model="search"
          label="Pesquisa"
          class="mx-4"
          append-icon="mdi-magnify"
        />
      </template>
      <template v-slot:item.total_moviments="{ item }">
        <v-chip
          :color="getColor(item.total_moviments)"
          dark
        >
          {{ item.total_moviments }}
        </v-chip>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon
          small
          class="mr-2"
          title="Visualizar"
        >
          <!--@click="viewItem(item)"-->
          mdi-magnify-scan
        </v-icon>
        <v-icon
          small
          class="mr-2"
          title="Editar"
        >
          <!--@click="editItem(item)"-->
          mdi-pencil
        </v-icon>
        <v-icon
          small
          title="Excluir"
        >
          <!--@click="deleteItem(item)"-->
          mdi-delete
        </v-icon>
      </template>
    </v-data-table>
  </div>
</template>

<script>
  import { format as DateFormatter } from '../../helper/DateFormatter';
  import { format as CurrencyFormatter } from '../../helper/CurrencyFormatter';

  export default {
    name: 'BrokerageNotesListing',
    data() {
      return {
        search: '',
      };
    },
    methods: {
      filterList (value, search, item) {
        return value != null &&
          search != null &&
          value.toString().toLocaleUpperCase().indexOf(search.toLocaleUpperCase()) !== -1
      },
      getColor (total_movimentacao) {
        if (total_movimentacao.indexOf('-') >= 0 )
          return 'red'

        return 'green'
      },
    },
    computed: {
      isLoadingBrokerageNotes() {
        return this.$store.getters["brokerageNote/isLoading"]
      },
      brokerageNotes() {
        const brokerageNotes = this.$store.getters["brokerageNote/brokerageNotes"];
        const brokers = this.$store.getters["broker/brokers"];

        const brokerageNotesForListing = brokerageNotes.map(brokerageNote => {
          return {
            ...brokerageNote,
            date: DateFormatter(brokerageNote.date),
            total_moviments: CurrencyFormatter(brokerageNote.total_moviments),
            net_total: CurrencyFormatter(brokerageNote.net_total),
            result: CurrencyFormatter(brokerageNote.result),
            broker: brokers.find(broker => brokerageNote.broker_id === broker.id),
          }
        });

        return brokerageNotesForListing.sort(function(a, b){
          return new Date(a.date) - new Date(b.date);
        });
      },
      headers () {
        return [
          {
            text: 'Corretora',
            align: 'start',
            sortable: true,
            value: 'broker.name',
          },
          {
            text: 'Data',
            align: 'start',
            sortable: true,
            value: 'date',
          },
          {
            text: 'Número',
            align: 'start',
            sortable: true,
            value: 'number',
          },
          {
            text: 'Movimentação',
            align: 'end',
            sortable: true,
            value: 'total_moviments',
          },
          {
            text: 'Total Líquido',
            align: 'end',
            sortable: true,
            value: 'net_total',
          },
          {
            text: 'Resultado',
            align: 'end',
            sortable: true,
            value: 'result',
          },
          {
            text: 'Ações',
            align: 'end',
            sortable: false,
            value: 'actions',
          },
        ]
      }
    }
  }
</script>

<style scoped>

</style>