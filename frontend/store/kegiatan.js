export const state = () => ({
  kegiatan: {
    data: [],
    total: 0,
    current_page: 1
  },
  kegiatanLoader: false,
})

export const mutations = {
  setKegiatans(state, data) {
    state.kegiatan = data
  },
  setLoader(state){
      state.kegiatanLoader = !state.kegiatanLoader
  },
  setPage(state, data){
      state.kegiatan.current_page = data
  },
}

export const getters = {
  getKegiatans(state) {
       return state.kegiatan
  },
  getLoader(state){
      return state.kegiatanLoader
  },
};

export const actions = {
  getAllPoktan(context, { user_id, showall = 1, search = '', defaultPage = false}){
      context.commit('setLoader')
      let page = defaultPage ? 1 : context.state.kegiatan.current_page
      this.$axios.get(`/lihat_kegiatan?user_id=${user_id}&showall=${showall}&page=${page}&search=${search}`).then(resp => {
          context.commit('setKegiatans', resp.data)
      }).catch(e => {
          console.log(e)
      }).finally(() => {
          context.commit('setLoader')
      })
  },
  getAll(context, {showall = 1, search = '', defaultPage = false}){
    context.commit('setLoader')
    let page = defaultPage ? 1 : context.state.kegiatan.current_page
    this.$axios.get(`/lihat_semua_kegiatan?showall=${showall}&page=${page}&search=${search}`).then(resp => {
        context.commit('setKegiatans', resp.data)
    }).catch(e => {
        console.log(e)
    }).finally(() => {
        context.commit('setLoader')
    })
},
}
