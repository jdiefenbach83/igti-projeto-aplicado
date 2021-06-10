<template>
  <v-dialog
    v-model="dialog"
    persistent
    max-width="600"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-icon
        small
        title="Excluir"
        v-bind="attrs"
        v-on="on"
      >
        mdi-delete
      </v-icon>
    </template>
    <v-card>
      <v-card-title class="headline">
        Deseja remover a nota de corretagem?
      </v-card-title>
      <v-card-text>
        Corretora: {{ brokerage_note.broker.name }} <br>
        Número: {{ brokerage_note.number }} <br>
        Data: {{ brokerage_note.date }} <br>
        Total de movimentações: {{ brokerage_note.total_moviments }}
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          color="primary darken-1"
          small
          @click="dialog = false"
        >
          Não
        </v-btn>
        <v-btn
          color="error darken-1"
          small
          @click="removeBrokerageNote()"
        >
          Sim
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    name: "RemoveModal",
    props: {
      brokerage_note: null
    },
    data () {
      return {
        dialog: false,
      }
    },
    methods: {
      async removeBrokerageNote() {
        const payload = {
          ...this.brokerage_note,
        }

        const result = await this.$store.dispatch("brokerageNote/remove", payload);
        this.dialog = false;

        if (result.success === false) {
          console.log(result);
        }
      },
    }
  }
</script>

<style scoped>

</style>