export const state = () => ({
  stok_lumbung: {
    data: [],
    total: 0,
    current_page: 1
  },
  stok_lumbungLoader: false,
})

export const mutations = {
  setStokLumbungs(state, data) {
    state.stok_lumbung = data
  },
  setLoader(state){
      state.stok_lumbungLoader = !state.stok_lumbungLoader
  },
  setPage(state, data){
      state.stok_lumbung.current_page = data
  },
}

export const getters = {
  getStokLumbungs(state) {
       return state.stok_lumbung
  },
  getLoader(state){
      return state.stok_lumbungLoader
  },
};

export const actions = {
  getAllPoktan(context, { user_id, showall = 1, search = '', defaultPage = false}){
      context.commit('setLoader')
      let page = defaultPage ? 1 : context.state.stok_lumbung.current_page
      this.$axios.get(`/lihat_stok_lumbung?user_id=${user_id}&showall=${showall}&page=${page}&search=${search}`).then(resp => {
          context.commit('setStokLumbungs', resp.data)
      }).catch(e => {
          console.log(e)
      }).finally(() => {
          context.commit('setLoader')
      })
  },
  getAll(context, { user_id, showall = 1, search = '', defaultPage = false}){
    context.commit('setLoader')
    let page = defaultPage ? 1 : context.state.stok_lumbung.current_page
    this.$axios.get(`/lihat_semua_stok_lumbung?user_id=${user_id}&showall=${showall}&page=${page}&search=${search}`).then(resp => {
        context.commit('setStokLumbungs', resp.data)
    }).catch(e => {
        console.log(e)
    }).finally(() => {
        context.commit('setLoader')
    })
},
}
