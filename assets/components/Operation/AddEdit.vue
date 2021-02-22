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
        :disabled='!is_valid_form'
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
    created() {
      this.local_brokerage_note_id = parseInt(this.$props.brokerage_note_id);
      this.local_operation_id = parseInt(this.$props.operation_id);

      const hasBrokerageNoteId = !!this.local_brokerage_note_id;
      const hasOperationId = !!this.local_operation_id;

      if (hasBrokerageNoteId && hasOperationId) {
        this.loadOperationToEdit(this.local_brokerage_note_id, this.local_operation_id);
      }
    },
    data() {
      return {
        local_brokerage_note_id: null,
        local_operation_id: null,
        type: null,
        asset: null,
        quantity: null,
        price: null,
        total: null,

        is_valid_form: false,
        is_disabled_form: false,
        flash_message: {
          show: false,
          description: '',
        }
      }
    },
    watch: {
      isLoading(newValue, oldValue) {
        const canLoadOperationToEdit = (newValue === false && oldValue === true);

        if (canLoadOperationToEdit) {
          this.loadOperationToEdit(this.local_brokerage_note_id);
        }
      },
    },
    computed: {
      isLoading(){
        return this.$store.getters["asset/assets"] || this.$store.getters["brokerageNote/isLoading"];
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
        return !(!!this.local_operation_id);
      },
      loadOperationToEdit(brokerage_note_id, operation_id) {
        const hasAssets = this.$store.getters["asset/hasAssets"];
        const hasBrokerageNotes = this.$store.getters["brokerageNote/hasBrokerageNotes"];

        if (!hasAssets) return;
        if (!hasBrokerageNotes) return;

        const operation = this.$store.getters["brokerageNote/getOperationById"](brokerage_note_id, operation_id);

        this.type = operation.type;
        this.asset = this.$store.getters["asset/getById"](operation.asset_id);
        this.quantity = operation.quantity;
        this.price = operation.price;
      },
      showFlashMessage(){
        this.flash_message.show = true;
        this.flash_message.description = 'A operação foi salva com sucesso! Você será redirecionado';
        this.is_disabled_form = true;
        const self = this;

        setTimeout(() => {
          self.flash_message.show = false;
          self.flash_message.description = '';
          this.$router.push({ name: 'OperationListing', params: { id: this.brokerage_note_id }});
        }, 2000);
      },
      async saveOperation() {
        const payload = {
          id: this.local_operation_id,
          brokerage_note_id: this.local_brokerage_note_id,
          type: this.type,
          asset_id: this.asset.id,
          quantity: parseInt(this.quantity),
          price: parseFloat(this.price).toFixed(2),
        }

        let result = null;

        if (this.isNewOperation()) {
          result = await this.$store.dispatch("brokerageNote/addOperation", payload);
        }

        this.showFlashMessage();

        if (!!result) {
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