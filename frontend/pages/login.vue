<template>
  <div class="border-0">
    <div class="bg-transparent logo" style="border: none">
      <a class="navbar-brand rounded mx-auto d-block" style="margin-bottom: 20px;">
        <img src="../assets/img/Gapoktan-Logo.png" width="200" height="200" />
      </a>

    </div>
    <div class="text-center text-muted mb-4" style="padding-top: 40px">
      <small><b>LOGIN</b></small>
    </div>
    <div class="card-body">

      <el-alert v-if="errorMessage !== ''" :title="errorMessage" type="error" class="mb-3" show-icon>
      </el-alert>
      <form role="form" @submit.prevent="login()">
        <div class="form-group mb-3">
          <div class="input-group input-group-alternative">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="ni ni-email-83"></i></span>
            </div>
            <input class="form-control" v-model="username" name="username" required placeholder="Email" type="text">
          </div>
        </div>
        <div class="form-group">
          <div class="input-group input-group-alternative">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
            </div>
            <input class="form-control" v-model="password" name="password" required placeholder="Password"
              type="password">
          </div>
        </div>
        <div class="text-center" style="margin-bottom: 54px">
          <el-button type="primary" :loading="showLoading" class="my-4" round native-type="submit">Login
          </el-button>
          <br>
          <br>
          <router-link to="/register">
            <a href="#">Belum ada account ?</a>
          </router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script>

  export default {
    layout: 'auth',
    data() {
      return {
        username: '',
        password: '',
        showLoading: false,
        errorMessage: ''
      }
    },
    mounted() {
      //
    },
    created () {
      if (this.$auth.loggedIn) {

        if (this.$auth.$storage.getUniversal('redirect')) {
          this.$router.replace(this.$auth.$storage.getUniversal('redirect'))
          this.$auth.$storage.removeUniversal('redirect')
          return;
        }

        this.$router.replace('/')
        return
      };
    },
    methods: {
      async login() {
        this.showLoading = true;
        try {
          await this.$auth.loginWith('local', {
            data: {
              "email": this.username,
              "password": this.password,
            }
          }).catch(e => {
            console.log(e);
            this.$notify.error({
              title: 'Error',
              message: e.response.data.message
            });
          });
          this.showLoading = false;
          if (this.$auth.loggedIn) {
            this.$router.push('/')
          }
        } catch (e) {
          console.log(e)
          this.showLoading = false;
        }
      }
    },
    watch: {
      username(newValue, oldValue) {
        this.errorMessage = '';
      },
      password(newValue, oldValue) {
        this.errorMessage = '';
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
