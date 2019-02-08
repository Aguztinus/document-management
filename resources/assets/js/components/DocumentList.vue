<template>
  <div class="row">
    <div class="col-md-12">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>All Documents</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                  <router-link to="/dashboard">Home</router-link>
                </li>
                <li class="breadcrumb-item active">Documents</li>
              </ol>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->
      </section>

      <filter-bar-doc></filter-bar-doc>
      <div class="card card-primary card-outline">
        <div class="card-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-md-4"></div>
              <!-- Tombol Header -->
              <div class="col-md-8">
                <div v-show="!visible">
                  <div class="btn-group float-sm-right" v-if="$gate.isAdminOrUploader()">
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
                    </div>
                  </div>
                  <!-- <button
                    type="button"
                    @click="clikEmail"
                    class="btn btn-secondary float-sm-right mr-2"
                  >
                    <i class="fas fa-envelope"></i> Email
                  </button>-->
                </div>

                <div v-show="visible">
                  <button
                    type="button"
                    class="btn btn-secondary float-sm-right mr-2"
                    @click="closeside"
                  >
                    <i class="fa fa-times"></i>
                    Cancel
                  </button>
                  
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
                    class="btn btn-danger float-sm-right mr-2"
                    @click="Delete"
                  >
                    <i class="fas fa-trash"></i>
                    Delete
                  </button>

                  <div class="btn-group float-sm-right mr-2">
                    <button type="button" class="btn btn-success">
                      <i class="nav-icon fas fa-cogs"></i> More Action
                    </button>
                    <button
                      type="button"
                      class="btn btn-success dropdown-toggle"
                      data-toggle="dropdown"
                    >
                      <span class="caret"></span>
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" role="menu">
                      <a class="dropdown-item" @click="Edit" v-show="isMine">
                        <i class="fas fa-edit"></i> Edit
                      </a>
                      <a class="dropdown-item" :href="geturl()" download>
                        <i class="fas fa-arrow-circle-down"></i> Download
                      </a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" @click="SeeDetail">
                        <i class="fas fa-eye"></i> View Documment
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <div :class="[{'vuetable-wrapper ui basic segment': true}, loading]">
            <div class="vuetable-pagination ui basic segment grid">
              <vuetable-pagination-info ref="paginationInfoTop"></vuetable-pagination-info>
              <vuetable-pagination
                ref="paginationTop"
                @vuetable-pagination:change-page="onChangePage"
              ></vuetable-pagination>
            </div>
            <vuetable
              ref="vuetable"
              api-url="api/document"
              :fields="fields"
              :multi-sort="true"
              :append-params="moreParams"
              multi-sort-key="ctrl"
              :http-options="httpOptions"
              :row-class="rowClassCB"
              pagination-path
              @vuetable:pagination-data="onPaginationData"
              @vuetable:cell-clicked="onCellClicked"
              @vuetable:load-error="onLoadError"
              @vuetable:loading="showLoader"
              @vuetable:loaded="hideLoader"
            ></vuetable>
            <div class="vuetable-pagination ui basic segment grid">
              <vuetable-pagination-info ref="paginationInfo"></vuetable-pagination-info>
              <vuetable-pagination ref="pagination" @vuetable-pagination:change-page="onChangePage"></vuetable-pagination>
            </div>
          </div>
        </div>
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
            <h5 class="modal-title" id="addNewLabel">{{ judul }}</h5>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <DocUpload
            :isEdit="isEdit"
            :isMine="isMine"
            :doc="detail"
            :isUploadNew="isUploadNew"
            :judul="judul"
            :isPopup="true"
          ></DocUpload>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="Email"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addNewLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg modal-dialog-centered" role="upload">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addNewLabel">Send Email</h5>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <SendEmail></SendEmail>
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
              <DocumentDetail :Mydetail="detail"></DocumentDetail>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="seeDoc"
      tabindex="-1"
      role="dialog"
      aria-labelledby="moreDetaillabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg modal-dialog-centered" role="upload">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addNewLabel">Document</h5>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="container-fluid">
              <div class="row">
                <div class="col-md-12">
                  <PreviewDoc :jenis="detail.file_ext" :url="geturl()"></PreviewDoc>
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
import DocItem from "./documents/DocumentItem";
import PreviewDoc from "./documents/DocumentPreview";
import DocUpload from "./documents/DocumentUpload";
import FilterBarDoc from "./documents/FilterBarDoc";
import DocumentDetail from "./documents/DocumentDetail";
import SendEmail from "./emails/SendEmail";

