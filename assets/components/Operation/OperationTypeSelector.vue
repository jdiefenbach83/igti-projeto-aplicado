<template>
  <v-combobox
    v-model="localType"
    :items="operationTypes"
    item-text="description"
    item-value="code"
    label="Tipo"
    required
    :rules="validationRules"
    background-color="#ffffbb"
    @input="changeInputValue"
  />
</template>

<script>
  export default {
    name: "OperationTypeSelector",
    props: {
      type: null,
    },
    data() {
      return {
        localType: null
      }
    },
    created() {
      this.localType = this.getOperationValue(this.$props.type);
    },
    watch: {
      type(newValue) {
        this.localType = this.getOperationValue(newValue);
      }
    },
    computed: {
      operationTypes() {
        return [
          { code: "BUY", description: "Compra" },
          { code: "SELL", description: "Venda" },
        ];
      },
      validationRules() {
        return [
          v => !!v || "O ativo é requerido",
        ];
      }
    },
    methods: {
      getOperationValue(operation) {
        return this.operationTypes.find(item => item.code === operation);
      },
      changeInputValue(value){
        this.$emit("changeValue", value);
      },
    }
  }
</script>