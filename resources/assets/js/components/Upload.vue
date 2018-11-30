<template>
  <div>
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Upload File</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body p-0">
        <vue-clip :options="options">
          <template slot="clip-uploader-action">
            <div class="upload-zone text-center">
              <div class="dz-message">
                <h2>Click or Drag and Drop files here upload</h2>
              </div>
            </div>
          </template>

          <template slot="clip-uploader-body" slot-scope="props">
            <ul
              v-for="file in props.files"
              :key="file.name"
              class="products-list product-list-in-card pl-2 pr-2"
            >
              <li class="item">
                <div class="product-img">
                  <img v-bind:src="file.dataUrl" class="img-size-70">
                </div>
                <div class="product-info">
                  <a href="javascript:void(0)" class="product-title">
                    {{ file.name }}
                    <span class="badge badge-danger float-right">
                      <button type="button" class="btn btn-tool" data-widget="remove">
                        <i class="fa fa-times"></i>
                      </button>
                    </span>
                  </a>
                  <span class="product-description">{{ file.status }}</span>
                  <div class="progress progress-xs">
                    <div
                      class="progress-bar bg-primary progress-bar-striped"
                      role="progressbar"
                      aria-valuenow="20"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      v-bind:style="{width: file.progress + '%'}"
                    ></div>
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
        headers: window.axios.defaults.headers.common
      }
    };
  }
};
</script>

<style lang="scss" scoped>
.upload-zone {
  border-style: dashed;
  border-width: 2px;
  border-color: #000;
  margin: 20px 30px;
  padding: 20px 10px 10px 10px;
}
</style>