Vue.component("custom-actions", {
  template: [
    "<div class='d-flex flex-row'>",
    '<button class="btn btn-info " @click="onClickDetail(rowData)"><i class="zoom icon"></i></button>',
    "</div>"
  ].join(""),
  props: {
    rowData: {
      type: Object,
      required: true
    }
  },
  methods: {
    onClickDetail(data) {
      Fire.$emit("Detail", data);
    }
  }
});

export default {
  mounted() {
    console.log("Tes Component mounted.");
  },
  components: {
    DocItem,
    PreviewDoc,
    FilterBarDoc,
    DocUpload,
    SendEmail,
    DocumentDetail
  },
  data() {
    return {
      isEdit: false,
      isMine: false,
      visible: false,
      judul: "Upload File",
      isUploadNew: 0,
      detail: {},
      cetak: {},
      docTypes: {},
      fields: [
        {
          name: "number",
          sortField: "number"
        },
        {
          name: "name",
          title: "Document Name",
          sortField: "name"
        },
        {
          name: "description",
          sortField: "description"
        },
        {
          name: "userowner.name",
          title: "Made By",
          sortField: "userowner.name"
        },
        {
          name: "documentautor.name",
          title: "Author By",
          sortField: "documentautor.name"
        },
        {
          name: "created_at",
          title: "Created At",
          sortField: "created_at",
          titleClass: "center aligned",
          dataClass: "center aligned",
          callback: "formatDate|DD-MM-YYYY h:mm:ss"
        },
        {
          name: "__component:custom-actions",
          title: "Actions",
          titleClass: "center aligned",
          dataClass: "center aligned",
          width: "35px"
        }
      ],
      moreParams: {},
      loading: "",
      selectdoc: ""
    };
  },
  computed: {
    httpOptions() {
      return {
        headers: window.axios.defaults.headers.common //table props -> :http-options="httpOptions"
      };
    }
  },
  methods: {
    allcap(value) {
      return value.toUpperCase();
    },
    onCellClicked(data, field, event) {
      this.selectdoc = data.id;
      this.isMine = data.is_mine;
      this.visible = true;
      this.isEdit = true;
      this.detail = data;
    },
    rowClassCB(data, index) {
      if (this.selectdoc == data.id) {
        if (this.visible == true) {
          return "highlight";
        } else {
          return index % 2 === 0 ? "odd" : "even";
        }
      } else {
        return index % 2 === 0 ? "odd" : "even";
      }
    },
    showLoader() {
      this.loading = "loading";
    },
    hideLoader() {
      this.loading = "";
    },
    onLoadError(response) {
      if (response.status == 400) {
        sweetAlert("Something's Wrong!", response.data.message, "error");
      } else {
        sweetAlert("Oops", E_SERVER_ERROR, "error");
      }
    },
    genderLabel(value) {
      return value === "M"
        ? '<span class="label label-success"><i class="glyphicon glyphicon-star"></i> Male</span>'
        : '<span class="label label-danger"><i class="glyphicon glyphicon-heart"></i> Female</span>';
    },
    formatNumber(value) {
      return accounting.formatNumber(value, 2);
    },
    onPaginationData(paginationData) {
      this.$refs.paginationTop.setPaginationData(paginationData);
      this.$refs.paginationInfoTop.setPaginationData(paginationData);

      this.$refs.pagination.setPaginationData(paginationData);
      this.$refs.paginationInfo.setPaginationData(paginationData);
    },
    onChangePage(page) {
      this.$refs.vuetable.changePage(page);
    },
    formatDate(value, fmt = "D MMM YYYY h:mm:ss") {
      return value == null
        ? ""
        : moment(value, "YYYY-MM-DD h:mm:ss").format(fmt);
    },
    clikEmail() {
      $("#Email").modal("show");
    },
    geturl() {
      let url =
        "storage/uploads/" + this.cetak.owner_id + "/" + this.cetak.realname;
      //console.log(url);
      return url;
    },
    Upload() {
      this.$Progress.start();
      this.detail = {};
      this.isEdit = false;
      this.isUploadNew = 0;
      this.$Progress.finish();
      $("#addNew").modal("show");
    },
    UploadNew() {
      this.$Progress.start();
      this.isEdit = true;
      this.judul = "Upload New File";
      this.isUploadNew = 1; //bedanya sm edit
      Fire.$emit("LoadForm");
      this.$Progress.finish();
      $("#addNew").modal("show");
    },
    Edit() {
      this.$Progress.start();
      this.isEdit = true;
      this.judul = "Edit File";
      this.isUploadNew = 0;
      Fire.$emit("LoadForm");

      $("#addNew").modal("show");
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
          axios
            .delete("api/deletefile/" + this.selectdoc)
            .then(() => {
              swal("Deleted!", "Your file has been deleted.", "success");
              Fire.$emit("LoadTable");
            })
            .catch(err => {
              swal("Failed!", "There was something wrong." + err, "warning");
            });
        }
      });
    },
    SeeDetail() {
      this.cetak = this.detail;
      $("#seeDoc").modal("show");
    },
    closeModal() {
      $("#addNew").modal("hide");
    },
    closeside() {
      this.visible = false;
    },
    MoreDetail(data) {
      //this.detail = data;
      Fire.$emit("Detaild", data);
      $("#moreDetail").modal("show");
    }
  },
  events: {
    "filter-set"(
      filterName,
      filterDesc,
      filterNo,
      filterMade,
      filterAutor,
      date
    ) {
      this.moreParams = {
        filname: filterName,
        fildesc: filterDesc,
        filNo: filterNo,
        filMade: filterMade,
        filAutor: filterAutor,
        date: date
      };
      Vue.nextTick(() => this.$refs.vuetable.refresh());
    },
    "filter-reset"() {
      this.moreParams = {};
      Vue.nextTick(() => this.$refs.vuetable.refresh());
    }
  },
  created() {
    Fire.$on("LoadTable", () => {
      this.$refs.vuetable.refresh();
    });

    Fire.$on("Edit", data => {
      this.editModal(data);
    });

    Fire.$on("Delete", data => {
      this.deleteUnit(data);
    });

    Fire.$on("Detail", data => {
      this.MoreDetail(data);
    });

    Fire.$on("Close", () => {
      this.closeModal();
    });
  }
};
</script>

