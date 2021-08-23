export const state = () => ({
  user: {
    data: [],
    total: 0,
    current_page: 1
  },

  dataLoader: false,
})

export const mutations = {
  setUsers(state, data) {
    state.user = data
  },
  setLoader(state){
    state.dataLoader = !state.dataLoader
  },
  setPage(state, data){
      state.user.current_page = data
  },
}

export const getters = {
  getUsers(state) {
       return state.user
  },
  getLoader(state){
    return state.dataLoader
  },
};

export const actions = {
  getUserPoktan(context, {user_id, showall = 1, search = '', defaultPage = false}){
    context.commit('setLoader')
    let page = defaultPage ? 1 : context.state.user.current_page
    this.$axios.get(`/lihat_poktan?user_id=${user_id}&showall=${showall}&page=${page}&search=${search}`).then(resp => {
        context.commit('setUsers', resp.data)
    }).catch(e => {
        console.log(e)
    }).finally(() => {
        context.commit('setLoader')
    })
  },

  getAllUserGapoktan(context, {showall = 1, search = '', defaultPage = false}) {
    context.commit('setLoader')
    let page = defaultPage ? 1 : context.state.user.current_page
    this.$axios.get(`/lihat_semua_gapoktan?showall=${showall}&page=${page}&search=${search}`).then(resp => {
        context.commit('setUsers', resp.data)
    }).catch(e => {
        console.log(e)
    }).finally(() => {
        context.commit('setLoader')
    })
  },

  getAllUserAdmin(context, {showall = 1, search = '', defaultPage = false}) {
    context.commit('setLoader')
    let page = defaultPage ? 1 : context.state.user.current_page
    this.$axios.get(`/lihat_semua_admin?showall=${showall}&page=${page}&search=${search}`).then(resp => {
        context.commit('setUsers', resp.data)
    }).catch(e => {
        console.log(e)
    }).finally(() => {
        context.commit('setLoader')
    })
  },

  getAllUserCostumer(context, {showall = 1, search = '', defaultPage = false}) {
    context.commit('setLoader')
    let page = defaultPage ? 1 : context.state.user.current_page
    this.$axios.get(`/lihat_semua_costumer?showall=${showall}&page=${page}&search=${search}`).then(resp => {
        context.commit('setUsers', resp.data)
    }).catch(e => {
        console.log(e)
    }).finally(() => {
        context.commit('setLoader')
    })
  },
}
