<template>
  <v-menu
    ref="menu1"
    v-model="menu1"
    :close-on-content-click="false"
    transition="scale-transition"
    offset-y
    max-width="290px"
    min-width="auto"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-text-field
        v-model="dateFormatted"
        label="Date"
        v-bind="attrs"
        @blur="localDate = localISODateFormatter(dateFormatted)"
        v-on="on"
        required
        :rules="validationRules"
        background-color="#ffffbb"
        :disabled="disabled"
      ></v-text-field>
    </template>
    <v-date-picker
      v-model="localDate"
      no-title
      @input="menu1 = false"
      locale="pt-br"
    ></v-date-picker>
  </v-menu>
</template>

<script>
  import {
    formatBrazilianDate as brazilianDateFormatter,
    formatISODate as ISODateFormatter,
    getNewISODate as newDate
  } from "@/helper/DateFormatter";

  export default {
    name: "DateSelector",
    props: {
      value: null,
      disabled: false,
    },
    data() {
      return {
        localDate: null,
        dateFormatted: null,
        menu1: false,
      }
    },
    created() {
      this.localDate = this.$props.value ?? newDate();
    },
    watch: {
      localDate (val) {
        this.dateFormatted = brazilianDateFormatter(this.localDate)
        this.$emit('changeValue', this.localDate);
      },
    },
    methods: {
      localISODateFormatter(date) {
        return ISODateFormatter(date);
      },
    },
    computed: {
      validationRules() {
        return [
          v => !!v || 'A data é requerida',
        ];
      }
    },
  }
</script>