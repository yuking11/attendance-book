<template>
  <footer class="l-footer" role="contentinfo">
    <div class="l-inner">
      <ul class="fnav">
        <li
          class="fnav_item"
          v-if="isLogin"
        >
          <a
            href="#"
            class="fnav_link"
            @click="logout"
          >ログアウト</a>
        </li>
        <li class="fnav_item">
          <router-link
            class="fnav_link"
            to="/privacy"
          >プライバシーポリシー</router-link>
        </li>
      </ul>
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
