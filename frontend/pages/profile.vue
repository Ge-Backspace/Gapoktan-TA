<template>
  <div>
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <!-- Card stats -->
          <h1 class="heading">Profil</h1>
        </div>
      </div>
    </div>
    <div class="container-fluid mt--5">
      <div class="row">
        <div class="col-md-12">
          <vs-button
            @click="tambahDialog = true"
            icon
            dark
            shadow
            style="float: right; margin-left: -30px; margin-top: 10px"
          >
            <i class="bx bx-edit"></i>
          </vs-button>
          <el-card style="margin-top: 20px">
            <div class="text-center">
              <!-- <el-avatar
                size="large"
                style="width: 100px; height: 100px"
                :src="form.foto_url"
              ></el-avatar> -->
              <br />
              <!-- <h3>{{ getEmployees.name }} ({{ form.nip }})</h3> -->
              <!-- <span class="badge badge-primary">{{ form.jabatan }}</span> -->
            </div>
            <div style="margin-top: 20px">
              <vs-table striped>
                <template #thead>
                  <vs-tr >
                    <vs-th class="name"> Data Diri </vs-th>
                    <vs-th></vs-th>
                  </vs-tr>
                </template>
                <template #tbody>
                  <vs-tr>
                    <vs-td><b>Email</b></vs-td>
                    <!-- <vs-td>{{ form.email }}</vs-td> -->
                  </vs-tr>
                  <vs-tr>
                    <vs-td><b>No HP</b></vs-td>
                    <!-- <vs-td>{{ form.no_hp }}</vs-td> -->
                  </vs-tr>
                  <vs-tr>
                    <vs-td><b>NIP</b></vs-td>
                    <!-- <vs-td>{{ form.no_hp }}</vs-td> -->
                  </vs-tr>
                  <!-- <vs-tr>
                    <vs-td><b>Pemda Prov / Kab / Kota</b></vs-td>
                    <vs-td>{{ form.goverment ? form.goverment.nama : '-' }}</vs-td>
                  </vs-tr>
                  <vs-tr>
                    <vs-td><b>Unit Kerja</b></vs-td>
                    <vs-td>{{ form.unit_kerja}}</vs-td>
                  </vs-tr>
                  <vs-tr>
                    <vs-td><b>Organisasi</b></vs-td>
                    <vs-td>{{ form.unit_kerja}}</vs-td>
                  </vs-tr> -->
                </template>
              </vs-table>
            </div>
          </el-card>
        </div>
      </div>
    </div>

    <vs-dialog
      v-model="tambahDialog"
      :width="$store.state.drawer.mode === 'mobile' ? '80%' : '60%'"
    >
      <template #header>
        <h1 class="not-margin">Ubah Profile</h1>
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
            <label>Foto</label>
            <!-- <el-upload
              :action="api_url + '/fake-upload'"
              :on-change="handleChangeFile"
              list-type="picture-card"
              accept="image/*"
              :file-list="files"
              :limit="1"
            >
              <i class="el-icon-plus"></i>
            </el-upload> -->
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
              placeholder="Nama"
            ></vs-input>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>NIP</label>
            <vs-input
              type="number"
              v-model="form.nip"
              placeholder="NIP"
            ></vs-input>
          </vs-col>
          <!-- <vs-col vs-type="flex" vs-justify="center" vs-align="center" w="6" style="padding:5px">
            <label>No HP</label>
            <vs-input type="number" v-model="form.no_hp" placeholder="No HP"></vs-input>
          </vs-col> -->
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Email</label>
            <vs-input v-model="form.email" placeholder="Email"></vs-input>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Jabatan</label>
            <vs-input
              type="text"
              v-model="form.jabatan"
              placeholder="Jabatan"
            ></vs-input>
          </vs-col>
          <!-- <vs-col vs-type="flex" vs-justify="center" vs-align="center" w="6" style="padding:5px">
            <label>Pemda Prov/Kab/Kota</label>
            <vs-select filter placeholder="Pemda Prov/Kab/Kota" v-model="form.id_goverment">
              <vs-option v-for="item in getGoverments.data" :key="item.id" :label="item.nama" :value="item.id">
                {{item.nama}}
              </vs-option>
            </vs-select>
          </vs-col> -->
          <!-- <vs-col vs-type="flex" vs-justify="center" vs-align="center" w="6" style="padding:5px">
            <label>Organisasi</label>
            <vs-input type="text" v-model="form.organisasi" placeholder="Organisasi Daerah"></vs-input>
          </vs-col>
          <vs-col vs-type="flex" vs-justify="center" vs-align="center" w="6" style="padding:5px">
            <label>Unit Kerja</label>
            <vs-input type="text" v-model="form.unit_kerja" placeholder="Unit Kerja"></vs-input>
          </vs-col> -->
          <vs-col
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
              placeholder="Password"
            ></vs-input>
          </vs-col>
          <vs-col
            vs-type="flex"
            vs-justify="center"
            vs-align="center"
            w="6"
            style="padding: 5px"
          >
            <label>Konfirmasi Password</label>
            <vs-input
              type="password"
              v-model="form.konfirmasi_password"
              placeholder="Konfirmasi Password"
            ></vs-input>
          </vs-col>
        </vs-row>
      </div>

      <template #footer>
        <div class="footer-dialog">
          <vs-row>
            <vs-col w="6" style="padding: 5px">
              <vs-button block :loading="btnLoader" @click="confirmation()"
                >Simpan</vs-button
              >
            </vs-col>
            <vs-col w="6" style="padding: 5px">
              <vs-button block border @click="tambahDialog = false"
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
  layout: "admin",
  data() {
    return {
      table: {
        max: 10,
      },

      request: false,
      error: {},
      active: "",
      option: [],
      page: 1,
      current_page: 1,
      titleDialog: "Tambah Employee",
      tambahDialog: false,
      importDialog: false,
      search: '',
      company_id: JSON.parse(JSON.stringify(this.$auth.user.company_id)),
      isUpdate: false,
      btnLoader: false,
      form: {
        name: '',
        email: '',
        nip: '',
        position_id: '',
        group: '',
        kontak: '',
        status: '',
        username: '',
        password: '',
        admin: '',
        aktif: '',
        file: ''
      },
      // active: "",
      // searchDate: ["", ""],
      // searchGoverment: "",
    };
  },
  mounted() {
    this.company_id = JSON.parse(JSON.stringify(this.$auth.user.company_id));
    this.$store.dispatch("employee/getAll", { company_id: this.company_id });
    this.$axios
      .get(`/employees?company_id=${this.company_id}`)
      .then((resp) => {});
    this.$axios
      .get(`/optionPosition?company_id=${this.company_id}`)
      .then((resp) => {
        this.option = resp.data.data;
      });
  },
  methods: {
    searchData() {
      this.$store.dispatch("employee/getAll", {
        search: this.search,
      });
    },
    onFileChange(e){
      this.file = e.target.files[0];
    },
    edit(data) {
      this.form.id = data.id;
      this.form.name = data.name;
      this.form.nip = data.nip;
      this.form.position_id = data.position_id;
      this.form.kontak = data.kontak;
      this.form.status = data.status;
      this.form.admin = data.admin;
      this.form.aktif = data.aktif;
      this.tambahDialog = true;
      this.titleDialog = "Edit Employee";
      this.isUpdate = true;
    },
    resetForm() {
      this.form = {
        name: '',
        email: '',
        nip: '',
        position_id: '',
        group: '',
        kontak: '',
        status: '',
        username: '',
        password: '',
        admin: '',
        aktif: '',
        file: ''
      };
      this.isUpdate = false;
    },
    handleCurrentChange(val) {
      this.$store.commit("employee/setPage", val);
      this.$store.dispatch("employee/getAll", {});
    },
    onSubmit(type = "store") {
      this.btnLoader = true;
      let url = `/user/${this.form.id}/show`;
      let formData = new FormData();
      formData.append("company_id", this.company_id);
      formData.append("email", this.form.email);
      formData.append("password", this.form.password);
      formData.append("username", this.form.username);
      console.log(this.form)

      if (type == "update") {
        url = `/user/${this.form.id}/update`;
      }
      this.$axios
        .post(url, formData)
        .then((resp) => {
          if (resp.data.success) {
            this.$notify.success({
              title: "Success",
              message: `Berhasil ${
                type == "store" ? "Menambah" : "Mengubah"
              } Employee`,
            });
            this.resetForm();
            this.tambahDialog = false;
            this.$store.dispatch("employee/getAll", {
              company_id: this.company_id,
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

  },
  computed: {
    ...mapGetters("employee", [
      "getEmployees",
      "getLoader"
    ]),
    ...mapGetters("option", ["getOption"]),
    ...mapGetters("position", ["getPosition"]),
  },
};
</script>

<style lang="scss" scoped>
.heading {
  color: white;
  font-size: 25px;
  font-weight: bold;
}

.datadiri {
  width: 60px !important;
}
</style>
