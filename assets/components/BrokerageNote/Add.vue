<template>
  <div>
    <transition>
      <v-alert type="success" v-if="flash_message.show">{{ flash_message.description }}</v-alert>
    </transition>

    <v-form
      v-model="isValidForm"
      @submit.prevent
      :disabled="isDisabledForm"
    >
      <v-row>
        <v-col
          cols="12"
          sm="3"
        >
          <BrokerSelector
            :broker="broker"
            @changeValue="broker = $event"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <DateSelector
            :value="date"
            @changeValue="date = $event"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <input-numeric
            :value="number"
            label="Número"
            @changeValue="number = $event"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <input-numeric
            :value="total_moviments"
            label="Movimentação"
            @changeValue="total_moviments = $event"
            only-positives="false"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <input-numeric
            :value="operational_fee"
            label="Taxa operacional"
            @changeValue="operational_fee = $event"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <input-numeric
            :value="registration_fee"
            label="Taxa de registro"
            @changeValue="registration_fee = $event"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <input-numeric
            :value="emolument_fee"
            label="Emolumentos"
            @changeValue="emolument_fee = $event"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <input-numeric
            :value="iss_pis_cofins"
            label="ISS/PIS/COFINS"
            @changeValue="iss_pis_cofins = $event"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <input-numeric
            :value="note_irrf_tax"
            label="IRRF"
            @changeValue="note_irrf_tax = $event"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <input-calculated
            :value="getTotalFees"
            label="Total das taxas"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <input-calculated
            :value="getNetTotal"
            label="Total líquido"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <input-calculated
            :value="getTotalCosts"
            label="Custo total"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <input-calculated
            :value="getResult"
            label="Resultado"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <input-calculated
            :value="getBasisIr"
            label="Base de cáculo para IR"
          />
        </v-col>
      </v-row>
      <v-spacer />
      <v-btn
        color='primary'
        class='mb-2'
        small
        @click='saveBrokageNote()'
        :disabled='!isValidForm'
      >Salvar
      </v-btn>
    </v-form>
  </div>
</template>

<script>
  import BrokerSelector from "@/components/BrokerageNote/BrokerSelector";
  import DateSelector from "@/components/BrokerageNote/DateSelector";
  import InputCalculated from "@/components/BrokerageNote/InputCalculated";
  import InputNumeric from "@/components/BrokerageNote/InputNumeric";

  export default {
    name: "BrokerageNotesAdd",
    components: {
      BrokerSelector,
      DateSelector,
      InputCalculated,
      InputNumeric
    },
    data() {
      return {
        broker: null,
        date: null,
        number: null,
        total_moviments: .0,
        operational_fee: .0,
        registration_fee: .0,
        emolument_fee: .0,
        iss_pis_cofins: .0,
        note_irrf_tax: .0,

        isValidForm: false,
        isDisabledForm: false,
        flash_message: {
          show: false,
          description: '',
        }
      }
    },
    computed: {
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
    },
    methods: {
      showFlashMessage(){
        this.flash_message.show = true;
        this.flash_message.description = 'A note de corretagem foi salva com sucesso! Você será redirecionado';
        this.isDisabledForm = true;
        const self = this;

        setTimeout(() => {
          self.flash_message.show = false;
          self.flash_message.description = '';
          this.$router.push({ name: 'BrokerageNoteListing'});
        }, 2000);
      },
      async saveBrokageNote() {
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
        this.showFlashMessage();

        if (result !== undefined) {
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