<template>
    <div>
        <h1>Ativos</h1>
        <asset-listing />
    </div>
</template>

<script>
  import AssetListing from "@/components/Asset/Listing";
  export default {
    name: "AssetListingView",
    components: {AssetListing}
  }
</script>

<style scoped>

</style>