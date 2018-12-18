<template>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Upload Documents</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item">
                    <router-link to="/dashboard">Home</router-link>
                  </li>
                  <li class="breadcrumb-item active">Upload Documents</li>
                </ol>
              </div>
            </div>
          </div>
          <!-- /.container-fluid -->
        </section>

        <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">Upload Documents</h3>
          </div>

          <form @submit.prevent="isEdit? updateit() : saveit()">
            <div class="card-body table-responsive">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-6">
                    <vue-clip ref="vc" :options="options" :on-complete="complete">
                      <template slot="clip-uploader-action">
                        <div class="upload-zone text-center">
                          <div class="dz-message">
                            <h4>Click or Drag and Drop files here upload</h4>
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
                                <button
                                  type="button"
                                  class="btn btn-block btn-sm"
                                  @click="removeFile(file)"
                                >
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
                              </div>
                            </div>
                          </li>
                          <!-- /.item -->
                        </ul>
                      </template>
                    </vue-clip>

                    <div class="form-group">
                      <label class="control-label">Document Number</label>
                      <multiselect
                        v-model="form.selectdocnum"
                        :options="docnum"
                        :custom-label="nameWithId"
                        placeholder="Select one"
                        label="name"
                        track-by="name"
                      ></multiselect>
                      <has-error :form="form" field="selectdocnum"></has-error>
                    </div>
                    <div class="form-group">
                      <label class="control-label">Author</label>
                      <multiselect
                        v-model="form.author"
                        :options="authorlist"
                        :custom-label="nameAuthor"
                        placeholder="Select one"
                        label="name"
                        track-by="name"
                      ></multiselect>
                      <has-error :form="form" field="author"></has-error>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>File Name</label>
                      <input type="text" class="form-control" v-model="form.name" disabled>
                      <input type="hidden" class="form-control" v-model="form.id">
                    </div>
                    <div class="form-group">
                      <label for="labelDescription">Description</label>
                      <textarea
                        v-model="form.description"
                        name="description"
                        id="description"
                        placeholder="Short description for document (Optional)"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.has('description') }"
                      ></textarea>
                      <has-error :form="form" field="description"></has-error>
                    </div>
                    <div class="form-group">
                      <label for="labelUnit">Document Type</label>
                      <select
                        name="documentType"
                        v-model="form.docTypes"
                        id="documentType"
                        @change="onChangeDoc()"
                        class="form-control"
                        style="width: 100%;"
                        tabindex="-1"
                        aria-hidden="true"
                        :class="{ 'is-invalid': form.errors.has('documentType') }"
                      >
                        <option
                          v-for="doct in docTypes"
                          :key="doct.id"
                          :value="doct"
                        >{{ doct.name }}</option>
                      </select>
                      <has-error :form="form" field="documentType"></has-error>
                      <input v-model="form.docType_id" type="hidden" name="docType_id">
                    </div>
                    <div class="form-group">
                      <label class="typo__label" for="ajax">Document Refrence</label>
                      <multiselect
                        v-model="selectedDocuments"
                        id="ajax"
                        label="name"
                        track-by="code"
                        placeholder="Type to search"
                        open-direction="bottom"
                        :options="Multidocuments"
                        :multiple="true"
                        :searchable="true"
                        :loading="isLoading"
                        :internal-search="false"
                        :clear-on-select="false"
                        :close-on-select="false"
                        :options-limit="50"
                        :limit="5"
                        :limit-text="limitText"
                        :max-height="600"
                        :show-no-results="false"
                        :hide-selected="true"
                        @search-change="asyncFind"
                      >
                        <template slot="tag" slot-scope="{ option, remove }">
                          <span class="custom__tag">
                            <span>{{ option.name }}</span>
                            <span class="custom__remove" @click="remove(option)">❌</span>
                          </span>
                        </template>
                        <template slot="clear" slot-scope="props">
                          <div
                            class="multiselect__clear"
                            v-if="selectedDocuments.length"
                            @mousedown.prevent.stop="clearAll(props.search)"
                          ></div>
                        </template>
                        <span
                          slot="noResult"
                        >Oops! No elements found. Consider changing the search query.</span>
                      </multiselect>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Save changes</button>
              <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Reset</button>
            </div>
          </form>
        </div>
      </div>
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
      isEdit: false,
      isMine: false,
      judul: "Upload File",
      selectedDocuments: [],
      Multidocuments: [],
      docTypes: {},
      tampfile: {},
      isLoading: false,
      docnum: [],
      authorlist: [],
      form: new Form({
        id: 0,
        name: "",
        description: "",
        isUploadNew: 0,
        docType_id: 0,
        uploadDoc: [],
        refDoc: [],
        selectdocnum: [],
        author: []
      }),
      options: {
        url: "api/upload",
        paramName: "file",
        acceptedFiles: {
          extensions: ["application/pdf"],
          message: "You are uploading an invalid file"
        },
        headers: window.axios.defaults.headers.common,
        maxFilesize: {
          limit: 10,
          message: "file Size is greater than the 15mb"
        },
        maxFiles: {
          limit: 1,
          message: "You can only upload a max of 1 files"
        }
      }
    };
  },
  methods: {
    loadDocNum() {
      axios
        .get("api/allDocNum")
        .then(({ data }) => (this.docnum = data))
        .catch(error => {
          if (error.response) {
            console.log(error.response);
          }
          this.$Progress.fail();
        });
    },
    loadAuthor() {
      axios
        .get("api/allAuthor")
        .then(({ data }) => (this.authorlist = data))
        .catch(error => {
          if (error.response) {
            console.log(error.response);
          }
          this.$Progress.fail();
        });
    },
    nameWithId({ number, name }) {
      return `[${number}] — ${name}`;
    },
    nameAuthor({ id, name }) {
      return `[${id}] — ${name}`;
    },
    onChangeDoc() {
      //untuk change dropdown document type
      this.form.docType_id = this.form.docTypes.id;
    },
    saveit() {
      this.form.refDoc = this.selectedDocuments;
      //console.log(this.form.refDoc);
      this.$Progress.start();
      this.form
        .post("api/document")
        .then(() => {
          this.clearAll();
          this.claerFile();
          this.$Progress.finish();

          swal({
            title: "Success Upload File!",
            text: "You won't be able to revert this!",
            type: "success",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Upload Again?",
            confirmButtonText: "List Document"
          }).then(result => {
            // Send request to the server
            if (result.value) {
              this.$router.push("documentlist");
            }
          });
        })
        .catch(error => {
          if (error.response) {
            console.log(error.response);
            toast({
              type: "error",
              title: "There was something wrong"
            });
          }
          this.$Progress.fail();
        });
    },
    updateit() {
      this.form.refDoc = this.selectedDocuments;
      this.form.docType_id = this.form.docTypes.id;
      //console.log(this.form.docType_id);
      this.$Progress.start();
      this.form
        .put("api/document/" + this.form.id)
        .then(() => {
          // success
          this.clearAll();
          this.claerFile();
          $("#addNew").modal("hide");
          toast({
            type: "success",
            title: "Document Created in successfully"
          });

          this.$Progress.finish();
        })
        .catch(error => {
          if (error.response) {
            console.log(error.response);
          }
          this.$Progress.fail();
        });
    },
    removeFile(file) {
      swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then(result => {
        // Send request to the server
        if (result.value) {
          axios
            .delete("api/document/" + file.name)
            .then(() => {
              swal("Deleted!", "Your File has been deleted.", "success");
            })
            .catch(() => {});
          this.$refs.vc.removeFile(file);
          var index = this.$refs.vc.files.indexOf(file);
          this.$refs.vc.files.splice(index, 1);
        }
      });
    },
    loadDocTypes() {
      //load dropdown document type
      this.$Progress.start();
      axios
        .get("api/allDocTypes")
        .then(({ data }) => (this.docTypes = data))
        .catch(error => {
          if (error.response) {
            console.log(error.response);
          }
          this.$Progress.fail();
        });
      this.$Progress.finish();
    },
    clearAll() {
      this.selectedDocuments = [];
      this.Multidocuments = [];
      this.form.docTypes = null;
      this.form.docTypes_id = null;
      this.form.reset();
    },
    claerFile() {
      this.$refs.vc.removeFile(this.tampfile);
      this.$refs.vc.files = [];
    },
    limitText(count) {
      return `and ${count} other `;
    },
    asyncFind: _.debounce(function(e) {
      //serach get data di multiselect document
      this.isLoading = true;
      axios
        .get("api/findDoc?q=" + e)
        .then(data => {
          console.log(data.data.data);
          this.Multidocuments = data.data.data;
          this.isLoading = false;
        })
        .catch(() => {});
      this.isLoading = false;
    }, 600),
    complete(file, status, xhr) {
      let filename = file.name;
      this.tampfile = file;
      this.form.uploadDoc.push(Object.assign({}, file));

      if (file.status !== "error") {
        this.form.name = filename;
        let text = "File " + filename + " has been successfully uploaded";
        toast({
          type: "success",
          title: text
        });
      } else {
        this.$refs.vc.removeFile(file);
        var index = this.$refs.vc.files.indexOf(file);
        this.$refs.vc.files.splice(index, 1);
        let errmsg = file.errorMessage;
        if (file.errorMessage.massage) {
          errmsg = file.errorMessage.massage;
        }
        let text =
          "File " + filename + " has been not successfully uploaded, " + errmsg;
        toast({
          type: "error",
          title: text
        });
      }
    }
  },
  created() {
    this.$Progress.start();
    this.loadDocTypes();
    this.loadDocNum();
    this.loadAuthor();
    this.$Progress.finish();
  }
};
</script>

<style lang="scss" scoped>
.custom__tag {
  display: inline-block;
  padding: 3px 12px;
  background: #41b883;
  color: #fff;
  margin-right: 8px;
  margin-bottom: 8px;
  border-radius: 10px;
  cursor: pointer;
}

.custom__remove {
  padding: 0;
  font-size: 10px;
  margin-left: 5px;
}

.upload-zone {
  border-style: dashed;
  border-width: 2px;
  border-color: #ccc;
  color: #ccc;
  margin: 20px 30px;
  padding: 20px 10px 10px 10px;
}
</style>

