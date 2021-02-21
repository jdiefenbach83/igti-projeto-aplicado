<template>
  <div>
    <h1>Nova nota de corretagem</h1>
    <hr class='mb-3'/>
    <brokerage-notes-add-edit />
  </div>
</template>

<script>
  import BrokerageNotesAddEdit from "@/components/BrokerageNote/AddEdit";

  export default {
    name: "BrokerageNoteAddView",
    components: {BrokerageNotesAddEdit}
  }
</script>

<style scoped>

</style>