<template>
  <div>
    <div class="header bg-success pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <!-- Card stats -->
          <h1 class="heading">Alamat</h1>
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
              <vs-th>Nama Alamat</vs-th>
              <vs-th>Nomor Handphone</vs-th>
              <vs-th>Alamat Lengkap</vs-th>
            </vs-tr>
          </template>
          <template #tbody>
            <vs-tr
              :key="i"
              v-for="(tr, i) in $vs.getSearch(getAddresses.data, search)"
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
              <vs-td>{{ tr.nama }}</vs-td>
              <vs-td>{{ tr.nomor_hp }}</vs-td>
              <vs-td>{{ tr.alamat }}</vs-td>
            </vs-tr>
          </template>
          <template #footer>
            <vs-row>
              <vs-col w="2">
                <small>Total : {{ getAddresses.total }} Data</small>
              </vs-col>
              <vs-col w="10">
                <vs-pagination
                  v-model="page"
                  :length="Math.ceil(getAddresses.total / table.max)"
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
      content="Tambah Alamat Baru"
      placement="top-start"
    >
      <a
        class="float"
        @click="
          formDialog = true;
          titleDialog = 'Tambah Alamat Baru';
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
            <label>Nama Alamat</label>
            <vs-input
              type="text"
              v-model="form.nama"
              placeholder="Masukkan Nama Alamat ..."
            ></vs-input>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Nomer Handphone</label>
            <vs-input type="text"
            placeholder="Masukkan Nomor Handphone ..."
            v-model="form.nomor_hp"/>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Provinsi</label>
            <el-select
              placeholder="Pilih Provinsi"
              v-model="form.provinsi"
              @change="handleChangeProvinsi"
            >
              <el-option
                v-for="(item, i) in optionProvinsi"
                :key="i"
                :label="item.province"
                :value="item.province_id">
              </el-option>
            </el-select>
          </vs-col>
          <vs-col
            v-loading="kotaLoader"
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Kota / Kabupaten</label>
            <el-select v-if="selectKota" v-model="form.city" placeholder="Pilih Kota / Kabupaten" @change="handleChangeKota">
              <el-option
                v-for="(item, i) in optionKota"
                :key="i"
                :label="item.type+' '+item.city_name+' '+item.postal_code"
                :value="item">
              </el-option>
            </el-select>
            <el-select v-else disabled v-model="form.city" placeholder="Pilih Kota / Kabupaten">
            </el-select>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="12"
            style="padding: 5px"
          >
            <label>Pilih Lokasi</label>
            <!-- <el-input
              type="textarea"
              :rows="2"
              placeholder="Masukkan Alamat Lengkap ..."
              v-model="form.alamat">
            </el-input> -->
          </vs-col>
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" w="6" style="padding:5px">
            <label>Latitude</label>
            <vs-input type="number" v-model="form.lat" placeholder="Latitude"></vs-input>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" w="6" style="padding:5px">
            <label>Longitude</label>
            <vs-input type="number" v-model="form.lng" placeholder="Longitude"></vs-input>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" w="12" style="padding:5px">
            <GmapMap
            v-bind:center="center"
            v-bind:zoom="10"
            map-type-id="terrain"
            style="height: 250px"
            >
            <GmapMarker
              v-bind:position="{lat: form.lat, lng: form.lng}"
              v-bind:clickable="true"
              v-bind:draggable="true"
              @drag="updateCoordinates"
            />
            </GmapMap>
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
                >Batal</vs-button>
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
      optionProvinsi: [],
      optionKota: [],
      form: {
        id: "",
        nama: "",
        nomor_hp: "",
        alamat: "",
        provinsi: "",
        city: [],
        lat: -6.3473696,
        lng: 108.2946503,
      },
      kotaLoader: false,
      selectKota: false,
      center: {lat: -6.3473696, lng: 108.2946503},
      formDialog: false,
    };
  },
  mounted() {
    this.user_id = JSON.parse(JSON.stringify(this.$auth.user.id))
    this.$store.dispatch("address/getAll", { user_id: this.user_id })
    this.$axios.get('/provinsi').then(resp => {
      let reson = resp.data.data.rajaongkir.results
      let r = []
      reson.forEach(el => {
        r.push({
          province_id: el.province_id,
          province: el.province,
        })
      });
      this.optionProvinsi = r
    })
    console.log(this.optionProvinsi)
  },
  computed: {
    ...mapGetters("address", ["getAddresses", "getLoader"])
  },
  methods: {
    resetForm() {
      this.form = {
        id: "",
        nama: "",
        nomor_hp: "",
        alamat: "",
      };
      this.isUpdate = false;
    },
    handleChangeProvinsi(data) {
      this.kotaLoader = true
      this.$axios.get(`/kota/${data}`)
      .then(resp => {
        let reson = resp.data.data.rajaongkir.results
        let r = []
        reson.forEach(el => {
          r.push({
            city_id: el.city_id,
            province_id: el.province_id,
            province: el.province,
            type: el.type,
            city_name: el.city_name,
            postal_code: el.postal_code,
          })
        });
        this.optionKota = r
        this.selectKota = true
      })
    },
    handleChangeKota(data) {
      console.log(data)
    },
    onSubmit(type = "store") {
      this.btnLoader = true;
      let formData = new FormData();
      formData.append("nama", this.form.nama);
      formData.append("nomor_hp", this.form.nomor_hp);
      formData.append("alamat", this.form.alamat);
      formData.append("user_id", this.user_id);
      let url = "/tambah_address";
      if (type == "update") {
        url = `/ubah_address/${this.form.id}`;
      }

      this.$axios
        .post(url, formData)
        .then((resp) => {
          if (resp.data.success) {
            this.$notify.success({
              title: "Success",
              message: `Berhasil ${
                type == "store" ? "Menambah" : "Mengubah"
              } Alamat`,
            });
            this.resetForm();
            this.formDialog = false;
            this.$store.dispatch("address/getAll", { user_id: this.user_id });
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
      this.form.nama = data.nama;
      this.form.nomor_hp = data.nomor_hp;
      this.form.alamat = data.alamat;
      this.formDialog = true;
      this.titleDialog = "Edit Alamat";
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
            .delete(`/hapus_address/${id}`)
            .then((resp) => {
              if (resp.data.success) {
                this.$notify.success({
                  title: "Success",
                  message: "Berhasil Menghapus Data",
                });
                this.shiftDialog = false;
                this.$store.dispatch("address/getAll", { user_id: this.user_id, defaultPage: true });
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
    updateCoordinates(location) {
      this.form.lat = location.latLng.lat(),
      this.form.lng = location.latLng.lng()
    },
    markers (location) {
      return {lat: Number(location.lat), lng: Number(location.lng)}
    },
    markersDefault(location){
      return {lat: Number(-6.3473696), lng: Number(108.2946503) }
    },
  },
  watch: {
    page(newValue, oldValue) {
      this.$store.commit('address/setPage', newValue)
      this.$store.dispatch('address/getAll', {user_id: this.user_id})
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
