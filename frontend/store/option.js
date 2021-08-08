export const state = () => ({
  poktan: {
    data: [],
  },

  kategori: {
    data: [],
  },

  optionLoader: false,
})

export const mutations = {
  setOptionPoktans(state, data) {
    state.poktan = data
  },
  setOptionKategoris(state, data) {
    state.kategori = data
  },
  setLoader(state){
    state.optionLoader = !state.optionLoader
  },
}

export const getters = {
  getOptionPoktans(state) {
       return state.poktan
  },
  getOptionKategoris(state) {
    return state.kategori
  },
  getLoader(state){
    return state.optionLoader
  },
};

export const actions = {
  getPoktans(context, {}){
    context.commit('setLoader')
    this.$axios.get(`/option_poktan`)
    .then(resp => {
        context.commit('setOptionPoktans', resp.data)
    }).catch(e => {
        console.log(e)
    }).finally(() => {
        context.commit("setLoader")
    })
  },

  getKategoris(context, {}){
    context.commit('setLoader')
    this.$axios.get(`/option_kategori`)
    .then(resp => {
        context.commit('setOptionKategoris', resp.data)
    }).catch(e => {
        console.log(e)
    }).finally(() => {
        context.commit("setLoader")
    })
  },
}
