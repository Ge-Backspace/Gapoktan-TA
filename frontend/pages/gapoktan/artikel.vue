<template>
  <div>
    <div class="header bg-success pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <h1 class="heading">Artikel</h1>
        </div>
      </div>
    </div>
    <div class="container-fluid mt--5">
      <el-card v-loading="getLoader" style="margin-top: 60px">
        <div class="row" style="margin-bottom: 20px">
          <div class="col-md-3 offset-md-9">
            <el-input placeholder="Cari" v-model="search" size="mini">
              <i slot="prefix" class="el-input__icon el-icon-search"></i>
            </el-input>
          </div>
        </div>
        <vs-row style="justify-content: space-between; margin-top: 20px;">
          <vs-col w="6"
            :key="i"
            v-for="(tr, i) in $vs.getSearch(getArtikels.data, search)"
            :data="tr"
          >
            <vs-card type="3">
              <template #title>
                <h3>{{tr.judul}}</h3>
              </template>
              <template #img>
                <img v-if="tr.foto" :src="api_url+'/storage/ARTIKEL/'+tr.foto" alt="" />
                <img v-else src="../../assets/img/404.png" alt="" />
              </template>
              <template #text>
                <p>{{shortText(tr.isi)}}</p>
                <vs-button @click="deleteData(tr.id)" danger icon style="margin-left: 90px">
                  <i class="bx bx-trash"></i>
                </vs-button>
                <vs-button @click="edit(tr)" primary icon style="margin-left: 90px">
                  <i class="bx bx-edit"></i>
                </vs-button>
              </template>
            </vs-card>
          </vs-col>
        </vs-row>
      </el-card>
    </div>

    <!-- Floating Button -->
    <el-tooltip
      class="item"
      effect="dark"
      content="Tambah Artikel Baru"
      placement="top-start"
    >
      <a
        class="float"
        @click="
          formDialog = true;
          titleDialog = 'Tambah Artikel Baru';
        "
      >
        <i class="el-icon-plus my-float"></i>
      </a>
    </el-tooltip>
    <!-- End floating button -->

    <vs-dialog
      v-model="formDialog"
      :width="$store.state.drawer.mode === 'mobile' ? '80%' : '60%'"
      @close="resetForm()"
    >
      <template #header>
        <h1 class="not-margin">
          {{ titleDialog }}
        </h1>
      </template>
      <div class="con-form">
        <vs-row>
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" w="12" style="padding:5px">
            <label>Foto Thumbnail Artikel</label>
            <el-upload :auto-upload="false" :on-change="handleChangeFile" list-type="picture-card" accept="image/*"
              :file-list="fileList" :limit="1">
              <i class="el-icon-plus"></i>
            </el-upload>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="12"
            style="padding: 5px"
          >
            <label>Judul</label>
            <vs-input
              type="text"
              v-model="form.judul"
              placeholder="Masukkan Judul ..."
            ></vs-input>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="12"
            style="padding: 5px"
          >
            <label>Isi</label>
            <template>
              <vue-editor v-model="form.isi" />
            </template>
          </vs-col>
        </vs-row>
      </div>

      <template #footer>
        <div class="footer-dialog">
          <vs-row>
            <vs-col w="6" style="padding: 5px">
              <vs-button
                block
                :loading="btnLoader"
                @click="onSubmit('update')"
                v-if="isUpdate"
                >Update</vs-button
              >
              <vs-button
                block
                :loading="btnLoader"
                @click="onSubmit('store')"
                v-else
                >Simpan</vs-button
              >
            </vs-col>
            <vs-col w="6" style="padding: 5px">
              <vs-button
                block
                border
                @click="
                  formDialog = false;
                  resetForm();
                "
                >Batal</vs-button
              >
            </vs-col>
          </vs-row>
          <div>&nbsp;</div>
        </div>
      </template>
    </vs-dialog>

  </div>
</template>

