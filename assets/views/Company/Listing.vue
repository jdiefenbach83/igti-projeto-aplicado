<template>
    <div>
        <h1>Empresas</h1>
        <company-listing />
    </div>
</template>

<script>
  import CompanyListing from "@/components/Company/Listing";
  export default {
    name: "CompanyListingView",
    components: {CompanyListing}
  }
</script>

<style scoped>

</style>