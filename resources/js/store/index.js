import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'
import error from './error'
import category from './category'
import member from './member'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    auth,
    error,
    category,
    member
  }
})

export default store
