<template>
  <div>
    <img
      v-if="jenis == 'png' || jenis == 'jpg' || jenis == 'gif'"
      :src="url"
      style="width:100%;min-height:640px;"
    >
    <iframe
      v-else-if="jenis == 'pdf'"
      :src="url"
      frameborder="0"
      style="width:100%;min-height:640px;"
    ></iframe>
    <iframe
      v-else-if="jenis == 'txt' || jenis == 'doc' || jenis == 'docx'"
      :src="getUrl()"
      frameborder="0"
      style="width:100%;min-height:640px;"
    ></iframe>
  </div>
</template>

<script>
export default {
  props: ["jenis", "url"],
  methods: {
    getUrl() {
      let uri =
        "https://docs.google.com/gview?url=" +
        baseUrl +
        this.url +
        "&embedded=true";
      console.log(uri);

      return uri;
    }
  }
};
</script>

<style>
</style>
