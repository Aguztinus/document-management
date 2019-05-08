<template>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Dashboard</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item">
                    <router-link to="/dashboard">Home</router-link>
                  </li>
                </ol>
              </div>
            </div>
          </div>
          <!-- /.container-fluid -->
        </section>
        <div class="alert alert-info alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h5>
            <i class="icon fas fa-info"></i>
            Welcome, {{ myname }}
          </h5>
        </div>

        <div class="row">
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{countup}}</h3>

                <p>Upload</p>
              </div>
              <div class="icon">
                <i class="fa fa-upload"></i>
              </div>
              <a href="/documentlist" class="small-box-footer">
                More info
                <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{countdown}}</h3>

                <p>Donwload</p>
              </div>
              <div class="icon">
                <i class="fa fa-download"></i>
              </div>
              <a href="/documentlist" class="small-box-footer">
                More info
                <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{ sumdownload }}</h3>

                <p>My Document Size</p>
              </div>
              <div class="icon">
                <i class="fa fa-hdd"></i>
              </div>
              <a href="/documentlist" class="small-box-footer">
                More info
                <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{ countlogin }}</h3>

                <p>Login Count</p>
              </div>
              <div class="icon">
                <i class="fa fa-user"></i>
              </div>
              <a href="/profile" class="small-box-footer">
                More info
                <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <!-- ./col -->
        </div>

        <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">My Documment</h3>
          </div>

          <div class>
            <h3 class="card-title mt-3 ml-3">
              <filter-bar></filter-bar>
            </h3>
            <div class="card-body table-responsive p-0">
              <div :class="[{'vuetable-wrapper ui basic segment': true}, loading]">
                <vuetable
                  ref="vuetable"
                  api-url="api/mydocument"
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
      </div>
    </div>
  </div>
</template>

<script>
import DocumentList from "./Document";
import DocumentDetail from "./documents/DocumentDetail";

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
  data() {
    return {
      myname: "",
      countup: 0,
      countdown: 0,
      sumdownload: 0,
      countlogin: 0,
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
          name: "description",
          sortField: "description"
        },
        {
          name: "userowner.name",
          title: "Made By",
          sortField: "userowner.name"
        },
        {
          name: "created_at",
          title: "Created At",
          sortField: "created_at",
          titleClass: "center aligned",
          dataClass: "center aligned",
          callback: "formatDate|DD-MM-YYYY H:mm:ss"
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
      detail: {}
    };
  },
  components: {
    DocumentList,
    DocumentDetail
  },
  mounted() {
    console.log("Component mounted.");
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
    formatDate(value, fmt = "D MMM YYYY H:mm:ss") {
      return value == null
        ? ""
        : moment(value, "YYYY-MM-DD H:mm:ss").format(fmt);
    },
    getCount() {
      axios.get("api/getUtilprofile").then(response => {
        this.countup = response.data.countup;
        this.countdown = response.data.countdown;
        this.sumdownload = response.data.sizedok;
        this.countlogin = response.data.logincount;
        //console.log(response);
      });
    },
    MoreDetail(data) {
      this.detail = data;
      //console.log(this.detail.name);
      //this.$refs.dtl.MoreDetail();
      Fire.$emit("Detaild", data);

      $("#moreDetail").modal("show");
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
    axios.get("api/profile").then(({ data }) => (this.myname = data.name));
    this.getCount();

    Fire.$on("Detail", data => {
      this.MoreDetail(data);
    });
  }
};
</script>
