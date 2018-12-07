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
              <div class="col-sm-3 d-flex flex-row">
                <div class="btn-group mr-2">
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
                    <a class="dropdown-item" @click="sortResults('name')">Name</a>
                    <a class="dropdown-item" @click="sortResults('created_at')">Created</a>
                    <a class="dropdown-item" @click="sortResults('size_int')">Size</a>
                  </div>
                </div>
                <div class="btn-group">
                  <button type="button" class="btn btn-default">Filter By</button>
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
                    <div v-for="doct in docTypes" :key="doct.id" :value="doct">
                      <a class="dropdown-item" @click="filterResults(doct.id)">{{ doct.name }}</a>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Tombol Header -->
              <div class="col-md-6">
                <div v-show="!visible">
                  <div class="btn-group float-sm-right">
                    <button type="button" class="btn btn-primary" @click="Upload">Upload</button>
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
                      <a class="dropdown-item" href="#">Folder</a>
                    </div>
                  </div>
                  <button type="button" class="btn btn-secondary float-sm-right mr-2">
                    <i class="fas fa-envelope"></i> Email
                  </button>
                </div>

                <div v-show="visible">
                  <button
                    type="button"
                    class="btn btn-primary float-sm-right mr-2"
                    @click="UploadNew"
                    v-show="isMine"
                  >
                    <i class="fas fa-file"></i> Upload New Version
                  </button>
                  <button
                    type="button"
                    v-show="isMine"
                    class="btn btn-primary float-sm-right mr-2"
                    @click="Edit"
                  >
                    <i class="fas fa-edit"></i> Edit
                  </button>
                  
                  <a class="btn btn-secondary float-sm-right mr-2" v-bind:href="geturl()" download>
                    <i class="fas fa-arrow-circle-down"></i> download
                  </a>
                  <button
                    type="button"
                    v-show="isMine"
                    class="btn btn-danger float-sm-right mr-2"
                    @click="Delete"
                  >
                    <i class="fas fa-trash"></i>
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
          <!-- /.container-fluid -->
        </section>
        <hr v-show="!scrolled">
      </div>
      <!-- List Document -->
      <div class="dok" v-bind:class="[visible ? ' col-md-9' : ' col-md-12']">
        <div class="card" v-for="doc in documents.data" :key="doc.id" v-on:click="clikfile(doc)">
          <DocItem :doc="doc"></DocItem>
        </div>
        <div class="col-md-12">
          <pagination :data="documents" :limit="4" @pagination-change-page="getResults"></pagination>
        </div>
      </div>
      <!-- Detail samping -->
      <transition name="fade">
        <div
          class="col-md-3 position-fixed"
          v-show="visible"
          :class="[scrolled ? ' side-detail-scroll' : ' side-detail']"
        >
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
              </div>
            </li>

            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Size</h6>
                <p>
                  <small class="text-muted">{{ detail.size }}</small>
                </p>
              </div>
              <div class="float-right">
                <button
                  class="btn-plain"
                  v-tooltip="'More Option'"
                  aria-label="More Options"
                  @click="MoreDetail"
                >
                  <svg
                    version="1.1"
                    class="ellipsis-svg"
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 16 4"
                    width="16"
                    height="4"
                    enable-background="new 0 0 16 4"
                    xml:space="preserve"
                    role="img"
                  >
                    <circle cx="2" cy="2" r="2"></circle>
                    <circle cx="8" cy="2" r="2"></circle>
                    <circle cx="14" cy="2" r="2"></circle>
                  </svg>
                </button>
              </div>
            </li>
          </ul>
        </div>
      </transition>
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
            <h5 class="modal-title" id="addNewLabel">{{ judul }}</h5>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="isEdit? updateit() : saveit()">
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
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="moreDetail"
      tabindex="-1"
      role="dialog"
      aria-labelledby="moreDetaillabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg modal-dialog-centered" role="upload">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addNewLabel">More Detail</h5>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="container-fluid">
              <div class="row">
                <div class="col-md-12">
                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h3 class="card-title">Document Refrence</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                      <div class="doksmall mt-3">
                        <div
                          class="card"
                          v-for="doc in documentsref.data"
                          :key="doc.id"
                          v-on:click="clikfile(doc)"
                        >
                          <DocItem :doc="doc"></DocItem>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h3 class="card-title">Document History</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">Tes History</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { directive as onClickaway } from "vue-clickaway";
