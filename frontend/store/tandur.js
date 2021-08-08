export const state = () => ({
  tandur: {
    data: [],
    total: 0,
    current_page: 1
  },
  tandurLoader: false,
})

export const mutations = {
  setTandurs(state, data) {
    state.tandur = data
  },
  setLoader(state){
      state.tandurLoader = !state.tandurLoader
  },
  setPage(state, data){
      state.tandur.current_page = data
  },
}

export const getters = {
  getTandurs(state) {
       return state.tandur
  },
  getLoader(state){
      return state.tandurLoader
  },
};

export const actions = {
  getAllPoktan(context, { user_id, showall = 1, search = '', defaultPage = false}){
      context.commit('setLoader')
      let page = defaultPage ? 1 : context.state.tandur.current_page
      this.$axios.get(`/lihat_tandur?user_id=${user_id}&showall=${showall}&page=${page}&search=${search}`).then(resp => {
          context.commit('setTandurs', resp.data)
      }).catch(e => {
          console.log(e)
      }).finally(() => {
          context.commit('setLoader')
      })
  },
  getAll(context, {showall = 1, search = '', defaultPage = false}){
    context.commit('setLoader')
    let page = defaultPage ? 1 : context.state.tandur.current_page
    this.$axios.get(`/lihat_semua_tandur?showall=${showall}&page=${page}&search=${search}`).then(resp => {
        context.commit('setTandurs', resp.data)
    }).catch(e => {
        console.log(e)
    }).finally(() => {
        context.commit('setLoader')
    })
},
}
