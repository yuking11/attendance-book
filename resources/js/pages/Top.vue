<template>
  <div class="l-content">
    <div class="l-inner">

      <h1 class="c-ttl-primary u-tac u-mb15">{{ currentYear }}年{{ currentMonth }}月</h1>

      <ul class="c-books_nav">
        <li class="nav_item nav_item-prev">
          <button
            class="nav_link"
            v-bind:disabled="yearMonth === '201904'"
            @click="goPrevMonth"
            >Prev</button>
        </li>
        <li class="nav_item nav_item-current">
          <button
            class="nav_link"
            v-bind:disabled="yearMonth === now"
            @click="goCurrentMonth"
            >Today</button>
        </li>
        <li class="nav_item nav_item-next">
          <button
            class="nav_link"
            v-bind:disabled="yearMonth === '205003'"
            @click="goNextMonth"
          >Next</button>
        </li>
      </ul>

      <Loader v-show="isLoading" />
      <div id="c-books_container" ref="books_container" class="c-books_wrap" v-show="! isLoading">
        <div id="c-books" :data-days="days">
          <div class="c-books_head">
            <div class="head_ttl"><span>名前</span></div>
            <div class="head_count"><span>出席数</span></div>
            <div class="head_date">
              <div class="date_days">
                <div
                  class="day"
                  v-for="(calendar, index) in calendars"
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
  props: {
    id: null
  },
  data () {
    return {
      isLoading: false,
      calendars: [],
      days: [],
      items: [],
      current: 0,
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
    now() {
      return moment().format('YYYYMM')
    },
    currentMoment() {
      return moment().add(this.current, 'months')
    },
    currentYear() {
      return this.currentMoment.format('YYYY')
    },
    currentMonth() {
      return this.currentMoment.format('MM')
    },
    yearMonth() {
      return this.currentYear + this.currentMonth
    },
  },
  created () {
    this.fetchCalendars()
  },
  methods: {
    async fetchCalendars () {
      this.isLoading = true

      const RESPONSE = await axios.get('/api/index/' + this.yearMonth)

      if (RESPONSE.status !== OK) {
        this.$store.commit('error/setCode', RESPONSE.status)
        return false
      }

      this.items = RESPONSE.data.data
      this.calendars = RESPONSE.data.calendar
      this.days = RESPONSE.data.days
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
      formData.append('date', this.yearMonth)

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
    },
    goNextMonth() {
      this.current++
      this.fetchCalendars()
    },
    goPrevMonth() {
      this.current--
      this.fetchCalendars()
    },
    goCurrentMonth() {
      this.current = 0
      this.fetchCalendars()
    },
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
