<template>
  <div>
    <v-data-table
        :headers="headers"
        :items="exempts"
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

export default {
  name: 'ExemptListing',
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
    exempts() {
      const consolidations = this.$store.getters['consolidation/consolidations'];

      const exempts = consolidations
          .filter(consolidation => {
            return consolidation.asset_type === 'STOCK' && consolidation.is_exempt === true;
          });

      const exemptsByYears = [];
      
      exempts.forEach((exempt)=> {
        const year = exempt.year;
        
        if (!exemptsByYears[year]) {
          exemptsByYears[year] = { year, amount: 0 };
        }
        
        exemptsByYears[year].amount += exempt.basis_to_ir
      });
    
      return exemptsByYears.map((item) => {
        return {
          year: item.year,
          code: '20 - Ganhos líquidos em operações no mercado à vista de ações negociadas em bolsas de valores nas alienações realizadas até R$ 20.000,00 em cada mês, para o conjunto de ações',
          total: currencyFormatter(item.amount)
        };
      });
    },
    headers() {
      return [
        {
          text: 'Ano',
          align: 'start',
          sortable: true,
          value: 'year',
        },
        {
          text: 'Código',
          align: 'start',
          sortable: true,
          value: 'code',
        },
        {
          text: 'Valor',
          align: 'start',
          sortable: true,
          value: 'total',
        }
      ]
    }
  }
}
</script>
