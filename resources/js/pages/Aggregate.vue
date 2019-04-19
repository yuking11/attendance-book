<template>
  <div class="l-content-small">
    <div class="l-inner">

      <h1 class="c-ttl-primary u-tac">集計</h1>

      <div v-if="aggregateErrors" class="c-errors">
        <ul v-if="aggregateErrors" class="c-errors_list">
          <li
            class="c-errors_item"
            v-for="(msg, key) in aggregateErrors"
            :key="key"
          >{{ msg[0] }}</li>
        </ul>
      </div>

      <Loader v-show="isLoading" />

      <section
        class="l-section"
         v-show="! isLoading"
      >
        <h2 class="c-ttl-secondary">期間設定</h2>
        <form
          id="form-aggr"
          class="c-form"
          @submit.prevent="count"
        >
          <div class="c-form-aggr">
            <datepicker
              name="start"
              v-model="search.start"
              :language="ja"
              :format="formatDate"
              :wrapper-class="'c-form-aggr_item c-form-aggr_input'"
              :input-class="'c-form_input c-form-wide'"
            ></datepicker>
            <span class="c-form-aggr_item">〜</span>
            <datepicker
              name="end"
              v-model="search.end"
              :language="ja"
              :format="formatDate"
              :wrapper-class="'c-form-aggr_item c-form-aggr_input'"
              :input-class="'c-form_input c-form-wide'"
            ></datepicker>
          </div>
          <div class="c-btn-wrap">
            <button
              type="submit"
              class="c-btn c-btn-block c-btn-black"
            >集計</button>
          </div>
        </form>
      </section>

      <section
        class="l-section"
        v-show="! isLoading"
      >
        <h2 class="c-ttl-secondary">{{ res.start | formatDateJp }}〜{{ res.end | formatDateJp }}の集計結果</h2>
        <div id="aggregate" ref="aggregate">
          <table class="c-tbl c-tbl-aggr">
            <thead>
              <tr>
                <th>メンバー</th>
                <th>出席数</th>
              </tr>
            </thead>
            <tbody>
              <template v-for="(category, i) in items">
                <tr>
                  <th class="category" colspan="2">{{ category.name }}</th>
                </tr>
                <template v-for="(member, c) in category.members">
                  <tr>
                    <th class="name">{{ member.name }}</th>
                    <td class="count">{{ member.statuses_count }}</td>
                  </tr>
                </template>
              </template>
            </tbody>
          </table>
        </div>
      </section>

    </div>
  </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import { OK } from '../util'
import Loader from '../components/Loader.vue'
import moment from 'moment'
import Datepicker from 'vuejs-datepicker'
import {en, ja} from 'vuejs-datepicker/dist/locale'

moment.lang('ja', {
  weekdays: ["日曜日","月曜日","火曜日","水曜日","木曜日","金曜日","土曜日"],
  weekdaysShort: ["日","月","火","水","木","金","土"],
})

export default {
  components: {
    Loader,
    Datepicker
  },
  data () {
    return {
      isLoading: false,
      ja: ja,
      formatDate: 'yyyy年MM月dd日',
      search: {
        start: null,
        end: null
      },
      res: {
        start: null,
        end: null
      },
      items: [],
    }
  },
  computed: {
    ...mapState({
      apiStatus: state => state.member.apiStatus,
      aggregateErrors: state => state.member.aggregateErrorMessages,
    }),
    now() {
      return moment().startOf('month').toDate()
    },
    next() {
      return moment().endOf('month').toDate()
    },
  },
  created () {
    this.fetchCalendars()
  },
  methods: {
    async fetchCalendars () {
      this.isLoading = true

      this.search.start = this.now
      this.search.end = this.next
      this.res.start = this.now
      this.res.end = this.next

      const RESPONSE = await axios.get('/api/aggregate')

      if (RESPONSE.status !== OK) {
        this.$store.commit('error/setCode', RESPONSE.status)
        return false
      }

      this.items = RESPONSE.data

      this.isLoading = false
    },
    async count () {
      this.isLoading = true

      const formData = new FormData()
      formData.append('start', this.search.start)
      formData.append('end', this.search.end)

      const RESPONSE = await this.$store.dispatch('member/aggregate', formData)

      if (this.apiStatus) {
        this.items = RESPONSE.data
        this.res.start = this.search.start
        this.res.end = this.search.end
      }

      this.isLoading = false
    },
    clearMessage () {
      this.$store.commit('member/setAggregateErrorMessages', null)
    },
  },
  filters: {
    formatDateJp (date) {
      return moment(date).format('YYYY年MM月DD日')
    },
  }
}
</script>
