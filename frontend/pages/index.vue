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
    this.$notify.success({
      title: 'Berhasil Login',
      message: 'Selamat Datang Admin! :)'
    });
    let user = JSON.parse(JSON.stringify(this.$auth.user))
    if (user) {
      this.$axios.get('/account/'+ user.id)
      .then((resp) => {
        let account = resp.data.data
        if (account == 1) {
          this.$router.push('/admin/')
        } else if (account == 2) {
          this.$router.push('/gapoktan/')
        } else if (account == 3) {
          this.$router.push('/poktan/')
        } else if (account == 4) {
          this.$router.push('/costumer/')
        } else {
          this.$router.push('/')
        }
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
      })
    } else {
      this.$router.push('/login')
    }
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
