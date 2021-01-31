<template>
  <div>
    <v-form
      v-model="isValidForm"
      @submit.prevent
    >
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
            required
            :rules="validationRules.broker"
            :background-color="getRequiredColor()"
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
                label="Data"
                v-bind="attrs"
                @blur="date = localISODateFormatter(dateFormatted)"
                v-on="on"
                required
                :rules="validationRules.date"
                :background-color="getRequiredColor()"
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
            required
            :rules="validationRules.number"
            :background-color="getRequiredColor()"
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
            required
            :rules="validationRules.total_moviments"
            :background-color="getRequiredColor()"
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
            required
            :rules="validationRules.operational_fee"
            :background-color="getRequiredColor()"
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
            required
            :rules="validationRules.registration_fee"
            :background-color="getRequiredColor()"
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
            required
            :rules="validationRules.emolument_fee"
            :background-color="getRequiredColor()"
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
            required
            :rules="validationRules.iss_pis_cofins"
            :background-color="getRequiredColor()"
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
            required
            :rules="validationRules.note_irrf_tax"
            :background-color="getRequiredColor()"
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
            class="calculated-field"
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
            class="calculated-field"
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
            class="calculated-field"
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
            class="calculated-field"
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
            class="calculated-field"
          />
        </v-col>
      </v-row>
      <v-spacer />
      <v-btn
        color='primary'
        class='mb-2'
        small
        @click='addBrokageNote()'
        :disabled='!isValidForm'
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
        isValidForm: false,
      }
    },
    computed: {
      brokers() {
        return this.$store.getters["broker/brokers"];
      },
      getTotalFees() {
        const registration_fee = parseFloat(this.registration_fee);
        const operational_fee = parseFloat(this.operational_fee);
        const emolument_fee = parseFloat(this.emolument_fee);

        return (
          (isNaN(registration_fee) ? .0 : registration_fee) +
          (isNaN(operational_fee) ? .0 : operational_fee) +
          (isNaN(emolument_fee) ? .0 : emolument_fee)
        ).toFixed(2);
      },
      getNetTotal() {
        const total_moviments = parseFloat(this.total_moviments);
        const total_fees = parseFloat(this.getTotalFees);
        const iss_pis_cofins = parseFloat(this.iss_pis_cofins);
        const note_irrf_tax = parseFloat(this.note_irrf_tax);

        return (
          (isNaN(total_moviments) ? .0 : total_moviments) -
          (isNaN(total_fees) ? .0 : total_fees) -
          (isNaN(iss_pis_cofins) ? .0 : iss_pis_cofins) -
          (isNaN(note_irrf_tax) ? .0 : note_irrf_tax)
        ).toFixed(2);
      },
      getTotalCosts() {
        const total_fees = parseFloat(this.getTotalFees);
        const iss_pis_cofins = parseFloat(this.iss_pis_cofins);
        const note_irrf_tax = parseFloat(this.note_irrf_tax);

        return (
          (isNaN(total_fees) ? .0 : total_fees) +
          (isNaN(iss_pis_cofins) ? .0 : iss_pis_cofins) +
          (isNaN(note_irrf_tax) ? .0 : note_irrf_tax)
        ).toFixed(2);
      },
      getResult() {
        const total_moviments = parseFloat(this.total_moviments);
        const total_fees = parseFloat(this.getTotalFees);
        const iss_pis_cofins = parseFloat(this.iss_pis_cofins);

        return (
          (isNaN(total_moviments) ? .0 : total_moviments) -
          (isNaN(total_fees) ? .0 : total_fees) -
          (isNaN(iss_pis_cofins) ? .0 : iss_pis_cofins)
        ).toFixed(2);
      },
      getBasisIr() {
        return this.getResult < .0 ? 0 : this.getResult;
      },
      validationRules() {
        return {
          broker: [
            v => !!v || 'A corretora é requerida',
          ],
          date: [
            v => !!v || 'A data é requerida',
          ],
          number: [
            v => !!v || 'O número é requerido',
          ],
          total_moviments: [
            v => !!v || 'O total da movimentação é requerido',
          ],
          operational_fee: [
            v => !!v || 'A taxa operacional é requerida',
            v => !!this.numberGreaterThanOrEqualsZero(v) || 'O valor deve ser maior ou igual a zero',
          ],
          registration_fee: [
            v => !!v || 'A taxa de registro é requerida',
            v => !!this.numberGreaterThanOrEqualsZero(v) || 'O valor deve ser maior ou igual a zero',
          ],
          emolument_fee: [
            v => !!v || 'O emolumento é requerido',
            v => !!this.numberGreaterThanOrEqualsZero(v) || 'O valor deve ser maior ou igual a zero',
          ],
          iss_pis_cofins: [
            v => !!v || 'O ISS/PIS/COFINS é requerido',
            v => !!this.numberGreaterThanOrEqualsZero(v) || 'O valor deve ser maior ou igual a zero',
          ],
          note_irrf_tax: [
            v => !!v || 'O IRRF é requerido',
            v => !!this.numberGreaterThanOrEqualsZero(v) || 'O valor deve ser maior ou igual a zero',
          ],
        }
      }
    },
    watch: {
      date (val) {
        this.dateFormatted = brazilianDateFormatter(this.date)
      },
    },
    methods: {
      getRequiredColor() {
        return '#ffffbb';
      },
      localISODateFormatter(date) {
        return ISODateFormatter(date);
      },
      numberGreaterThanOrEqualsZero(value) {
        const parsedValue = parseFloat(value);

        if (isNaN(parsedValue)) {
          return false;
        }

        return parsedValue >= .0;
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

  .calculated-field {
    font-weight: bold;
  }
</style>