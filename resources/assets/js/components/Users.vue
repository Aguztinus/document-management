<template>
  <div class="container">
    <div class="row" v-if="$gate.isAdmin()">
      <div class="col-md-12">
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Master Users</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item">
                    <router-link to="/dashboard">Home</router-link>
                  </li>
                  <li class="breadcrumb-item active">Users</li>
                </ol>
              </div>
            </div>
          </div>
          <!-- /.container-fluid -->
        </section>

        <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">
              <!-- <i class="fas fa-building"></i> Units Table -->
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
                ref="vuetableUser"
                api-url="api/user"
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
            <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update User's Info</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="editmode ? updateUser() : createUser()">
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
                <label for="labelEmail">Email Address</label>
                <input
                  v-model="form.email"
                  type="email"
                  name="email"
                  placeholder="Email Address"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('email') }"
                >
                <has-error :form="form" field="email"></has-error>
              </div>

              <div class="form-group">
                <label for="labelBio">Bio</label>
                <textarea
                  v-model="form.bio"
                  name="bio"
                  id="bio"
                  placeholder="Short bio for user (Optional)"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('bio') }"
                ></textarea>
                <has-error :form="form" field="bio"></has-error>
              </div>

              <div class="form-group">
                <label for="labelType">Role</label>
                <select
                  name="type"
                  v-model="form.type"
                  id="type"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('type') }"
                >
                  <option value>Select User Role</option>
                  <option value="admin">Admin</option>
                  <option value="user">Standard User</option>
                  <option value="uploader">Uploader</option>
                </select>
                <has-error :form="form" field="type"></has-error>
              </div>

              <div class="form-group">
                <label for="labelUnit">Unit</label>
                <multiselect
                  v-model="form.units"
                  :options="Unitoptions"
                  :multiple="true"
                  :close-on-select="false"
                  :clear-on-select="false"
                  :preserve-search="true"
                  placeholder="Pick some"
                  label="name"
                  track-by="name"
                  :preselect-first="true"
                >
                  <template slot="selection" slot-scope="{ values, search, isOpen }">
                    <span
                      class="multiselect__single"
                      v-if="values.length &amp;&amp; !isOpen"
                    >{{ values.length }} options selected</span>
                  </template>
                </multiselect>

                <has-error :form="form" field="units"></has-error>
                <input v-model="form.unit_id" type="hidden" name="unit_id">
              </div>

              <div class="form-group">
                <label for="labelPassword">Password</label>
                <input
                  v-model="form.password"
                  type="password"
                  name="password"
                  id="password"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('password') }"
                >
                <has-error :form="form" field="password"></has-error>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button
                v-show="editmode"
                v-bind:class="{ disabled: isDisabled }"
                type="submit"
                class="btn btn-success"
              >Update</button>
              <button
                v-show="!editmode"
                v-bind:class="{ disabled: isDisabled }"
                type="submit"
                class="btn btn-primary"
              >Create</button>
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
    '<button class="btn btn-success ml-1" @click="onClickEditUser(rowData)"><i class="edit icon"></i></button>',
    '<button class="btn btn-danger ml-1" @click="onClickDeleteUser(rowData)"><i class="delete icon"></i></button>',
    "</div>"
  ].join(""),
  props: {
    rowData: {
      type: Object,
      required: true
    }
  },
  methods: {
    onClickEditUser(data) {
      console.log("Editing data");
      Fire.$emit("Edit", data);
    },
    onClickDeleteUser(data) {
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
          name: "email",
          sortField: "email"
        },
        {
          name: "type",
          sortField: "type"
        },
        {
          name: "created_at",
          title: "Created At",
          sortField: "created_at",
          callback: "formatDate|DD-MM-YYYY"
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
      isDisabled: false,
      visible: false,
      Unitoptions: [],
      users: {},
      units: {},
      form: new Form({
        id: "",
        name: "",
        email: "",
        password: "",
        type: "",
        units: [],
        bio: "",
        photo: ""
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
      this.$refs.vuetableUser.changePage(page);
    },
    onChange() {
      this.form.unit_id = this.form.unit.id;
    },
    getResults(page = 1) {
      this.$Progress.start();
      axios.get("api/user?page=" + page).then(response => {
        this.users = response.data;
      });
      this.$Progress.finish();
    },
    formatDate(value, fmt = "D MMM YYYY h:mm:ss") {
      return value == null
        ? ""
        : moment(value, "YYYY-MM-DD h:mm:ss").format(fmt);
    },
    updateUser() {
      this.$Progress.start();
      // console.log('Editing data');
      this.form
        .put("api/user/" + this.form.id)
        .then(() => {
          // success
          $("#addNew").modal("hide");
          swal("Updated!", "Information has been updated.", "success");
          this.$Progress.finish();
          Fire.$emit("LoadTableUser");
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    editModal(user) {
      this.editmode = true;
      this.form.reset();
      $("#addNew").modal("show");
      this.form.fill(user);
    },
    newModal() {
      this.editmode = false;
      this.form.reset();
      $("#addNew").modal("show");
    },
    deleteMe(id) {
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
            .delete("api/user/" + id)
            .then(() => {
              swal("Deleted!", "Your file has been deleted.", "success");
              Fire.$emit("LoadTableUser");
            })
            .catch(() => {
              swal("Failed!", "There was something wronge.", "warning");
            });
        }
      });
    },
    loadUsers() {
      this.$Progress.start();
      if (this.$gate.isAdminOrAuthor()) {
        axios.get("api/user").then(({ data }) => (this.users = data));
      }
      this.$Progress.finish();
    },
    loadUnits() {
      if (this.$gate.isAdminOrAuthor()) {
        this.$Progress.start();
        axios
          .get("api/allUnit")
          .then(({ data }) => (this.Unitoptions = data))
          .catch(error => {
            if (error.response) {
              console.log(error.response);
            }
            this.$Progress.fail();
          });
        this.$Progress.finish();
      }
    },
    createUser() {
      this.$Progress.start();
      this.isDisabled = true;
      this.form
        .post("api/user")
        .then(() => {
          Fire.$emit("LoadTableUser");
          $("#addNew").modal("hide");

          toast({
            type: "success",
            title: "User Created in successfully"
          });
          this.$Progress.finish();
        })
        .catch(error => {
          if (error.response) {
            console.log(error.response);
          }
          this.$Progress.fail();
        });
      this.isDisabled = false;
    }
  },
  events: {
    "filter-set"(filterText) {
      this.moreParams = {
        filter: filterText
      };
      Vue.nextTick(() => this.$refs.vuetableUser.refresh());
    },
    "filter-reset"() {
      this.moreParams = {};
      Vue.nextTick(() => this.$refs.vuetableUser.refresh());
    }
  },
  created() {
    Fire.$on("LoadTableUser", () => {
      this.$events.fire("filter-reset"); // untuk Reload Table
    });

    Fire.$on("Edit", data => {
      this.editModal(data);
    });

    Fire.$on("Delete", data => {
      this.deleteMe(data);
    });
    this.loadUnits();
  }
};
</script>
