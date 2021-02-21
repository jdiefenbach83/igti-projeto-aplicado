<template>
  <div>
    <h1>Nova operação para nota de corretagem {{ !!brokerage_note ? brokerage_note.number : '' }}</h1>
    <hr class='mb-3'/>
  </div>
</template>

<script>
  export default {
    name: "OperationAddView",
    data () {
      return {
        local_brokerage_note_id: null,
        brokerage_note: null,
        brokerage_note_number: null,
      };
    },
    created() {
      this.local_brokerage_note_id = parseInt(this.$route.params.brokerage_note_id);

      if (!!this.local_brokerage_note_id) {
        this.loadBrokerageNoteToAddOperation(this.local_brokerage_note_id);
      }
    },
    watch: {
      isLoading(newValue, oldValue) {
        const canLoadBrokerageNoteToAddOperation = (newValue === false && oldValue === true);

        if (canLoadBrokerageNoteToAddOperation) {
          this.loadBrokerageNoteToAddOperation(this.local_brokerage_note_id);
        }
      },
    },
    computed: {
      isLoading() {
        return this.$store.getters["brokerageNote/isLoading"];
      },
    },
    methods: {
      loadBrokerageNoteToAddOperation(brokerage_note_id) {
        const hasBrokerageNotes = this.$store.getters["brokerageNote/hasBrokerageNotes"];

        if (!hasBrokerageNotes) return;

        this.brokerage_note = this.$store.getters["brokerageNote/getById"](brokerage_note_id);
      },
    }
  }
</script>