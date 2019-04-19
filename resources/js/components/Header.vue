<template>
  <header class="l-header">
    <div class="l-inner">
      <div class="logo">
        <RouterLink class="logo_link" to="/">Web出席簿</RouterLink>
      </div>
      <nav class="navbar">
        <ul v-if="isLogin" class="navbar_menu">
          <li class="navbar_item">
            <RouterLink class="c-btn c-btn-link" to="/">
              トップ
            </RouterLink>
          </li>
          <li class="navbar_item">
            <RouterLink class="c-btn c-btn-link" to="/member">
              メンバー
            </RouterLink>
          </li>
          <li class="navbar_item">
            <RouterLink class="c-btn c-btn-link" to="/category">
              カテゴリ
            </RouterLink>
          </li>
          <li class="navbar_item">
            <RouterLink class="c-btn c-btn-link" to="/aggregate">
              集計
            </RouterLink>
          </li>
          <li class="navbar_item navbar-user">
            <RouterLink class="c-btn c-btn-link" to="/profile">
              <i class="fas fa-user u-mr5"></i>{{ user.name }}
            </RouterLink>
          </li>
        </ul>
        <ul v-else class="navbar_menu">
          <li class="navbar_item">
            <RouterLink class="c-btn c-btn-link" to="/login">
              ログイン / 登録
            </RouterLink>
          </li>
        </ul>
      </nav>
    </div>
  </header>
</template>

<script>
import { mapState, mapGetters } from 'vuex'

export default {
  computed: {
    ...mapState({
      apiStatus: state => state.auth.apiStatus
    }),
    ...mapGetters({
      isLogin: 'auth/check',
      user: 'auth/user'
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
