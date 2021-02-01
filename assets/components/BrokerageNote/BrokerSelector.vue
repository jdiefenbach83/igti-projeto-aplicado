<template>
  <v-combobox
    :value='broker'
    :items='brokers'
    item-text='name'
    item-value='id'
    label='Corretora'
    required
    :rules='validationRules'
    background-color='#ffffbb'
    @input='changeInputValue'
  />
</template>

<script>
  export default {
    name: 'BrokerSelector',
    props: {
      broker: null,
    },
    created() {
      this.broker = this.$props.broker ?? null;
    },
    computed: {
      brokers() {
        return this.$store.getters['broker/brokers'];
      },
      validationRules() {
        return [
          v => !!v || 'A corretora é requerida',
        ];
      }
    },
    methods: {
      changeInputValue(value){
        this.$emit('changeValue', value);
      },
    }
  }
</script>

<style scoped>

</style>