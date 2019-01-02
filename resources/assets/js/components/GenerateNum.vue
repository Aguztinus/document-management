<template>
  <div class="container">
    <div class="row" v-if="$gate.isAdminOrUploader()">
      <div class="col-md-12">
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Generate Number</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item">
                    <router-link to="/dashboard">Home</router-link>
                  </li>
                  <li class="breadcrumb-item active">Generate Number</li>
                </ol>
              </div>
            </div>
          </div>
          <!-- /.container-fluid -->
        </section>

        <div class="card">
          <div class="card-header">
            <ul class="nav nav-pills ml-auto p-2">
              <li class="nav-item">
                <a class="nav-link active show" href="#tab_1" data-toggle="tab">Generate Number</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#tab_2" data-toggle="tab">List Number</a>
              </li>
            </ul>
          </div>

          <!-- /.card-header -->
          <div class="tab-content">
            <div class="tab-pane active show" id="tab_1">
              <form @submit.prevent="createNumber()">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="inputName3" class="control-label">Number</label>
                        
                        <input
                          type="text"
                          class="form-control"
                          id="inputName3 "
                          v-model="form.number"
                          placeholder="Auto generate"
                          disabled
                        >
                      </div>
                    </div>
                    <div class="col-md-8">
                      <div class="form-group">
                        <label for="inputdescription" class="control-label">Nama File/Description</label>
                        
                        <input
                          type="text"
                          class="form-control"
                          id="inputdescription"
                          v-model="form.name"
                          placeholder="Description"
                        >
                      </div>
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-8">
                      <label class="control-label">Jenis Document</label>
                      <multiselect
                        v-model="form.selectdoc"
                        :options="doctypes"
                        :custom-label="nameWithId"
                        placeholder="Select one"
                        label="name"
                        track-by="name"
                      ></multiselect>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Create</button>
                  <button class="btn btn-secondary float-right">Reset</button>
                </div>
              </form>
            </div>
            <div class="tab-pane" id="tab_2">
              <h3 class="card-title mt-3 ml-3">
                <!-- <i class="fas fa-building"></i> Units Table -->
                <filter-bar></filter-bar>
              </h3>
              <div class="card-body table-responsive p-0">
                <div :class="[{'vuetable-wrapper ui basic segment': true}, loading]">
                  <vuetable
                    ref="vuetable"
                    api-url="api/documentnum"
                    :fields="fields"
                    :multi-sort="true"
                    :append-params="moreParams"
                    multi-sort-key="ctrl"
                    :http-options="httpOptions"
                    pagination-path
                    @vuetable:pagination-data="onPaginationData"
                    @vuetable:load-error="onLoadError"
                    @vuetable:loading="showLoader"
                    @vuetable:loaded="hideLoader"
                  ></vuetable>
                  <div class="vuetable-pagination ui basic segment grid">
                    <vuetable-pagination-info ref="paginationInfo"></vuetable-pagination-info>
                    <vuetable-pagination
                      ref="pagination"
                      @vuetable-pagination:change-page="onChangePage"
                    ></vuetable-pagination>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>

    <div v-if="!$gate.isAdminOrUploader()">
      <not-found></not-found>
    </div>

    <!-- Modal -->
    <div
      class="modal fade"
      id="addNew"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addNewLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Author's Info</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="updateUnit()">
            <div class="modal-body">
              <div class="form-group">
                <label for="labelName">Name</label>
                <input
                  v-model="form.name"
                  type="text"
                  name="name"
                  placeholder="Name"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('name') }"
                >
                <has-error :form="form" field="name"></has-error>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import FilterBar from "./vuetable/FilterBar";
Vue.component("filter-bar", FilterBar);

Vue.component("custom-actions-simple", {
  template: [
    "<div class='d-flex flex-row'>",
    '<button class="btn btn-success ml-1" @click="onClickEdit(rowData)"><i class="edit icon"></i></button>',
    '<button class="btn btn-danger ml-1" @click="onClickDelete(rowData)"><i class="delete icon"></i></button>',
    "</div>"
  ].join(""),
  props: {
    rowData: {
      type: Object,
      required: true
    }
  },
  methods: {
    onClickEdit(data) {
      Fire.$emit("Edit", data);
    },
    onClickDelete(data) {
      Fire.$emit("Delete", data.id);
    }
  }
});

