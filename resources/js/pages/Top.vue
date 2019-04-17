<template>
  <div class="l-content">
    <div class="l-inner">

      <h1 class="c-ttl-primary u-tac">2019年度</h1>

    <Loader v-show="isLoading" />
    <div id="c-books_container" ref="books_container" class="c-books_wrap" v-show="! isLoading">
      <div id="c-books" :data-days="days">
        <div class="c-books_head">
          <div class="head_ttl"><span>名前</span></div>
          <div class="head_count"><span>出席数</span></div>
          <div class="head_date">
            <div class="date_monthly">
              <div
                class="month"
                v-for="(value, key, index) in monthly"
                :key="index"
                :data-count="value"
              ><span>{{ key }}</span></div>
            </div>
            <div class="date_days">
              <div
                v-for="(calendar, index) in calendars"
                class="day"
                v-bind:class="classWeekday(calendar.date)"
              >
                <div>{{ calendar.date | days }}</div>
                <div>{{ calendar.date | weekdays }}</div>
              </div>
            </div>
          </div>
        </div>
        <div class="c-books_body">
          <template v-for="(category, i) in items">
            <div
              class="body_container"
              v-if="category.members.length"
              :key="category.id"
            >
              <div class="body_head"><span>{{ category.name }}</span></div>
              <div
                class="body_line"
                v-for="(member, n) in category.members"
                :key="member.id"
              >
                <div class="body_name"><span>{{ member.name }}</span></div>
                <div class="body_count"><span>{{ member.statuses.length }}</span></div>
                <div class="body_days c-form">
                  <template
                    v-for="(calendar, c) in calendars"
                  >
                    <div
                      class="body_cell"
                      v-bind:class="classWeekday(calendar.date)"
                      :key="calendar.date"
                    >
                      <button
                        type="button"
                        class=""
                        v-if="isAttendance(member.statuses, calendar.id)"
                        @click="update(calendar.id, member.id, 0)"
                      ><i class="fas fa-check-square"></i></button>
                      <button
                        type="button"
                        class=""
                        v-else
                        @click="update(calendar.id, member.id, 1)"
                      ><i class="far fa-square"></i></button>
                    </div>
                  </template>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>

    </div>
  </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import { OK } from '../util'
import Loader from '../components/Loader.vue'
import moment from 'moment'

moment.lang('ja', {
  weekdays: ["日曜日","月曜日","火曜日","水曜日","木曜日","金曜日","土曜日"],
  weekdaysShort: ["日","月","火","水","木","金","土"],
})

export default {
  components: {
    Loader
  },
  data () {
    return {
      isLoading: false,
      calendars: [],
      monthly: [],
      days: [],
      items: [],
      statuses: [],
    }
  },
  computed: {
    ...mapState({
      apiStatus: state => state.top.apiStatus,
      updateErrors: state => state.top.updateErrorMessages,
    }),
    ...mapGetters({
      userId: 'auth/userid'
    }),
  },
  created () {
    this.fetchCalendars()
  },
  methods: {
    async fetchCalendars () {
      this.isLoading = true

      const RESPONSE = await axios.get('/api/index')

      if (RESPONSE.status !== OK) {
        this.$store.commit('error/setCode', RESPONSE.status)
        return false
      }

      this.items = RESPONSE.data.data
      this.calendars = RESPONSE.data.calendar
      this.days = RESPONSE.data.days
      this.monthly = {
        '4月': 30,
        '5月': 31,
        '6月': 30,
        '7月': 31,
        '8月': 31,
        '9月': 30,
        '10月': 31,
        '11月': 30,
        '12月': 31,
        '1月': 31,
        '2月': 28,
        '3月': 31
      }
      if (this.days === 366) {
        this.monthly['2月'] = 29
      }
      const TODAY = moment().format('YYYY-MM-DD 00:00:00')
      const count = moment(this.calendars[0].date).diff(TODAY, 'days')
      this.$nextTick(() => this.$refs.books_container.scrollLeft = 32 * -count)

      this.isLoading = false
    },
    async update (calendarId, memberId, statusValue) {
      this.isLoading = true

      const formData = new FormData()
      formData.append('calendar_id', Number(calendarId))
      formData.append('member_id', Number(memberId))
      formData.append('status', statusValue)

      const RESPONSE = await this.$store.dispatch('top/status', formData)

      if (this.apiStatus) {
        this.items = RESPONSE.data
      }

      this.isLoading = false
    },
    classWeekday (date) {
      const TODAY = moment().format('YYYY-MM-DD 00:00:00')
      let week = moment(date).day()
      return {
        saturday: week === 6 ? true : false,
        sunday: week === 0 ? true : false,
        today: date === TODAY ? true : false
      }
    },
    isAttendance (array, id) {
      const data = array.find(item => item.calendar_id === id)
      return data
    },
    clearMessage () {
      this.$store.commit('top/setUpdateErrorMessages', null)
    }
  },
  filters: {
    month (date) {
      // return moment(date).format('YYYY/MM/DD HH:mm')
      return moment(date).format('MM月')
    },
    days (date) {
      return moment(date).format('D')
    },
    weekdays (date) {
      return moment(date).format('ddd')
    }
  }
}
</script>
