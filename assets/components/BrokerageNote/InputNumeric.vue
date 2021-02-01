<template>
  <v-text-field
    :value='value'
    :label='label'
    type='number'
    required
    :rules='validationRules'
    background-color='#ffffbb'
    @input='changeInputValue'
  />
</template>

<script>
  export default {
    name: 'InputNumeric',
    props: {
      value: .0,
      label: '',
      onlyPositives: true
    },
    computed: {
      validationRules() {
        return [
          v => !!v || 'O campo é requerido',
          v => !!this.isValidNumber(v) || 'O valor é inválido',
        ];
      }
    },
    methods: {
      changeInputValue(value){
        this.$emit('changeValue', value);
      },
      isValidNumber(value) {
        const parsedValue = parseFloat(value);

        if (isNaN(parsedValue)) {
          return false;
        }

        const onlyPositives = this.$props.onlyPositives;
        if (onlyPositives === undefined || onlyPositives.toLowerCase() === 'true') {
          return parsedValue >= .0;
        }

        return true;
      },
    }
  }
</script>