<script>
import { mapMutations, mapGetters } from "vuex";
import { config } from "../../global.config";
export default {
  layout: "gapoktan",
  data() {
    return {
      api_url: config.baseApiUrl,
      table: {
        max: 10,
      },
      page: 1,
      active: "",
      search: "",
      titleDialog: "",
      isUpdate: false,
      btnLoader: false,
      form: {
        id: "",
        judul: "",
        isi: "",
        foto: "",
      },
      formDialog: false,
    };
  },
  mounted() {
    this.user_id = JSON.parse(JSON.stringify(this.$auth.user.id))
    this.$store.dispatch("artikel/getAll", {user_id: this.user_id});
    // this.$store.dispatch("option/getPoktans", {});
  },
  computed: {
    ...mapGetters("artikel", ["getArtikels", "getLoader"]),
    // ...mapGetters("option", ["getOptionPoktans"]),
  },
  methods: {
    resetForm() {
      this.form = {
        id: "",
        judul: "",
        isi: "",
        foto: "",
      };
      this.isUpdate = false;
    },
    handleChangeFile(file, fileList) {
      this.form.foto = file.raw
    },
    handleCurrentChange(val) {
      // this.$store.commit("kegiatan/setPage", val);
      // this.$store.dispatch("kegiatan/getAll", {});
    },
    shortText(text) {
      return (text.length > 50) ? text.substr(0, 50-1) + ' . . .' : text;
    },
    onSubmit(type = "store") {
      this.btnLoader = true;
      let formData = new FormData();
      formData.append("judul", this.form.judul);
      formData.append("isi", this.form.isi);
      formData.append("foto", this.form.foto);
      formData.append("user_id", this.user_id);
      let url = "/tambah_artikel";
      if (type == "update") {
        url = `/ubah_artikel/${this.form.id}`;
      }

      this.$axios
        .post(url, formData)
        .then((resp) => {
          if (resp.data.success) {
            this.$notify.success({
              title: "Success",
              message: `Berhasil ${
                type == "store" ? "Menambah" : "Mengubah"
              } Artikel`,
            });
            this.resetForm();
            this.formDialog = false;
            this.$store.dispatch("artikel/getAll", {user_id: this.user_id})
          }
        })
        .finally(() => {
          this.btnLoader = false;
        })
        .catch((err) => {
          let error = err.response.data.data;
          if (error) {
            this.showErrorField(error);
          } else {
            this.$notify.error({
              title: "Error",
              message: err.response.data.message,
            });
          }
        });
    },
    edit(data) {
      this.form.id = data.id;
      this.form.judul = data.judul;
      this.form.isi = data.isi;
      this.form.foto = data.foto;
      this.formDialog = true;
      this.titleDialog = "Edit Artikel";
      this.isUpdate = true;
    },
    deleteData(id) {
      this.$swal({
        title: "Perhatian!",
        text: "Yakin Menghapus Data Ini?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak",
      }).then((result) => {
        if (result.isConfirmed) {
          this.$axios
            .delete(`/hapus_artikel/${id}`)
            .then((resp) => {
              if (resp.data.success) {
                this.$notify.success({
                  title: "Success",
                  message: "Berhasil Menghapus Data",
                });
                this.formDialog = false;
                this.$store.dispatch("artikel/getAll", {user_id: this.user_id, defaultPage: true})
              }
            })
            .finally(() => {
              //
            })
            .catch((err) => {
              this.$notify.error({
                title: "Error",
                message: err.response.data.message,
              });
            });
        }
      });
    },
  },
  watch: {
    page(newValue, oldValue) {
      this.$store.commit('artikel/setPage', newValue)
      this.$store.dispatch('artikel/getAll', {user_id: this.user_id});
    }
  },
};
</script>

<style lang="scss" scoped>
.heading {
  color: white;
  font-size: 25px;
  font-weight: bold;
}
</style>
