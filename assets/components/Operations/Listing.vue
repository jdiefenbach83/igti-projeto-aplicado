<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="operations"
      :items-per-page="5"
      item-key="id"
      class="elevation-1"
      :search="search"
      :custom-filter="filterList"
      sear
      :loading="isLoading"
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
    </v-data-table>
  </div>
</template>

<script>
  import { format as currencyFormatter } from '@/helper/CurrencyFormatter';
  import { format as numberFormatter } from '@/helper/NumberFormatter';

  export default {
    name: 'OperationsListing',
    data() {
      return {
        search: '',
        local_brokerage_note_id: null,
        operations: [],
      };
    },
    props: {
      brokerage_note_id: null,
    },
    created() {
      this.local_brokerage_note_id = parseInt(this.$props.brokerage_note_id) ?? null;

      if (this.local_brokerage_note_id !== null) {
        this.loadOperations(this.local_brokerage_note_id);
      }
    },
    methods: {
      loadOperations(brokerage_note_id) {
        const hasAssets = this.$store.getters["asset/hasAssets"];
        const hasBrokerageNotes = this.$store.getters["brokerageNote/hasBrokerageNotes"];

        if (!hasAssets) return;
        if (!hasBrokerageNotes) return;

        const { operations } = this.$store.getters["brokerageNote/getById"](brokerage_note_id);
        const assets = this.$store.getters["asset/assets"];

        const operationsForListing = operations.map(operation => {
          return {
            ...operation,
            asset: assets.find(asset => operation.asset_id === asset.id),
            quantity: numberFormatter(operation.quantity),
            price: currencyFormatter(operation.price),
            total: currencyFormatter(operation.total),
          }
        });

        this.operations = operationsForListing.sort(function(a, b){
          return a.line - b.line;
        });
      },
      editItem(item) {
        const brokerage_note_id = item.id;

        this.$router.push({ name: 'BrokerageNoteEdit', params: { id: brokerage_note_id }});
      },
      filterList (value, search) {
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
    watch: {
      isLoading(newValue, oldValue) {
        const canLoadOperations = (newValue === false && oldValue === true);

        if (canLoadOperations) {
          this.loadOperations(this.local_brokerage_note_id);
        }
      },
    },
    computed: {
      isLoading(){
        return this.$store.getters["asset/isLoading"] || this.$store.getters["brokerageNote/isLoading"];
      },
      headers () {
        return [
          {
            text: 'Linha',
            align: 'start',
            sortable: true,
            value: 'line',
          },
          {
            text: 'Tipo',
            align: 'start',
            sortable: true,
            value: 'type',
          },
          {
            text: 'Ativo',
            align: 'start',
            sortable: true,
            value: 'asset.code',
          },
          {
            text: 'Quantidade',
            align: 'end',
            sortable: true,
            value: 'quantity',
          },
          {
            text: 'Preço',
            align: 'end',
            sortable: true,
            value: 'price',
          },
          {
            text: 'Total',
            align: 'end',
            sortable: true,
            value: 'total',
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