<template>
  <div>
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Upload File</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body p-0 mb-4">
        <vue-clip ref="vc" :options="options" :on-complete="complete">
          <template slot="clip-uploader-action">
            <div class="upload-zone text-center">
              <div class="dz-message">
                <h2>Click or Drag and Drop files here upload</h2>
              </div>
            </div>
          </template>

          <template slot="clip-uploader-body" slot-scope="props" class="ml-4 mr-4">
            <ul
              v-for="file in props.files"
              :key="file.name"
              class="products-list product-list-in-card pl-2 pr-2"
            >
              <li class="item ml-4 mr-4">
                <div class="product-img">
                  <img
                    v-bind:src="file.dataUrl == ''? 'img/file.png' : file.dataUrl"
                    class="img-size-50"
                  >
                </div>
                <div class="product-info">
                  <a href="javascript:void(0)" class="product-title">{{ file.name }}</a>
                  <span class="badge float-right">
                    <button type="button" class="btn btn-block btn-sm" @click="removeFile(file)">
                      <i class="fa fa-times"></i>
                    </button>
                  </span>
                  <span class="product-description">{{ file.status }}</span>
                  <div
                    class="progress progress-xxs"
                    v-if="file.status !== 'error' && file.status !=='success'"
                  >
                    <div
                      class="progress-bar bg-primary progress-bar-striped"
                      role="progressbar"
                      aria-valuenow="10"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      v-bind:style="{width: file.progress + '%'}"
                    ></div>
                    {{ file.progress }}
                  </div>
                </div>
              </li>
              <!-- /.item -->
            </ul>
          </template>
        </vue-clip>
      </div>
      <!-- /.card-body -->
      <!-- /.card-footer -->
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("Upload Component mounted.");
  },
  data() {
    return {
      options: {
        url: "api/upload",
        paramName: "file",
        acceptedFiles: {
          extensions: [
            "image/*",
            "application/pdf",
            "text/plain",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/vnd.ms-excel",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "application/vnd.ms-powerpoint",
            "application/vnd.openxmlformats-officedocument.presentationml.presentation",
            "application/vnd.ms-access",
            "application/x-rar-compressed",
            "application/rtf",
            "application/x-tar",
            "application/zip",
            "application/x-7z-compressed"
          ],
          message: "You are uploading an invalid file"
        },
        headers: window.axios.defaults.headers.common,
        maxFilesize: {
          limit: 5,
          message: "file Size is greater than the 5mb"
        }
      }
    };
  },
  methods: {
    removeFile(file) {
      console.log(file.name);
      axios
        .delete("api/document/" + file.name)
        .then(() => {
          swal("Deleted!", "Your File has been deleted.", "success");
        })
        .catch(() => {});
      this.$refs.vc.removeFile(file);
      var index = this.$refs.vc.files.indexOf(file);
      this.$refs.vc.files.splice(index, 1);
    },
    complete(file, status, xhr) {
      // Adding server id to be used for deleting
      // the file.
      //file.addAttribute('id', xhr.response.id)
      let filename = file.name;
      //console.log(file.dataUrl);
      if (file.status !== "error") {
        let text = "File " + filename + " has been successfully uploaded";
        toast({
          type: "success",
          title: text
        });
      } else {
        let text =
          "File " +
          filename +
          " has been not successfully uploaded, " +
          file.errorMessage;
        toast({
          type: "error",
          title: text
        });
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.upload-zone {
  border-style: dashed;
  border-width: 2px;
  border-color: #ccc;
  color: #ccc;
  margin: 20px 30px;
  padding: 20px 10px 10px 10px;
}
</style>

