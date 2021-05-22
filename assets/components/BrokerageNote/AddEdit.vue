<template>
  <div>
    <transition>
      <v-alert type="success" v-if="flash_message.show">{{ flash_message.description }}</v-alert>
    </transition>

    <v-form
      v-model="is_valid_form"
      @submit.prevent
      :disabled="is_disabled_form"
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
          <DateSelector
            :value="date"
            @changeValue="date = $event"
            :disabled="is_editing === true"
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
              :value="brokerage"
              label="Corretagem"
              @changeValue="brokerage = $event"
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
            :value="irrf_normal_tax"
            label="IRRF - Normal"
            @changeValue="irrf_normal_tax = $event"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <input-numeric
            :value="irrf_daytrade_tax"
            label="IRRF - Daytrade"
            @changeValue="irrf_daytrade_tax = $event"
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
      </v-row>
      <v-spacer />
      <v-btn
        color='primary'
        class='mb-2'
        small
        @click='saveBrokageNote()'
        :disabled='!is_valid_form'
      >Salvar
      </v-btn>
    </v-form>
  </div>
</template>

<script>
import BrokerSelector from "@/components/BrokerageNote/BrokerSelector";
import DateSelector from "@/components/Common/DateSelector";
import InputCalculated from "@/components/Common/InputCalculated";
import InputNumeric from "@/components/Common/InputNumeric";

