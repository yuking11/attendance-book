<template>
  <div class="l-content-small">
    <div class="l-inner">

      <h1 class="c-ttl-primary">ログイン / 登録</h1>

      <div class="c-tab">
        <ul class="c-tab_list">
          <li
            class="list_item"
            :class="{'list_item-active': tab === 1 }"
            @click="tab = 1"
          >ログイン</li>
          <li
            class="list_item"
            :class="{'list_item-active': tab === 2 }"
            @click="tab = 2"
          >登録</li>
        </ul>
        <div class="c-tab_panel">
          <div
            class="panel_item"
            v-show="tab === 1"
          >
            <form class="c-form" @submit.prevent="login">
              <div v-if="loginErrors" class="c-errors">
                <ul v-if="loginErrors.name" class="c-errors_list">
                  <li v-for="msg in loginErrors.name" :key="msg" class="c-errors_item">{{ msg }}</li>
                </ul>
                <ul v-if="loginErrors.password" class="c-errors_list">
                  <li v-for="msg in loginErrors.password" :key="msg" class="c-errors_item">{{ msg }}</li>
                </ul>
              </div>
              <div class="c-form_line c-form_line-wrap">
                <label for="login-name">ユーザー名</label>
                <input type="text" class="c-form_input c-form-wide" id="login-name" v-model="loginForm.name">
              </div>
              <div class="c-form_line c-form_line-wrap">
                <label for="login-password">パスワード</label>
                <input type="password" class="c-form_input c-form-wide" id="login-password" v-model="loginForm.password">
              </div>
              <div class="c-btn-wrap c-btn-wrap-right">
                <button type="submit" class="c-btn c-btn-black">ログイン</button>
              </div>
            </form>
          </div>
          <div
            class="panel_item"
            v-show="tab === 2"
          >
            <form class="c-form" @submit.prevent="register">
              <div v-if="registerErrors" class="c-errors">
                <ul v-if="registerErrors.name" class="c-errors_list">
                  <li v-for="msg in registerErrors.name" :key="msg" class="c-errors_item">{{ msg }}</li>
                </ul>
                <ul v-if="registerErrors.email" class="c-errors_list">
                  <li v-for="msg in registerErrors.email" :key="msg" class="c-errors_item">{{ msg }}</li>
                </ul>
                <ul v-if="registerErrors.password" class="c-errors_list">
                  <li v-for="msg in registerErrors.password" :key="msg" class="c-errors_item">{{ msg }}</li>
                </ul>
              </div>
              <div class="c-form_line c-form_line-wrap">
                <label for="username">ユーザー名</label>
                <input type="text" class="c-form_input c-form-wide" id="username" v-model="registerForm.name">
              </div>
              <div class="c-form_line c-form_line-wrap">
                <label for="email">メールアドレス</label>
                <input type="text" class="c-form_input c-form-wide" id="email" v-model="registerForm.email">
              </div>
              <div class="c-form_line c-form_line-wrap">
                <label for="password">パスワード</label>
                <input type="password" class="c-form_input c-form-wide" id="password" v-model="registerForm.password">
              </div>
              <div class="c-form_line c-form_line-wrap">
                <label for="password-confirmation">パスワード (確認)</label>
                <input type="password" class="c-form_input c-form-wide" id="password-confirmation" v-model="registerForm.password_confirmation">
              </div>
              <div class="c-btn-wrap c-btn-wrap-right">
                <button type="submit" class="c-btn c-btn-black">登録</button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  data () {
    return {
      tab: 1,
      loginForm: {
        name: '',
        password: ''
      },
      registerForm: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      }
    }
  },
  computed: mapState({
    apiStatus: state => state.auth.apiStatus,
    loginErrors: state => state.auth.loginErrorMessages,
    registerErrors: state => state.auth.registerErrorMessages
  }),
  created () {
    this.clearError()
  },
  methods: {
    async login () {
      // authストアのloginアクションを呼び出す
      await this.$store.dispatch('auth/login', this.loginForm)

      if (this.apiStatus) {
        // トップページに移動する
        this.$router.push('/')
      }
    },
    async register () {
      // authストアのresigterアクションを呼び出す
      await this.$store.dispatch('auth/register', this.registerForm)

      if (this.apiStatus) {
        // トップページに移動する
        this.$router.push('/')
      }
    },
    clearError () {
      this.$store.commit('auth/setLoginErrorMessages', null)
      this.$store.commit('auth/setRegisterErrorMessages', null)
    }
  }
}
</script>
