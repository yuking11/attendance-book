<template>
  <footer class="l-footer" role="contentinfo">
    <div class="l-inner">
      <a
        href="#"
        class="c-btn c-btn-link"
        v-if="isLogin"
        @click="logout"
      >ログアウト</a>
      <p class="copy">(c) 2019 yuking11.net</p>
    </div>
  </footer>
</template>

<script>
import { mapState, mapGetters } from 'vuex'

export default {
  computed: {
    ...mapState({
      apiStatus: state => state.auth.apiStatus
    }),
    ...mapGetters({
      isLogin: 'auth/check'
    })
  },
  methods: {
    async logout () {
      await this.$store.dispatch('auth/logout')

      if (this.apiStatus) {
        this.$router.push('/login')
      }
    }
  }
}
</script>
