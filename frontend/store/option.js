export const state = () => ({
  poktan: {
    data: [],
  },

  kategori: {
    data: [],
  },

  address: {
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
  setOptionAddresses(state, data) {
    state.address = data
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
  getOptionAddresses(state) {
    return state.address
  },
  getOpLoader(state){
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

  getAddresses(context, { user_id }){
    context.commit('setLoader')
    this.$axios.get(`/option_address?user_id=${user_id}`)
    .then(resp => {
        context.commit('setOptionAddresses', resp.data)
    }).catch(e => {
        console.log(e)
    }).finally(() => {
        context.commit("setLoader")
    })
  },
}