export default {
    name: "BrokerageNoteAddEdit",
    components: {
      BrokerSelector,
      DateSelector,
      InputCalculated,
      InputNumeric
    },
    props: {
      brokerage_note_id: null,
    },
    created() {
      this.local_brokerage_note_id = parseInt(this.$props.brokerage_note_id);

      if (!!this.local_brokerage_note_id) {
        this.loadBrokerageNoteToEdit(this.local_brokerage_note_id);
      }
    },
    data() {
      return {
        local_brokerage_note_id: null,
        broker: null,
        date: null,
        number: null,
        total_moviments: null,
        operational_fee: null,
        registration_fee: null,
        emolument_fee: null,
        brokerage: null,
        iss_pis_cofins: null,
        irrf_normal_tax: null,
        irrf_daytrade_tax: null,

        is_valid_form: false,
        is_disabled_form: false,
        is_editing: false,
        flash_message: {
          show: false,
          description: '',
        }
      }
    },
    watch: {
      isLoading(newValue, oldValue) {
        const canLoadBrokerageNoteToEdit = (newValue === false && oldValue === true && this.isNewBrokerageNote() === false);

        if (canLoadBrokerageNoteToEdit) {
          this.loadBrokerageNoteToEdit(this.local_brokerage_note_id);
        }
      },
    },
    computed: {
      isLoading(){
        return this.$store.getters["broker/isLoading"] || this.$store.getters["brokerageNote/isLoading"];
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
        const brokerage = parseFloat(this.brokerage);
        const iss_pis_cofins = parseFloat(this.iss_pis_cofins);
        const irrf_normal_tax = parseFloat(this.irrf_normal_tax);
        const irrf_daytrade_tax = parseFloat(this.irrf_daytrade_tax);

        return (
          (isNaN(total_moviments) ? .0 : total_moviments) -
          (isNaN(brokerage) ? .0 : brokerage) -
          (isNaN(total_fees) ? .0 : total_fees) -
          (isNaN(iss_pis_cofins) ? .0 : iss_pis_cofins) -
          (isNaN(irrf_normal_tax) ? .0 : irrf_normal_tax) -
          (isNaN(irrf_daytrade_tax) ? .0 : irrf_daytrade_tax)
        ).toFixed(2);
      },
      getTotalCosts() {
        const total_fees = parseFloat(this.getTotalFees);
        const brokerage = parseFloat(this.brokerage);
        const iss_pis_cofins = parseFloat(this.iss_pis_cofins);
        const irrf_normal_tax = parseFloat(this.irrf_normal_tax);
        const irrf_daytrade_tax = parseFloat(this.irrf_daytrade_tax);

        return (
          (isNaN(total_fees) ? .0 : total_fees) +
          (isNaN(brokerage) ? .0 : brokerage) +
          (isNaN(iss_pis_cofins) ? .0 : iss_pis_cofins) +
          (isNaN(irrf_normal_tax) ? .0 : irrf_normal_tax) +
          (isNaN(irrf_daytrade_tax) ? .0 : irrf_daytrade_tax)
        ).toFixed(2);
      },
      getResult() {
        const total_moviments = parseFloat(this.total_moviments);
        const total_fees = parseFloat(this.getTotalFees);
        const brokerage = parseFloat(this.brokerage);
        const iss_pis_cofins = parseFloat(this.iss_pis_cofins);
        const irrf_normal_tax = parseFloat(this.irrf_normal_tax);
        const irrf_daytrade_tax = parseFloat(this.irrf_daytrade_tax);

        return (
          (isNaN(total_moviments) ? .0 : total_moviments) -
          (isNaN(total_fees) ? .0 : total_fees) -
          (isNaN(brokerage) ? .0 : brokerage) -
          (isNaN(iss_pis_cofins) ? .0 : iss_pis_cofins) -
          (isNaN(irrf_normal_tax) ? .0 : irrf_normal_tax) -
          (isNaN(irrf_daytrade_tax) ? .0 : irrf_daytrade_tax)
        ).toFixed(2);
      },
    },
    methods: {
      isNewBrokerageNote() {
        return !(!!this.local_brokerage_note_id);
      },
      loadBrokerageNoteToEdit(brokerage_note_id) {
        const hasBrokers = this.$store.getters["broker/hasBrokers"];
        const hasBrokerageNotes = this.$store.getters["brokerageNote/hasBrokerageNotes"];

        if (!hasBrokers) return;
        if (!hasBrokerageNotes) return;

        const brokerageNote = this.$store.getters["brokerageNote/getById"](brokerage_note_id);

        this.broker = this.$store.getters["broker/getById"](brokerageNote.broker_id);
        this.date = brokerageNote.date;
        this.number = brokerageNote.number;
        this.total_moviments = brokerageNote.total_moviments;
        this.operational_fee = brokerageNote.operational_fee;
        this.registration_fee = brokerageNote.registration_fee;
        this.emolument_fee = brokerageNote.emolument_fee;
        this.brokerage = brokerageNote.brokerage;
        this.iss_pis_cofins = brokerageNote.iss_pis_cofins;
        this.irrf_normal_tax = brokerageNote.irrf_normal_tax;
        this.irrf_daytrade_tax = brokerageNote.irrf_daytrade_tax;

        this.is_editing = true;
      },
      showFlashMessage(redirect = false, id = null){
        this.flash_message.show = true;
        this.flash_message.description = 'A note de corretagem foi salva com sucesso! Você será redirecionado';
        this.is_disabled_form = true;
        const self = this;

        setTimeout(() => {
          self.flash_message.show = false;
          self.flash_message.description = '';

          if (redirect) {
            this.$router.push({ name: 'OperationAdd', params: { brokerageNoteId: id }});
          } else {
            this.$router.push({ name: 'BrokerageNoteListing'});
          }
        }, 2000);
      },
      async saveBrokageNote() {
        const payload = {
          id: this.local_brokerage_note_id,
          broker_id: this.broker.id,
          date: this.date,
          number: this.number,
          total_moviments: parseFloat(this.total_moviments).toFixed(2),
          operational_fee: parseFloat(this.operational_fee).toFixed(2),
          registration_fee: parseFloat(this.registration_fee).toFixed(2),
          emolument_fee: parseFloat(this.emolument_fee).toFixed(2),
          brokerage: parseFloat(this.brokerage).toFixed(2),
          iss_pis_cofins: parseFloat(this.iss_pis_cofins).toFixed(2),
          irrf_normal_tax: parseFloat(this.irrf_normal_tax).toFixed(2),
          irrf_daytrade_tax: parseFloat(this.irrf_daytrade_tax).toFixed(2),
        }

        let result = null;
        let redirect = false;
        let newBrokerageNoteId = null;

        if (this.isNewBrokerageNote()) {
          result = await this.$store.dispatch("brokerageNote/add", payload);
          redirect = true;
          newBrokerageNoteId = result.id;
        } else {
          result = await this.$store.dispatch("brokerageNote/edit", payload);
        }

        this.showFlashMessage(redirect, newBrokerageNoteId);
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