<style>
.dokdtl svg {
  text-align: center;
  height: 50%;
  width: 50%;
  margin: auto;
  padding-top: 1rem;
}
.ui.vertical.stripe h3 {
  font-size: 2em;
}
.secondary.pointing.menu .toc.item {
  display: none;
}
.vuetable {
  margin-top: 1em !important;
}
.vuetable-wrapper.ui.basic.segment {
  padding: 0em;
}
.vuetable button.ui.button {
  padding: 0.5em 0.5em;
  font-weight: 400;
}
.vuetable button.ui.button i.icon {
  margin: 0;
}
.vuetable td i.handle-icon:hover {
  cursor: pointer;
}
.vuetable-actions {
  width: 15%;
  padding: 12px 0px;
  text-align: center;
}
.vuetable-pagination {
  background: #f9fafb !important;
}
.vuetable-pagination-info {
  margin-top: auto;
  margin-bottom: auto;
}
.highlight {
  background-color: rgb(255, 255, 154);
}
.vuetable-detail-row {
  height: 200px;
}
.detail-row {
  margin-left: 40px;
}
.expand-transition {
  transition: all 0.5s ease;
}
.expand-enter,
.expand-leave {
  height: 0;
  opacity: 0;
}
tr.odd {
  background-color: #e6f5ff;
}
</style>
