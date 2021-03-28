<template>
  <div>
    <h1>Posições</h1>
    <position-listing />
  </div>
</template>

<script>
  import PositionListing from "@/components/Position/Listing";
  export default {
    name: "PositionListingView",
    components: {PositionListing}
  }
</script>