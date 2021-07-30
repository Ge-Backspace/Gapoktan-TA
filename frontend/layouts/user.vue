<template>
  <div :class="{'g-sidenav-show': $store.state.drawer.drawer, 'g-sidenav-pinned': $store.state.drawer.drawer}">

    <sidebar></sidebar>
    <!-- Main content -->
    <div class="main-content" id="panel">
      <!-- Topnav -->
      <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary">
        <div class="container-fluid">
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav align-items-center  ml-md-auto ">
              <li class="nav-item d-xl-none">
                <!-- Sidenav toggler -->
                <div class="pr-3 sidenav-toggler2 sidenav-toggler-dark" @click="toggle()" style="cursor: pointer"
                  data-target="#sidenav-main">
                  <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                  </div>
                </div>
              </li>
              <li class="nav-item d-md-none">
                <h3 style="color: #fff; font-weight: bold; margin-top:10px">Attendance</h3>
              </li>
            </ul>
            <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
              <li class="nav-item dropdown">
                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                  aria-expanded="false">
                  <div class="media align-items-center">
                    <!-- <span class="avatar avatar-sm rounded-circle"> -->
                    <!-- <img class="avatar" :src="user.photo_url" /> -->
                    <div class="avatar avatar-sm rounded-circle"
                      :style="'background: #f3f3f3 url(' + $auth.user.foto_url + ') no-repeat top center / cover; width:25px; height:25px'">
                    </div>
                    <!-- </span> -->
                    <!-- <div class="classname" :style="'background: #f3f3f3 url(' + user.photo_url + ') no-repeat top center / cover'"></div> -->
                    <div class="media-body  ml-2  d-none d-lg-block">
                      <span class="mb-0 text-sm  font-weight-bold">{{user.nama}} <i style="margin-left: 5px"
                          class="fas fa-angle-down"></i> </span>
                    </div>
                  </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                  <!-- <router-link to="/profile"> -->
                    <a class="dropdown-item text-primary" @click="$router.push('/profile')" style="cursor:pointer">
                      <i class="ni ni-single-02"></i>
                      <span>Profile</span>
                    </a>
                  <!-- </router-link> -->
                  <a class="dropdown-item text-primary" @click="logout()" style="cursor:pointer">
                    <i class="ni ni-user-run"></i>
                    <span>Logout</span>
                  </a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <div style="min-height: 600px">
        <nuxt />
      </div>
      <!-- Footer -->
      <footer class="footer pt-20">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-lg-6">
            <div class="copyright text-center  text-lg-left  text-muted">
              &copy; AFSEN <a href="#" class="font-weight-bold ml-1 text-primary">
                2020 - 2021</a>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
</template>
<script>
  import Sidebar from '../components/SidebarUser'
  import {
    mapMutations
  } from 'vuex';

  export default {
    middleware: ['auth', 'user'],
    components: {
      'sidebar': Sidebar
    },
    data() {
      return {
        showSideBar: false,
        collapse: true,
        user: {
          nama: '',
          photo_url: ''
        }
      }
    },
    mounted() {
      window.onresize = () => {
        this.changeDrawer();
      };

      window.onload = () => {
        this.changeDrawer();
      }
    },
    methods: {
      changeDrawer() {
        if (window.innerWidth <= 1200) {
          this.$store.commit('drawer/changeMode', 'mobile')
        } else {
          this.$store.commit('drawer/changeMode', 'web')
        }
      },
      showMenu() {
        this.showSidebar = !this.showSidebar;
        console.log(this.showSidebar)
      },
      ...mapMutations({
        toggle: "drawer/toggle"
      }),
      logout() {
        this.$auth.logout();
      }
    }
  }

</script>
<style lang="scss" scoped>
  page-enter-active,
  .page-leave-active {
    transition: all .30s ease-out;
  }

  .page-enter,
  .page-leave-active {
    opacity: 0;
    transform-origin: 50% 50%;
  }

  .sidenav {
    overflow-y: auto;
    overflow-x: hidden !important;
  }

  .navbar-vertical.navbar-expand-xs {
    max-width: 58px;
  }

  // .navbar-vertical.navbar-expand-xs.fixed-left+.main-content {
  //   margin-left: 58px;
  // }

  .footer {
    padding-left: 30px;
    width: 90%;
  }

</style>
