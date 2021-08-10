<template>
  <div>
    <div class="header bg-success pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <!-- Card stats -->
          <h1 class="heading">Manage Produk</h1>
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
        <vs-table striped>
          <template #thead>
            <vs-tr>
              <vs-th>Action</vs-th>
              <vs-th></vs-th>
              <vs-th>Nama Produk</vs-th>
              <vs-th>Stok</vs-th>
              <vs-th>Harga</vs-th>
              <vs-th>Status</vs-th>
            </vs-tr>
          </template>
          <template #tbody>
            <vs-tr
              :key="i"
              v-for="(tr, i) in $vs.getSearch(getProduks.data, search)"
              :data="tr"
              @click="getGP(tr.id)"
            >
              <vs-td>
                <el-tooltip content="Edit" placement="top-start" effect="dark">
                  <el-button
                    size="mini"
                    @click="edit(tr)"
                    icon="fa fa-edit"
                  ></el-button>
                </el-tooltip>

                <el-tooltip
                  content="Delete"
                  placement="top-start"
                  effect="dark"
                >
                  <el-button
                    size="mini"
                    type="primary"
                    @click="deleteData(tr.id)"
                    icon="fa fa-trash"
                  >
                  </el-button>
                </el-tooltip>
              </vs-td>
              <vs-td>
                <vs-avatar v-if="!tr.nama_thumbnail">
                  <img :src="api_url+'/storage/USER/no-user-image.png'" alt="">
                </vs-avatar>
                <vs-avatar v-else>
                  <img  :src="api_url+'/storage/THUBNAILPRODUK/'+tr.nama_thumbnail" alt="">
                </vs-avatar>
              </vs-td>
              <vs-td>{{ tr.nama }}</vs-td>
              <vs-td>{{ tr.stok }}</vs-td>
              <vs-td>{{ tr.harga }}</vs-td>
              <vs-td>
                <span class="badge badge-success" v-if="tr.status"
                  >Aktif</span
                >
                <span class="badge badge-primary" v-else>Belum Aktif</span>
              </vs-td>
              <template #expand>
                <div class="con-content" v-loading="getGLoader">
                  <img :src="api_url+'/storage/USER/no-user-image.png'" alt="" width="100" height="auto">
                  <img :src="api_url+'/storage/USER/no-user-image.png'" alt="" width="100" height="auto">
                </div>
                <hr>
                <div>
                  <vs-button
                    relief
                    :active="active == 1"
                    @click="gambarDialog = true; titleDialog = 'Tambah Gambar Produk';"
                  >
                    Tambah Gambar Produk
                  </vs-button>
                </div>
              </template>
            </vs-tr>
          </template>
          <template #footer>
            <vs-row>
              <vs-col w="2">
                <small>Total : {{ getProduks.total }} Data</small>
              </vs-col>
              <vs-col w="10">
                <vs-pagination
                  v-model="page"
                  :length="Math.ceil(getProduks.total / table.max)"
                />
              </vs-col>
            </vs-row>
          </template>
        </vs-table>
      </el-card>
    </div>
    <!-- Floating Button -->
    <el-tooltip
      class="item"
      effect="dark"
      content="Tambah Produk Baru"
      placement="top-start"
    >
      <a
        class="float"
        @click="
          formDialog = true;
          titleDialog = 'Tambah Produk Baru';
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
            <label>Foto Thumbnail Produk</label>
            <el-upload :auto-upload="false" :on-change="handleChangeFile" list-type="picture-card" accept="image/*"
              :file-list="fileList" :limit="1">
              <i class="el-icon-plus"></i>
            </el-upload>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Nama Produk</label>
            <vs-input
              type="text"
              v-model="form.nama"
              placeholder="Masukkan Nama Produk ..."
            ></vs-input>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Kategori</label>
            <vs-select
              placeholder="Pilih Poktan"
              v-model="form.kategori_id"
            >
              <vs-option
                v-for="op in getOptionKategoris.data"
                :key="op.id"
                :label="op.nama"
                :value="op.id"
              >
                {{ op.nama }}
              </vs-option>
            </vs-select>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Stok</label>
            <vs-input
              type="number"
              v-model="form.stok"
              placeholder="Masukkan Stok ..."
            ></vs-input>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Harga</label>
            <vs-input
              type="number"
              v-model="form.harga"
              placeholder="Masukkan Harga ..."
            ></vs-input>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="12"
            style="padding: 5px"
          >
            <label>Deskripsi</label>
            <el-input
              type="textarea"
              :rows="2"
              placeholder="Masukkan Deskripsi ..."
              v-model="form.deskripsi"
            >
            </el-input>
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

    <vs-dialog
      v-model="gambarDialog"
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
            <el-upload
              multiple
              list-type="picture-card"
              :on-change="handleChangeGambars"
              :file-list="fileList"
              :auto-upload="false"
              >
              <i class="el-icon-plus"></i>
            </el-upload>
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
                @click="onSubmitGambar()"
                >Simpan</vs-button
              >
            </vs-col>
            <vs-col w="6" style="padding: 5px">
              <vs-button
                block
                border
                @click="
                  gambarDialog = false;
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
import { config } from "../../../global.config";
  export default {
    components: {},
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
        fileList: [],
        user_id: "",
        form: {
          id: "",
          nama: "",
          kategori_id: "",
          stok: "",
          harga: "",
          deskripsi: "",
          foto: "",
        },
        formDialog: false,
        gambarDialog: false,
      }
    },
    mounted () {
      this.user_id = JSON.parse(JSON.stringify(this.$auth.user.id))
      this.$store.dispatch("produk/getAll", {user_id: this.user_id})
      this.$store.dispatch("option/getKategoris", {})
    },
    computed: {
    ...mapGetters("produk", ["getProduks", "getLoader"]),
    ...mapGetters("gambarproduk", ["getGambars", "getGLoader"]),
    ...mapGetters("option", ["getOptionKategoris"])
    },
     methods: {
      logTest(data) {
        console.log(data);
        return data;
      },
      getGP(id) {
        this.$store.dispatch("gambarproduk/getAll", {produk_id: id})
      },
      resetForm() {
        this.form = {
          id: "",
          nama: "",
          kategori_id: "",
          stok: "",
          harga: "",
          deskripsi: "",
          foto: "",
        };
        this.fileList = [];
        this.isUpdate = false;
      },
      handleChangeFile(file, fileList) {
        this.form.foto = file.raw
      },
      handleChangeGambars(file, fileList) {
        this.fileList = fileList
      },
      handleCurrentChange(val) {
        this.$store.commit("produk/setPage", val);
        this.$store.dispatch("produk/getAll", {user_id: this.user_id});
      },
      onSubmit(type = "store") {
        this.btnLoader = true;
        let formData = new FormData();
        formData.append("nama", this.form.nama);
        formData.append("kategori_id", this.form.kategori_id);
        formData.append("stok", this.form.stok);
        formData.append("harga", this.form.harga);
        formData.append("deskripsi", this.form.deskripsi);
        formData.append("foto", this.form.foto);
        formData.append("user_id", this.user_id);
        let url = "/tambah_produk";
        if (type == "update") {
          url = `/ubah_produk/${this.form.id}`;
        }

        this.$axios
          .post(url, formData)
          .then((resp) => {
            if (resp.data.success) {
              this.$notify.success({
                title: "Success",
                message: `Berhasil ${
                  type == "store" ? "Menambah" : "Mengubah"
                } Produk`,
              });
              this.resetForm();
              this.formDialog = false;
              this.$store.dispatch("produk/getAll", { user_id: this.user_id });
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
      onSubmitGambar() {
        this.btnLoader = true;
        let formData = new FormData();
        let test = [];
        this.fileList.forEach(everyFile => {
          formData.append("gambars", everyFile.raw)
          test.push(everyFile.raw)
        })
        console.log(test)
        this.$axios.post('/tambah_gambar_produk', formData)
        .then((res) => {
          console.log(res)
          this.resetForm();
          this.gambarDialog = false;
        }).finally(() => {
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
        this.form.kategori_id = data.kategori_id;
        this.form.nama = data.nama;
        this.form.stok = data.stok;
        this.form.harga = data.harga;
        this.form.deskripsi = data.deskripsi;
        this.formDialog = true;
        this.titleDialog = "Edit Produk";
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
              .delete(`/hapus_poktan/${id}`)
              .then((resp) => {
                if (resp.data.success) {
                  this.$notify.success({
                    title: "Success",
                    message: "Berhasil Menghapus Data",
                  });
                  this.shiftDialog = false;
                  this.$store.dispatch("user/getUserPoktan", { user_id: this.user_id });
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
      formatDate(date) {
        return moment(date).format("DD MMMM YYYY");
      },
    },
  }
</script>

<style lang="scss" scoped>
.heading {
  color: white;
  font-size: 25px;
  font-weight: bold;
}
</style>
