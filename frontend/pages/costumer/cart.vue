<template>
  <div>
    <div class="header bg-success pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <!-- Card stats -->
          <h1 class="heading">Keranjang</h1>
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
        <!-- <el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange">Check all</el-checkbox> -->
        <el-checkbox-group v-model="checked" @change="handleCheckedChange">
        <vs-row style="justify-content: space-between; margin-top: 20px;">
          <vs-col w="6"
            :key="i"
            v-for="(tr, i) in $vs.getSearch(getCarts.data, search)"
            :data="tr"
          >
            <vs-row>
            <el-checkbox :label="tr" :key="i">{{tr.nama}}</el-checkbox>
            </vs-row>
            <vs-card type="3">
              <template #title>
                <h3>{{tr.nama}}</h3>
              </template>
              <template #img>
                <img v-if="tr.nama_thumbnail" :src="api_url+'/storage/THUBNAILPRODUK/'+tr.nama_thumbnail" alt="" />
                <img v-else src="../../assets/img/404.png" alt="" />
              </template>
              <template #text>
                <p>{{tr.deskripsi}}</p>
                <p>Rp.{{tr.harga}}</p><br>
                <label for="">Jumlah yang ingin dipesan:</label>
                <!-- <p><b>{{tr.jumlah}}</b></p> -->
                <vs-input
                  type="number"
                  v-model="tr.jumlah"
                  disabled
                ></vs-input>
                <!-- <el-input-number v-model="tr.jumlah" :disabled="true" @change="handleChangeJumlah" :min="1" :max="50"></el-input-number> -->
              </template>
              <template #interactions>
                <vs-tooltip>
                  <vs-button @click="deleteData(tr.id)" danger icon>
                    <i class="bx bx-trash"></i>
                  </vs-button>
                  <template #tooltip>
                    Hapus dari cart
                  </template>
                </vs-tooltip>
                <vs-tooltip>
                  <vs-button @click="editJumlah(tr)" primary icon>
                    <i class="bx bx-edit"></i>
                  </vs-button>
                  <template #tooltip>
                    Ubah jumlah cart
                  </template>
                </vs-tooltip>
              </template>
            </vs-card>
          </vs-col>
        </vs-row>
        </el-checkbox-group>
      </el-card>
    </div>

    <el-tooltip
      class="item"
      effect="dark"
      content="Tambah Dalam Order"
      placement="top-start"
    >
      <a
        class="float"
        @click="
          formDialog = true;
          titleDialog = 'Buat Order';
          countTotal();
        "
      >
        <i class="el-icon-shopping-cart-full my-float"></i>
      </a>
    </el-tooltip>

    <vs-dialog
      v-model="jmlDialog"
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
            <img v-if="form.nama_thumbnail" :src="api_url+'/storage/THUBNAILPRODUK/'+form.nama_thumbnail" width="400" alt="" />
              <img v-else src="../../assets/img/404.png" width="400" alt="" />
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Nama</label>
            <p>{{form.nama}}</p>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Jumlah</label>
            <vs-input
              type="number"
              v-model="form.jumlah"
              placeholder="Masukkan Jumlah ..."
            ></vs-input>
            <!-- <el-input-number v-model="form.jumlah" :min="1" :max="50"></el-input-number> -->
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
                @click="onJumlahSubmit()"
                >Simpan</vs-button
              >
            </vs-col>
            <vs-col w="6" style="padding: 5px">
              <vs-button
                block
                border
                @click="
                  jmlDialog = false;
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
                v-for="(tr, i) in cart"
                :data="tr"
              >
                <vs-td></vs-td>
                <vs-td>
                  <img v-if="tr.nama_thumbnail" :src="api_url+'/storage/THUBNAILPRODUK/'+tr.nama_thumbnail" width="400" alt="">
                  <img v-else src="../../assets/img/404.png" width="400" alt="" />
                </vs-td>
                <vs-td></vs-td>
                <vs-td>{{ tr.nama }}</vs-td>
                <vs-td>{{ tr.harga }}</vs-td>
                <vs-td>{{ tr.jumlah }}</vs-td>
              </vs-tr>
            </template>
            <template #footer>
            <vs-row>
              <vs-col w="4">
                <small>Total : Rp. {{ total }}</small>
              </vs-col>
              <vs-col w="8">

              </vs-col>
            </vs-row>
          </template>
          </vs-table>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="12"
            style="padding: 5px"
          >
            <label>Deskripsi / Catatan Order</label>
            <el-input
              type="textarea"
              :rows="2"
              placeholder="Masukkan Deskripsi / Catatan ..."
              v-model="form.deskripsi"
            >
            </el-input>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Alamat</label>
            <vs-select
              filter
              placeholder="Pilih Alamat"
              v-model="form.address_id"
            >
              <vs-option
                v-for="op in getOptionAddresses.data"
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

  </div>
