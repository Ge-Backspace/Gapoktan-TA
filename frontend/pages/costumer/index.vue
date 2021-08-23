<template>
  <div>
    <div class="header bg-success pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <!-- Card stats -->
          <div class="row" style="padding-top: 20px">
            <div class="col-xl-12">
              <h1 class="mt-3 mb-0 text-sm">
                <span class="text-nowrap" style="font-size: 40px; color:white"
                  ><b>Hari yang bagus {{ getAccount.data.nama }} !</b>
                </span>
              </h1>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapMutations, mapGetters } from "vuex";
  export default {
    layout: "costumer",
    data() {
      return {
        user_id:"",
        nama: ""
      }
    },
    mounted () {
      this.user_id = JSON.parse(JSON.stringify(this.$auth.user.id))
      this.$store.dispatch("account/getUserAccount", { user_id: this.user_id });
    },
    computed: {
      ...mapGetters("account", ["getAccount"]),
    },
  }
</script>

<style lang="scss" scoped>

</style>
