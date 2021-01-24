<template>
    <div>
        <h1>Corretoras</h1>
        <BrokersListing />
    </div>
</template>

<script>
  import BrokersListing from "@/components/BrokersListing";
  export default {
    name: "Broker",
    components: {BrokersListing}
  }
</script>

<style scoped>

</style>