export const state = () => ({
  gambar: {
    data: [],
    total: 0,
    current_page: 1
  },
  compLoader: false,
})

export const mutations = {
  setGambars(state, data) {
    state.gambar = data
  },
  setLoader(state){
      state.compLoader = !state.compLoader
  },
  setPage(state, data){
      state.gambar.current_page = data
  },
}

export const getters = {
  getGambars(state) {
       return state.gambar
  },
  getGLoader(state){
      return state.compLoader
  },
};

export const actions = {
  getAll(context, {produk_id, showall = 1, search = '', defaultPage = false}){
      context.commit('setLoader')
      let page = defaultPage ? 1 : context.state.gambar.current_page
      this.$axios.get(`/lihat_gambar_produk?produk_id=${produk_id}&showall=${showall}&page=${page}&search=${search}`).then(resp => {
          context.commit('setGambars', resp.data)
      }).catch(e => {
          console.log(e)
      }).finally(() => {
          context.commit('setLoader')
      })
  },
}
