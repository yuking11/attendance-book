import { OK, CREATED, NO_CONTENT, CONFLICT, UNPROCESSABLE_ENTITY } from '../util'

const state = {
  apiStatus: null,
  registerErrorMessages: null,
  updateErrorMessages: null,
  deleteErrorMessages: null
}

const mutations = {
  setApiStatus (state, status) {
    state.apiStatus = status
  },
  setRegisterErrorMessages (state, messages) {
    state.registerErrorMessages = messages
  },
  setUpdateErrorMessages (state, messages) {
    state.updateErrorMessages = messages
  },
  setDeleteErrorMessages (state, messages) {
    state.deleteErrorMessages = messages
  }
}

const actions = {

  // カテゴリ登録
  async register (context, data) {
    context.commit('setApiStatus', null)
    const RESPONSE = await axios.post('/api/category/create', data)

    if (RESPONSE.status === CREATED) {
      context.commit('setApiStatus', true)
      return RESPONSE
    }

    context.commit('setApiStatus', false)
    if (RESPONSE.status === UNPROCESSABLE_ENTITY) {
      context.commit('setRegisterErrorMessages', RESPONSE.data.errors)
    } else {
      context.commit('error/setCode', RESPONSE.status, { root: true })
    }
  },
  // カテゴリ更新
  async update (context, data) {
    context.commit('setApiStatus', null)
    const RESPONSE = await axios.put('/api/category/update', data)

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
  // カテゴリ削除
  async delete (context, data) {
    context.commit('setApiStatus', null)
    const RESPONSE = await axios.delete('/api/category/delete', data)

    if (RESPONSE.status === NO_CONTENT) {
      context.commit('setApiStatus', true)
      return RESPONSE
    }

    context.commit('setApiStatus', false)
    if (RESPONSE.status === CONFLICT) {
      context.commit('setDeleteErrorMessages', RESPONSE.data)
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
