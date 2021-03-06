<template>
  <div>
    <h1>Nova nota de corretagem</h1>
    <hr class='mb-3'/>
    <brokerage-note-add-edit />
  </div>
</template>

<script>
  import BrokerageNoteAddEdit from "@/components/BrokerageNote/AddEdit";

  export default {
    name: "BrokerageNoteAddView",
    components: {BrokerageNoteAddEdit}
  }
</script>

<style scoped>

</style>