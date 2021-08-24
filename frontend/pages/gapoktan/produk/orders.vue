<template>
  <div>
    <div class="header bg-success pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <!-- Card stats -->
          <h1 class="heading">Konfirmasi Order</h1>
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
              <vs-th>Kode Order</vs-th>
              <vs-th>Nama Produk</vs-th>
              <vs-th>Jumlah</vs-th>
              <vs-th>Status Pembayaran</vs-th>
              <vs-th>Status Order</vs-th>
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
                <el-tooltip
                  v-if="tr.status_order < 3"
                  content="Change Status"
                  placement="top-start"
                  effect="dark"
                >
                  <el-button
                    size="mini"
                    type="warning"
                    @click="changeStatus(tr)"
                    icon="el-icon-refresh"
                  >
                  </el-button>
                </el-tooltip>

                <el-tooltip
                  v-else
                  content="Change Status"
                  placement="top-start"
                  effect="dark"
                >
                  <el-button
                    disabled
                    size="mini"
                    type="warning"
                    icon="el-icon-refresh"
                  >
                  </el-button>
                </el-tooltip>

              </vs-td>
              <vs-td>
                <vs-avatar v-if="!tr.nama_thumbnail">
                  <img :src="api_url+'/storage/USER/no-user-image.png'" alt="">
                </vs-avatar>
                <vs-avatar v-else>
                  <img  @click="handlePictureCardPreview(tr.nama_thumbnail)" :src="api_url+'/storage/THUBNAILPRODUK/'+tr.nama_thumbnail" alt="">
                </vs-avatar>
              </vs-td>
              <vs-td>{{ tr.kd_order }}</vs-td>
              <vs-td>{{ tr.nama_produk }}</vs-td>
              <vs-td>{{ tr.jumlah }}</vs-td>
              <vs-td>
                <span class="badge badge-success" v-if="tr.status_payment"
                  >Sudah Upload</span
                >
                <span class="badge badge-primary" v-else>Belum Upload</span>
              </vs-td>
              <vs-td>
                <!-- <span class="badge badge-light" v-if="tr.status_order == 0">Belum Dikonfirmasi</span> -->
                <span class="badge badge-primary" v-if="tr.status_order == 1">Terkonformasi</span>
                <span class="badge badge-success" v-else-if="tr.status_order == 2">Dikemas</span>
                <span class="badge badge-info" v-else-if="tr.status_order == 3">Terkirim</span>
                <span class="badge badge-danger" v-else>Ditolak</span>
              </vs-td>
              <template #expand>
                <el-card>
                  <label><b>Alamat Lengkap :</b></label>
                  <p>{{tr.nama_alamat}} - {{tr.alamat}}</p>
                  <label><b>Deskripsi / Catatan Order :</b></label>
                  <p>{{tr.deskripsi}}</p>
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
            <label>Status Order</label>
            <vs-select
              placeholder="Status Order"
              v-model="statusOrder"
            >
              <vs-option v-if="form.status < 1" label="Konfirmasi" value="1">
                Konfirmasi
              </vs-option>
              <vs-option v-if="form.status < 2" label="Dikemas" value="2">
                Dikemas
              </vs-option>
              <vs-option v-if="form.status < 3" label="Terkirim" value="3">
                Terkirim
              </vs-option>
              <vs-option v-if="form.status < 4" label="Ditolak" value="4">
                Ditolak
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

    <el-dialog :visible.sync="dialogVisible">
      <img width="100%" :src="api_url+'/storage/THUBNAILPRODUK/'+dialogImageUrl" alt="">
    </el-dialog>

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
        fileList: [],
        user_id: "",
        statusOrder: 0,
        form: {
          id: "",
          status: "",
        },
        isUpdate: false,
        btnLoader: false,
        titleDialog: "",
        dialogImageUrl: "",
        dialogVisible: false,
        formDialog: false,
        gambarDialog: false,
      }
    },
    mounted () {
      this.user_id = JSON.parse(JSON.stringify(this.$auth.user.id))
      this.$store.dispatch("order/getGapoktan", {user_id: this.user_id})
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
          status: "",
        };
        this.statusOrder = 0;
        this.fileList = [];
        this.isUpdate = false;
      },
      handlePictureCardPreview(gambar) {
        this.dialogImageUrl = gambar;
        this.dialogVisible = true;
      },
      handlePictureCardPreview(gambar){
        this.dialogImageUrl = gambar;
        this.dialogVisible = true;
      },
      changeStatus(data) {
        this.form.id = data.id;
        this.form.status = data.status_order;
        this.isUpdate = true;
        this.titleDialog = "Ubah Status Order";
        this.formDialog = true;
      },
      onSubmit(type = "store") {
        this.btnLoader = true
        let formData = new FormData()
        formData.append("status_order", this.statusOrder)
        let url = "/tambah_order";
        if (type == 'update') {
          url = `/ubah_status_order/${this.form.id}`
        }
        this.$axios.post(url, formData).then(resp => {
          if (resp.data.success) {
            this.$notify.success({
              title: 'Success',
              message: `Berhasil ${type == 'store' ? 'Menambah' : 'Mengubah'} Status Order`
            })
            this.resetForm()
            this.formDialog = false
            this.$store.dispatch("order/getGapoktan", {user_id: this.user_id})
          }
        }).finally(() => {
          this.btnLoader = false
        }).catch(err => {
          let error = err.response.data.data
          if (error) {
            this.showErrorField(error)
          } else {
            this.$notify.error({
              title: 'Error',
              message: err.response.data.message
            })
          }
        })
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
        this.$store.commit("order/setPage", newValue);
        this.$store.dispatch("order/getGapoktan", {user_id: this.user_id});
      }
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
