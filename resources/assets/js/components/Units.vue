<template>
  <div class="container">
    <div class="row" v-if="$gate.isAdmin()">
      <div class="col-md-12">
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Master Units</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item">
                    <router-link to="/dashboard">Home</router-link>
                  </li>
                  <li class="breadcrumb-item active">Units</li>
                </ol>
              </div>
            </div>
          </div>
          <!-- /.container-fluid -->
        </section>

        <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">
              <filter-bar></filter-bar>
            </h3>

            <div class="card-tools">
              <button class="btn btn-success" @click="newModal">
                Add New
                <i class="fas fa-plus-circle fa-fw"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <div :class="[{'vuetable-wrapper ui basic segment': true}, loading]">
              <vuetable
                ref="vuetable"
                api-url="api/unit"
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
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>

    <div v-if="!$gate.isAdmin()">
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
            <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
            <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Unit's Info</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="editmode ? updateUnit() : createUnit()">
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

              <div class="form-group">
                <label for="labelDescription">Description</label>
                <textarea
                  v-model="form.description"
                  name="text"
                  id="description"
                  placeholder="Description (Optional)"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('description') }"
                ></textarea>

                <has-error :form="form" field="description"></has-error>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
              <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
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
          name: "name",
          sortField: "name"
        },
        {
          name: "description",
          sortField: "description"
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
      units: {},
      form: new Form({
        id: "",
        name: "",
        description: ""
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
    updateUnit() {
      this.$Progress.start();
      // console.log('Editing data');
      this.form
        .put("api/unit/" + this.form.id)
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
            .delete("api/unit/" + id)
            .then(() => {
              swal("Deleted!", "Your unit has been deleted.", "success");
              Fire.$emit("LoadTable");
            })
            .catch(() => {
              swal("Failed!", "There was something wronge.", "warning");
            });
        }
      });
    },
    loadUnits() {
      this.$Progress.start();
      if (this.$gate.isAdminOrAuthor()) {
        axios.get("api/unit").then(({ data }) => (this.units = data));
      }
      this.$Progress.finish();
    },

    createUnit() {
      this.$Progress.start();
      this.form
        .post("api/unit")
        .then(() => {
          Fire.$emit("LoadTable");
          $("#addNew").modal("hide");

          toast({
            type: "success",
            title: "Unit Created in successfully"
          });
          this.$Progress.finish();
        })
        .catch(error => {
          if (error.response) {
            console.log(error.response);
          }
          this.$Progress.fail();
        });
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
    Fire.$on("LoadTable", () => {
      this.$events.fire("filter-reset");
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
