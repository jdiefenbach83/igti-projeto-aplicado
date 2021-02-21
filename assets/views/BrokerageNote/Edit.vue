<template>
  <div>
    <h1>Editar nota de corretagem</h1>
    <hr class='mb-3'/>
    <brokerage-note-add-edit :brokerage_note_id="this.$route.params.id"/>
  </div>
</template>

<script>
  import BrokerageNoteAddEdit from "@/components/BrokerageNote/AddEdit";

  export default {
    name: "BrokerageNoteEditView",
    components: {BrokerageNoteAddEdit},
  }
</script>

<style scoped>

</style>