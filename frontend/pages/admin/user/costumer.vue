<template>
  <div>
    <div class="header bg-success pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <!-- Card stats -->
          <h1 class="heading">User Costumer</h1>
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
              <vs-th>Nama</vs-th>
              <vs-th>Email</vs-th>
              <vs-th>Status</vs-th>
              <vs-th>Kontak</vs-th>
            </vs-tr>
          </template>
          <template #tbody>
            <vs-tr
              :key="i"
              v-for="(tr, i) in $vs.getSearch(getUsers.data, search)"
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
              <vs-td></vs-td>
              <vs-td>
                <vs-avatar v-if="!tr.nama_foto">
                  <img @click="handlePictureCardPreview()" :src="api_url+'/storage/USER/no-user-image.png'" alt="">
                </vs-avatar>
                <vs-avatar v-else>
                  <img @click="handlePictureCardPreview(tr.nama_foto)" :src="api_url+'/storage/USER/'+tr.nama_foto" alt="">
                </vs-avatar>
              </vs-td>
              <vs-td></vs-td>
              <vs-td>{{ tr.nama }}</vs-td>
              <vs-td>{{ tr.email }}</vs-td>
              <vs-td>
                <span class="badge badge-success" v-if="tr.aktif">Aktif</span>
                <span class="badge badge-warning" v-else>Non Aktif</span>
              </vs-td>
              <vs-td>{{ tr.nomer_hp }}</vs-td>
            </vs-tr>
          </template>
          <template #footer>
            <vs-row>
              <vs-col w="2">
                <small>Total : {{ getUsers.total }} Data</small>
              </vs-col>
              <vs-col w="10">
                <vs-pagination
                  v-model="page"
                  :length="Math.ceil(getUsers.total / table.max)"
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
      content="Tambah Admin Baru"
      placement="top-start"
    >
      <a
        class="float"
        @click="
          formDialog = true;
          titleDialog = 'Tambah Admin Baru';
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
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" w="12" style="padding:5px">
            <label>Foto</label>
            <el-upload :auto-upload="false" :on-change="handleChangeFile" list-type="picture-card" accept="image/*"
              :file-list="files" :limit="1">
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
            <label>Nama</label>
            <vs-input
              type="text"
              v-model="form.nama"
              placeholder="Masukkan Nama ..."
            ></vs-input>
          </vs-col>
          <vs-col
            v-if="!isUpdate"
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Email</label>
            <vs-input
              type="text"
              v-model="form.email"
              placeholder="Masukkan Email ..."
            ></vs-input>
          </vs-col>
          <vs-col
            v-if="!isUpdate"
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Password</label>
            <vs-input
              type="password"
              v-model="form.password"
              placeholder="Masukkan Password ..."
            ></vs-input>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Kontak / Nomer Hp</label>
            <vs-input
              type="text"
              v-model="form.nomer_hp"
              placeholder="Masukkan Kontak ..."
            ></vs-input>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Status</label>
            <vs-switch style="width: 20px" v-model="form.aktif" />
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
      <img v-if="dialogImageUrl" width="100%" :src="api_url+'/storage/USER/'+dialogImageUrl" alt="">
      <img v-else width="100%" :src="api_url+'/storage/USER/no-user-image.png'" alt="">
    </el-dialog>
  </div>
</template>

<script>
import { mapMutations, mapGetters } from "vuex";
import { config } from "../../../global.config";
export default {
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
      titleDialog: "",
      isUpdate: false,
      btnLoader: false,
      user_id: "",
      form: {
          id: "",
          nama: "",
          email: "",
          password: "",
          nomer_hp: "",
          aktif: false,
          foto: "",
        },
      formDialog: false,
      dialogImageUrl: "",
      dialogVisible: false,
    };
  },
  mounted() {
    this.user_id = JSON.parse(JSON.stringify(this.$auth.user.id))
    this.$store.dispatch("user/getAllUserCostumer", {})
  },
  computed: {
    ...mapGetters("user", ["getUsers", "getLoader"])
  },
  methods: {
    handlePictureCardPreview(foto) {
      this.dialogImageUrl = foto;
      this.dialogVisible = true;
    },
    resetForm() {
      this.form = {
        id: "",
        nama: "",
        email: "",
        password: "",
        nomer_hp: "",
        aktif: false,
        foto: "",
      };
      this.isUpdate = false;
    },
    handleChangeFile(file, fileList) {
      this.form.foto = file.raw
    },
    onSubmit(type = "store") {
      this.btnLoader = true
      let formData = new FormData()
      formData.append("nama", this.form.nama);
      formData.append("email", this.form.email);
      formData.append("password", this.form.password);
      formData.append("nomer_hp", this.form.nomer_hp);
      formData.append("aktif", this.form.aktif == true ? 1 : 0);
      formData.append("foto", this.form.foto);
      formData.append("user_id", this.user_id);
      let url = "/tambah_costumer";
      if (type == 'update') {
        url = `/ubah_costumer/${this.form.id}`
      }
      this.$axios.post(url, formData).then(resp => {
        if (resp.data.success) {
          this.$notify.success({
            title: 'Success',
            message: `Berhasil ${type == 'store' ? 'Menambah' : 'Mengubah'} Costumer`
          })
          this.resetForm()
          this.formDialog = false
          this.$store.dispatch("user/getAllUserCostumer", {})
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
      console.log(data.aktif);
      this.form.id = data.id;
      this.form.nama = data.nama;
      this.form.nomer_hp = data.nomer_hp;
      this.form.aktif = data.aktif == 1 ? true : false;
      this.form.email = '';
      this.form.password = '';
      this.formDialog = true;
      this.titleDialog = "Edit Costumer";
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
            .delete(`/hapus_admin/${id}`)
            .then((resp) => {
              if (resp.data.success) {
                this.$notify.success({
                  title: "Success",
                  message: "Berhasil Menghapus Data",
                });
                this.shiftDialog = false;
                this.$store.dispatch("user/getAllUserCostumer", {})
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
  watch: {
    page(newValue, oldValue) {
      this.$store.commit("user/setPage", newValue);
      this.$store.dispatch("user/getAllUserCostumer", {});
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
