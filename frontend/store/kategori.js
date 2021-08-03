export const state = () => ({
  kategori: {
    data: [],
    total: 0,
    current_page: 1
  },
  kategoriLoader: false,
})

export const mutations = {
  setKategoris(state, data) {
    state.kategori = data
  },
  setLoader(state){
      state.kategoriLoader = !state.kategoriLoader
  },
  setPage(state, data){
      state.kategori.current_page = data
  },
}

export const getters = {
  getKategoris(state) {
       return state.kategori
  },
  getLoader(state){
      return state.kategoriLoader
  },
};

export const actions = {
  getAll(context, {showall = 1, search = '', defaultPage = false}){
      context.commit('setLoader')
      let page = defaultPage ? 1 : context.state.kategori.current_page
      this.$axios.get(`/lihat_kategori?showall=${showall}&page=${page}&search=${search}`).then(resp => {
          context.commit('setKategoris', resp.data)
      }).catch(e => {
          console.log(e)
      }).finally(() => {
          context.commit('setLoader')
      })
  },
}
