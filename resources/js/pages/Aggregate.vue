<template>
  <div class="l-content">
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
      <form
        id="form-aggregate"
        class="c-form"
        @submit.prevent="count"
      >
        <datepicker
          name="start"
          v-model="search.start"
          :language="ja"
          :format="formatDate"
        ></datepicker>
        <datepicker
          name="end"
          v-model="search.end"
          :language="ja"
          :format="formatDate"
        ></datepicker>
        <button
          type="submit"
        >集計</button>
      </form>

      <Loader v-show="isLoading" />
      <div id="aggregate" ref="aggregate" v-show="! isLoading">
        <table class="c-tbl">
          <thead>
            <tr>
              <th>メンバー</th>
              <th>{{ res.start | formatDateJp }}〜{{ res.end | formatDateJp }}</th>
            </tr>
          </thead>
          <tbody>
            <template v-for="(category, i) in items">
              <tr>
                <th colspan="2">{{ category.name }}</th>
              </tr>
              <template v-for="(member, c) in category.members">
                <tr>
                  <th>{{ member.name }}</th>
                  <td>{{ member.statuses_count }}</td>
                </tr>
              </template>
            </template>
          </tbody>
        </table>
      </div>

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
