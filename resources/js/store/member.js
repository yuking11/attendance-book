import { OK, CREATED, NO_CONTENT, UNPROCESSABLE_ENTITY } from '../util'

const state = {
  apiStatus: null,
  registerErrorMessages: null,
  updateErrorMessages: null,
  deleteErrorMessages: null,
  aggregateErrorMessages: null
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
  },
  setAggregateErrorMessages (state, messages) {
    state.aggregateErrorMessages = messages
  }
}

const actions = {

  // メンバー登録
  async register (context, data) {
    context.commit('setApiStatus', null)
    const RESPONSE = await axios.post('/api/member/create', data)

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

  // メンバー更新
  async update (context, data) {
    context.commit('setApiStatus', null)
    const RESPONSE = await axios.put('/api/member/update', data)

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

  // メンバー削除
  async delete (context, data) {
    context.commit('setApiStatus', null)
    const RESPONSE = await axios.delete('/api/member/delete', data)

    if (RESPONSE.status === NO_CONTENT) {
      context.commit('setApiStatus', true)
      return RESPONSE
    }

    context.commit('setApiStatus', false)
    if (RESPONSE.status === UNPROCESSABLE_ENTITY) {
      context.commit('setDeleteErrorMessages', RESPONSE.data.errors)
    } else {
      context.commit('error/setCode', RESPONSE.status, { root: true })
    }
  },

  // ステータス集計
  async aggregate (context, data) {
    context.commit('setApiStatus', null)
    const RESPONSE = await axios.post('/api/aggregate', data)

    if (RESPONSE.status === OK) {
      context.commit('setApiStatus', true)
      return RESPONSE
    }

    context.commit('setApiStatus', false)
    if (RESPONSE.status === UNPROCESSABLE_ENTITY) {
      context.commit('setAggregateErrorMessages', RESPONSE.data.errors)
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
