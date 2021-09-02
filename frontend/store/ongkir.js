export const state = () => ({
  ongkir: {
    data: {
      rajaongkir:{
        results:[]
      }
    }
  },

  compLoader: false,
})

export const mutations = {
  setOngkirs(state, data) {
    state.ongkir = data
  },
  setLoader(state){
    state.compLoader = !state.compLoader
  },
}

export const getters = {
  getOngkirs(state) {
       return state.ongkir
  },
  getOngkirLoader(state){
    return state.compLoader
  },
};

export const actions = {
  getCosts(context, {berat, courier}){
    context.commit('setLoader')
    let formData = new FormData();
    formData.append("weight", berat);
    formData.append("courier", courier);
    formData.append("origin", 149);
    formData.append("destination", 149);
    this.$axios.post('/ongkir', formData)
    .then(resp => {
        // let data = resp.data.data.rajaongkir.results
        // let ongkir = []
        // data.forEach(element => {
        //   ongkir.push({
        //     code: element.code,
        //     name: element.name,
        //     costs: element.costs,
        //   })
        // });
        context.commit('setOngkirs', resp.data)
    }).catch(e => {
        console.log(e)
    }).finally(() => {
        context.commit("setLoader")
    })
  },
}
