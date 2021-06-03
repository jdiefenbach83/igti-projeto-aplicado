<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="taxes"
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
import store from "@/store";
import {format as currencyFormatter} from "@/helper/CurrencyFormatter";
import {formatBrazilianDate as brazilianDateFormatter} from "@/helper/DateFormatter";

export default {
  name: 'TaxListing',
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
  created() {
    store.dispatch('consolidation/getAll');
  },
  computed: {
    taxes() {
      const consolidations = this.$store.getters['consolidation/consolidations'];

      consolidations.sort(function(a, b){
        return (
            a.year - b.year ||
            a.month - b.month
        );
      });

      return consolidations
        .filter(consolidation => {
          return consolidation.ir_to_pay > 0;
        })
        .map(consolidation => {
          const countingPeriodMount = consolidation.month.toString().padStart(2, '0');
          const countingPeriod = `${countingPeriodMount}/${consolidation.year}`;

          const countingPeriodDueDate = consolidation.month + 1;
          let dueDate = new Date(consolidation.year, countingPeriodDueDate, 0);
          dueDate = brazilianDateFormatter(dueDate.toISOString().substr(0, 10));

          return {
            code: '6015',
            countingPeriod: countingPeriod,
            dueDate: dueDate,
            value: currencyFormatter(consolidation.ir_to_pay)
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
          text: 'Período de apuração',
          align: 'start',
          sortable: true,
          value: 'countingPeriod',
        },
        {
          text: 'Vencimento',
          align: 'start',
          sortable: true,
          value: 'dueDate',
        },
        {
          text: 'Valor total',
          align: 'end',
          sortable: true,
          value: 'value',
        },
      ]
    }
  }
}
</script>