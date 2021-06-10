<template>
  <div>
    <transition>
      <v-alert type="success" v-if="flashMessage.show">{{ flashMessage.description }}</v-alert>
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
          <operation-type-selector
            :type="type"
            @changeValue="type = $event.code"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <asset-selector
            :asset="asset"
            @changeValue="asset = $event"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <input-numeric
            :value="quantity"
            label="Quantidade"
            @changeValue="quantity = $event"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <input-numeric
            :value="price"
            label="Preço"
            @changeValue="price = $event"
          />
        </v-col>
        <v-col
          cols="12"
          sm="3"
        >
          <input-calculated
            :value="getTotal"
            label="Total"
          />
        </v-col>
      </v-row>
      <v-spacer />
      <v-btn
        color='primary'
        class='mb-2'
        small
        @click='saveOperation()'
        :disabled='!isValidForm'
      >Salvar
      </v-btn>
    </v-form>
  </div>
</template>

<script>
import OperationTypeSelector from "@/components/Operation/OperationTypeSelector";
import AssetSelector from "@/components/Operation/AssetSelector";
import InputCalculated from "@/components/Common/InputCalculated";
import InputNumeric from "@/components/Common/InputNumeric";

export default {
    name: "OperationAddEdit",
    components: {
      OperationTypeSelector,
      AssetSelector,
      InputCalculated,
      InputNumeric
    },
    props: {
      brokerage_note_id: null,
      operation_id: null,
    },
    async created() {
      const hasAssets = this.$store.getters['asset/hasAssets'];

      if (!hasAssets) {
        await this.$store.dispatch('asset/getAll');
      }

      this.localBrokerageNoteId = parseInt(this.$props.brokerage_note_id);
      this.localOperationId = parseInt(this.$props.operation_id);

      const hasBrokerageNoteId = !!this.localBrokerageNoteId;
      const hasOperationId = !!this.localOperationId;

      if (hasBrokerageNoteId && hasOperationId) {
        this.loadOperationToEdit(this.localBrokerageNoteId, this.localOperationId);
      }
    },
    data() {
      return {
        localBrokerageNoteId: null,
        localOperationId: null,
        line: null,
        type: null,
        asset: null,
        quantity: null,
        price: null,
        total: null,

        isValidForm: false,
        isDisabledForm: false,
        flashMessage: {
          show: false,
          description: '',
        }
      }
    },
    watch: {
      isLoading(newValue, oldValue) {
        const canLoadOperationToEdit = (newValue === false && oldValue === true && this.isNewOperation === false);

        if (canLoadOperationToEdit) {
          this.loadOperationToEdit(this.localBrokerageNoteId, this.localOperationId);
        }
      },
    },
    computed: {
      isLoading(){
        return this.$store.getters["asset/isLoading"] || this.$store.getters["brokerageNote/isLoading"];
      },
      getTotal() {
        const quantity = parseFloat(this.quantity);
        const price = parseFloat(this.price);

        return (
          (isNaN(quantity) ? .0 : quantity) *
          (isNaN(price) ? .0 : price)
        ).toFixed(2);
      },
    },
    methods: {
      isNewOperation() {
        return !(!!this.localOperationId);
      },
      loadOperationToEdit(brokerage_note_id, operation_id) {
        const hasAssets = this.$store.getters["asset/hasAssets"];
        const hasBrokerageNotes = this.$store.getters["brokerageNote/hasBrokerageNotes"];

        if (!hasAssets) return;
        if (!hasBrokerageNotes) return;

        const operation = this.$store.getters["brokerageNote/getOperationById"](brokerage_note_id, operation_id);

        this.line = operation.line;
        this.type = operation.type;
        this.asset = this.$store.getters["asset/getById"](operation.asset_id);
        this.quantity = operation.quantity;
        this.price = operation.price;
        this.total = operation.total;
        this.operational_fee = operation.operational_fee;
        this.registration_fee = operation.registration_fee;
        this.emolument_fee = operation.emolument_fee;
        this.brokerage = operation.brokerage;
        this.iss_pis_cofins = operation.iss_pis_cofins;
      },
      showFlashMessage(){
        this.flashMessage.show = true;
        this.flashMessage.description = 'A operação foi salva com sucesso! Você será redirecionado';
        this.isDisabledForm = true;
        const self = this;

        setTimeout(() => {
          self.flashMessage.show = false;
          self.flashMessage.description = '';
          this.$router.push({ name: 'OperationListing', params: { id: this.localBrokerageNoteId }});
        }, 2000);
      },
      async saveOperation() {
        const payload = {
          id: this.localOperationId,
          brokerage_note_id: this.localBrokerageNoteId,
          line: this.line,
          type: this.type,
          asset_id: this.asset.id,
          quantity: parseInt(this.quantity),
          price: parseFloat(this.price).toFixed(2),
          total: parseFloat(this.total).toFixed(2)
        }

        let result = null;

        if (this.isNewOperation()) {
          result = await this.$store.dispatch("brokerageNote/addOperation", payload);
        } else {
          result = await this.$store.dispatch("brokerageNote/editOperation", payload);
        }

        this.showFlashMessage();

        if (result.success === false) {
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