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
          <h3 class="card-title">Documents</h3>
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
  </div>
</template>

<script>
import FilterBarDoc from "./vuetable/FilterBarDoc";
Vue.component("filter-bar-doc", FilterBarDoc);

Vue.component("custom-actions", {
  template: [
    "<div class='d-flex flex-row'>",
    '<button class="btn btn-info " @click="onClick(\'view-item\', rowData)"><i class="zoom icon"></i></button>',
    '<button class="btn btn-success ml-1" @click="onClick(\'edit-item\', rowData)"><i class="edit icon"></i></button>',
    '<button class="btn btn-danger ml-1" @click="onClick(\'delete-item\', rowData)"><i class="delete icon"></i></button>',
    "</div>"
  ].join(""),
  props: {
    rowData: {
      type: Object,
      required: true
    }
  },
  methods: {
    onClick(action, data) {
      console.log("actions: on-click", data.name);
      swal(action, data.name);
    }
  }
});

export default {
  mounted() {
    console.log("Tes Component mounted.");
  },
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
          callback: "formatDate|DD-MM-YYYY"
        },
        {
          name: "__component:custom-actions",
          title: "Actions",
          titleClass: "center aligned",
          dataClass: "center aligned",
          width: "150px"
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
      // console.log(data.id);
      this.selectdoc = data.id;
    },
    rowClassCB(data, index) {
      console.log(this.selectdoc);
      if (this.$refs.vuetable.selectedTo.indexOf(data.id) > -1) {
        return "highlight";
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
    formatDate(value, fmt = "D MMM YYYY") {
      return value == null ? "" : moment(value, "YYYY-MM-DD").format(fmt);
    }
  },
  events: {
    "filter-set"(filterName, filterDesc) {
      this.moreParams = {
        filname: filterName,
        fildesc: filterDesc
      };
      console.log(filterName);
      console.log(filterDesc);
      Vue.nextTick(() => this.$refs.vuetable.refresh());
    },
    "filter-reset"() {
      this.moreParams = {};
      Vue.nextTick(() => this.$refs.vuetable.refresh());
    }
  }
};
</script>

<style>
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
  background-color: yellow;
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
