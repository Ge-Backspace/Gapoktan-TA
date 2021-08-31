<template>
  <div>
    <div class="header bg-success pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <!-- Card stats -->
          <div class="row" style="padding-top: 20px">
            <div class="col-xl-12">
              <h1 class="mt-3 mb-0 text-sm">
                <span class="text-nowrap" style="font-size: 40px; color:white"
                  ><b>Hari yang bagus Mr.Guest!</b>
                </span>
              </h1>
            </div>
          </div>
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
          <div class="col-md-9 offset-md-3">
            Produk yang Baru ditambahkan :
          </div>
        </div>
        <vs-row style="margin-top: 20px;">
          <vs-col w="4"
            :key="i"
            v-for="(tr, i) in $vs.getSearch(getProduks.data, search)"
            :data="tr"
          >
            <vs-row>
            </vs-row>
            <vs-card type="3">
              <template #title>
                <h3>{{tr.nama}}</h3>
              </template>
              <template #img>
                <img v-if="tr.nama_thumbnail" :src="api_url+'/storage/THUBNAILPRODUK/'+tr.nama_thumbnail" alt="" />
                <img v-else src="../assets/img/404.png" alt="" />
              </template>
              <template #text>
                <p>{{shortText(tr.deskripsi)}}</p>
                <p>Rp.{{tr.harga}}</p><br>
                <!-- <label for="">Jumlah yang ingin dipesan:</label>
                <p><b>{{tr.jumlah}}</b></p>
                <vs-input
                  type="number"
                  v-model="tr.jumlah"
                  disabled
                ></vs-input> -->
                <!-- <el-input-number v-model="tr.jumlah" :disabled="true" @change="handleChangeJumlah" :min="1" :max="50"></el-input-number> -->
              </template>
              <template #interactions>
                <vs-tooltip>
                  <vs-button @click="$router.push('/login')" primary icon>
                    <i class="el-icon-shopping-cart-full"></i>
                  </vs-button>
                  <template #tooltip>
                    Tambah Ke Keranjang
                  </template>
                </vs-tooltip>
              </template>
            </vs-card>
          </vs-col>
        </vs-row>
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
          <vs-col w="3"></vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <img v-if="form.nama_thumbnail" :src="api_url+'/storage/THUBNAILPRODUK/'+form.nama_thumbnail" width="400" alt="" />
              <img v-else src="../assets/img/404.png" width="400" alt="" />
          </vs-col>
          <vs-col w="3"></vs-col>
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
            <label>Harga</label>
            <p>{{form.harga}}</p>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="12"
            style="padding: 5px"
          >
            <label>Deskripsi</label>
            <p>{{form.deskripsi}}</p>
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
                @click="onSubmit()"
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
  </div>
</template>

<script>
  import { mapMutations, mapGetters } from "vuex";
  import { config } from "../global.config";
  export default {
    layout: "guest",
    data() {
      return {
        api_url: config.baseApiUrl,
        page: 1,
        active: "",
        search: "",
        user_id:"",
        nama: "",
        formDialog: false,
        titleDialog: "",
        form: {
          produk_id: "",
          jumlah: "",
          nama: "",
          harga: "",
          deskripsi: "",
          nama_thumbnail:"",
        }
      }
    },
    mounted () {
      // this.user_id = JSON.parse(JSON.stringify(this.$auth.user.id))
      // this.$store.dispatch("account/getUserAccount", { user_id: this.user_id });
      this.$store.dispatch("produk/getAllCostumer", {});
    },
    computed: {
      ...mapGetters("account", ["getAccount"]),
      ...mapGetters("produk", ["getProduks", "getLoader"]),
    },
    methods: {
      shortText(text) {
        return (text.length > 50) ? text.substr(0, 50-1) + ' . . .' : text;
      },
      resetForm() {
        this.form = {
          produk_id: "",
          jumlah: "",
          nama: "",
          harga: "",
          deskripsi: "",
          nama_thumbnail:"",
        };
      },
      cartDetail(data) {
        this.form.produk_id = data.id;
        this.form.nama = data.nama;
        this.form.harga = data.harga;
        this.form.deskripsi = data.deskripsi;
        this.form.nama_thumbnail = data.nama_thumbnail;
        this.formDialog = true;
        this.titleDialog = "Detail Produk";
      },
      onSubmit() {
        this.btnLoader = true;
        let formData = new FormData();
        formData.append("produk_id", this.form.produk_id);
        formData.append("jumlah", this.form.jumlah);
        formData.append("user_id", this.user_id);
        this.$axios.post(`/tambah_chart`, formData)
        .then((resp) => {
          if (resp.data.success) {
            this.$notify.success({
              title: "Success",
              message: `Berhasil Menambah Produk Ke Keranjang`,
            });
            this.resetForm();
            this.formDialog = false;
            this.$store.dispatch("produk/getAllCostumer", {})
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
    },
  }
</script>

<style lang="scss" scoped>

</style>
