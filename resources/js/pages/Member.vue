<template>
  <div class="l-content-small">
    <div class="l-inner">

      <h1 class="c-ttl-primary">メンバー 登録 / 編集</h1>

      <div class="c-tab">
        <ul class="c-tab_list">
          <li
            class="list_item"
            :class="{'list_item-active': tab === 1 }"
            @click="tab = 1"
          >登録</li>
          <li
            class="list_item"
            :class="{'list_item-active': tab === 2 }"
            @click="tab = 2"
          >編集</li>
        </ul>
        <div class="c-tab_panel">
          <div
            class="panel_item"
            v-show="tab === 1"
          >
            <div v-if="registerErrors" class="c-errors">
              <ul v-if="registerErrors" class="c-errors_list">
                <li v-for="(msg, key) in registerErrors" :key="key" class="c-errors_item">{{ msg[0] }}</li>
              </ul>
            </div>
            <Loader v-show="isLoading" />
            <form
              id="form-regist"
              class="c-form"
              v-show="! isLoading"
              @submit.prevent="regist"
            >
              <div class="c-form_list-head">
                <div class="head_item head_item-name">名前入力</div>
                <div class="head_item head_item-category">カテゴリ</div>
                <div class="head_item head_item-btn">追加</div>
                <div class="head_item head_item-btn">削除</div>
              </div>
              <ul class="c-form_list">
                <li
                  class="c-form_line"
                  v-for="(item, index) in newMembers"
                  :key="index"
                >
                  <input
                    type="text"
                    name="member[][name]"
                    class="c-form_input c-form-wide line_item"
                    required
                    placeholder="名前"
                    v-model="item.name"
                  >
                  <label class="c-form_select-label line_item">
                    <select
                      name="member[][category]"
                      class="c-form_select c-form_category"
                      required
                      v-model="item.category_id"
                    >
                      <option value="" disabled selected>ー</option>
                      <option
                        v-for="(category, index) in categories"
                        :value="category.id"
                      >{{ category.name }}</option>
                    </select>
                  </label>
                  <button
                    type="button"
                    class="c-form_add line_item"
                    @click="addLine"
                  ><i class="fas fa-plus-square"></i></button>
                  <button
                    type="button"
                    class="c-form_del line_item"
                    v-bind:disabled="newMembers.length <= 1"
                    @click="deleteLine(index)"
                  ><i class="fas fa-minus-square"></i></button>
                </li>
              </ul>
              <div class="c-btn-wrap c-btn-wrap-right">
                <button type="submit" class="c-btn c-btn-black">登録</button>
              </div>
            </form>
            <ul class="c-notice u-mb0">
              <li class="c-notice_item">「＋」「−」で行の追加／削除が可能です。</li>
              <li class="c-notice_item">空白行があると登録エラーとなりますのでご注意ください。</li>
            </ul>
          </div>
          <div
            class="panel_item"
            v-show="tab === 2"
          >
            <div v-if="success" class="c-success">
              <ul class="c-success_list">
                <li class="c-success_item">{{ success }}</li>
              </ul>
            </div>
            <div v-if="updateErrors" class="c-errors">
              <ul v-if="updateErrors" class="c-errors_list">
                <li v-for="(msg, key) in updateErrors" :key="key" class="c-errors_item">{{ msg[0] }}</li>
              </ul>
            </div>
            <div v-if="deleteErrors" class="c-errors">
              <ul v-if="deleteErrors" class="c-errors_list">
                <li v-for="(msg, key) in deleteErrors" :key="key" class="c-errors_item">{{ msg[0] }}</li>
              </ul>
            </div>
            <template v-if="members.length">
              <Loader v-show="isLoading" />
              <form
                id="form-edit"
                class="c-form"
                v-show="! isLoading"
                @submit.prevent="update"
              >
                <div class="c-form_list-head">
                  <div class="head_item head_item-name">名前編集</div>
                  <div class="head_item head_item-category">カテゴリ</div>
                  <div class="head_item head_item-sort">表示順</div>
                  <div class="head_item head_item-btn">削除</div>
                </div>
                <draggable
                  class="c-form_list"
                  v-model="members"
                  tag="ul"
                  @end="onEnd"
                >
                  <li
                    class="c-form_line c-form_line-drag"
                    v-for="(item, index) in members"
                  >
                    <input
                      type="hidden"
                      name="[][id]"
                      class="member_id"
                      v-model="item.id"
                    >
                    <input
                      type="text"
                      name="[][name]"
                      class="c-form_input c-form-wide line_item"
                      required
                      v-model="item.name"
                    >
                    <label class="c-form_select-label line_item">
                      <select
                        name="[][category_id]"
                        class="c-form_select c-form_category"
                        v-model="item.category_id"
                      >
                        <option
                          v-for="(category, index) in categories"
                          :value="category.id"
                          v-bind:selected="item.category_id === category.id"
                        >{{ category.name }}</option>
                      </select>
                    </label>
                    <input
                      type="number"
                      name="[][sort]"
                      class="c-form_input c-form_sort line_item"
                      min="0"
                      v-model="item.sort"
                    >
                    <button
                      type="button"
                      class="c-form_del line_item"
                      @click="deleteMember(item.id, item.name)"
                    ><i class="fas fa-minus-square"></i></button>
                  </li>
                </draggable>
                <div class="c-btn-wrap c-btn-wrap-right">
                  <button type="submit" class="c-btn c-btn-black">更新</button>
                </div>
              </form>
              <ul class="c-notice u-mb0">
                <li class="c-notice_item">ドラッグ＆ドロップで順序入れ替え可能です。<br>その際、表示順の数字は上から自動で振り分けられますのでご注意ください。</li>
                <li class="c-notice_item">表示順はカテゴリの表示順が優先され、入力された数字が小さいものから上に表示されます。</li>
              </ul>
            </template>
            <template v-else>
              <p>メンバーが登録されていません</p>
            </template>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import { OK } from '../util'
