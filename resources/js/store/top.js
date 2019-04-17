import { OK, CREATED, NO_CONTENT, UNPROCESSABLE_ENTITY } from '../util'

const state = {
  apiStatus: null,
  updateErrorMessages: null
}

const mutations = {
  setApiStatus (state, status) {
    state.apiStatus = status
  },
  setUpdateErrorMessages (state, messages) {
    state.updateErrorMessages = messages
  },
}

const actions = {

  // ステータス更新
  async status (context, data) {
    context.commit('setApiStatus', null)
    const RESPONSE = await axios.post('/api/status', data)

    if (RESPONSE.status === OK) {
      context.commit('setApiStatus', true)
      return RESPONSE
    }

    context.commit('setApiStatus', false)
    if (RESPONSE.status === UNPROCESSABLE_ENTITY) {
      context.commit('setUpdateErrorMessages', RESPONSE.data.errors)
    } else {
      context.commit('error/setCode', RESPONSE.status, { root: true })
    }
  },
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
