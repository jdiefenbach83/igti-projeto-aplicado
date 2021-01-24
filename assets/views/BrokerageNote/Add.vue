<template>
  <div>
    <h1>Nova nota de corretagem</h1>
    <hr class='mb-3'/>
    <BrokerageNotesAdd />
  </div>
</template>

<script>
  import BrokerageNotesAdd from "../../components/BrokerageNote/Add";

  export default {
    name: "BrokerageNoteAdd",
    components: {BrokerageNotesAdd}
  }
</script>

<style scoped>

</style>