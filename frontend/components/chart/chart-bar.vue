<template>
  <div>
    <!-- <canvas id="fooCanvas" count="2" /> -->
    <chartjs-bar :backgroundcolor="types[0].bgColor" :beginzero="beginZero" :bind="true"
      :bordercolor="types[0].borderColor" :data="types[0].data" :datalabel="types[0].dataLabel" :labels="labels" />

    <chartjs-bar :backgroundcolor="types[1].bgColor" :beginzero="beginZero" :bind="true"
      :bordercolor="types[1].borderColor" :data="types[1].data" :datalabel="types[1].dataLabel" :labels="labels2" />
  </div>
</template>

<script>
  import {
    mapMutations,
    mapGetters
  } from 'vuex';

  export default {
    data() {
      return {
        beginZero: true,
        labels: ["-", "-", "-"],
        labels2: ["-", "-", "-"],
        types: [{
            bgColor: "#de98ab",
            borderColor: "0c0306",
            data: [0, 0, 0],
            dataLabel: "Segmentasi"
          },
          {
            bgColor: "#98ddde",
            borderColor: "030c0c",
            data: [0, 0, 0],
            dataLabel: "Kagetori"
          }
        ]
      };
    },
    mounted() {
      this.$store.dispatch('service/getChartLaporanMasuk', {
        type: 'segmentasi'
      })
      this.$store.dispatch('service/getChartLaporanMasuk', {
        type: 'kategori'
      })
    },
    computed: {
      ...mapGetters("service", [
        'getChart',
      ]),
    },
    watch: {
      getChart: {
        handler(val) {
          this.labels = val.segmentasi.map(el => {
            return el.text
          })

          this.types[0].data = val.segmentasi.map(el => {
            return el.data
          })

          this.labels2 = val.kategori.map(el => {
            return el.text
          })

          this.types[1].data = val.kategori.map(el => {
            return el.data
          })
        },
        deep: true
      }
    },
  };

</script>
