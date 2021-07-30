<template>
  <div>
    <chartjs-doughnut
        :bind="true"
        :datasets="datasets"
        :labels="labels"
        :option="option"
      />
  </div>
</template>

<script>
export default {
  data() {
    return {
      datasets: [
        {
          data: [0, 0, 0, 0, 0],
          backgroundColor: ["#f36e60", "#ffdb3b", "#185190", "#29f1c3", '#be90d4'],
          hoverBackgroundColor: ["#fbd2cd", "#fef5c9", "#d1e3f7", '#7befb2', '#9b59b6']
        }
      ],
      labels: ["Sila Ke 1", "Sila Ke 2", "Sila Ke 3", "Sila Ke 4", "Sila Ke 5"],
      option: {}
    };
  },
  mounted () {
    this.getLaporan();
  },
  methods: {
    async getLaporan() {
        await this.$axios.get('/laporan-kandungan-pancasila').then(response => {
          if (response.data.success) {
            let data = response.data.data;
            this.datasets[0].data = [
              data.sila_ke_1,
              data.sila_ke_2,
              data.sila_ke_3,
              data.sila_ke_4,
              data.sila_ke_5
            ];
          }
        }).finally(() => {
          this.loadingBeritaPopuler = false
        })
      },
  },
};
</script>