<template>
  <div>
    <h1>Editar nota de corretagem</h1>
    <hr class='mb-3'/>
    <BrokerageNotesAddEdit :brokerage_note_id="this.$route.params.id"/>
  </div>
</template>

<script>
  import BrokerageNotesAddEdit from "@/components/BrokerageNote/AddEdit";

  export default {
    name: "BrokerageNoteEdit",
    components: {BrokerageNotesAddEdit},
  }
</script>

<style scoped>

</style>