<template>
  <div>
    <Document></Document>
  </div>
</template>

<script>
import Document from "./Document";

export default {
  mounted() {
    console.log("Component mounted.");
  },
  components: {
    Document
  }
};
</script>
