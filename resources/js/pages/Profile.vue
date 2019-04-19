<template>
  <div class="l-content-small">
    <div class="l-inner">

      <h1 class="c-ttl-primary u-tac">アカウント</h1>

      <div v-if="success" class="c-success">
        <ul class="c-success_list">
          <li class="c-success_item">{{ success }}</li>
        </ul>
      </div>
      <div v-if="updateErrors" class="c-errors">
        <ul v-if="updateErrors.errors" class="c-errors_list">
          <li v-for="(msg, key) in updateErrors.errors.name" :key="key" class="c-errors_item">{{ msg }}</li>
          <li v-for="(msg, key) in updateErrors.errors.email" :key="key" class="c-errors_item">{{ msg }}</li>
        </ul>
        <ul v-else="updateErrors.message" class="c-errors_list">
          <li class="c-errors_item">{{ updateErrors.message }}</li>
        </ul>
      </div>
      <div v-if="deleteErrors" class="c-errors">
        <ul v-if="deleteErrors" class="c-errors_list">
          <li v-for="(msg, key) in deleteErrors" :key="key" class="c-errors_item">{{ msg }}</li>
        </ul>
      </div>
      <Loader v-show="isLoading" />
      <form
        id="form-profile"
        class="c-form"
        v-show="! isLoading"
        @submit.prevent="updateUser"
      >
        <div class="c-form_line c-form_line-wrap">
          <label for="login-name">ユーザー名</label>
          <input type="text" class="c-form_input c-form-wide" id="login-name" v-model="updateForm.name">
        </div>
        <div class="c-form_line c-form_line-wrap">
          <label for="login-email">メールアドレス</label>
          <input type="text" class="c-form_input c-form-wide" id="login-email" v-model="updateForm.email">
        </div>
        <div class="c-btn-wrap">
          <button
            type="submit"
            class="c-btn c-btn-block c-btn-black"
          >更新</button>
        </div>
        <div class="c-btn-wrap">
          <button
            type="button"
            class="c-btn c-btn-block c-btn-caution"
            @click="deleteUser"
          >アカウント削除</button>
        </div>
      </form>

    </div>
  </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import { OK } from '../util'
import Loader from '../components/Loader.vue'

export default {
  components: {
    Loader
  },
  data () {
    return {
      isLoading: false,
      updateForm: {
        name: null,
        email: null
      },
      success: null,
    }
  },
  computed: {
    ...mapState({
      setUser: state => state.auth.user,
      apiStatus: state => state.user.apiStatus,
      updateErrors: state => state.user.updateErrorMessages,
      deleteErrors: state => state.user.deleteErrorMessages
    }),
    ...mapGetters({
      user: 'auth/user'
    }),
  },
  created () {
    this.fetchUser()
  },
  methods: {
    async fetchUser () {
      this.isLoading = true
      this.clearMessage()
      this.updateForm.name = this.user.name
      this.updateForm.email = this.user.email
      this.isLoading = false
    },
    async updateUser () {
      this.isLoading = true
      this.clearMessage()

      const DATA = {id: this.user.id, name: this.updateForm.name, email: this.updateForm.email}

      const RESPONSE = await this.$store.dispatch('user/update', DATA)

      if (this.apiStatus) {
        this.success = 'アカウントを更新しました。'
        this.$store.commit('auth/setUser', RESPONSE.data)
      }

      this.isLoading = false
    },
    async deleteUser () {
      const result = confirm('アカウントを削除してよろしいですか？\n削除したデータは戻すことができません。');

      if (result) {
        this.clearMessage()

        const DATA = {id: this.user.id}
        const RESPONSE = await this.$store.dispatch('user/delete', {data: DATA})

        if (this.apiStatus) {
          window.alert('削除しました。')
          await this.$store.dispatch('auth/logout')
          this.$router.push('/login')
        }

      } else {
        return false
      }

    },
    clearMessage () {
      this.$store.commit('user/setUpdateErrorMessages', null)
      this.$store.commit('user/setDeleteErrorMessages', null)
      this.success = null
    },
  },
  filters: {
  }
}
</script>
