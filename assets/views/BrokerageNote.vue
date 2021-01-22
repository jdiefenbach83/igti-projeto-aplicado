<template>
    <div>
        <div class="d-flex align-center">
            <h1>Notas de corretagem</h1>
            <v-spacer />
            <v-btn
                color="primary"
                dark
                class="mb-2"
                small>
                Nova
            </v-btn>
        </div>
        <hr class="mb-3"/>
        <BrokerageNotesListing />
    </div>
</template>

<script>
  import BrokerageNotesListing from "../components/BrokerageNotesListing";
  export default {
    name: "BrokerageNote",
    components: {BrokerageNotesListing}
  }
</script>

<style scoped>

</style>