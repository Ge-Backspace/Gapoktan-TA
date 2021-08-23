export const state = () => ({
  account: {
    data: [],
  },
})

export const mutations = {
  setAccount(state, data) {
    state.account = data
  },
}

export const getters = {
  getAccount(state) {
       return state.account
  },
};

export const actions = {
  getUserAccount(context, { user_id }){
      this.$axios.get(`/lihat_profil?user_id=${user_id}`).then(resp => {
          context.commit('setAccount', resp.data)
      }).catch(e => {
          console.log(e)
      }).finally(() => {
        //
      })
  },
}
