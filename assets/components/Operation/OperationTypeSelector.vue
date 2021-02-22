<template>
  <v-combobox
    :value="local_type"
    :items="operation_types"
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
        local_type: null
      }
    },
    created() {
      this.type = this.$props.type ?? null;
    },
    computed: {
      operation_types() {
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
      changeInputValue(value){
        this.$emit("changeValue", value);
      },
    }
  }
</script>