<template>
  <div class="container">
    <div class="lds-ellipsis">
      <div></div>
      <div></div>
      <div></div>
      <div></div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    let account = JSON.parse(JSON.stringify(this.$auth.account));
    // if (user) {
    //   if (!user.aktif) {
    //     this.$auth.logout()
    //     this.$router.push('/login')
    //     this.$notify.error({
    //       title: "Error",
    //       message: "Akun Anda Tidak Aktif"
    //     })
    //   }else {
    //     let roles = user.role_id;
    //     if (roles == 1) {
    //       this.$router.push('/superadmin/beranda');
    //     } else if (roles == 2) {
    //       this.$router.push('/admin/beranda');
    //     } else if (roles == 3) {
    //       this.$router.push('/user/beranda');
    //     }
    //   }
    // } else {
    //   this.$router.push('/login')
    // }
  },
};
</script>

<style lang="scss" scoped>
.container {
  display: flex;
  height: 100vh;
  width: 100%;
  align-items: center;
  align-content: center;
  flex-direction: column;
}
.lds-ellipsis {
  display: inline-block;
  position: relative;
  width: 80px;
  height: 80px;
  margin-top: 25%;
}
.lds-ellipsis div {
  position: absolute;
  top: 33px;
  width: 13px;
  height: 13px;
  border-radius: 50%;
  background: #1f3a93;
  animation-timing-function: cubic-bezier(0, 1, 1, 0);
}
.lds-ellipsis div:nth-child(1) {
  left: 8px;
  animation: lds-ellipsis1 0.6s infinite;
}
.lds-ellipsis div:nth-child(2) {
  left: 8px;
  animation: lds-ellipsis2 0.6s infinite;
}
.lds-ellipsis div:nth-child(3) {
  left: 32px;
  animation: lds-ellipsis2 0.6s infinite;
}
.lds-ellipsis div:nth-child(4) {
  left: 56px;
  animation: lds-ellipsis3 0.6s infinite;
}
@keyframes lds-ellipsis1 {
  0% {
    transform: scale(0);
  }
  100% {
    transform: scale(1);
  }
}
@keyframes lds-ellipsis3 {
  0% {
    transform: scale(1);
  }
  100% {
    transform: scale(0);
  }
}
@keyframes lds-ellipsis2 {
  0% {
    transform: translate(0, 0);
  }
  100% {
    transform: translate(24px, 0);
  }
}
</style>
