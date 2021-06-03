<template>
  <div>
    <v-card v-for="consolidation in consolidations" :key="consolidation.key" elevation="2">
      <v-card-title>{{ consolidation.header }}</v-card-title>
        <v-card-subtitle>
          <v-row>
            <v-col>
              <strong>Operações comuns:</strong> {{ consolidation.asset.normal_value }}
            </v-col>
            <v-col>
              <strong>Operações daytrade:</strong> {{ consolidation.asset.daytrade_value }}
            </v-col>
            <v-col>
              &nbsp;
            </v-col>
          </v-row>
        </v-card-subtitle>
      <v-card-text>
        <v-row>
          <v-col>
            <strong>IR fonte no mês:</strong> {{ consolidation.irrf_normal_total }}
          </v-col>
          <v-col>
            <strong>IR fonte Daytrade no mês:</strong> {{ consolidation.irrf_daytrade_total }}
          </v-col>
          <v-col>
            <strong>Imposto:</strong> {{ consolidation.ir }}
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
  name: "ConsolidationListing",
  created() {
    store.dispatch('consolidation/getAll');
  },
  methods: {
    getHeader(year, month, marketType, assetType) {
      const monthNames = [
        'Janeiro',
        'Fereveiro',
        'Março',
        'Abril',
        'Maio',
        'Junho',
        'Julho',
        'Agosto',
        'Setembro',
        'Outubro',
        'Novembro',
        'Dezembro'
      ];

      let markets = {};
      markets['SPOT'] = 'Mercado à Vista';
      markets['FUTURE'] = 'Mercado Futuro';

      let assets = {}
      assets['STOCK'] = 'Ações';
      assets['INDEX'] = 'Índices';
      assets['DOLAR'] = 'Dólar dos EUA';

      return `${year} - ${monthNames[month]} - ${markets[marketType]} - ${assets[assetType]}`;
    }
  },
  computed: {
    isLoadingConsolidations() {
      return this.$store.getters["consolidation/isLoading"];
    },
    consolidations() {
      const consolidations = this.$store.getters["consolidation/consolidations"];

      let consolidationsToShow = [];

      consolidations
        .filter((consolidation) => {
          return consolidation.is_exempt === false
        })
        .forEach((consolidation) => {
          let toShow = consolidationsToShow.find(toShow => {
            return (
              consolidation.year === toShow.year &&
              consolidation.month === toShow.month &&
              consolidation.market_type === toShow.market_type &&
              consolidation.asset_type === toShow.asset.type
            );
          });

          const normal_value = (consolidation.negotiation_type === 'NORMAL') ? consolidation.basis_to_ir : .0;
          const daytrade_value = (consolidation.negotiation_type === 'DAYTRADE') ? consolidation.basis_to_ir : .0;
          const irrf_normal_total = (consolidation.negotiation_type === 'NORMAL') ? consolidation.irrf_to_pay : .0;
          const irrf_daytrade_total = (consolidation.negotiation_type === 'DAYTRADE') ? consolidation.irrf_to_pay : .0;
          const ir = consolidation.ir_to_pay;

          if (!!toShow === false) {
            toShow = {
              year: consolidation.year,
              month: consolidation.month,
              market_type: consolidation.market_type,
              asset: {
                type: consolidation.asset_type,
                normal_value: normal_value,
                daytrade_value: daytrade_value,
              },
              irrf_normal_total: irrf_normal_total,
              irrf_daytrade_total: irrf_daytrade_total,
              ir: ir
            };
          } else {
            toShow = {
              year: consolidation.year,
              month: consolidation.month,
              market_type: consolidation.market_type,
              asset: {
                type: consolidation.asset_type,
                normal_value: toShow.asset.normal_value + normal_value,
                daytrade_value: toShow.asset.daytrade_value + daytrade_value,
              },
              irrf_normal_total: toShow.irrf_normal_total + irrf_normal_total,
              irrf_daytrade_total: toShow.irrf_daytrade_total + irrf_daytrade_total,
              ir: toShow.ir + ir
            };

            consolidationsToShow = consolidationsToShow.filter((consolidationToShow) => {
              return (
                consolidationToShow.year !== toShow.year ||
                consolidationToShow.month !== toShow.month ||
                consolidationToShow.market_type !== toShow.market_type ||
                consolidationToShow.asset.type !== toShow.asset.type
              );
            });
          }

          consolidationsToShow.push(toShow);
        });

      consolidationsToShow.sort(function(a, b){
        return (
          a.year - b.year ||
          a.month - b.month
        );
      });

      return consolidationsToShow
          .filter(toShow => {
            return (
                toShow.asset.normal_value !== 0 ||
                toShow.asset.daytrade_value !== 0
            );
          })
          .map(toShow => {
            return {
              ...toShow,
              key: `${toShow.year}-${toShow.month}-${toShow.market_type}-${toShow.asset.type}`,
              header: this.getHeader(toShow.year, toShow.month - 1, toShow.market_type, toShow.asset.type),
              asset: {
                type: toShow.asset.type,
                normal_value: currencyFormatter(toShow.asset.normal_value),
                daytrade_value: currencyFormatter(toShow.asset.daytrade_value),
              },
              irrf_normal_total: currencyFormatter(toShow.irrf_normal_total),
              irrf_daytrade_total: currencyFormatter(toShow.irrf_daytrade_total),
              ir: currencyFormatter(toShow.ir),
            }
          });
    },
  },
}
</script>
