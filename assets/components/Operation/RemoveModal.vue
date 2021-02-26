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
        Deseja remover a operação?
      </v-card-title>
      <v-card-text>
        Linha: {{ operation.line }} <br>
        Tipo: {{ operation.type }} <br>
        Ativo: {{ operation.asset.code }} <br>
        Quantidade: {{ operation.quantity }} <br>
        Preço: {{ operation.price }} <br>
        Total: {{ operation.total }}
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
          @click="removeOperation()"
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
      operation: null
    },
    data () {
      return {
        dialog: false,
      }
    },
    methods: {
      async removeOperation() {
        const payload = {
          ...this.operation,
        }

        const result = await this.$store.dispatch('brokerageNote/removeOperation', payload);
        this.dialog = false;

        if (result !== undefined) {
          console.log(result);
        }
      },
    }
  }
</script>