<template>
  <div>
    <filter-bar-doc></filter-bar-doc>
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Documents</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <div class="vuetable-pagination">
          <vuetable-pagination-info ref="paginationInfoTop"></vuetable-pagination-info>
          <vuetable-pagination
            ref="paginationTop"
            :css="css.pagination"
            @vuetable-pagination:change-page="onChangePage"
          ></vuetable-pagination>
        </div>
        <vuetable
          ref="vuetable"
          api-url="api/document"
          :fields="fields"
          :multi-sort="true"
          :css="css.table"
          :append-params="moreParams"
          multi-sort-key="ctrl"
          :http-options="httpOptions"
          pagination-path
          @vuetable:pagination-data="onPaginationData"
        ></vuetable>
        <div class="vuetable-pagination">
          <vuetable-pagination-info ref="paginationInfo"></vuetable-pagination-info>

          <vuetable-pagination
            ref="pagination"
            :css="css.pagination"
            @vuetable-pagination:change-page="onChangePage"
          ></vuetable-pagination>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import FilterBarDoc from "./vuetable/FilterBarDoc";
Vue.component("filter-bar-doc", FilterBarDoc);

export default {
  mounted() {
    console.log("Tes Component mounted.");
  },
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
        }
      ],
      css: {
        table: {
          tableClass: "table table-bordered table-striped table-hover",
          ascendingIcon: "glyphicon glyphicon-chevron-up",
          descendingIcon: "glyphicon glyphicon-chevron-down"
        },
        pagination: {
          wrapperClass: "pagination pull-right",
          activeClass: "active",
          disabledClass: "disabled",
          pageClass: "page",
          linkClass: "link",
          icons: {
            first: "",
            prev: "",
            next: "",
            last: ""
          }
        },
        icons: {
          first: "glyphicon glyphicon-step-backward",
          prev: "glyphicon glyphicon-chevron-left",
          next: "glyphicon glyphicon-chevron-right",
          last: "glyphicon glyphicon-step-forward"
        }
      },
      moreParams: {}
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
    "filter-set"(filterText) {
      this.moreParams = {
        filter: filterText
      };
      console.log("tes");
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
.vuetable-pagination {
  display: inline;
}
.vuetable-pagination-info {
  width: 200px;
  float: left;
}
.pagination {
  margin: 0;
  float: right;
}
.pagination a.page {
  border: 1px solid rgb(92, 83, 83);
  border-radius: 3px;
  padding: 5px 10px;
  margin-right: 2px;
}
.pagination a.page.active {
  color: white;
  background-color: #337ab7;
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 10px;
  margin-right: 2px;
}
.pagination a.btn-nav {
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 7px;
  margin-right: 2px;
}
.pagination a.btn-nav.disabled {
  color: lightgray;
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 7px;
  margin-right: 2px;
  cursor: not-allowed;
}
.pagination-info {
  float: left;
}
</style>