export default {
  data() {
    return {
      fields: [
        {
          name: "number",
          sortField: "number"
        },
        {
          name: "name",
          sortField: "name"
        },
        {
          name: "used",
          sortField: "used",
          title: "Use",
          titleClass: "center aligned",
          dataClass: "center aligned",
          callback: "usedLabel",
          width: "100px"
        },
        {
          name: "__component:custom-actions-simple",
          title: "Actions",
          titleClass: "center aligned",
          dataClass: "center aligned",
          width: "150px"
        }
      ],
      moreParams: {},
      loading: "",
      editmode: false,
      visible: false,
      doctypes: [],
      form: new Form({
        id: "",
        name: "",
        number: "",
        selectdoc: []
      })
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
    showLoader() {
      this.loading = "loading";
    },
    hideLoader() {
      this.loading = "";
    },
    usedLabel(value) {
      return value == "1"
        ? '<span class="badge bg-danger"> Used</span>'
        : '<span class="badge bg-primary"> Unused</span>';
    },
    onLoadError(response) {
      if (response.status == 400) {
        sweetAlert("Something's Wrong!", response.data.message, "error");
      } else {
        sweetAlert("Oops", E_SERVER_ERROR, "error");
      }
    },
    onPaginationData(paginationData) {
      this.$refs.pagination.setPaginationData(paginationData);
      this.$refs.paginationInfo.setPaginationData(paginationData);
    },
    onChangePage(page) {
      this.$refs.vuetable.changePage(page);
    },
    nameWithId({ id, name }) {
      return `[${id}] — ${name}`;
    },
    updateUnit() {
      this.$Progress.start();
      // console.log('Editing data');
      this.form
        .put("api/documentnum/" + this.form.id)
        .then(() => {
          // success
          $("#addNew").modal("hide");
          swal("Updated!", "Information has been updated.", "success");
          this.$Progress.finish();
          Fire.$emit("LoadTable");
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    editModal(unit) {
      this.editmode = true;
      this.form.reset();
      $("#addNew").modal("show");
      this.form.fill(unit);
    },
    newModal() {
      this.editmode = false;
      this.form.reset();
      $("#addNew").modal("show");
    },
    deleteUnit(id) {
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
            .delete("api/documentnum/" + id)
            .then(() => {
              swal("Deleted!", "Your author has been deleted.", "success");
              Fire.$emit("LoadTable");
            })
            .catch(() => {
              swal("Failed!", "There was something wronge.", "warning");
            });
        }
      });
    },
    createNumber() {
      this.$Progress.start();
      this.form
        .post("api/documentnum")
        .then(response => {
          if (response.data.success) {
            Fire.$emit("LoadTable");
            this.form.number = response.data.msg;
            swal(
              response.data.msg,
              "Your Number successfuly created ",
              "success"
            );
          } else {
            swal(response.data.msg, "Not successfuly created ", "error");
          }

          this.$Progress.finish();
        })
        .catch(error => {
          if (error.response) {
            console.log(error.response);
          }
          this.$Progress.fail();
        });
    },
    loadDocTypes() {
      this.$Progress.start();
      axios
        .get("api/allDocTypes")
        .then(({ data }) => (this.doctypes = data))
        .catch(error => {
          if (error.response) {
            console.log(error.response);
          }
          this.$Progress.fail();
        });
      this.$Progress.finish();
    }
  },
  events: {
    "filter-set"(filterText) {
      this.moreParams = {
        filter: filterText
      };
      Vue.nextTick(() => this.$refs.vuetable.refresh());
    },
    "filter-reset"() {
      this.moreParams = {};
      Vue.nextTick(() => this.$refs.vuetable.refresh());
    }
  },
  created() {
    this.loadDocTypes();
    Fire.$on("LoadTable", () => {
      this.$refs.vuetable.refresh();
    });

    Fire.$on("Edit", data => {
      this.editModal(data);
    });

    Fire.$on("Delete", data => {
      this.deleteUnit(data);
    });
  }
};
</script>
