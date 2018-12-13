<template>
  <div>
    <filter-bar></filter-bar>
    <div class="vuetable-pagination ui basic segment grid">
      <vuetable-pagination-info ref="paginationInfoTop"></vuetable-pagination-info>
      <vuetable-pagination ref="paginationTop" @vuetable-pagination:change-page="onChangePage"></vuetable-pagination>
    </div>
    <vuetable
      ref="vuetable"
      api-url="https://vuetable.ratiw.net/api/users"
      :fields="fields"
      :multi-sort="true"
      :append-params="moreParams"
      multi-sort-key="ctrl"
      pagination-path
      @vuetable:pagination-data="onPaginationData"
    ></vuetable>
    <div class="vuetable-pagination ui basic segment grid">
      <vuetable-pagination-info ref="paginationInfo"></vuetable-pagination-info>

      <vuetable-pagination ref="pagination" @vuetable-pagination:change-page="onChangePage"></vuetable-pagination>
    </div>
  </div>
</template>

<script>
import accounting from "accounting";
import FilterBar from "./vuetable/FilterBar";
Vue.component("filter-bar", FilterBar);

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
          name: "email",
          sortField: "email"
        },
        {
          name: "birthdate",
          sortField: "birthdate",
          titleClass: "center aligned",
          dataClass: "center aligned",
          callback: "formatDate|DD-MM-YYYY"
        },
        {
          name: "nickname",
          sortField: "nickname",
          callback: "allcap"
        },
        {
          name: "gender",
          sortField: "gender",
          titleClass: "center aligned",
          dataClass: "center aligned",
          callback: "genderLabel"
        },
        {
          name: "salary",
          sortField: "salary",
          titleClass: "center aligned",
          dataClass: "right aligned",
          callback: "formatNumber"
        }
      ],
      moreParams: {}
    };
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


<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.7/semantic.min.css");
</style>
