<template>
  <div>
    <div class='d-flex align-center'>
      <h1>Operações</h1>
      <v-spacer />
      <router-link to='/brokerageNotes/add'>
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
    <operations-listing :brokerage_note_id="this.$route.params.id"/>
  </div>
</template>

<script>
  import OperationsListing from "@/components/Operations/Listing";
  export default {
    name: 'BrokerageNoteOperations',
    components: {OperationsListing}
  }
</script>
