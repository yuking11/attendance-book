<template>
  <div class="l-content">
    <div class="l-inner">

      <h1 class="c-ttl-primary u-tac">2019年度</h1>

    <div id="c-books_container" ref="books_container" class="c-books_wrap">
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
          <div class="body_line">
            <div class="body_name"><span>山田　太郎</span></div>
            <div class="body_count"><span>10</span></div>
            <div class="body_days c-form">
              <template
                v-for="calendar in calendars"
              >
                <div
                  class="body_cell"
                  v-bind:class="classWeekday(calendar.date)"
                  :key="calendar.id"
                >
                  <label class="c-form_label-check">
                    <input type="checkbox" name="check[]" value="1">
                    <span></span>
                  </label>
                  <!-- <span><label for=""><input type="checkbox" value="1"></label></span> -->
                </div>
              </template>
            </div>
          </div>
          <div class="body_line">
            <div class="body_name"><span>山田　太郎</span></div>
            <div class="body_count"><span>10</span></div>
            <div class="body_days c-form">
              <template
                v-for="calendar in calendars"
              >
                <div
                  class="body_cell"
                  v-bind:class="classWeekday(calendar.date)"
                  :key="calendar.id"
                >
                  <label class="c-form_label-check">
                    <input type="checkbox" name="check[]" value="1">
                    <span></span>
                  </label>
                  <!-- <span><label for=""><input type="checkbox" value="1"></label></span> -->
                </div>
              </template>
            </div>
          </div>
          <div class="body_line">
            <div class="body_name"><span>山田　太郎</span></div>
            <div class="body_count"><span>10</span></div>
            <div class="body_days c-form">
              <template
                v-for="calendar in calendars"
              >
                <div
                  class="body_cell"
                  v-bind:class="classWeekday(calendar.date)"
                  :key="calendar.id"
                >
                  <label class="c-form_label-check">
                    <input type="checkbox" name="check[]" value="1">
                    <span></span>
                  </label>
                  <!-- <span><label for=""><input type="checkbox" value="1"></label></span> -->
                </div>
              </template>
            </div>
          </div>
          <div class="body_line">
            <div class="body_name"><span>山田　太郎</span></div>
            <div class="body_count"><span>10</span></div>
            <div class="body_days c-form">
              <template
                v-for="calendar in calendars"
              >
                <div
                  class="body_cell"
                  v-bind:class="classWeekday(calendar.date)"
                  :key="calendar.id"
                >
                  <label class="c-form_label-check">
                    <input type="checkbox" name="check[]" value="1">
                    <span></span>
                  </label>
                  <!-- <span><label for=""><input type="checkbox" value="1"></label></span> -->
                </div>
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>

    </div>
  </div>
</template>

<script>
import { OK } from '../util'
import moment from 'moment'

moment.lang('ja', {
  weekdays: ["日曜日","月曜日","火曜日","水曜日","木曜日","金曜日","土曜日"],
  weekdaysShort: ["日","月","火","水","木","金","土"],
})

export default {
  components: {
  },
  data () {
    return {
      calendars: [],
      monthly: [],
      days: []
    }
  },
  computed: {
  },
  methods: {
    async fetchCalendars () {
      const RESPONSE = await axios.get('/api/index')
console.log(RESPONSE);
      if (RESPONSE.status !== OK) {
        this.$store.commit('error/setCode', RESPONSE.status)
        return false
      }
      this.calendars = RESPONSE.data['calendar']
      this.days = RESPONSE.data['days']
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
      this.$refs.books_container.scrollLeft = 32 * -count
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
  },
  created () {
    this.fetchCalendars()
  },
  mounted () {
  },
  // watch: {
  //   $route: {
  //     async handler () {
  //       await this.fetchCalendars()
  //     },
  //     immediate: true
  //   }
  // },
  filters: {
    month (date) {
      // return moment(date).format('YYYY/MM/DD HH:mm')
      return moment(date).format('MM月')
    },
    days (date) {
      // return moment(date).format('YYYY/MM/DD HH:mm')
      return moment(date).format('D')
    },
    weekdays (date) {
      return moment(date).format('ddd')
    }
  }
}
</script>
