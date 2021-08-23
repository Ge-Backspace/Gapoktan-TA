export const state = () => ({
  od: {
    data: [],
    total: 0,
    current_page: 1
  },
  compLoader: false,
})

export const mutations = {
  setODs(state, data) {
    state.od = data
  },
  setLoader(state){
      state.compLoader = state.compLoader
  },
  setPage(state, data){
      state.od.current_page = data
  },
}

export const getters = {
  getODs(state) {
       return state.od
  },
  getODLoader(state){
      return state.compLoader
  },
};

export const actions = {
  getCostumer(context, { order_id, showall = 1, search = '', defaultPage = false}){
      context.commit('setLoader')
      let page = defaultPage ? 1 : context.state.od.current_page
      this.$axios.get(`/lihat_order_detail?order_id=${order_id}&showall=${showall}&page=${page}&search=${search}`).then(resp => {
          context.commit('setODs', resp.data)
      }).catch(e => {
          console.log(e)
      }).finally(() => {
          context.commit('setLoader')
      })
  },

  getGapoktan(context, { user_id, showall = 1, search = '', defaultPage = false}){
    context.commit('setLoader')
    let page = defaultPage ? 1 : context.state.od.current_page
    this.$axios.get(`/lihat_order_detail_gapoktan?user_id=${user_id}&showall=${showall}&page=${page}&search=${search}`).then(resp => {
        context.commit('setODs', resp.data)
    }).catch(e => {
        console.log(e)
    }).finally(() => {
        context.commit('setLoader')
    })
  },

}
