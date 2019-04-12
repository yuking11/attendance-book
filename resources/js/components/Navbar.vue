<template>
  <div class="l-nav">
    <input type="checkbox" id="gnav-ctrl" name="gnav-ctrl">
    <label for="gnav-ctrl" class="gnav-btn"><span class="gnav-btn_inner"></span></label>
    <label for="gnav-ctrl" class="gnav-overlay"></label>
    <nav class="gnav" role="navigation">
      <div v-if="isLogin" class="gnav_ttl"><i class="fas fa-user u-mr5"></i>{{ username }}</div>
      <ul class="gnav_list">
        <template v-if="isLogin">
          <li class="gnav_item">
            <RouterLink class="gnav_link" to="/" @click.native="menuOff()">
              トップ
            </RouterLink>
          </li>
          <li class="gnav_item">
            <RouterLink class="gnav_link" to="/member" @click.native="menuOff()">
              メンバー
            </RouterLink>
          </li>
          <li class="gnav_item">
            <RouterLink class="gnav_link" to="/category" @click.native="menuOff()">
              カテゴリ
            </RouterLink>
          </li>
          <li class="gnav_item">
            <a href="#" class="gnav_link" @click="logout">
              ログアウト
            </a>
          </li>
        </template>
        <template v-else>
          <li class="gnav_item">
            <RouterLink class="gnav_link" to="/login">
              ログイン / 登録
            </RouterLink>
          </li>
        </template>
      </ul><!-- /.gnav_list -->
    </nav><!-- /.gnav -->
  </div><!-- /.l-nav -->
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
      username: 'auth/username'
    })
  },
  methods: {
    async logout () {
      await this.$store.dispatch('auth/logout')

      if (this.apiStatus) {
        this.menuOff()
        this.$router.push('/login')
      }
    },
    async menuOff () {
      document.getElementById('gnav-ctrl').checked = false
    }
  }
}
</script>
