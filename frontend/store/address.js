export const state = () => ({
  address: {
    data: [],
    total: 0,
    current_page: 1
  },
  compLoader: false,
})

export const mutations = {
  setAddresses(state, data) {
    state.address = data
  },
  setLoader(state){
      state.compLoader = !state.compLoader
  },
  setPage(state, data){
      state.address.current_page = data
  },
}

export const getters = {
  getAddresses(state) {
       return state.address
  },
  getLoader(state){
      return state.compLoader
  },
};

export const actions = {
  getAll(context, { user_id, showall = 1, search = '', defaultPage = false}){
      context.commit('setLoader')
      let page = defaultPage ? 1 : context.state.address.current_page
      this.$axios.get(`/lihat_address?user_id=${user_id}&showall=${showall}&page=${page}&search=${search}`).then(resp => {
          context.commit('setAddresses', resp.data)
      }).catch(e => {
          console.log(e)
      }).finally(() => {
          context.commit('setLoader')
      })
  },
//   getAll(context, {showall = 1, search = '', defaultPage = false}){
//     context.commit('setLoader')
//     let page = defaultPage ? 1 : context.state.kegiatan.current_page
//     this.$axios.get(`/lihat_semua_kegiatan?showall=${showall}&page=${page}&search=${search}`).then(resp => {
//         context.commit('setKegiatans', resp.data)
//     }).catch(e => {
//         console.log(e)
//     }).finally(() => {
//         context.commit('setLoader')
//     })
// },
}