</template>

<script>
const cityOptions = ['Shanghai', 'Beijing', 'Guangzhou', 'Shenzhen'];
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
      num: [],
      user_id: "",
      form: {
        id: "",
        nama: "",
        nama_thumbnail: "",
        address_id: "",
        deskripsi: "",
        jumlah: ""
      },
      cart: [],
      formDialog: false,
      jmlDialog: false,
      checkAll: false,
      total: "",
      checked: [],
      cities: cityOptions,
      isIndeterminate: true
    };
  },
  mounted() {
    this.user_id = JSON.parse(JSON.stringify(this.$auth.user.id));
    this.$store.dispatch("cart/getAll", { user_id: this.user_id });
    this.$store.dispatch("option/getAddresses", {user_id: this.user_id});
  },
  computed: {
    ...mapGetters("cart", ["getCarts", "getLoader"]),
    ...mapGetters("option", ["getOptionAddresses", "getOpLoader"]),
  },
  methods: {
    setCartId(id) {
      this.form.id = id
      console.log(id)
    },
    handleChangeJumlah(currentValue, oldValue) {
      console.log(currentValue, this.form.id)
      // let formData = new FormData();
      // formData.append("jumlah", val);
      // this.$axios.post(`/ubah_kegiatan/${this.form.id}`, formData)
      // .then((res) => {
      //   //
      // })
    },
    handleCheckAllChange(val) {
      // this.checked = val ? cityOptions : [];
      // this.isIndeterminate = false;
    },
    handleCheckedChange(value) {
      this.cart = value
      console.log(value, this.cart)
      // let checkedCount = value.length;
      // this.checkAll = checkedCount === this.cities.length;
      // this.isIndeterminate = checkedCount > 0 && checkedCount < this.getCharts.length;
    },
    resetForm() {
      this.form = {
        id: "",
        nama: "",
        nama_thumbnail: "",
        address_id: "",
        deskripsi: "",
        jumlah: ""
      };
      this.cart = [];
      this.checked = [];
      this.isUpdate = false;
    },
    editJumlah(data) {
      this.form.id = data.id;
      this.form.nama = data.nama;
      this.form.jumlah = data.jumlah;
      this.form.nama_thumbnail = data.nama_thumbnail;
      this.jmlDialog = true;
      this.titleDialog = "Edit Jumlah Cart Produk";
    },
    onJumlahSubmit() {
      this.btnLoader = true;
      let formData = new FormData();
      formData.append("jumlah", this.form.jumlah);
      this.$axios.post(`/ubah_jumlah_chart/${this.form.id}`, formData)
      .then((resp) => {
        if (resp.data.success) {
          this.$notify.success({
            title: "Success",
            message: `Berhasil Mengubah Jumlah Cart`,
          });
          this.resetForm();
          this.jmlDialog = false;
          this.$store.dispatch("cart/getAll", { user_id: this.user_id })
        }
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
    countTotal(){
      let tot = 0
      this.cart.forEach((el, i) => {
        tot = tot + (el.harga * el.jumlah)
      })
      this.total = tot
    },
    onSubmit(type = "store") {
      this.btnLoader = true;
      let formData = new FormData();
      formData.append("address_id", this.form.address_id);
      formData.append("deskripsi", this.form.deskripsi);
      formData.append("total_harga", this.total);
      formData.append("user_id", this.user_id);
      this.cart.forEach((el, i) => {
        formData.append("cart["+i+"]", el.id);
      });

      let url = "/tambah_order";
      if (type == "update") {
        url = `/ubah_order/${this.form.id}`;
      }

      this.$axios
        .post(url, formData)
        .then((resp) => {
          if (resp.data.success) {
            this.$notify.success({
              title: "Success",
              message: `Berhasil ${
                type == "store" ? "Menambah" : "Mengubah"
              } Order`,
            });
            this.resetForm();
            this.formDialog = false;
            this.$store.dispatch("cart/getAll", { user_id: this.user_id });
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
            .delete(`/hapus_chart/${id}`)
            .then((resp) => {
              if (resp.data.success) {
                this.$notify.success({
                  title: "Success",
                  message: "Berhasil Menghapus Data",
                });
                this.formDialog = false;
                this.$store.dispatch("cart/getAll", { defaultPage: true, user_id: this.user_id });
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
    // formatDate(date) {
    //   return moment(date).format("DD MMMM YYYY");
    // },
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
