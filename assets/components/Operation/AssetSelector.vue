<template>
  <v-combobox
    :value="asset"
    :items="assets"
    item-text="code"
    item-value="id"
    label="Ativo"
    required
    :rules="validationRules"
    background-color="#ffffbb"
    @input="changeInputValue"
  />
</template>

<script>
  export default {
    name: "AssetSelector",
    props: {
      asset: null,
    },
    created() {
      this.asset = this.$props.asset ?? null;
    },
    computed: {
      assets() {
        return this.$store.getters["asset/assets"];
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