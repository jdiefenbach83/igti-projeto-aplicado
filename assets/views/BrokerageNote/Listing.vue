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
      <v-btn
        color='green darken-2'
        class='mb-2 ml-3 white--text'
        small
        @click="refreshBrokerageNotes()"
        :disabled="isLoadingBrokerageNotes"
      >
        <v-icon dark>
          mdi-refresh
        </v-icon>
      </v-btn>
    </div>
    <hr class='mb-3'/>
    <brokerage-note-listing />
  </div>
</template>

<script>
  import BrokerageNoteListing from '@/components/BrokerageNote/Listing';
  import store from "@/store";
  export default {
    name: 'BrokerageNoteListingView',
    components: { BrokerageNoteListing },
    methods: {
      refreshBrokerageNotes() {
        store.dispatch('broker/getAll');

        store.dispatch('brokerageNote/getAll');
      },
    },
    computed: {
      isLoadingBrokerageNotes() {
        return this.$store.getters['brokerageNote/isLoading'];
      },
    },
  }
</script>
