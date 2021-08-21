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
        <vs-row>
          <vs-col w="6"
            :key="i"
            v-for="(tr, i) in $vs.getSearch(getCarts.data, search)"
            :data="tr">
            <vs-row style="justify-content: space-between; margin-top: 20px;">
              <vs-card type="3">
                <template #title>
                  <h3>{{tr.nama}}</h3>
                </template>
                <template #img>
                  <img src="../../assets/img/404.png" alt="" />
                </template>
                <template #text>
                  <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit.</p>
                  <vs-button danger icon style="margin-left: 90px">
                    <i class="bx bx-trash"></i>
                  </vs-button>
                </template>
              </vs-card>
            </vs-row>
          </vs-col>
        </vs-row>
      </el-card>
    </div>
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
      user_id: "",
      form: {
        id: "",
        uraian: "",
        tanggal: "",
        keterangan: "",
      },
      formDialog: false,
      checkAll: false,
      checkedCities: ['Shanghai', 'Beijing'],
      cities: cityOptions,
      isIndeterminate: true
    };
  },
  mounted() {
    this.user_id = JSON.parse(JSON.stringify(this.$auth.user.id));
    this.$store.dispatch("cart/getAll", { user_id: this.user_id });
  },
  computed: {
    ...mapGetters("cart", ["getCarts", "getLoader"]),
  },
  methods: {
    handleCheckAllChange(val) {
      this.checkedCities = val ? cityOptions : [];
      this.isIndeterminate = false;
    },
    handleCheckedCitiesChange(value) {
      let checkedCount = value.length;
      this.checkAll = checkedCount === this.cities.length;
      this.isIndeterminate = checkedCount > 0 && checkedCount < this.cities.length;
    },
    resetForm() {
      this.form = {
        id: "",
        uraian: "",
        tanggal: "",
        keterangan: "",
      };
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
