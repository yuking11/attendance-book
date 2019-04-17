import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'
import error from './error'
import top from './top'
import category from './category'
import member from './member'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    auth,
    error,
    top,
    category,
    member
  }
})

export default store
