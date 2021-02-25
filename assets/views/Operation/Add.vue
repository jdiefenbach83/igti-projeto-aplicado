<template>
  <div>
    <h1>Nova operação para nota de corretagem {{ !!brokerageNote ? brokerageNote.number : '' }}</h1>
    <hr class='mb-3'/>
    <operation-add-edit :brokerage_note_id="localBrokerageNoteId" />
  </div>
</template>

<script>
  import OperationAddEdit from "@/components/Operation/AddEdit";
  export default {
    name: "OperationAddView",
    components: {OperationAddEdit},
    data () {
      return {
        localBrokerageNoteId: null,
        brokerageNote: null,
      };
    },
    created() {
      this.localBrokerageNoteId = parseInt(this.$route.params.brokerageNoteId);

      if (!!this.localBrokerageNoteId) {
        this.loadBrokerageNoteToAddOperation(this.localBrokerageNoteId);
      }
    },
    watch: {
      isLoading(newValue, oldValue) {
        const canLoadBrokerageNoteToAddOperation = (newValue === false && oldValue === true);

        if (canLoadBrokerageNoteToAddOperation) {
          this.loadBrokerageNoteToAddOperation(this.localBrokerageNoteId);
        }
      },
    },
    computed: {
      isLoading() {
        return this.$store.getters['brokerageNote/isLoading'];
      },
    },
    methods: {
      loadBrokerageNoteToAddOperation(brokerageNoteId) {
        const hasBrokerageNotes = this.$store.getters['brokerageNote/hasBrokerageNotes'];

        if (!hasBrokerageNotes) return;

        this.brokerageNote = this.$store.getters['brokerageNote/getById'](brokerageNoteId);
      },
    }
  }
</script>