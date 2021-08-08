<template>
  <div>
    <div class="card border-0">
      <div class="bg-transparent logo" style="border: none">
        <a class="navbar-brand rounded mx-auto d-block" style="margin-bottom: 20px;">
          <img src="../assets/img/Gapoktan-Logo.png" width="200" height="200" />
        </a>

      </div>
      <div class="text-center text-muted mb-4">
          <small><b>Daftar Akun</b></small>
        </div>
      <div class="card-body">
        <el-alert v-if="errorMessage !== ''" :title="errorMessage" type="error" class="mb-3" show-icon>
        </el-alert>
        <form role="form" @submit.prevent="register">
          <div class="form-group mb-3">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"></span>
              </div>
              <input class="form-control" v-model="form.nama" name="name" required placeholder="Nama" type="text">
            </div>
          </div>
          <div class="form-group mb-3">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"></span>
              </div>
              <input class="form-control" v-model="form.email" name="email" required placeholder="Email" type="text">
            </div>
          </div>
          <div class="form-group mb-3">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"></span>
              </div>
              <input class="form-control" v-model="form.password" name="password" required placeholder="Password" type="password">
            </div>
          </div>
          <div class="text-center">
            <el-button type="primary" :loading="showLoading" class="my-4" round native-type="submit"> Register
            </el-button>
            <br>
            <router-link to="/login">
              <a href="#">Sudah ada akun?</a>
            </router-link>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    layout: 'register',
    data() {
      return {
        form: {
          nama: '',
          email: '',
          password: ''
        },
        showLoading: false,
        errorMessage: '',
      }
    },
    methods: {
      async register(){
        this.showLoading = true;
        this.$axios.post('/register', this.form)
        .then( response => {
          this.$notify.success({
            title: 'Success',
            message: 'Berhasil Mendaftar :)'
          })
          this.$auth.loginWith('local', {
            data: {
              "email": this.form.email,
              "password": this.form.password,
            }
          }).catch(e => {
            this.$notify.error({
              title: 'Error',
              message: e.response.data.message
            });
          })
          if (this.$auth.loggedIn) {
            this.showLoading = false;
            this.$router.push('/')
          }
        })
        .catch(e => {
          console.log(e.response.data.message);
          this.$notify.error({
            title: 'Error',
            message: e.response.data.message
          });
        }).finally(() => {
          this.showLoading = false;
        })
      }
    },
  }

</script>



<style scoped>
  .header {
    border-radius: 0;
  }

  .input-group {
    box-shadow: none;
  }

  .form-control,
  .input-group-text {
    background-color: #eee;
  }

  .input-group-text {
    border-bottom-left-radius: 40px;
    border-top-left-radius: 40px;
  }

  .form-control {
    border-bottom-right-radius: 40px;
    border-top-right-radius: 40px;
  }

  .card {
    box-shadow: none !important;
  }

</style>
