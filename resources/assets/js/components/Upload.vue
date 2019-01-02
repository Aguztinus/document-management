<template>
  <div class="container">
    <div class="row" v-if="$gate.isAdminOrUploader()">
      <div class="col-md-12">
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Upload Documents</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item">
                    <router-link to="/dashboard">Home</router-link>
                  </li>
                  <li class="breadcrumb-item active">Upload Documents</li>
                </ol>
              </div>
            </div>
          </div>
          <!-- /.container-fluid -->
        </section>

        <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">Upload Documents</h3>
          </div>

          <DocUpload :isPopup="false"></DocUpload>
        </div>
      </div>
    </div>

    <div v-if="!$gate.isAdminOrUploader()">
      <not-found></not-found>
    </div>
  </div>
</template>

<script>
import DocUpload from "./documents/DocumentUpload";

export default {
  components: {
    DocUpload
  }
};
</script>


