<template>
  <div>
    <div class="header bg-success pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <!-- Card stats -->
          <h1 class="heading">Tandur</h1>
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
              <vs-th>Tanaman</vs-th>
              <vs-th>Luas</vs-th>
              <vs-th>Tanggal Tandur</vs-th>
              <vs-th>Tanggal Panen</vs-th>
            </vs-tr>
          </template>
          <template #tbody>
            <vs-tr
              :key="i"
              v-for="(tr, i) in $vs.getSearch(getTandurs.data, search)"
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
              <vs-td>{{ tr.tanaman }}</vs-td>
              <vs-td>{{ tr.luas_tandur }} Hektar</vs-td>
              <vs-td>{{ formatDate(tr.tanggal_tandur) }}</vs-td>
              <vs-td>{{ formatDate(tr.tanggal_panen) }}</vs-td>
              <!-- <template #expand>
                <div class="con-content">
                  <p>Keterangan:<br />{{ tr.keterangan }}</p>
                </div>
              </template> -->
            </vs-tr>
          </template>
          <template #footer>
            <vs-row>
              <vs-col w="2">
                <small>Total : {{ getTandurs.total }} Data</small>
              </vs-col>
              <vs-col w="10">
                <vs-pagination
                  v-model="page"
                  :length="Math.ceil(getTandurs.total / table.max)"
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
      content="Tambah Tandur Baru"
      placement="top-start"
    >
      <a
        class="float"
        @click="
          formDialog = true;
          titleDialog = 'Tambah Tandur Baru';
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
            w="6"
            style="padding: 5px"
          >
            <label>Tanaman</label>
            <vs-input
              type="text"
              v-model="form.tanaman"
              placeholder="Masukkan Tanaman ..."
            ></vs-input>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Luas Tandur (Hektar)</label>
            <vs-input
              type="numer"
              v-model="form.luas_tandur"
              placeholder="Masukkan Luas ..."
            ></vs-input>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Tanggal Tandur</label>
            <vs-input type="date" v-model="form.tanggal_tandur"/>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Tanggal Panen</label>
            <vs-input type="date" v-model="form.tanggal_panen"/>
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
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Dari Tanggal</label>
            <vs-select
              placeholder="Dari Tanggal"
              v-model="form.state"
            >
              <vs-option label="Tanggal Tandur" value="1">
                Tanggal Tandur
              </vs-option>
              <vs-option label="Tanggal Panen" value="2">
                Tanggal Panen
              </vs-option>
            </vs-select>
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
        tanaman: "",
        luas_tandur: "",
        tanggal_tandur: "",
        tanggal_panen: "",
        poktan_id: "",
        state: "",
        tanggal_awal: "",
        tanggal_akhir: "",
      },
      formDialog: false,
      exportDialog: false,
    };
  },
  mounted() {
    this.user_id = JSON.parse(JSON.stringify(this.$auth.user.id))
    this.$store.dispatch("tandur/getAll", {user_id: this.user_id});
    this.$store.dispatch("option/getPoktans", {});
  },
  computed: {
    ...mapGetters("tandur", ["getTandurs", "getLoader"]),
    ...mapGetters("option", ["getOptionPoktans"]),
  },
  methods: {
    logTest(data) {
      console.log(data);
    },
    exportData(type = 'excel'){
      this.btnLoader = true;
      let as = 'excel'
      if (type == 'pdf') {
        as = 'pdf'
      }
      this.$axios.get(`/export_tandur_gapoktan?user_id=${this.user_id}&tanggal_awal=${this.form.tanggal_awal}&tanggal_akhir=${this.form.tanggal_akhir}&state=${this.form.state}&as=${as}`, {
        responseType: 'blob'
      }).then((response) => {
        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(
          new Blob([response.data])
        );
        link.setAttribute('download', 'laporan_'+this.state ? 'tandur' : 'panen'+'_'+this.form.tanggal_awal+'-'+this.form.tanggal_akhir+'.xlsx');
        document.body.appendChild(link);
        link.click();
        this.btnLoader = false;
        this.exportDialog = false;
        this.resetForm();
      });
    },
    resetForm() {
      this.form = {
        id: "",
        tanaman: "",
        luas_tandur: "",
        tanggal_tandur: "",
        tanggal_panen: "",
        poktan_id: "",
        state: "",
        tanggal_awal: "",
        tanggal_akhir: "",
      };
      this.isUpdate = false;
    },
    onSubmit(type = "store") {
      this.btnLoader = true;
      let formData = new FormData();
      formData.append("tanaman", this.form.tanaman);
      formData.append("luas_tandur", this.form.luas_tandur);
      formData.append("tanggal_tandur", this.form.tanggal_tandur);
      formData.append("tanggal_panen", this.form.tanggal_panen);
      formData.append("poktan_id", this.form.poktan_id);
      let url = "/tambah_tandur";
      if (type == "update") {
        url = `/ubah_tandur/${this.form.id}`;
      }

      this.$axios
        .post(url, formData)
        .then((resp) => {
          if (resp.data.success) {
            this.$notify.success({
              title: "Success",
              message: `Berhasil ${
                type == "store" ? "Menambah" : "Mengubah"
              } Tandur`,
            });
            this.resetForm();
            this.formDialog = false;
            this.$store.dispatch("tandur/getAll", {user_id: this.user_id});
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
      this.form.tanaman = data.tanaman;
      this.form.luas_tandur = data.luas_tandur;
      this.form.tanggal_tandur = data.tanggal_tandur;
      this.form.tanggal_panen = data.tanggal_panen;
      this.form.poktan_id = data.poktan_id;
      this.formDialog = true;
      this.titleDialog = "Edit Tandur";
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
            .delete(`/hapus_tandur/${id}`)
            .then((resp) => {
              if (resp.data.success) {
                this.$notify.success({
                  title: "Success",
                  message: "Berhasil Menghapus Data",
                });
                this.shiftDialog = false;
                this.$store.dispatch("tandur/getAll", { user_id: this.user_id, defaultPage: true });
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
    page(newValue, oldValue) {
      this.$store.commit('stoklumbung/setPage', newValue)
      this.$store.dispatch('stoklumbung/getAll', {user_id: this.user_id});
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
