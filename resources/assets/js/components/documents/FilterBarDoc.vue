<template>
  <div class="card card-default collapsed-card">
    <div class="card-header">
      <h3 class="card-title">Search</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-widget="collapse">
          <i class="fa fa-plus"></i>
        </button>
      </div>
      <!-- /.card-tools -->
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="inputName3" class="control-label">Document Name</label>
            
            <input
              type="text"
              class="form-control"
              id="inputName3 "
              v-model="filterName"
              placeholder="Name"
            >
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="inputdescription" class="control-label">Description</label>
            
            <input
              type="text"
              class="form-control"
              id="inputdescription"
              v-model="filterDesc"
              placeholder="Description"
            >
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Created At</label>
            <div class="input-group">
              <flat-pickr
                v-model="date"
                :config="config"
                class="form-control"
                placeholder="Select date"
                name="date"
              ></flat-pickr>
              <div class="input-group-btn">
                <button class="btn btn-default" type="button" title="Toggle" data-toggle>
                  <i class="fa fa-calendar">
                    <span aria-hidden="true" class="sr-only">Toggle</span>
                  </i>
                </button>
                <button class="btn btn-default" type="button" title="Clear" data-clear>
                  <i class="fa fa-times">
                    <span aria-hidden="true" class="sr-only">Clear</span>
                  </i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="inputmade" class="control-label">Unit</label>

            <!-- <input
              type="text"
              class="form-control"
              id="inputmade"
              v-model="filterMade"
              placeholder="Made By"
            >-->
            <multiselect
              v-model="unitsch"
              :options="unitlist"
              placeholder="Select one"
              label="name"
              track-by="name"
              @input="selectunit"
            ></multiselect>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="inputmade" class="control-label">Made By</label>

            <!-- <input
              type="text"
              class="form-control"
              id="inputmade"
              v-model="filterMade"
              placeholder="Made By"
            >-->
            <multiselect
              v-model="madeby"
              :options="madebylist"
              placeholder="Select one"
              label="name"
              track-by="name"
              @input="selectmadeby"
            ></multiselect>
          </div>
        </div>
        <div class="col-md-4">
          <!-- <div class="form-group">
            <label for="inputautor" class="control-label">Autor By</label>
            
            <input
              type="text"
              class="form-control"
              id="inputautor"
              v-model="filterAutor"
              placeholder="Autor By"
            >
          </div>-->
          <div class="form-group">
            <label class="control-label">Author By</label>
            <multiselect
              v-model="author"
              :options="authorlist"
              placeholder="Select one"
              label="name"
              track-by="name"
              @input="selectautor"
            ></multiselect>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="inputNo" class="control-label">Number</label>
            
            <input
              type="text"
              class="form-control"
              id="inputNo"
              v-model="filterNo"
              placeholder="Number"
            >
          </div>
        </div>
      </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
      <button type="submit" class="btn btn-primary" @click.prevent="doFilter">Search</button>
      <button type="submit" class="btn btn-default float-right" @click.prevent="resetFilter">Reset</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      filterName: "",
      filterDesc: "",
      filterNo: "",
      filterMade: "",
      filterAutor: "",
      date: "",
      author: [],
      authorlist: [],
      madeby: [],
      madebylist: [],
      unitsch: [],
      unitlist: [],
      // Get more form https://chmln.github.io/flatpickr/options/
      config: {
        wrap: true, // set wrap to true only when using 'input-group'
        altFormat: "j M Y",
        altInput: true,
        dateFormat: "d-m-Y"
      }
    };
  },
  methods: {
    doFilter() {
      this.$events.fire(
        "filter-set",
        this.filterName,
        this.filterDesc,
        this.filterNo,
        this.filterMade,
        this.filterAutor,
        this.filterUnit,
        this.date
      );
    },
    resetFilter() {
      this.filterName = "";
      this.filterDesc = "";
      this.filterNo = "";
      this.filterMade = "";
      this.filterAutor = "";
      this.date = "";
      this.filterUnit = "";
      this.author = [];
      this.madeby = [];
      this.$events.fire("filter-reset");
    },
    selectautor() {
      //console.log(this.author.name);
      this.filterAutor = this.author.name;
    },
    selectmadeby() {
      //console.log(this.author.name);
      this.filterMade = this.madeby.name;
    },
    selectunit() {
      //console.log(this.author.name);
      this.filterUnit = this.unitsch.name;
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
    loadUser() {
      axios
        .get("api/allUser")
        .then(({ data }) => (this.madebylist = data))
        .catch(error => {
          if (error.response) {
            console.log(error.response);
          }
          this.$Progress.fail();
        });
    },
    loadUnit() {
      axios
        .get("api/allUnit")
        .then(({ data }) => (this.unitlist = data))
        .catch(error => {
          if (error.response) {
            console.log(error.response);
          }
          this.$Progress.fail();
        });
    }
  },
  created() {
    this.loadAuthor();
    this.loadUser();
    this.loadUnit();
  }
};
</script>
<style>
.filter-bar {
  padding: 10px;
}
</style>
