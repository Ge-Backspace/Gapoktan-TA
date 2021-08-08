<template>
  <div>
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <!-- Card stats -->
          <h1 class="heading">Master Kategori Produk</h1>
        </div>
      </div>
    </div>
    <div class="container-fluid mt--5">
      <el-card v-loading="getLoader" style="margin-top: 60px">
        <div class="row" style="margin-bottom: 20px">
          <div class="col-md-3 offset-md-9">
            <el-input
              placeholder="Cari"
              v-model="search"
              @change="searchData()"
              size="mini"
            >
              <i slot="prefix" class="el-input__icon el-icon-search"></i>
            </el-input>
          </div>
        </div>
        <vs-table striped>
          <template #thead>
            <vs-tr>
              <vs-th>Action</vs-th>
              <vs-th>Name</vs-th>
            </vs-tr>
          </template>
          <template #tbody>
            <vs-tr :key="i" v-for="(tr, i) in getKategoris.data" :data="tr">
              <vs-td>
                <el-tooltip content="Edit" placement="top-start" effect="dark">
                  <el-button size="mini" @click="edit(tr)" icon="fa fa-edit"></el-button>
                </el-tooltip>

                <el-tooltip content="Delete" placement="top-start" effect="dark">
                  <el-button size="mini" type="primary" @click="deleteKategori(tr.id)" icon="fa fa-trash">
                  </el-button>
                </el-tooltip>
              </vs-td>
              <vs-td>{{ tr.nama }}</vs-td>
            </vs-tr>
          </template>
          <template #footer>
            <vs-row>
              <vs-col w="2">
                <small>Total : {{getKategoris.total}} Data</small>
              </vs-col>
              <vs-col w="10">
                <vs-pagination v-model="page" :length="Math.ceil(getKategoris.total / table.max)" />
              </vs-col>
            </vs-row>
          </template>
        </vs-table>
      </el-card>
    </div>

    <!-- Floating Button -->
    <el-tooltip class="item" effect="dark" content="Tambah Kategori Baru" placement="top-start">
      <a class="float" @click="kategoriDialog = true; titleDialog = 'Tambah Kategori Baru'">
        <i class="el-icon-plus my-float"></i>
      </a>
    </el-tooltip>
    <!-- End floating button -->

    <vs-dialog v-model="kategoriDialog" :width="$store.state.drawer.mode === 'mobile' ? '80%' : '60%'"
      @close="resetForm()">
      <template #header>
        <h1 class="not-margin">
          {{titleDialog}}
        </h1>
      </template>
      <div class="con-form">
        <vs-row>
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" w="12" style="padding:5px">
            <label>Nama Kategori</label>
            <vs-input type="text" v-model="form.nama" placeholder="Masukkan Nama ..."></vs-input>
          </vs-col>
        </vs-row>
      </div>

      <template #footer>
        <div class="footer-dialog">
          <vs-row>
            <vs-col w="6" style="padding:5px">
              <vs-button block :loading="btnLoader" @click="onSubmit('update')" v-if="isUpdate">Update</vs-button>
              <vs-button block :loading="btnLoader" @click="onSubmit('store')" v-else>Simpan</vs-button>
            </vs-col>
            <vs-col w="6" style="padding:5px">
              <vs-button block border @click="kategoriDialog = false; resetForm()">Batal</vs-button>
            </vs-col>
          </vs-row>
          <div>&nbsp;</div>
        </div>
      </template>
    </vs-dialog>

  </div>
</template>

<script>
  import {mapMutations, mapGetters} from 'vuex';
  import {config} from '../../../global.config'
  export default {
    layout: 'admin',
    data() {
      return {
        api_url: config.baseApiUrl,
        table: {
          max: 10
        },
        page: 1,
        active: '',
        search:'',
        titleDialog: '',
        isUpdate: false,
        btnLoader: false,
        form: {
          id: '',
          nama: '',
        },
        kategoriDialog: false,
      }
    },
    mounted () {
      this.$store.dispatch('kategori/getAll', {});
    },
    computed: {
      ...mapGetters('kategori', [
        'getKategoris',
        'getLoader'
      ])
    },
    methods: {
      resetForm() {
        this.form = {
          id: '',
          nama: '',
        }
        this.isUpdate = false
      },
      handleCurrentChange(val) {
        this.$store.commit('kategori/setPage', val)
        this.$store.dispatch('kategori/getAll', {});
      },
      onSubmit(type = 'store') {
        this.btnLoader = true
        let formData = new FormData()
        formData.append("nama", this.form.nama)
        let url = "/tambah_kategori";
        if (type == 'update') {
          url = `/ubah_kategori/${this.form.id}`
        }

        this.$axios.post(url, formData).then(resp => {
          if (resp.data.success) {
            this.$notify.success({
              title: 'Success',
              message: `Berhasil ${type == 'store' ? 'Menambah' : 'Mengubah'} Kegiatan`
            })
            this.resetForm()
            this.kategoriDialog = false
            this.$store.dispatch('kategori/getAll', {});
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
      edit(data) {
        this.form.id = data.id
        this.form.nama = data.nama
        this.kategoriDialog = true
        this.titleDialog = 'Edit Kategori'
        this.isUpdate = true
      },
      deleteKategori(id) {
        this.$swal({
          title: 'Perhatian!',
          text: "Yakin Menghapus Data Ini?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya',
          cancelButtonText: 'Tidak'
        }).then((result) => {
          if (result.isConfirmed) {
            this.$axios.delete(`/hapus_kategori/${id}`).then(resp => {
              if (resp.data.success) {
                this.$notify.success({
                  title: 'Success',
                  message: 'Berhasil Menghapus Data'
                })
                this.shiftDialog = false
                this.$store.dispatch('kategori/getAll', { defaultPage: true });
              }
            }).finally(() => {
              //
            }).catch(err => {
              this.$notify.error({
                title: 'Error',
                message: err.response.data.message
              })
            })
          }
        })
      },
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
