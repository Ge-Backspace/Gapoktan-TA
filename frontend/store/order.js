export const state = () => ({
  order: {
    data: [],
    total: 0,
    current_page: 1
  },
  compLoader: false,
})

export const mutations = {
  setOrders(state, data) {
    state.order = data
  },
  setLoader(state){
      state.compLoader = !state.compLoader
  },
  setPage(state, data){
      state.order.current_page = data
  },
}

export const getters = {
  getOrders(state) {
       return state.order
  },
  getLoader(state){
      return state.compLoader
  },
};

export const actions = {
  getCostumer(context, { user_id, showall = 1, search = '', defaultPage = false}){
      context.commit('setLoader')
      let page = defaultPage ? 1 : context.state.order.current_page
      this.$axios.get(`/lihat_order?user_id=${user_id}&showall=${showall}&page=${page}&search=${search}`).then(resp => {
          context.commit('setOrders', resp.data)
      }).catch(e => {
          console.log(e)
      }).finally(() => {
          context.commit('setLoader')
      })
  },

  getGapoktan(context, { user_id, showall = 1, search = '', defaultPage = false}){
    context.commit('setLoader')
    let page = defaultPage ? 1 : context.state.order.current_page
    this.$axios.get(`/lihat_order_gapoktan?user_id=${user_id}&showall=${showall}&page=${page}&search=${search}`).then(resp => {
        context.commit('setOrders', resp.data)
    }).catch(e => {
        console.log(e)
    }).finally(() => {
        context.commit('setLoader')
    })
  },

  getAll(context, { showall = 1, search = '', defaultPage = false}){
    context.commit('setLoader')
    let page = defaultPage ? 1 : context.state.order.current_page
    this.$axios.get(`/lihat_semua_order?showall=${showall}&page=${page}&search=${search}`).then(resp => {
        context.commit('setOrders', resp.data)
    }).catch(e => {
        console.log(e)
    }).finally(() => {
        context.commit('setLoader')
    })
  },
}
