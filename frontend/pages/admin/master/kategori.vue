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
      <a class="float" @click="KategoriDialog = true; titleDialog = 'Tambah Kategori Baru'">
        <i class="el-icon-plus my-float"></i>
      </a>
    </el-tooltip>
    <!-- End floating button -->

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
        titleDialog: '',
        isUpdate: false,
        kategoriDialog: false,
        btnLoader: false,
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
  }
</script>

<style lang="scss" scoped>
.heading {
    color: white;
    font-size: 25px;
    font-weight: bold;
  }

</style>
