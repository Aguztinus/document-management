<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card card-default">
          <div class="card-header">Search Multiselect Component</div>
          <div class="card-body">
            <div class="row">
              <label class="typo__label" for="ajax">Async multiselect</label>
              <multiselect
                v-model="selectedDocuments"
                id="ajax"
                label="name"
                track-by="code"
                placeholder="Type to search"
                open-direction="bottom"
                :options="Multidocuments"
                :multiple="true"
                :searchable="true"
                :loading="isLoading"
                :internal-search="false"
                :clear-on-select="false"
                :close-on-select="false"
                :options-limit="50"
                :limit="5"
                :limit-text="limitText"
                :max-height="600"
                :show-no-results="false"
                :hide-selected="true"
                @search-change="asyncFind"
              >
                <template slot="tag" slot-scope="{ option, remove }">
                  <span class="custom__tag">
                    <span>{{ option.name }}</span>
                    <span class="custom__remove" @click="remove(option)">❌</span>
                  </span>
                </template>
                <template slot="clear" slot-scope="props">
                  <div
                    class="multiselect__clear"
                    v-if="selectedDocuments.length"
                    @mousedown.prevent.stop="clearAll(props.search)"
                  ></div>
                </template>
                <span slot="noResult">Oops! No elements found. Consider changing the search query.</span>
              </multiselect>
              <pre class="language-json"><code>{{ selectedDocuments  }}</code></pre>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("Tes Component mounted.");
  },
  data() {
    return {
      selectedDocuments: [],
      Multidocuments: [],
      isLoading: false
    };
  },
  methods: {
    limitText(count) {
      return `and ${count} other `;
    },
    asyncFind: _.debounce(function(e) {
      this.isLoading = true;
      axios
        .get("api/findDoc?q=" + e)
        .then(data => {
          console.log(data.data.data);
          this.Multidocuments = data.data.data;
          this.isLoading = false;
        })
        .catch(() => {});
      this.isLoading = false;
    }, 700),
    // asyncFind(query) {
    //   this.isLoading = true;
    //   axios
    //     .get("api/findUser?q=" + query)
    //     .then(data => {
    //       console.log(data.data.data);
    //       this.countries = data.data.data;
    //       this.isLoading = false;
    //     })
    //     .catch(() => {});
    //   this.isLoading = false;
    // },
    clearAll() {
      this.selectedDocuments = [];
    }
  }
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style lang="scss" scoped>
.custom__tag {
  display: inline-block;
  padding: 3px 12px;
  background: #41b883;
  color: #fff;
  margin-right: 8px;
  margin-bottom: 8px;
  border-radius: 10px;
  cursor: pointer;
}

.custom__remove {
  padding: 0;
  font-size: 10px;
  margin-left: 5px;
}
</style>

