<template>
    <div>
        <h1>Corretoras</h1>
        <broker-listing />
    </div>
</template>

<script>
  import BrokerListing from "@/components/Broker/Listing";
  export default {
    name: "BrokerListingView",
    components: {BrokerListing}
  }
</script>

<style scoped>

</style>