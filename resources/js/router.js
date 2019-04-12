import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポートする
import Top from './pages/Top.vue'
import Login from './pages/Login.vue'
import Member from './pages/Member.vue'
import Category from './pages/Category.vue'
import SystemError from './pages/errors/System.vue'
import NotFound from './pages/errors/NotFound.vue'

import store from './store'

// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter)

// パスとコンポーネントのマッピング
const routes = [
  {
    path: '/',
    component: Top,
    beforeEnter (to, from, next) {
      if (!store.getters['auth/check']) {
        next('/login')
      } else {
        next()
      }
    }
  },
  {
    path: '/login',
    component: Login,
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        next('/')
      } else {
        next()
      }
    }
  },
  {
    path: '/500',
    component: SystemError
  },
  {
    path: '/member',
    component: Member,
    beforeEnter (to, from, next) {
      if (!store.getters['auth/check']) {
        next('/login')
      } else {
        next()
      }
    }
  },
  {
    path: '/category',
    component: Category,
    beforeEnter (to, from, next) {
      if (!store.getters['auth/check']) {
        next('/login')
      } else {
        next()
      }
    }
  },
  {
    path: '*',
    component: NotFound
  }
]

// VueRouterインスタンスを作成する
const router = new VueRouter({
  mode: 'history', // ★ 追加
  routes
})

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router
