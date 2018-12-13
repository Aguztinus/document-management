<template>
  <div class="card card-default collapsed-card mt-3">
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
      <div class="filter-bar">
        <form class="form-inline">
          <div class="form-group">
            <label>Search:</label>
            <input
              type="text"
              v-model="filterText"
              class="form-control"
              @keyup.enter="doFilter"
              placeholder="name, nickname, or email"
            >
            <button class="btn btn-primary" @click.prevent="doFilter">Go</button>
            <button class="btn" @click.prevent="resetFilter">Reset</button>
          </div>
        </form>
      </div>
    </div>
    <!-- /.card-body -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      filterText: ""
    };
  },
  methods: {
    doFilter() {
      this.$events.fire("filter-set", this.filterText);
    },
    resetFilter() {
      this.filterText = "";
      this.$events.fire("filter-reset");
    }
  }
};
</script>
<style>
.filter-bar {
  padding: 10px;
}
</style>
