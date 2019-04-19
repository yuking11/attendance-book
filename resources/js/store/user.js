import { OK, CREATED, NO_CONTENT, CONFLICT, UNPROCESSABLE_ENTITY } from '../util'

const state = {
  apiStatus: null,
  updateErrorMessages: null,
  deleteErrorMessages: null,
}

const mutations = {
  setApiStatus (state, status) {
    state.apiStatus = status
  },
  setUpdateErrorMessages (state, messages) {
    state.updateErrorMessages = messages
  },
  setDeleteErrorMessages (state, messages) {
    state.deleteErrorMessages = messages
  },
}

const actions = {

  // ユーザー更新
  async update (context, data) {
    context.commit('setApiStatus', null)
    const RESPONSE = await axios.put('/api/profile/update', data)

    if (RESPONSE.status === OK) {
      context.commit('setApiStatus', true)
      return RESPONSE
    }

    context.commit('setApiStatus', false)
    if (RESPONSE.status === CONFLICT) {
      context.commit('setUpdateErrorMessages', RESPONSE.data)
    } else if (RESPONSE.status === UNPROCESSABLE_ENTITY) {
      context.commit('setUpdateErrorMessages', RESPONSE.data)
    } else  {
      context.commit('error/setCode', RESPONSE.status, { root: true })
    }
  },

  // ユーザー削除
  async delete (context, data) {
    context.commit('setApiStatus', null)
    const RESPONSE = await axios.delete('/api/profile/delete', data)

    if (RESPONSE.status === NO_CONTENT) {
      context.commit('setApiStatus', true)
      return RESPONSE
    }

    context.commit('setApiStatus', false)
    if (RESPONSE.status === CONFLICT) {
      context.commit('setUpdateErrorMessages', RESPONSE.data)
    } else if (RESPONSE.status === UNPROCESSABLE_ENTITY) {
      context.commit('setDeleteErrorMessages', RESPONSE.data)
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
