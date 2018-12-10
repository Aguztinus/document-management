<template>
  <div class="container">
    <div class="row" v-if="$gate.isAdminOrAuthor()">
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
              <!-- <i class="fas fa-building"></i> Units Table -->
              Units Table
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
            <table class="table table-striped">
              <tbody>
                <tr>
                  <th style="width: 10px">ID</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th style="width: 100px">Created At</th>
                  <th style="width: 130px">Modify</th>
                </tr>
                <tr v-for="unit in units.data" :key="unit.id">
                  <td>{{unit.id}}</td>
                  <td>{{unit.name}}</td>
                  <td>{{unit.description}}</td>
                  <td>{{unit.created_at | myDateshort}}</td>
                  <td>
                    <a href="#" class="btn btn-default" @click="editModal(unit)">
                      <i class="fa fa-edit blue"></i>
                    </a>
                    
                    <a href="#" class="btn btn-default" @click="deleteUnit(unit.id)">
                      <i class="fa fa-trash red"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <pagination :data="units" :limit="7" @pagination-change-page="getResults"></pagination>
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>

    <div v-if="!$gate.isAdminOrAuthor()">
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
export default {
  data() {
    return {
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
  methods: {
    getResults(page = 1) {
      this.$Progress.start();
      axios.get("api/unit?page=" + page).then(response => {
        this.units = response.data;
      });
      this.$Progress.finish();
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
          Fire.$emit("AfterCreate");
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
              Fire.$emit("AfterCreate");
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
          Fire.$emit("AfterCreate");
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
  created() {
    Fire.$on("searching", () => {
      let query = this.$parent.search;
      axios
        .get("api/findUnit?q=" + query)
        .then(data => {
          this.units = data.data;
        })
        .catch(() => {});
    });
    this.loadUnits();
    Fire.$on("AfterCreate", () => {
      this.loadUnits();
    });
    //    setInterval(() => this.loadUsers(), 3000);
  }
};
</script>
