<template>
  <div>
    <div class='d-flex align-center'>
      <h1>Notas de corretagem</h1>
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
    <brokerage-notes-listing />
  </div>
</template>

<script>
  import BrokerageNotesListing from '@/components/BrokerageNote/Listing';
  export default {
    name: 'BrokerageNoteListingView',
    components: {BrokerageNotesListing}
  }
</script>

<style scoped>

</style>