<template>
  <div>
    <div class="header bg-success pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <!-- Card stats -->
          <h1 class="heading">Kegiatan Poktan</h1>
        </div>
      </div>
    </div>
    <div class="container-fluid mt--5">
      <el-card v-loading="getLoader" style="margin-top: 60px">
        <div class="row" style="margin-bottom: 20px">
          <div class="col-md-2">
            <vs-button
              success
              style="float: right"
              :loading="globalLoader"
              gradient
              @click="exportDialog = true; titleDialog = 'Export Data'"
              >Download Excel
            </vs-button>
          </div>
          <div class="col-md-3 offset-md-7">
            <el-input placeholder="Cari" v-model="search" size="mini">
              <i slot="prefix" class="el-input__icon el-icon-search"></i>
            </el-input>
          </div>
        </div>
        <vs-table striped>
          <template #thead>
            <vs-tr>
              <vs-th>Action</vs-th>
              <vs-th>Poktan</vs-th>
              <vs-th>Tanggal</vs-th>
              <vs-th>Uraian</vs-th>
            </vs-tr>
          </template>
          <template #tbody>
            <vs-tr
              :key="i"
              v-for="(tr, i) in $vs.getSearch(getKegiatans.data, search)"
              :data="tr"
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
              <vs-td>{{ tr.nama_poktan }}</vs-td>
              <vs-td>{{ formatDate(tr.tanggal) }}</vs-td>
              <vs-td>{{ tr.uraian }}</vs-td>
              <template #expand>
                <div class="con-content">
                  <p>Keterangan:<br />{{ tr.keterangan }}</p>
                </div>
              </template>
            </vs-tr>
          </template>
          <template #footer>
            <vs-row>
              <vs-col w="2">
                <small>Total : {{ getKegiatans.total }} Data</small>
              </vs-col>
              <vs-col w="10">
                <vs-pagination
                  v-model="page"
                  :length="Math.ceil(getKegiatans.total / table.max)"
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
      content="Tambah Kegiatan Baru"
      placement="top-start"
    >
      <a
        class="float"
        @click="
          formDialog = true;
          titleDialog = 'Tambah Kegiatan Baru';
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
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="12"
            style="padding: 5px"
          >
            <label>Uraian</label>
            <vs-input
              type="text"
              v-model="form.uraian"
              placeholder="Masukkan Uraian ..."
            ></vs-input>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Poktan</label>
            <vs-select
              filter
              placeholder="Pilih Poktan"
              v-model="form.poktan_id"
            >
              <vs-option
                v-for="op in getOptionPoktans.data"
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
            <label>Tanggal</label>
            <vs-input type="date" v-model="form.tanggal"/>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="12"
            style="padding: 5px"
          >
            <label>Keterangan</label>
            <el-input
              type="textarea"
              :rows="2"
              placeholder="Masukkan Keterangan ..."
              v-model="form.keterangan"
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
      v-model="exportDialog"
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
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Dari Tanggal</label>
            <vs-input
              type="date"
              v-model="form.tanggal_awal"
            />
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Ke Tanggal</label>
            <vs-input
              type="date"
              v-model="form.tanggal_akhir"
            />
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
                @click="exportData()"
                v-else
                >Export</vs-button
              >
            </vs-col>
            <vs-col w="6" style="padding: 5px">
              <vs-button
                block
                border
                @click="
                  exportDialog = false;
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
      user_id: "",
      form: {
        id: "",
        uraian: "",
        tanggal: "",
        keterangan: "",
        poktan_id: "",
        tanggal_awal: "",
        tanggal_akhir: "",
      },
      formDialog: false,
      exportDialog: false,
    };
  },
  mounted() {
    this.user_id = JSON.parse(JSON.stringify(this.$auth.user.id))
    this.$store.dispatch("kegiatan/getAll", {user_id: this.user_id})
    this.$store.dispatch("option/getPoktans", {})
  },
  computed: {
    ...mapGetters("kegiatan", ["getKegiatans", "getLoader"]),
    ...mapGetters("option", ["getOptionPoktans"]),
  },
  methods: {
    logTest(data) {
      console.log(data);
    },
    resetForm() {
      this.form = {
        id: "",
        uraian: "",
        tanggal: "",
        keterangan: "",
        poktan_id: "",
        tanggal_awal: "",
        tanggal_akhir: "",
      };
      this.isUpdate = false;
    },
    exportData(type = 'excel'){
      this.btnLoader = true;
      let as = 'excel'
      if (type == 'pdf') {
        as = 'pdf'
      }
      this.$axios.get(`/export_kegiatan_gapoktan?user_id=${this.user_id}&tanggal_awal=${this.form.tanggal_awal}&tanggal_akhir=${this.form.tanggal_akhir}&as=${as}`, {
        responseType: 'blob'
      }).then((response) => {
        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(
          new Blob([response.data])
        );
        link.setAttribute('download', 'laporan_kegiatan_'+this.form.tanggal_awal+'-'+this.form.tanggal_akhir+'.xlsx');
        document.body.appendChild(link);
        link.click();
        this.btnLoader = false;
        this.exportDialog = false;
        this.resetForm();
      });
    },
    onSubmit(type = "store") {
      this.btnLoader = true;
      let formData = new FormData();
      formData.append("uraian", this.form.uraian);
      formData.append("tanggal", this.form.tanggal);
      formData.append("keterangan", this.form.keterangan);
      formData.append("poktan_id", this.form.poktan_id);
      let url = "/tambah_kegiatan";
      if (type == "update") {
        url = `/ubah_kegiatan/${this.form.id}`;
      }

      this.$axios
        .post(url, formData)
        .then((resp) => {
          if (resp.data.success) {
            this.$notify.success({
              title: "Success",
              message: `Berhasil ${
                type == "store" ? "Menambah" : "Mengubah"
              } Kegiatan`,
            });
            this.resetForm();
            this.formDialog = false;
            this.$store.dispatch("kegiatan/getAll", {user_id: this.user_id});
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
      this.form.uraian = data.uraian;
      this.form.tanggal = data.tanggal;
      this.form.keterangan = data.keterangan;
      this.form.poktan_id = data.poktan_id;
      this.formDialog = true;
      this.titleDialog = "Edit Kegiatan";
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
            .delete(`/hapus_kegiatan/${id}`)
            .then((resp) => {
              if (resp.data.success) {
                this.$notify.success({
                  title: "Success",
                  message: "Berhasil Menghapus Data",
                });
                this.shiftDialog = false;
                this.$store.dispatch("kegiatan/getAll", { user_id: this.user_id, defaultPage: true });
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
  watch: {
    watch: {
      page(newValue, oldValue) {
        this.$store.commit('kegiatan/setPage', newValue)
        this.$store.dispatch('kegiatan/getAll', {user_id: this.user_id});
      }
    },
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
