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
              <vs-th>Status Order</vs-th>
            </vs-tr>
          </template>
          <template #tbody>
            <vs-tr
              :key="i"
              v-for="(tr, i) in $vs.getSearch(getOrders.data, search)"
              :data="tr"
              @click="getOrderDetail(tr.id); setId(tr.id)"
            >
              <vs-td>
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
              <vs-td> RP. {{ tr.total_harga }}</vs-td>
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
                          <vs-td>
                            <img v-if="el.nama_thumbnail" :src="api_url+'/storage/THUBNAILPRODUK/'+el.nama_thumbnail" width="30" alt="">
                            <img v-else src="../../assets/img/404.png" width="30" alt="" />
                          </vs-td>
                          <vs-td>{{ el.nama }}</vs-td>
                          <vs-td>RP. {{ el.harga }}</vs-td>
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
                  <label><b>Kurir :</b></label>
                  <p>{{tr.kurir ? tr.kurir : "-"}}</p>
                  <label><b>Service / Paket :</b></label>
                  <p>{{tr.service ? tr.service : "-"}}</p>
                  <label><b>Ongkir :</b></label>
                  <p>{{tr.ongkir ? tr.ongkir : "-"}}</p>
                  <label><b>Estimasi Hari :</b></label>
                  <p>{{tr.etd ? tr.etd : "-"}}</p>
                </el-card>
                <hr>
                <div>
                  <vs-button
                    relief
                    :active="active == 1"
                    @click="gambarDialog = true; titleDialog = 'Upload Bukti Pembayaran'; handleOngkir(getODs.data)"
                  >
                    Upload Pembayaran
                  </vs-button>
                </div>
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
      <el-card v-loading="getLoader" style="margin-top: 60px">
        <div class="row" style="margin-bottom: 20px">
          <label><b>* Pembayaran Ke Rekening</b> : <br/>-  BNI 8769765809753444 <br/>- BRI 6759765466768091 <b><br/>* Setelah melakukan pembayaran segera upload bukti pembayaran anda</b></label>
        </div>
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

    <el-dialog
      :visible.sync="gambarDialog"
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
            <label>Foto Bukti Pembayaran</label>
            <el-upload :auto-upload="false" :on-change="handleChangeFile" action="https://jsonplaceholder.typicode.com/posts/" list-type="picture-card" accept="image/*"
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
            <label>Atas Nama</label>
            <vs-input
              type="text"
              v-model="form.atas_nama"
              placeholder="Masukkan Atas Nama ..."
            ></vs-input>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Nomer Rekening</label>
            <vs-input
              type="text"
              v-model="form.no_rek"
              placeholder="Masukkan Atas Nama ..."
            ></vs-input>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Tanggal Bayar</label>
            <vs-input type="date" v-model="form.tanggal_bayar" />
          </vs-col>
          <vs-col w="6"></vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Kurir</label>
            <el-select
              placeholder="Pilih Kurir"
              v-model="form.courier"
              @change="handleChangeCourier"
            >
              <el-option :value="'jne'" :label="'JNE'"></el-option>
              <el-option :value="'pos'" :label="'POS Indonesia'"></el-option>
            </el-select>
          </vs-col>
          <vs-col
            v-loading="kurirLoader"
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Service / Paket</label>
            <!-- <vs-select
              v-if="prosesKurir"
              placeholder="Pilih Service"
              v-model="form.cost"
              @change="handleChangeService"
            >
              <vs-option
                v-for="(op, i) in responOngkir"
                :key="i"
                :label="op.name+' '+op.service+' - RP. '+op.value+' ('+op.etd+')'"
               :value="[op.code,op.name, op. service, op.description, op.value, op.etd, op.note,]"
              >
                {{ op.name+' '+op.service+' - RP. '+op.value+' ('+op.etd+')' }}
              </vs-option>
            </vs-select>
            <vs-select
              v-else
              disabled
              placeholder="Pilih Service"
            ></vs-select> -->
            <el-select v-if="prosesKurir" v-model="form.cost" placeholder="Pilih Service" @change="handleChangeService">
              <el-option
                v-for="(item, i) in responOngkir"
                :key="i"
                :label="item.name+' '+item.service+' - RP. '+item.value+' Estimasi hari : '+item.etd+')'"
                :value="item">
              </el-option>
            </el-select>
            <el-select v-else disabled v-model="form.cost" placeholder="Pilih Service" @change="handleChangeService">
            </el-select>
          </vs-col>
          <!-- <vs-col
            v-loading="kurirLoader"
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Paket</label>
            <vs-select
              v-if="prosesPaket"
              placeholder="Pilih Paket"
              v-model="selectedPaket"
            >
              <vs-option
                v-for="c in costs"
                :key="c"
                :label="c.service"
                :value="c.cost"
              >
                {{ c.service }}
              </vs-option>
            </vs-select>
            <vs-select
              v-else
              disabled
              placeholder="Pilih Paket"
            ></vs-select>
          </vs-col> -->
        </vs-row>
      </div>

      <template #footer>
        <div class="footer-dialog">
          <vs-row>
            <vs-col w="6" style="padding: 5px">
              <vs-button
                block
                :loading="btnLoader"
                @click="onSubmit()"
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
    </el-dialog>
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
      fileList: [],
      btnLoader: false,
      gambarDialog: false,
      user_id: "",
      form: {
        id: "",
        atas_nama: "",
        no_rek: "",
        tanggal_bayar: "",
        foto: "",
        courier: "",
        service: "",
        ongkir: "",
        etd: "",
        berat: "",
        cost: [],
      },
      kurirLoader: false,
      responOngkir: [],
      prosesKurir: false,
      prosesPaket: false,
      selectedService: [],
      costs:[],
      selectedPaket: "",
      // prosesgetPaketLoader: false,
      // prosesgetPaket: false,
      // services: [],
      // selectedServices: [],
    };
  },
  mounted() {
    this.user_id = JSON.parse(JSON.stringify(this.$auth.user.id));
    this.$store.dispatch("order/getCostumer", { user_id: this.user_id });
    // this.$store.dispatch("ongkir/getCosts", { berat: 0, courier: 'jne' });
  },
  computed: {
    ...mapGetters("ongkir", ["getOngkirs", "getOngkirLoader"]),
    ...mapGetters("order", ["getOrders", "getLoader"]),
    ...mapGetters("orderdetail", ["getODs", "getODLoader"]),
  },
  methods: {
    getOrderDetail(id) {
      this.$store.dispatch("orderdetail/getCostumer", { order_id: id });
    },
    setId(id) {
      this.form.id = id
    },
    resetForm() {
      this.form = {
        id: "",
        atas_nama: "",
        no_rek: "",
        tanggal_bayar: "",
        foto: "",
      };
      this.cart = "",
      this.isUpdate = false;
    },
    handleChangeFile(file, fileList) {
      this.form.foto = file.raw
    },
    handleChangeService(data) {
      this.form.service = data.service;
      this.form.ongkir = data.value;
      this.form.etd = data.etd;
    },
    handleChangeCourier(data){
      this.kurirLoader = true
      let formData = new FormData();
      formData.append("weight", this.form.berat);
      formData.append("courier", data);
      formData.append("origin", 149);
      formData.append("destination", 149);
      this.$axios.post('/ongkir', formData)
      .then(resp => {
        let respon = resp.data.data.rajaongkir.results;
        respon.forEach((element, index) => {
          element.costs.forEach((el, i) => {
            let r = []
            r[i] = {
              code: element.code,
              name: element.name,
              service: el.service,
              description: el.description,
              value: el.cost[0].value,
              etd: el.cost[0].etd,
              note: el.cost[0].note,
            }
            this.responOngkir = r
          });
        });
        console.log(this.responOngkir);
        this.prosesKurir = true;
      }).catch(e => {
          console.log(e)
      }).finally(() => {
        this.kurirLoader = false;
      })
    },
    handleOngkir(data){
      let berat = 0;
      data.forEach((el, i) => {
        berat = berat + el.berat;
      });
      this.form.berat = berat;
    },
    onSubmit() {
      this.btnLoader = true;
      let formData = new FormData();
      console.log(this.form.courier);
      formData.append("atas_nama", this.form.atas_nama);
      formData.append("no_rek", this.form.no_rek);
      formData.append("tanggal_bayar", this.form.tanggal_bayar);
      formData.append("bukti_pembayaran", this.form.foto);
      formData.append("kurir", this.form.courier);
      formData.append("service", this.form.service);
      formData.append("ongkir", this.form.ongkir);
      formData.append("etd", this.form.etd);
      formData.append("web", true);
      let url = url = `/ubah_order/${this.form.id}`;

      this.$axios
        .post(url, formData)
        .then((resp) => {
          if (resp.data.success) {
            this.$notify.success({
              title: "Success",
              message: `Berhasil Mengupload Bukti Pembayaran`,
            });
            this.resetForm();
            this.gambarDialog = false;
            this.$store.dispatch("order/getCostumer", { user_id: this.user_id });
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
