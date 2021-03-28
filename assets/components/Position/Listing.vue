<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="positions"
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
  import {formatBrazilianDate as brazilianDateFormatter} from "@/helper/DateFormatter";
  import {format as currencyFormatter} from "@/helper/CurrencyFormatter";

  const POSITION_TYPES = {
    POSITION_TYPE_BUY: "BUY",
    POSITION_TYPE_SELL: "SELL"
  };

  export default {
    name: "PositionListing",
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
      positions() {
        const positions = this.$store.getters["position/positions"];

        return positions.map(position => {
          const asset = this.$store.getters["asset/getById"](position.asset_id);

          return {
            ...position,
            asset: asset?.code,
            type: position.type === POSITION_TYPES.OPERATION_TYPE_BUY ? 'Compra' : 'Venda',
            date: brazilianDateFormatter(position.date),
            accumulated_total: currencyFormatter(position.accumulated_total),
            average_price: currencyFormatter(position.average_price),
          }
        });
      },
      headers () {
        return [
          {
            text: 'Ativo',
            align: 'start',
            sortable: true,
            value: 'asset',
          },
          {
            text: 'Tipo',
            align: 'start',
            sortable: true,
            value: 'type',
          },
          {
            text: 'Data',
            align: 'start',
            sortable: true,
            value: 'date',
          },
          {
            text: 'Quantidade acumulada',
            align: 'end',
            sortable: true,
            value: 'accumulated_quantity',
          },
          {
            text: 'Total acumulado',
            align: 'end',
            sortable: true,
            value: 'accumulated_total',
          },
          {
            text: 'Preço médio',
            align: 'end',
            sortable: true,
            value: 'average_price',
          },
        ]
      }
    }
  }
</script>