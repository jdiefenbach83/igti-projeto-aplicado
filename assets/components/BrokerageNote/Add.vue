<template>
  <div>
    <v-form>
      <v-row>
        <v-col
          cols="12"
          sm="3"
        >
          <v-combobox
            v-model="broker"
            :items="brokers"
            item-text="name"
            item-value="id"
            label="Corretora"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <v-menu
            ref="menu1"
            v-model="menu1"
            :close-on-content-click="false"
            transition="scale-transition"
            offset-y
            max-width="290px"
            min-width="auto"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-text-field
                v-model="dateFormatted"
                label="Date"
                v-bind="attrs"
                @blur="date = localISODateFormatter(dateFormatted)"
                v-on="on"
              />
            </template>
            <v-date-picker
              v-model="date"
              no-title
              @input="menu1 = false"
              locale="pt-br"
            />
          </v-menu>
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <v-text-field
            v-model="number"
            label="Número"
            type="number"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <v-text-field
            v-model="total_moviments"
            label="Movimentação"
            type="number"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <v-text-field
            v-model="operational_fee"
            label="Taxa operacional"
            type="number"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <v-text-field
            v-model="registration_fee"
            label="Taxa de registro"
            type="number"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <v-text-field
            v-model="emolument_fee"
            label="Emolumentos"
            type="number"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <v-text-field
            v-model="iss_pis_cofins"
            label="ISS/PIS/COFINS"
            type="number"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <v-text-field
            v-model="note_irrf_tax"
            label="IRRF"
            type="number"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <v-text-field
            v-model="getTotalFees"
            label="Total das taxas"
            type="number"
            readonly
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <v-text-field
            v-model="getNetTotal"
            label="Total líquido"
            type="number"
            readonly
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <v-text-field
            v-model="getTotalCosts"
            label="Custo total"
            type="number"
            readonly
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <v-text-field
            v-model="getResult"
            label="Resultado"
            type="number"
            readonly
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <v-text-field
            v-model="getBasisIr"
            label="Base de cáculo para IR"
            type="number"
            readonly
          />
        </v-col>
      </v-row>
      <v-spacer />
      <v-btn
        color='primary'
        dark
        class='mb-2'
        small
        @click="addBrokageNote()"
      >Adicionar
      </v-btn>
    </v-form>
  </div>
</template>

<script>
  import { formatBrazilianDate as brazilianDateFormatter,
           getNewISODate as newDate,
           getNewBrazilianDate as newBrazilianDate,
           formatISODate as ISODateFormatter,
  } from '@/helper/DateFormatter';

  export default {
    name: "BrokerageNotesAdd",
    data() {
      return {
        broker: null,
        date: newDate(),
        dateFormatted: newBrazilianDate(),
        menu1: false,
        number: null,
        total_moviments: .0,
        operational_fee: .0,
        registration_fee: .0,
        emolument_fee: .0,
        iss_pis_cofins: .0,
        note_irrf_tax: .0,
      }
    },
    computed: {
      brokers() {
        return this.$store.getters["broker/brokers"];
      },
      getTotalFees() {
        return (
          parseFloat(this.registration_fee) +
          parseFloat(this.operational_fee) +
          parseFloat(this.emolument_fee)).toFixed(2);
      },
      getNetTotal() {
        return (
          parseFloat(this.total_moviments) -
          (parseFloat(this.registration_fee) + parseFloat(this.operational_fee) + parseFloat(this.emolument_fee)) -
          parseFloat(this.iss_pis_cofins) -
          parseFloat(this.note_irrf_tax)).toFixed(2);
      },
      getTotalCosts() {
        return (
          parseFloat(this.registration_fee) +
          parseFloat(this.operational_fee) +
          parseFloat(this.emolument_fee) +
          parseFloat(this.iss_pis_cofins) +
          parseFloat(this.note_irrf_tax)).toFixed(2);
      },
      getResult() {
        return (
          parseFloat(this.total_moviments) -
          (parseFloat(this.registration_fee) + parseFloat(this.operational_fee) + parseFloat(this.emolument_fee)) -
          parseFloat(this.iss_pis_cofins)).toFixed(2);
      },
      getBasisIr() {
        const result = (
          parseFloat(this.total_moviments) -
          (parseFloat(this.registration_fee) + parseFloat(this.operational_fee) + parseFloat(this.emolument_fee)) -
          parseFloat(this.iss_pis_cofins)).toFixed(2);

        return result < .0 ? 0 : result;
      }
    },
    watch: {
      date (val) {
        this.dateFormatted = brazilianDateFormatter(this.date)
      },
    },
    methods: {
      localISODateFormatter(date) {
        return ISODateFormatter(date);
      },
      async addBrokageNote() {
        const payload = {
          broker_id: this.broker.id,
          date: this.date,
          number: this.number,
          total_moviments: parseFloat(this.total_moviments).toFixed(2),
          operational_fee: parseFloat(this.operational_fee).toFixed(2),
          registration_fee: parseFloat(this.registration_fee).toFixed(2),
          emolument_fee: parseFloat(this.emolument_fee).toFixed(2),
          iss_pis_cofins: parseFloat(this.iss_pis_cofins).toFixed(2),
          note_irrf_tax: parseFloat(this.note_irrf_tax).toFixed(2),
        }
        const result = await this.$store.dispatch("brokerageNote/add", payload);

        if (result !== null) {
          console.log(result);
        }
      }
    },
  }
</script>

<style scoped>
  div.col-sm-3.col-12 {
    padding-top: 0;
    padding-bottom: 0;
  }
</style>