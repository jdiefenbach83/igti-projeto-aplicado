<template>
  <v-text-field
    :label='label'
    :value='value'
    type='number'
    readonly
    class='calculated-field'
  />
</template>

<script>
  export default {
    name: 'InputCalculated',
    props: {
      value: .0,
      label: ''
    },
  }
</script>

<style scoped>
  .calculated-field {
    font-weight: bold;
  }
</style>