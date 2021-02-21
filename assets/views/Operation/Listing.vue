<template>
  <div>
    <div class='d-flex align-center'>
      <h1>Operações</h1>
      <v-spacer />
      <router-link :to="`/brokerageNotes/${this.$route.params.id}/operations/add`">
        <v-btn
          color='primary'
          dark
          class='mb-2'
          small
        >
          Nova
        </v-btn>
      </router-link>
    </div>
    <hr class='mb-3'/>
    <operation-listing :brokerage_note_id="this.$route.params.id"/>
  </div>
</template>

<script>
  import OperationListing from "@/components/Operation/Listing";
  export default {
    name: 'OperationView',
    components: {OperationListing}
  }
</script>
