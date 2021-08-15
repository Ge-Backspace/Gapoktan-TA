export const state = () => ({
  produk: {
    data: [],
    total: 0,
    current_page: 1
  },
  compLoader: false,
})

export const mutations = {
  setProduks(state, data) {
    state.produk = data
  },
  setLoader(state){
      state.compLoader = !state.compLoader
  },
  setPage(state, data){
      state.produk.current_page = data
  },
}

export const getters = {
  getProduks(state) {
       return state.produk
  },
  getLoader(state){
      return state.compLoader
  },
};

export const actions = {
  getAll(context, {user_id , showall = 1, search = '', defaultPage = false}){
    context.commit('setLoader')
    let page = defaultPage ? 1 : context.state.produk.current_page
    this.$axios.get(`/lihat_produk?user_id=${user_id}&showall=${showall}&page=${page}&search=${search}`).then(resp => {
        context.commit('setProduks', resp.data)
    }).catch(e => {
        console.log(e)
    }).finally(() => {
        context.commit('setLoader')
    })
  },

  getAdmin(context, {showall = 1, search = '', defaultPage = false}){
    context.commit('setLoader')
    let page = defaultPage ? 1 : context.state.produk.current_page
    this.$axios.get(`/lihat_semua_produk?showall=${showall}&page=${page}&search=${search}`).then(resp => {
        context.commit('setProduks', resp.data)
    }).catch(e => {
        console.log(e)
    }).finally(() => {
        context.commit('setLoader')
    })
  },
}
