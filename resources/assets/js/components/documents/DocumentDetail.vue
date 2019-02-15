<template>
  <div class="row">
    <div class="col-md-12">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Document Detail</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <div class="row">
            <div class="col-md-12">
              <div class="info-box">
                <span class="info-box-icon bg-danger">
                  <i class="fas fa-file-pdf"></i>
                </span>

                <div class="info-box-content">
                  <span class="info-box-text">File Number</span>
                  <span class="info-box-number">{{ detail.number }}</span>
                  <a class :href="geturl()" download>
                    <i class="fas fa-arrow-circle-down"></i> Download
                  </a>
                </div>
                <!-- /.info-box-content -->
              </div>
            </div>
            <div class="col-md-6">
              <div class="card-body">
                <strong>Owner</strong>
                <p class="text-muted">{{ userowner }}</p>
                <hr>
                <strong>Author</strong>
                <p class="text-muted">{{ documentautor }}</p>
                <hr>
                <strong>Created</strong>
                <p class="text-muted">{{ detail.created_at }}</p>
                <hr>
                <strong>Modified</strong>
                <p class="text-muted">{{ detail.updated_at }}</p>
                <hr>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card-body">
                <strong>File name</strong>
                <p class="text-muted">{{ detail.name }}</p>
                <hr>
                <strong>File Description</strong>
                <p class="text-muted">{{ detail.description }}</p>
                <hr>
                <strong>Size</strong>
                <p class="text-muted">{{ detail.size }}</p>
                <hr>
                <strong>Document Type</strong>
                <p class="text-muted">{{ documenttype }}</p>
                <hr>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Document Refrence</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <div class="doksmall mt-3">
            <div
              class="card"
              v-for="doc in documentsref.data"
              :key="doc.id"
              v-on:click="clikfile(doc)"
            >
              <DocItem :doc="doc"></DocItem>
            </div>
          </div>
        </div>
      </div>
      <div v-if="$gate.isAdminOrUploader()" class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Document History</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <div class="doksmall mt-3">
            <div
              class="card"
              v-for="dochis in documentshis.data"
              :key="dochis.id"
              v-on:click="clikfile(dochis)"
            >
              <DocItem :doc="dochis"></DocItem>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DocItem from "./DocumentItem";

export default {
  mounted() {
    console.log("Component Detail mounted.");
  },
  components: {
    DocItem
  },
  props: {
    props: ["Mydetail"]
  },
  data() {
    return {
      documentsref: {},
      documentshis: {},
      detail: {},
      userowner: "",
      documentautor: "",
      documenttype: ""
    };
  },
  methods: {
    geturl() {
      let url =
        "storage/uploads/" + this.detail.owner_id + "/" + this.detail.realname;
      //console.log(url);
      return url;
    },
    MoreDetail(data) {
      this.$Progress.start();
      this.detail = data;
      this.userowner = data.userowner.name;
      this.documenttype = data.documenttype.name;
      this.documentautor = data.documentautor.name;
      axios.get("api/getref/" + data.id).then(response => {
        //console.log(response.data);
        this.documentsref = response.data;
      });
      axios.get("api/gethistory/" + data.id).then(response2 => {
        //console.log(response2.data);
        this.documentshis = response2.data;
      });
      this.$Progress.finish();
    }
  },
  created() {
    Fire.$on("Detaild", data => {
      this.MoreDetail(data);
    });
  }
};
</script>
