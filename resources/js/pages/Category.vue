<template>
  <div class="l-content-small">
    <div class="l-inner">

      <h1 class="c-ttl-primary">カテゴリ 登録 / 編集</h1>

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
                <div class="head_item head_item-name">カテゴリ名入力</div>
                <div class="head_item head_item-btn">追加</div>
                <div class="head_item head_item-btn">削除</div>
              </div>
              <ul class="c-form_list">
                <li
                  class="c-form_line"
                  v-for="(item, index) in newCategories.data"
                  :key="index"
                >
                  <input
                    type="text"
                    name="data[][name]"
                    class="c-form_input c-form-wide line_item"
                    required
                    placeholder="カテゴリ名"
                    v-model="item.name"
                    :ref="'category_' + index"
                  >
                  <button
                    type="button"
                    class="c-form_add line_item"
                    @click="addLine(index)"
                  ><i class="fas fa-plus-square"></i></button>
                  <button
                    type="button"
                    class="c-form_del line_item"
                    v-bind:disabled="newCategories.data.length <= 1"
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
              <template v-if="deleteErrors.errors">
                <ul v-if="deleteErrors.errors.id" class="c-errors_list">
                  <li v-for="(msg, key) in deleteErrors.errors.id" :key="key" class="c-errors_item">{{ msg }}</li>
                </ul>
                <ul v-if="deleteErrors.errors.name" class="c-errors_list">
                  <li v-for="(msg, key) in deleteErrors.errors.name" :key="key" class="c-errors_item">{{ msg }}</li>
                </ul>
              </template>
              <template v-else>
                <ul class="c-errors_list">
                  <li class="c-errors_item">{{ deleteErrors.message }}</li>
                </ul>
              </template>
            </div>
            <Loader v-show="isLoading" />
            <template v-if="! categories.data.length">
              <p v-show="! isLoading"><a href="#" @click="tab = 1">カテゴリ</a>が登録されていません。</p>
            </template>
            <template v-else>
              <form
                id="form-edit"
                class="c-form"
                v-show="! isLoading"
                @submit.prevent="update"
              >
                <div class="c-form_list-head">
                  <div class="head_item head_item-name">カテゴリ名編集</div>
                  <div class="head_item head_item-sort">表示順</div>
                  <div class="head_item head_item-btn">削除</div>
                </div>
                <draggable
                  class="c-form_list"
                  v-model="categories.data"
                  tag="ul"
                  @end="onEnd"
                >
                  <li
                    class="c-form_line c-form_line-drag"
                    v-for="(item, index) in categories.data"
                  >
                    <input
                      type="hidden"
                      name="data[][id]"
                      class="category_id"
                      v-model="item.id"
                    >
                    <input
                      type="text"
                      name="data[][name]"
                      class="c-form_input c-form-wide line_item"
                      required
                      v-model="item.name"
                    >
                    <input
                      type="number"
                      name="data[][sort]"
                      class="c-form_input c-form_sort line_item"
                      min="0"
                      v-model="item.sort"
                    >
                    <button
                      type="button"
                      class="c-form_del line_item"
                      @click="deleteCategory(item.id, item.name)"
                    ><i class="fas fa-minus-square"></i></button>
                  </li>
                </draggable>
                <div class="c-btn-wrap c-btn-wrap-right">
                  <button type="submit" class="c-btn c-btn-black">更新</button>
                </div>
              </form>
              <ul class="c-notice u-mb0">
                <li class="c-notice_item">ドラッグ＆ドロップで順序入れ替え可能です。<br>その際、表示順の数字は上から自動で振り分けられますのでご注意ください。</li>
                <li class="c-notice_item">表示順については入力された数字が小さいものから上に表示されます。</li>
                <li class="c-notice_item">所属するメンバーがいると削除できません。メンバーを別のカテゴリに移動させた上、削除を行ってください。</li>
              </ul>
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
      newCategories: {
        data: [ { name: null } ]
      },
      categories: {
        data: [
          {
            id: null,
            name: null,
            sort: null
          }
        ]
      },
      success: null
    }
  },
  computed: {
    ...mapState({
      apiStatus: state => state.category.apiStatus,
      registerErrors: state => state.category.registerErrorMessages,
      updateErrors: state => state.category.updateErrorMessages,
      deleteErrors: state => state.category.deleteErrorMessages
    })
  },
  created () {
    this.fetchCategories()
    this.clearMessage()
  },
  methods: {
    async fetchCategories () {
      this.isLoading = true

      const RESPONSE = await axios.get('/api/category')

      if (RESPONSE.status !== OK) {
        this.$store.commit('error/setCode', RESPONSE.status)
        this.isLoading = false
        return false
      }
      this.categories.data = RESPONSE.data
      this.isLoading = false
    },
    addLine (index) {
      this.newCategories.data.push({ name: null })
      this.$nextTick(() => this.$refs['category_' + (++index)]['0'].focus())
    },
    deleteLine (index) {
      this.newCategories.data.splice(index, 1)
    },
    reset () {
      this.newCategories.data = [{ name: null }]
      this.clearMessage()
    },
    async regist () {
      this.isLoading = true
      this.clearMessage()

      const RESPONSE = await this.$store.dispatch('category/register', this.newCategories)

      if (this.apiStatus) {
        this.reset()
        this.success = 'カテゴリを追加しました。'
        this.categories.data = RESPONSE.data
        this.tab = 2
      }

      this.isLoading = false
    },
    async update () {
      this.isLoading = true
      this.clearMessage()

      const RESPONSE = await this.$store.dispatch('category/update', this.categories)

      if (this.apiStatus) {
        this.success = 'カテゴリを更新しました。'
        this.categories.data = RESPONSE.data
      }

      this.isLoading = false
    },
    async deleteCategory(category_id, category_name) {
      const result = confirm('"' + category_name + '"を削除してよろしいですか？');

      if (result) {
        this.isLoading = true

        this.clearMessage()

        const DATA = {id: Number(category_id), name: category_name}
        const RESPONSE = await this.$store.dispatch('category/delete', {data: DATA})

        if (this.apiStatus) {
          window.alert('"' + category_name + '"を削除しました。')
          this.fetchCategories()
        }

        this.isLoading = false
      } else {
        return false
      }
    },
    onEnd (e) {
      let line = document.querySelectorAll('#form-edit .c-form_line');
      line.forEach( (el, index) => {
        this.categories.data[index].sort = index
      })
    },
    clearMessage () {
      this.$store.commit('category/setRegisterErrorMessages', null)
      this.$store.commit('category/setUpdateErrorMessages', null)
      this.$store.commit('category/setDeleteErrorMessages', null)
      this.success = null
    }
  }
}
</script>
