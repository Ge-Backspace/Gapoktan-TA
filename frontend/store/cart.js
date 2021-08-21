export const state = () => ({
  cart: {
    data: [],
    total: 0,
    current_page: 1
  },
  compLoader: false,
})

export const mutations = {
  setCarts(state, data) {
    state.cart = data
  },
  setLoader(state){
      state.compLoader = !state.compLoader
  },
  setPage(state, data){
      state.cart.current_page = data
  },
}

export const getters = {
  getCarts(state) {
       return state.cart
  },
  getLoader(state){
      return state.compLoader
  },
};

export const actions = {
  getAll(context, { user_id, showall = 1, search = '', defaultPage = false}){
      context.commit('setLoader')
      let page = defaultPage ? 1 : context.state.cart.current_page
      this.$axios.get(`/lihat_chart?user_id=${user_id}&showall=${showall}&page=${page}&search=${search}`).then(resp => {
          context.commit('setCarts', resp.data)
      }).catch(e => {
          console.log(e)
      }).finally(() => {
          context.commit('setLoader')
      })
  },
}
