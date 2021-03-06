<template>
  <div>
    <h1>Editar operação {{ operationLine }} da nota de corretagem {{ !!brokerageNote ? brokerageNote.number : '' }}</h1>
    <hr class='mb-3'/>
    <operation-add-edit :brokerage_note_id="localBrokerageNoteId" :operation_id="localOperationId" />
  </div>
</template>

<script>
  import OperationAddEdit from "@/components/Operation/AddEdit";
  export default {
    name: "OperationEditView",
    components: {OperationAddEdit},
    data () {
      return {
        localBrokerageNoteId: null,
        localOperationId: null,
        brokerageNote: null,
        operationLine: null,
      };
    },
    created() {
      this.localBrokerageNoteId = parseInt(this.$route.params.brokerageNoteId);
      this.localOperationId = parseInt(this.$route.params.operationId);

      if (!!this.localBrokerageNoteId && !!this.localOperationId) {
        this.loadBrokerageNoteToEditOperation(this.localBrokerageNoteId, this.localOperationId);
      }
    },
    watch: {
      isLoading(newValue, oldValue) {
        const canLoadBrokerageNoteToEditOperation = (newValue === false && oldValue === true) && this.localOperationId !== null;

        if (canLoadBrokerageNoteToEditOperation) {
          this.loadBrokerageNoteToEditOperation(this.localBrokerageNoteId, this.localOperationId);
        }
      },
    },
    computed: {
      isLoading() {
        return this.$store.getters['brokerageNote/isLoading'];
      },
    },
    methods: {
      loadBrokerageNoteToEditOperation(brokerageNoteId, operationId) {
        const hasBrokerageNotes = this.$store.getters['brokerageNote/hasBrokerageNotes'];

        if (!hasBrokerageNotes) return;

        this.brokerageNote = this.$store.getters['brokerageNote/getById'](brokerageNoteId);
        const operation = this.brokerageNote.operations.find((operation) => operation.id === operationId);
        this.operationLine = operation.line;
      },
    }
  }
</script>