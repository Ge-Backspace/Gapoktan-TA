export const state = () => ({
  poktan: {
    data: [],
    total: 0,
    current_page: 1
  },

  gapoktan: {
    data: [],
    total: 0,
    current_page: 1
  },

  dataLoader: false,
})

export const mutations = {
  setUserPoktans(state, data) {
    state.poktan = data
  },
  setUserGapoktans(state, data) {
    state.gapoktan = data
  },
  setLoader(state){
    state.dataLoader = !state.dataLoader
  },
  setPage(state, data){
      state.poktan.current_page = data
  },
}

export const getters = {
  getUserPoktans(state) {
       return state.poktan
  },
  getUserGapoktans(state) {
    return state.gapoktan
},
  getLoader(state){
    return state.dataLoader
  },
};

export const actions = {
  getUserPoktan(context, {user_id, showall = 1, search = '', defaultPage = false}){
    context.commit('setLoader')
    let page = defaultPage ? 1 : context.state.poktan.current_page
    this.$axios.get(`/lihat_poktan?user_id=${user_id}&showall=${showall}&page=${page}&search=${search}`).then(resp => {
        context.commit('setUserPoktans', resp.data)
    }).catch(e => {
        console.log(e)
    }).finally(() => {
        context.commit('setLoader')
    })
  },

  getAllUserGapoktan(context, {showall = 1, search = '', defaultPage = false}) {
    context.commit('setLoader')
    let page = defaultPage ? 1 : context.state.gapoktan.current_page
    this.$axios.get(`/lihat_semua_gapoktan?showall=${showall}&page=${page}&search=${search}`).then(resp => {
        context.commit('setUserGapoktans', resp.data)
    }).catch(e => {
        console.log(e)
    }).finally(() => {
        context.commit('setLoader')
    })

  }
}
