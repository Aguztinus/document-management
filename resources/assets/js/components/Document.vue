<template>
  <div class="container">
    <div class="row">
      <div class="col-md-12" v-bind:class="[scrolled ? ' fixed-header' : '']">
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-md-3">
                <h1>My Documents</h1>
              </div>
              <div class="col-sm-3">
                <div class="btn-group">
                  <button type="button" class="btn btn-default">Sort By</button>
                  <button
                    type="button"
                    class="btn btn-default dropdown-toggle"
                    data-toggle="dropdown"
                    aria-expanded="false"
                  >
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div
                    class="dropdown-menu"
                    role="menu"
                    x-placement="bottom-start"
                    style="position: absolute; transform: translate3d(67px, 38px, 0px); top: 0px; left: 0px; will-change: transform;"
                  >
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div v-show="!visible">
                  <div class="btn-group float-sm-right">
                    <button type="button" class="btn btn-primary">Upload</button>
                    <button
                      type="button"
                      class="btn btn-primary dropdown-toggle"
                      data-toggle="dropdown"
                      aria-expanded="false"
                    >
                      <span class="caret"></span>
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div
                      class="dropdown-menu"
                      role="menu"
                      x-placement="bottom-start"
                      style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(67px, 38px, 0px);"
                    >
                      <a class="dropdown-item" @click="Upload">File</a>
                      <a class="dropdown-item" href="#">Foleder</a>
                    </div>
                  </div>
                </div>

                <div v-show="visible">
                  <button type="button" class="btn btn-primary float-sm-right mr-2">
                    <i class="fas fa-file"></i> Upload New Version
                  </button>
                  <button type="button" class="btn btn-secondary float-sm-right mr-2">
                    <i class="fas fa-arrow-circle-down"></i> Download
                  </button>
                  <button type="button" class="btn btn-secondary float-sm-right mr-2">
                    <i class="fas fa-trash"></i> Delete
                  </button>
                  <button type="button" class="btn btn-secondary float-sm-right mr-2">
                    <i class="fas fa-envelope"></i> Email
                  </button>
                </div>
              </div>
            </div>
          </div>
          <!-- /.container-fluid -->
        </section>
        <hr v-show="!scrolled">
      </div>

      <div class="dok" v-bind:class="[visible ? ' col-md-9' : ' col-md-12']">
        <div class="card" v-for="doc in documents.data" :key="doc.id" v-on:click="clikfile(doc)">
          <svg
            version="1.1"
            preserveAspectRatio="xMidYMid meet"
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink"
            viewBox="0 0 32 32"
            width="32"
            height="32"
            enable-background="new 0 0 32 32"
            xml:space="preserve"
            role="img"
          >
            <path fill="#fff" d="M25 27H7V5h13l5 5v17"></path>
            <path
              d="M20 4H7a1 1 0 0 0-1 1v22a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V9.89zm5 23H7V5h13v4a1 1 0 0 0 1 1h4z"
              fill="#e33d55"
            ></path>
            <path
              d="M21.53 20.17a2 2 0 0 0-1.76-.93 10 10 0 0 0-1.54.11 4.77 4.77 0 0 0 3.13.81 1 1 0 0 1-.84.86 2.4 2.4 0 0 1-1.52-.21 17.59 17.59 0 0 1-1.73-1 .55.55 0 0 0-.54-.09l-2.58.93a.85.85 0 0 0-.37.3c-1.29 1.95-2.44 3-2.93 3.06s-1.13-.18-.86-1.37 1.23-1.49 2.29-1.74c-1.08.8-1.5 1.42-1.41 2.1a.83.83 0 0 0 .13-.14 24.24 24.24 0 0 0 3.8-5.45.4.4 0 0 0 0-.41 6.94 6.94 0 0 1-.9-3.66c.26-2.53 2.55-1.55 2 .38a1.87 1.87 0 0 0-.51-1.1c-.29-.22-.54-.12-.59.23a5.77 5.77 0 0 0 .51 3.35 5.46 5.46 0 0 0 .46-2.2.9.9 0 0 1 .52.62 2.8 2.8 0 0 1-.08 1.62c-.08.28-.2.56-.28.84a.34.34 0 0 0 0 .27c.37.42.75.82 1.15 1.22a.36.36 0 0 0 .3 0 7.22 7.22 0 0 1 2.11-.44 1.82 1.82 0 0 1 2 1.62zm-6-2.12l-1 1.84 1.9-.88z"
              fill="#e33d55"
            ></path>
          </svg>
          <div class="card-body">
            <div class="filename">
              <a href="#">{{doc.name}}</a>
            </div>
            <div class="filedate">
              <small class="text-muted">{{doc.created_at | myDateshort}}, by {{doc.userowner.name}}</small>
            </div>
            <div class="filesize">
              <small class="text-muted">{{doc.size}}</small>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <pagination :data="documents" :limit="4" @pagination-change-page="getResults"></pagination>
        </div>
      </div>

      <div class="col-md-3 position-fixed side-detail" v-show="visible">
        <h4 class="d-flex justify-content-between align-items-center">
          <span class="text-muted detail-head">Detail File</span>
          <button type="button" class="btn btn-tool" data-widget="remove" v-on:click="closeside">
            <i class="fa fa-times"></i>
          </button>
        </h4>
        <ul class="list-group mb-3">
          <li class="list-group-item d-flex justify-content-between lh-condensed">
            <div>
              <h6 class="my-0">File name</h6>
              <small class="text-muted">{{ detail.name }}</small>
            </div>
          </li>
          <li class="list-group-item d-flex justify-content-between lh-condensed">
            <div>
              <h6 class="my-0">File Description</h6>
              <p>
                <small class="text-muted">{{ detail.description }}</small>
              </p>
            </div>
          </li>
          <li class="list-group-item d-flex justify-content-between lh-condensed">
            <div>
              <h6 class="my-0">Owner</h6>
              <p>
                <small class="text-muted">{{ userowner }}</small>
              </p>
              <h6 class="my-0">Created</h6>
              <p>
                <small class="text-muted">{{ detail.created_at }}</small>
              </p>
              <h6 class="my-0">Modified</h6>
              <p>
                <small class="text-muted">{{ detail.updated_at }}</small>
              </p>
              <h6 class="my-0">Size</h6>
              <p>
                <small class="text-muted">{{ detail.size }}</small>
              </p>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div
      class="modal fade"
      id="addNew"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addNewLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg modal-dialog-centered" role="upload">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addNewLabel">Upload</h5>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="saveit()">
            <div class="modal-body">
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
                                {{ file.progress }}
                              </div>
                            </div>
                          </li>
                          <!-- /.item -->
                        </ul>
                      </template>
                    </vue-clip>
                  </div>
                  <div class="col-md-6">
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
                        <option selected="selected">Select Document Type</option>
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
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      visible: false,
      documents: {},
      selectedDocuments: [],
      Multidocuments: [],
      isLoading: false,
      detail: {},
      docTypes: {},
      userowner: "",
      scrolled: false,
      form: new Form({
        id: "",
        docType_id: 0,
        uploadDoc: [],
        refDoc: []
      }),
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
    getResults(page = 1) {
      this.$Progress.start();
      axios.get("api/document?page=" + page).then(response => {
        this.documents = response.data;
      });
      this.$Progress.finish();
    },
    Upload() {
      //popup modal upload
      $("#addNew").modal("show");
    },
    clikfile(doc) {
      //click event user click document di list
      this.visible = true;
      this.detail = doc;
      this.userowner = doc.userowner.name;
      console.log(this.userowner);
    },
    closeside() {
      this.visible = false;
    },
    saveit() {
      this.form.refDoc = this.selectedDocuments;
      //console.log(this.form.refDoc);
      this.$Progress.start();
      this.form
        .post("api/document")
        .then(() => {
          this.clearAll();
          this.loadDocs();
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
    loadDocs() {
      //load semua document di list depan
      this.$Progress.start();
      axios.get("api/document").then(({ data }) => (this.documents = data));
      this.$Progress.finish();
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
    onChangeDoc() {
      //untuk change dropdown document type
      this.form.docType_id = this.form.docTypes.id;
    },
    handleScroll() {
      this.scrolled = window.scrollY > 0;
      //console.log(this.scrolled);
    },
    removeFile(file) {
      //remove file di document upload
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
      //complete event document upload
      // Adding server id to be used for deleting
      // the file.
      //file.addAttribute('id', xhr.response.id)
      let filename = file.name;
      this.form.uploadDoc.push(Object.assign({}, file));
      console.log(file);

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
    },
    limitText(count) {
      //limit text di multiselect document
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
    }, 700),
    clearAll() {
      //clear all  multiselect document
      this.$refs.vc.files = [];
      this.selectedDocuments = [];
      this.form.docTypes = null;
      this.form.docTypes_id = null;
    }
  },
  created() {
    this.loadDocs();
    this.loadDocTypes();
    window.addEventListener("scroll", this.handleScroll);
    Fire.$on("searching", () => {
      let query = this.$parent.search;
      axios
        .get("api/searchDoc?q=" + query)
        .then(data => {
          this.documents = data.data;
        })
        .catch(() => {});
    });

    Fire.$on("AfterCreate", () => {
      this.loadDocs();
    });
    //    setInterval(() => this.loadUsers(), 3000);
  },
  destroyed() {
    window.removeEventListener("scroll", this.handleScroll);
  }
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

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

.fixed-header {
  position: fixed;
  top: 0;
  left: 0;
  background-color: #343a40;
  color: rgb(194, 199, 208);
  height: 65px;
  width: 100%;
  z-index: 20;
}
.side-detail {
  top: 135px;
  right: 0;
  padding: 0 40px 0 30px;
}

.upload-zone {
  border-style: dashed;
  border-width: 2px;
  border-color: #ccc;
  color: #ccc;
  margin: 20px 30px;
  padding: 20px 10px 10px 10px;
}

@media (min-width: 1px) and (max-width: 1024px) {
  //CSS
  .fixed-header {
    margin-left: 0;
    width: calc(100%);
  }
}

.content-header {
  padding-bottom: 0;
}
hr {
  margin-left: 15px;
}
h4 {
  .detail-head {
    font-size: 1.05rem;
  }
  .btn {
    padding-right: 0;
  }
}
.dok {
  display: flex;
  flex-wrap: wrap;

  .card {
    margin-left: 1rem;
    width: 30%;
    min-width: 11rem;
    max-width: 14.5rem;

    svg {
      height: 50%;
      opacity: 1;
      -webkit-transition: opacity 0.2s ease-in;
      transition: opacity 0.2s ease-in;
      width: 50%;
      margin: auto;
      padding-top: 1.35rem;
    }

    a {
      color: rgb(71, 71, 71);
    }

    &:hover {
      opacity: 0.7;
      border-color: #0964af;
      transition: all 0.4s ease 0s;
    }
  }
}
</style>