import DocItem from "./documents/DocumentItem";

export default {
  directives: {
    onClickaway: onClickaway
  },
  components: {
    DocItem
  },
  data() {
    return {
      isEdit: false,
      isMine: false,
      judul: "Upload File",
      visible: false,
      documents: {},
      documentsref: {},
      documentshis: {},
      selectedDocuments: [],
      Multidocuments: [],
      isLoading: false,
      detail: {},
      docTypes: {},
      userowner: "",
      scrolled: false,
      form: new Form({
        id: 0,
        name: "",
        description: "",
        isUploadNew: 0,
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
        },
        maxFiles: {
          limit: 1,
          message: "You can only upload a max of 1 files"
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
    sortResults(qry = "name") {
      this.$Progress.start();
      axios.get("api/sortDoc?sort=" + qry).then(response => {
        this.documents = response.data;
      });
      this.$Progress.finish();
    },
    filterResults(filter) {
      this.$Progress.start();
      axios.get("api/filterDoc?filter=" + filter).then(response => {
        this.documents = response.data;
      });
      this.$Progress.finish();
    },
    Upload() {
      //popup modal upload
      this.clearAll();
      this.judul = "Upload File";
      this.isEdit = false;
      this.form.isUploadNew = 0;
      $("#addNew").modal("show");
    },
    UploadNew() {
      this.isEdit = true;
      this.judul = "Upload New File";
      this.form.isUploadNew = 1; //bedanya sm edit
      this.$Progress.start();
      axios.get("api/getdocumentref/" + this.form.id).then(response => {
        //console.log(response.data);
        this.selectedDocuments = response.data;
      });
      this.$Progress.finish();
      $("#addNew").modal("show");
    },
    MoreDetail() {
      axios.get("api/getref/" + this.form.id).then(response => {
        //console.log(response.data);
        this.documentsref = response.data;
      });
      $("#moreDetail").modal("show");
    },
    Edit() {
      this.isEdit = true;
      this.judul = "Edit File";
      this.form.isUploadNew = 0;
      //this.form.reset();
      this.$Progress.start();
      axios.get("api/getdocumentref/" + this.form.id).then(response => {
        //console.log(response.data);
        this.selectedDocuments = response.data;
      });
      this.$Progress.finish();
      $("#addNew").modal("show");
    },
    clikfile(doc) {
      //click event user click document di list
      this.form.reset();
      this.isMine = doc.is_mine;
      this.visible = true;
      this.detail = doc;
      this.form.id = doc.id;
      this.form.name = doc.name;
      this.form.description = doc.description;
      this.form.docTypes = doc.documenttype;
      this.userowner = doc.userowner.name;
      console.log(doc.id);
    },
    away: function() {
      console.log("clicked away");
      this.visible = false;
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

      this.$Progress.start();
      this.form
        .put("api/document/" + this.form.id)
        .then(() => {
          // success
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
    geturl() {
      let url =
        "storage/uploads/" + this.detail.owner_id + "/" + this.detail.name;
      return url;
    },
    Delete() {
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
          this.form
            .delete("api/deletefile/" + this.form.id)
            .then(() => {
              swal("Deleted!", "Your file has been deleted.", "success");
              this.clearAll();
              this.loadDocs();
            })
            .catch(() => {
              swal("Failed!", "There was something wrong.", "warning");
            });
        }
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
      this.form.reset();
    }
  },
  created() {
    this.loadDocs();
    this.loadDocTypes();
    window.addEventListener("scroll", this.handleScroll);
    Fire.$on("searching", () => {
      let query = this.$parent.search;
      axios
        .get("api/findDochome?q=" + query)
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
  //color: rgb(194, 199, 208);
  color: #343a40;
  height: 65px;
  width: 100%;
  z-index: 20;
  opacity: 0.9;
}
.side-detail {
  top: 135px;
  right: 0;
  padding: 0 40px 0 30px;
}

.side-detail-scroll {
  top: 75px;
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
.btn-plain {
  background: 0 0;
  box-shadow: none;
}
.btn-plain,
.btn-plain:active,
.btn-plain:hover {
  cursor: pointer;
  margin: 0;
  padding: 0;
  border: none;
  font-weight: 400;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>