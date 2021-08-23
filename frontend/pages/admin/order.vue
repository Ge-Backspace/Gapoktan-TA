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
              <vs-th></vs-th>
              <vs-th></vs-th>
              <vs-th>Nama Produk</vs-th>
              <vs-th>Stok</vs-th>
              <vs-th>Harga</vs-th>
              <vs-th>Status</vs-th>
            </vs-tr>
          </template>
          <template #tbody>
            <vs-tr
              :key="i"
              v-for="(tr, i) in $vs.getSearch(getProduks.data, search)"
              :data="tr"
            >
              <vs-td>
                <el-tooltip
                  v-if="tr.status == 0"
                  content="Change Status"
                  placement="top-start"
                  effect="dark"
                >
                  <el-button
                    size="mini"
                    type="warning"
                    @click="changeStatus(tr.id)"
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
              <vs-td></vs-td>
              <vs-td>
                <vs-avatar v-if="!tr.nama_thumbnail">
                  <img :src="api_url+'/storage/USER/no-user-image.png'" alt="">
                </vs-avatar>
                <vs-avatar v-else>
                  <img  @click="handlePictureCardPreview(tr.nama_thumbnail)" :src="api_url+'/storage/THUBNAILPRODUK/'+tr.nama_thumbnail" alt="">
                </vs-avatar>
              </vs-td>
              <vs-td></vs-td>
              <vs-td>{{ tr.nama }}</vs-td>
              <vs-td>{{ tr.stok }}</vs-td>
              <vs-td>{{ tr.harga }}</vs-td>
              <vs-td>
                <span class="badge badge-primary" v-if="tr.status == 0">Belum Aktif</span>
                <span class="badge badge-success" v-else-if="tr.status == 1">Aktif</span>
                <span class="badge badge-warning" v-else>Ditolak</span>
              </vs-td>
            </vs-tr>
          </template>
          <template #footer>
            <vs-row>
              <vs-col w="2">
                <small>Total : {{ getProduks.total }} Data</small>
              </vs-col>
              <vs-col w="10">
                <vs-pagination
                  v-model="page"
                  :length="Math.ceil(getProduks.total / table.max)"
                />
              </vs-col>
            </vs-row>
          </template>
        </vs-table>
      </el-card>
    </div>

    <el-dialog :visible.sync="dialogVisible">
      <img width="100%" :src="api_url+'/storage/THUBNAILPRODUK/'+dialogImageUrl" alt="">
    </el-dialog>
  </div>
</template>

<script>
import { mapMutations, mapGetters } from "vuex";
import { config } from "../../global.config";
  export default {
    components: {},
    layout: "admin",
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
        form: {
          id: "",
          status: "",
        },
        dialogImageUrl: "",
        dialogVisible: false,
        formDialog: false,
        gambarDialog: false,
      }
    },
    mounted () {
      this.user_id = JSON.parse(JSON.stringify(this.$auth.user.id))
      this.$store.dispatch("produk/getAdmin", {})
    },
    computed: {
    ...mapGetters("produk", ["getProduks", "getLoader"]),
    },
    methods: {
      resetForm() {
        this.form = {
          id: "",
          status: "",
        };
        this.fileList = [];
        this.isUpdate = false;
      },
      handlePictureCardPreview(gambar) {
        this.dialogImageUrl = gambar;
        this.dialogVisible = true;
      },
      handleCurrentChange(val) {
        this.$store.commit("produk/setPage", val);
        this.$store.dispatch("produk/getAdmin", {});
      },
      changeStatus(id) {
        this.$swal({
        title: "Konfirmasi",
        text: "Ubah Status Produk ini",
        icon: "warning",
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonColor: "#3085d6",
        denyButtonColor: "#d33",
        confirmButtonText: "Aktifkan",
        denyButtonText: "Tolak",
        cancelButtonText: "Batal",
        }).then((result) => {
          let status = 0;
          if (result.isConfirmed) {
            status = 1;
          } else if (result.isDenied) {
            status = 2;
          }
          if (status != 0) {
            this.change(id, status);
          }
        });
      },
      change(id, status) {
      let formData = new FormData();
      formData.append("status", status);
      this.$axios
        .post(`/ubah_status_produk/${id}`, formData)
        .then((resp) => {
          if (resp.data.success) {
            this.$notify.success({
              title: "Success",
              message: "Berhasil Mengubah Data",
            });
          }
        })
        .catch((err) => {
          this.$notify.error({
            title: "Error",
            message: err.response.data.message,
          });
        })
        .finally(() => {
          this.$store.dispatch("produk/getAdmin", {
            company_id: this.company_id,
          });
        });
      }
    },
    watch: {
      page(newValue, oldValue) {
        this.$store.commit("produk/setPage", newValue);
        this.$store.dispatch("produk/getAdmin", {});
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
