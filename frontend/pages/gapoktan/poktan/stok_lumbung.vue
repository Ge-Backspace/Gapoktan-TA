<template>
  <div>
    <div class="header bg-success pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <!-- Card stats -->
          <h1 class="heading">Stok Lumbung</h1>
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
              <vs-th>Poktan</vs-th>
              <vs-th>Komoditas</vs-th>
              <vs-th>Jumlah</vs-th>
              <vs-th>Tahun Baper</vs-th>
              <vs-th>Tanggal Lapor</vs-th>
            </vs-tr>
          </template>
          <template #tbody>
            <vs-tr
              :key="i"
              v-for="(tr, i) in $vs.getSearch(getStokLumbungs.data, search)"
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
              <vs-td>{{ tr.komoditas }}</vs-td>
              <vs-td>{{ tr.jumlah }} Kg</vs-td>
              <vs-td>{{ formatYear(tr.tahun_banper) }}</vs-td>
              <vs-td>{{ formatDate(tr.tanggal_lapor) }}</vs-td>
            </vs-tr>
          </template>
          <template #footer>
            <vs-row>
              <vs-col w="2">
                <small>Total : {{ getStokLumbungs.total }} Data</small>
              </vs-col>
              <vs-col w="10">
                <vs-pagination
                  v-model="page"
                  :length="Math.ceil(getStokLumbungs.total / table.max)"
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
      content="Tambah Stok Lumbung Baru"
      placement="top-start"
    >
      <a
        class="float"
        @click="
          formDialog = true;
          titleDialog = 'Tambah Stok Lumbung Baru';
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
            <label>Komoditas</label>
            <vs-input
              type="text"
              v-model="form.komoditas"
              placeholder="Masukkan Komoditas ..."
            ></vs-input>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Jumlah (Kilogram)</label>
            <vs-input
              type="numer"
              v-model="form.jumlah"
              placeholder="Masukkan Jumlah ..."
            ></vs-input>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Tanggal Lapor</label>
            <vs-input type="date" v-model="form.tanggal_lapor"/>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Tahun Banper</label>
            <vs-select
              filter
              placeholder="Pilih Poktan"
              v-model="form.tahun_banper"
            >
              <vs-option
                v-for="op in years"
                :key="op.year"
                :label="op.year"
                :value="op.year"
              >
                {{ op.year }}
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
                @click="onSubmit('store'), logTest(form)"
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
      form: {
        id: "",
        komoditas: "",
        jumlah: "",
        tahun_banper: "",
        tanggal_lapor: "",
        poktan_id: "",
      },
      formDialog: false,
      years: []
    };
  },
  mounted() {
    this.$store.dispatch("stoklumbung/getAll", {})
    this.$store.dispatch("option/getPoktans", {})
    let y = [];
    for (let index = 2000; index < 2031; index++) {
      y.push({year: index})
    }
    this.years = y
  },
  computed: {
    ...mapGetters("stoklumbung", ["getStokLumbungs", "getLoader"]),
    ...mapGetters("option", ["getOptionPoktans"]),
  },
  methods: {
    logTest(data) {
      console.log(data);
    },
    resetForm() {
      this.form = {
        id: "",
        komoditas: "",
        jumlah: "",
        tahun_banper: "",
        tanggal_lapor: "",
        poktan_id: "",
      };
      this.isUpdate = false;
    },
    handleCurrentChange(val) {
      this.$store.commit("stoklumbung/setPage", val);
      this.$store.dispatch("stoklumbung/getAll", {});
    },
    onSubmit(type = "store") {
      this.btnLoader = true;
      let formData = new FormData();
      formData.append("komoditas", this.form.komoditas);
      formData.append("jumlah", this.form.jumlah);
      formData.append("tahun_banper", this.form.tahun_banper);
      formData.append("tanggal_lapor", this.form.tanggal_lapor);
      formData.append("poktan_id", this.form.poktan_id);
      let url = "/tambah_stok_lumbung";
      if (type == "update") {
        url = `/ubah_stok_lumbung/${this.form.id}`;
      }

      this.$axios
        .post(url, formData)
        .then((resp) => {
          if (resp.data.success) {
            this.$notify.success({
              title: "Success",
              message: `Berhasil ${
                type == "store" ? "Menambah" : "Mengubah"
              } Stok Lumbung`,
            });
            this.resetForm();
            this.formDialog = false;
            this.$store.dispatch("stoklumbung/getAll", {});
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
      this.form.komoditas = data.komoditas;
      this.form.jumlah = data.jumlah;
      this.form.tahun_banper = data.tahun_banper;
      this.form.tanggal_lapor = data.tanggal_lapor;
      this.form.poktan_id = data.poktan_id;
      this.formDialog = true;
      this.titleDialog = "Edit Stok Lumbung";
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
            .delete(`/hapus_stok_lumbung/${id}`)
            .then((resp) => {
              if (resp.data.success) {
                this.$notify.success({
                  title: "Success",
                  message: "Berhasil Menghapus Data",
                });
                this.shiftDialog = false;
                this.$store.dispatch("stoklumbung/getAll", { defaultPage: true });
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
    formatYear(date) {
      return moment(date).format("YYYY");
    },
    formatDate(date) {
      return moment(date).format("DD MMMM YYYY");
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
