<template>
  <div>
    <div class="header bg-success pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <!-- Card stats -->
          <h1 class="heading">Order</h1>
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
              <vs-th>Kode Order</vs-th>
              <vs-th>Total harga</vs-th>
              <vs-th>Status Pembayaran</vs-th>
              <vs-th>Order</vs-th>
            </vs-tr>
          </template>
          <template #tbody>
            <vs-tr
              :key="i"
              v-for="(tr, i) in $vs.getSearch(getOrders.data, search)"
              :data="tr"
              @click="getOrderDetail(tr.id)"
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
              <vs-td>{{ tr.kd_order }}</vs-td>
              <vs-td>{{ tr.total_harga }}</vs-td>
              <vs-td>
                <span class="badge badge-success" v-if="tr.status_payment"
                  >Sudah Upload</span
                >
                <span class="badge badge-primary" v-else>Belum Upload</span>
              </vs-td>
              <vs-td>
                <span class="badge badge-success" v-if="tr.status_order"
                  >Terkonformasi</span
                >
                <span class="badge badge-primary" v-else>Belum Dikonfirmasi</span>
              </vs-td>
              <template #expand>
                <el-card>
                  <label><b>Alamat Lengkap :</b></label>
                  <p>{{tr.nama_alamat}} - {{tr.alamat}}</p>
                  <label><b>Deskripsi / Catatan Order :</b></label>
                  <p>{{tr.deskripsi}}</p>
                  <label><b>Detil Order :</b></label>
                  <el-card v-loading="getODLoader">
                    <vs-table striped>
                      <template #thead>
                        <vs-tr>
                          <vs-th></vs-th>
                          <vs-th></vs-th>
                          <vs-th></vs-th>
                          <vs-th>Nama Produk</vs-th>
                          <vs-th>Harga</vs-th>
                          <vs-th>Jumlah</vs-th>
                        </vs-tr>
                      </template>
                      <template #tbody>
                        <vs-tr
                          :key="i"
                          v-for="(el, i) in getODs.data"
                          :data="el"
                        >
                          <vs-td></vs-td>
                          <vs-td>
                            <img v-if="el.nama_thumbnail" :src="api_url+'/storage/THUBNAILPRODUK/'+el.nama_thumbnail" width="400" alt="">
                            <img v-else src="../../assets/img/404.png" width="400" alt="" />
                          </vs-td>
                          <vs-td></vs-td>
                          <vs-td>{{ el.nama }}</vs-td>
                          <vs-td>{{ el.harga }}</vs-td>
                          <vs-td>{{ el.jumlah }}</vs-td>
                        </vs-tr>
                      </template>
                      <template #footer>
                        <vs-row>
                          <vs-col w="4">
                            <small>Total : Rp. {{ tr.total_harga }}</small>
                          </vs-col>
                          <vs-col w="8">

                          </vs-col>
                        </vs-row>
                      </template>
                    </vs-table>
                  </el-card>
                  <br>
                  <label><b>Atas Nama :</b></label>
                  <p>{{tr.atas_nama ? tr.atas_nama : "-"}}</p>
                  <label><b>Nomor Rekening :</b></label>
                  <p>{{tr.no_rek ? tr.no_rek : "-"}}</p>
                  <label><b>Tanggal Bayar :</b></label>
                  <p>{{formatDate(tr.tanggal_bayar) ? formatDate(tr.tanggal_bayar) : "-"}}</p>
                  <label><b>Bukti Pembayaran :</b></label>
                  <img v-if="tr.bukti_pembayaran" :src="api_url+'/storage/BUKTIPEMBAYARAN/'+tr.bukti_pembayaran" width="400" alt="">
                </el-card>
              </template>
            </vs-tr>
          </template>
          <template #footer>
            <vs-row>
              <vs-col w="2">
                <small>Total : {{ getOrders.total }} Data</small>
              </vs-col>
              <vs-col w="10">
                <vs-pagination
                  v-model="page"
                  :length="Math.ceil(getOrders.total / table.max)"
                />
              </vs-col>
            </vs-row>
          </template>
        </vs-table>
      </el-card>
    </div>

    <!-- Floating Button -->
    <!-- <el-tooltip
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
    </el-tooltip> -->
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
            <label>Tanggal Kegiatan</label>
            <vs-input type="date" v-model="form.tanggal" />
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
  </div>
</template>

<script>
import { mapMutations, mapGetters } from "vuex";
import { config } from "../../global.config";
export default {
  layout: "costumer",
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

      },
      formDialog: false,
    };
  },
  mounted() {
    this.user_id = JSON.parse(JSON.stringify(this.$auth.user.id));
    this.$store.dispatch("order/getCostumer", { user_id: this.user_id });
  },
  computed: {
    ...mapGetters("order", ["getOrders", "getLoader"]),
    ...mapGetters("orderdetail", ["getODs", "getODLoader"]),
  },
  methods: {
    getOrderDetail(id) {
      this.$store.dispatch("orderdetail/getCostumer", { order_id: id });
    },
    resetForm() {
      this.form = {
        id: "",
        uraian: "",
        tanggal: "",
        keterangan: "",
      };
      this.cart = "",
      this.isUpdate = false;
    },
    handleCurrentChange(val) {
      this.$store.commit("kegiatan/setPage", val);
      this.$store.dispatch("kegiatan/getAllPoktan", { user_id: this.user_id });
    },
    onSubmit(type = "store") {
      this.btnLoader = true;
      let formData = new FormData();
      formData.append("uraian", this.form.uraian);
      formData.append("tanggal", this.form.tanggal);
      formData.append("keterangan", this.form.keterangan);
      formData.append("user_id", this.user_id);
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
            this.$store.dispatch("kegiatan/getAllPoktan", {
              user_id: this.user_id,
            });
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
                this.$store.dispatch("kegiatan/getAllPoktan", {
                  user_id: this.user_id,
                  defaultPage: true,
                });
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
      if (date) {
        return moment(date).format("DD MMMM YYYY");
      }
      return null
    },
  },
  watch: {
    page(newValue, oldValue) {
      this.$store.commit('order/setPage', newValue)
      this.$store.dispatch('order/getCostumer', {user_id: this.user_id});
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