import Loader from '../components/Loader.vue'
import draggable from 'vuedraggable'

export default {
  components: {
    Loader,
    draggable
  },
  data () {
    return {
      tab: 1,
      isLoading: false,
      newMembers: [
        {
          name: null,
          category_id: ''
        }
      ],
      members: [
        {
          id: null,
          name: null,
          category_id: ''
        }
      ],
      categories: [],
      success: null
    }
  },
  computed: mapState({
    apiStatus: state => state.member.apiStatus,
    registerErrors: state => state.member.registerErrorMessages,
    updateErrors: state => state.member.updateErrorMessages,
    deleteErrors: state => state.member.deleteErrorMessages
  }),
  created () {
    this.fetchCategories()
    this.fetchMembers()
    this.clearMessage()
  },
  methods: {
    async fetchCategories () {
      this.categories = []
      const RESPONSE = await axios.get('/api/category')

      if (RESPONSE.status !== OK) {
        this.$store.commit('error/setCode', RESPONSE.status)
        return false
      }
      this.categories = RESPONSE.data
    },
    async fetchMembers () {
      this.isLoading = true
      this.members = []
      const RESPONSE = await axios.get('/api/member')

      if (RESPONSE.status !== OK) {
        this.$store.commit('error/setCode', RESPONSE.status)
        this.isLoading = false
        return false
      }
      this.members = RESPONSE.data.member
      this.isLoading = false
    },
    addLine () {
      this.newMembers.push({ name: null, category_id: '' })
    },
    deleteLine (index) {
      this.newMembers.splice(index, 1)
      console.log('delete' + index)
    },
    reset () {
      this.newMembers = [{ name: null, category_id: '' }]
      this.clearMessage()
    },
    async regist () {
      this.isLoading = true
      this.clearMessage()

      const RESPONSE = await this.$store.dispatch('member/register', this.newMembers)

      if (this.apiStatus) {
        this.reset()
        this.success = 'メンバーを追加しました。'
        this.members = RESPONSE.data.member
        this.tab = 2
      }

      this.isLoading = false
    },
    async update () {
      this.isLoading = true
      this.clearMessage()

      const RESPONSE = await this.$store.dispatch('member/update', this.members)

      if (this.apiStatus) {
        this.success = 'メンバーを更新しました。'
        this.members = RESPONSE.data.member
      }

      this.isLoading = false
    },
    async deleteMember(member_id, member_name) {
      const result = confirm('"' + member_name + '"を削除してよろしいですか？');

      if (result) {
        this.isLoading = true

        this.clearMessage()

        const DATA = {id: Number(member_id), name: member_name}
        const RESPONSE = await this.$store.dispatch('member/delete', {data: DATA})

        if (this.apiStatus) {
          window.alert('"' + member_name + '"を削除しました。')
          this.fetchMembers()
        }

        this.isLoading = false
      } else {
        return false
      }
    },
    onEnd (e) {
      let line = document.querySelectorAll('#form-edit .c-form_line');
      line.forEach( (el, index) => {
        this.members[index].sort = index
      })
    },
    clearMessage () {
      this.$store.commit('member/setRegisterErrorMessages', null)
      this.$store.commit('member/setUpdateErrorMessages', null)
      this.$store.commit('member/setDeleteErrorMessages', null)
      this.success = null
    }
  }
}
</script>
