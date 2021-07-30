<template>
  <div>

  </div>
</template>

<script>
  export default {
    mounted() {
      this.$router.push("/admin/register_company");
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
