<template>
  <div>
    <v-card v-for="good in goods" :key="good.cnpj" elevation="2">
      <v-card-text>
        <v-row>
          <v-col>
            <strong>Código:</strong> {{ good.code }}
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <strong>Localização (País):</strong> {{ good.location }}
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <strong>CNPJ:</strong> {{ good.cnpj }}
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <strong>Descrição:</strong> {{ good.description }}
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <strong>Situação até 31/12/2019:</strong> {{ good.situationYearBefore }}
          </v-col>
          <v-col>
            <strong>Situação até 31/12/2020:</strong> {{ good.situationCurrentYear }}
          </v-col>
          <v-col>
            &nbsp;
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import store from "@/store";
import {format as currencyFormatter} from "@/helper/CurrencyFormatter";

export default {
  name: 'GoodListing',
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
    store.dispatch('good/getAll');
  },
  computed: {
    goods() {
      const goods = this.$store.getters['good/goods'];

      return goods.map(good => {
        return {
          code: '31 - Ações (inclusive as provenientes de linha telefônica).',
          location: '105 - Brasil',
          cnpj: good.cnpj,
          description: good.description,
          situationYearBefore: currencyFormatter(good.situation_year_before),
          situationCurrentYear: currencyFormatter(good.situation_current_year)
        }
      });
    },
  }
}
</script>
