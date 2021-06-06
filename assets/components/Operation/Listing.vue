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
      <template v-slot:item.type="{ item }">
        <v-chip
            :color="getColorToBuySellColumn(item.original_type)"
            dark
        >
          {{ item.type }}
        </v-chip>
      </template>
      <template v-slot:body.append>
        <tr>
          <td colspan="2">
            <strong>Total:</strong>
          </td>
          <td>
            <strong>{{ getTotalDistinctAssets }}</strong>
          </td>
          <td class="text-end">
            <strong>{{ getQuantityTotal }}</strong>
          </td>
          <td class="text-end" colspan="2">
            <strong>{{ getValueTotal }}</strong>
          </td>
        </tr>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon
            small
            class="mr-2"
            title="Editar"
            @click="editItem(item)"
        >
          mdi-pencil
        </v-icon>
        <remove-modal :operation="item"/>
      </template>
    </v-data-table>
  </div>
</template>

<script>
  import RemoveModal from "@/components/Operation/RemoveModal";
  import { format as currencyFormatter } from '@/helper/CurrencyFormatter';
  import { format as numberFormatter } from '@/helper/NumberFormatter';

  const OPERATION_TYPES = {
    OPERATION_TYPE_BUY: "BUY",
    OPERATION_TYPE_SELL: "SELL"
  };

  export default {
    name: 'OperationListing',
    components: { RemoveModal },
    data() {
      return {
        search: '',
        localBrokerageNoteId: null,
        operations: [],
      };
    },
    props: {
      brokerage_note_id: null,
    },
    created() {
      this.localBrokerageNoteId = parseInt(this.$props.brokerage_note_id);

      if (!!this.localBrokerageNoteId) {
        this.loadOperations(this.localBrokerageNoteId);
      }
    },
    methods: {
      async loadOperations(brokerage_note_id) {
        const hasAssets = this.$store.getters["asset/hasAssets"];

        if (!hasAssets) {
          await this.$store.dispatch('asset/getAll');
        }

        const hasBrokerageNotes = this.$store.getters["brokerageNote/hasBrokerageNotes"];

        if (!hasAssets) return;
        if (!hasBrokerageNotes) return;

        const { operations } = this.$store.getters["brokerageNote/getById"](brokerage_note_id);
        const assets = this.$store.getters["asset/assets"];

        const operationsForListing = operations.map(operation => {
          return {
            ...operation,
            type: operation.type === OPERATION_TYPES.OPERATION_TYPE_BUY ? 'Compra' : 'Venda',
            asset: assets.find(asset => operation.asset_id === asset.id),
            quantity: numberFormatter(operation.quantity),
            price: currencyFormatter(operation.price),
            total: currencyFormatter(operation.total),
            original_type: operation.type,
            original_quantity: operation.quantity,
            original_total: operation.total,
            brokerage_note_id
          }
        });

        this.operations = operationsForListing.sort(function(a, b){
          return a.line - b.line;
        });
      },
      editItem(item) {
        const operationId = item.id;

        this.$router.push({ name: 'OperationEdit', params: { brokerageNoteId: this.localBrokerageNoteId, operationId: operationId }});
      },
      filterList (value, search) {
        return value != null &&
          search != null &&
          value.toString().toLocaleUpperCase().indexOf(search.toLocaleUpperCase()) !== -1
      },
      getColorToBuySellColumn (type) {
        if (type === OPERATION_TYPES.OPERATION_TYPE_BUY)
          return 'red'

        return 'green'
      },
    },
    watch: {
      isLoading(newValue, oldValue) {
        const canLoadOperations = (newValue === false && oldValue === true);

        if (canLoadOperations) {
          this.loadOperations(this.localBrokerageNoteId);
        }
      },
    },
    computed: {
      getTotalDistinctAssets(){
        if (!(!!this.$data.operations)) {
          return;
        }

        const assets = this.$data.operations.map(operation => operation.asset.code);
        const distincAssets = [...new Set(assets)];

        return numberFormatter(distincAssets.length);
      },
      getQuantityTotal(){
        if (!(!!this.$data.operations)) {
          return;
        }

        const total = this.$data.operations.reduce((accumulator, current) => {
          const value = (
              current.original_type === OPERATION_TYPES.OPERATION_TYPE_BUY
                  ? current.original_quantity * -1
                  : current.original_quantity);

          return accumulator += value;
        }, 0);

        return numberFormatter(total);
      },
      getValueTotal(){
        if (!(!!this.$data.operations)) {
          return;
        }

        const total = this.$data.operations.reduce((accumulator, current) => {
          const value = (
              current.original_type === OPERATION_TYPES.OPERATION_TYPE_BUY
                  ? current.original_total * -1
                  : current.original_total);

          return accumulator += value
        }, 0);

        return currencyFormatter(total);
      },
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