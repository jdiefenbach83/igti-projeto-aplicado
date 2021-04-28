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
      group-by="asset"
      show-group-by
      sear
      :loading="isLoadingPositions"
      loading-text="Carregando..."
    >
      <template v-slot:top>
        <v-row>
          <v-col
            cols="9"
            sm="9"
            md="9"
          >
            <v-text-field
              v-model="search"
              label="Pesquisa"
              class="mx-4"
              append-icon="mdi-magnify"
            />
          </v-col>
          <v-col
            cols="3"
            sm="3"
            md="3"
          >
            <v-switch
              v-model="lastPositionFilter"
              label="Apenas última posição"
            />
          </v-col>
        </v-row>
      </template>
      <template v-slot:item.asset="{ item }">
        <strong>{{ item.asset }}</strong>
      </template>
      <template v-slot:item.type="{ item }">
        <v-chip
          :color="getColorToBuySellColumn(item.original_type)"
          dark
        >
          {{ item.type }}
        </v-chip>
      </template>
      <template v-slot:item.negotiation_type="{ item }">
        <v-chip
            :color="getColorToNegotiationTypeColumn(item.original_negotiation_type)"
            dark
        >
          {{ item.negotiation_type }}
        </v-chip>
      </template>
      <template v-slot:item.average_price_to_ir="{ item }">
        <strong>{{ item.average_price_to_ir }}</strong>
      </template>
      <template v-slot:group.header="{ group, headers, toggle, remove, isOpen }">
        <td :colspan="headers.length">
          <v-btn @click="toggle" x-small icon :ref="group">
            <v-icon v-if="isOpen">mdi-plus</v-icon>
            <v-icon v-else>mdi-minus</v-icon>
          </v-btn>
          <span class="mx-5 font-weight-bold">{{ group }}</span>
          <v-btn @click="remove" x-small icon :ref="group">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </td>
      </template>
    </v-data-table>
  </div>
</template>

<script>
  import {formatBrazilianDate as brazilianDateFormatter} from "@/helper/DateFormatter";
  import {format as currencyFormatter} from "@/helper/CurrencyFormatter";
  import {format as numberFormatter} from "@/helper/NumberFormatter";
  import store from "@/store";

  const POSITION_TYPES = {
    POSITION_TYPE_BUY: "BUY",
    POSITION_TYPE_SELL: "SELL"
  };

  const NEGOTIATION_TYPES = {
    NEGOTIATION_TYPE_NORMAL: "NORMAL",
    NEGOTIATION_TYPE_DAYTRADE: "DAYTRADE"
  };

  export default {
    name: "PositionListing",
    created() {
      store.dispatch('position/getAll');
    },
    data() {
      return {
        search: '',
        lastPositionFilter: true,
      };
    },
    methods: {
      filterList (value, search, item) {
        return value != null &&
          search != null &&
          typeof value === 'string' &&
          value.toString().toLocaleUpperCase().indexOf(search.toLocaleUpperCase()) !== -1
      },
      getColorToBuySellColumn (type) {
        if (type === POSITION_TYPES.POSITION_TYPE_BUY)
          return 'red'

        return 'green'
      },
      getColorToNegotiationTypeColumn (type) {
        if (type === NEGOTIATION_TYPES.NEGOTIATION_TYPE_DAYTRADE)
          return 'red'

        return 'green'
      },
    },
    computed: {
      isLoadingPositions() {
        return this.$store.getters["position/isLoading"];
      },
      positions() {
        let positions = this.$store.getters["position/positions"];

        if (this.lastPositionFilter) {
          positions = positions.filter(position => position.is_last === this.lastPositionFilter);
        }

        return positions.map(position => {
          const asset = this.$store.getters["asset/getById"](position.asset_id);

          return {
            ...position,
            asset: asset?.code,
            original_type: position.type,
            original_negotiation_type: position.negotiation_type,
            type: position.type === POSITION_TYPES.POSITION_TYPE_BUY ? 'C' : 'V',
            negotiation_type: position.negotiation_type === NEGOTIATION_TYPES.NEGOTIATION_TYPE_NORMAL ? 'Normal' : 'Daytrade',
            date: brazilianDateFormatter(position.date),
            accumulated_quantity: numberFormatter(position.accumulated_quantity),
            accumulated_total: currencyFormatter(position.accumulated_total),
            accumulated_costs: currencyFormatter(position.accumulated_costs),
            average_price: currencyFormatter(position.average_price),
            average_price_to_ir: currencyFormatter(position.average_price_to_ir),
            accumulated_result: currencyFormatter(position.accumulated_result),
            position_price:  currencyFormatter(position.position_price),
            quantity: numberFormatter(position.quantity),
            result:  currencyFormatter(position.result),
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
            groupable: true,
          },
          {
            text: 'Tipo',
            align: 'start',
            sortable: true,
            value: 'type',
            groupable: false,
          },
          {
            text: 'Data',
            align: 'start',
            sortable: true,
            value: 'date',
            groupable: true,
          },
          {
            text: 'Negociação',
            align: 'start',
            sortable: true,
            value: 'negotiation_type',
            groupable: false,
          },
          {
            text: 'Quantidade',
            align: 'end',
            sortable: true,
            value: 'quantity',
            groupable: false,
          },
          {
            text: 'Preço',
            align: 'end',
            sortable: true,
            value: 'position_price',
            groupable: false,
          },
          {
            text: 'Preço (IR)',
            align: 'end',
            sortable: true,
            value: 'average_price_to_ir',
            groupable: false,
          },
          {
            text: 'Resultado',
            align: 'end',
            sortable: true,
            value: 'result',
            groupable: false,
          },
          {
            text: 'Quantidade acumulada',
            align: 'end',
            sortable: true,
            value: 'accumulated_quantity',
            groupable: false,
          },
          {
            text: 'Total acumulado',
            align: 'end',
            sortable: true,
            value: 'accumulated_total',
            groupable: false,
          },
          {
            text: 'Resultado acumulado',
            align: 'end',
            sortable: true,
            value: 'accumulated_result',
            groupable: false,
          },
        ]
      }
    }
  }
</script>