<template>
  <div :class="[darkMode ? 'dark' : '']">
    <div
        class="flex h-screen w-screen flex-col bg-slate-200 transition-colors duration-300 dark:bg-slate-800 dark:text-slate-50"
    >
      <div
          class="flex flex-row items-center justify-between bg-slate-50 p-4 transition-colors duration-300 dark:border-b dark:border-b-slate-700 dark:bg-slate-900"
      >
        <button @click="toggleTheme">
          <svg
              class="h-8 w-8 text-slate-500 transition duration-300 hover:text-slate-900 dark:text-slate-50"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
          >
            <path
                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
                stroke-linecap="round"
                stroke-linejoin="round"
            />
          </svg>
        </button>

        <button @click="getData">
          <svg class="h-8 w-8 text-slate-500 transition duration-300 hover:text-slate-900 dark:text-slate-50"
               fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
               xmlns="http://www.w3.org/2000/svg">
            <path
                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                stroke-linecap="round"
                stroke-linejoin="round"/>
          </svg>
        </button>

      </div>

      <div
          class="flex h-full flex-col transition-colors duration-300 dark:text-slate-50"
      >
        <div
            :class="[this.drawer ? 'h-4/6' : 'h-6/6']"
            class="flex h-full flex-col bg-slate-100 dark:bg-slate-900 transition-colors duration-300"
        >
          <div
              class="flex flex-grow items-center justify-center transition-colors"
          >
            <div class="">
              <h1
                  class="text-center text-7xl font-semibold text-slate-900 opacity-95 transition duration-300 dark:text-slate-50"
              >
                {{ getRounded(parsedData.temp) }}°
              </h1>
            </div>
          </div>
          <div class="flex w-full justify-center delay-100">
            <button class="" @click="toggleDrawer">
              <svg
                  :class="[this.drawer ? 'rotate-180' : 'rotate-0']"
                  class="h-8 w-8 self-center text-slate-500 transition-all delay-100 duration-300"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
              >
                <path
                    d="M5 15l7-7 7 7"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
              </svg>
            </button>
          </div>
        </div>

        <div
            v-if="this.drawer"
            class="flex h-2/6 w-full justify-center bg-slate-200 drop-shadow transition-colors duration-300 dark:bg-slate-800"
        >
          <div class="flex-grow p-2">
            <apexchart
                :key="this.update"
                :options="this.chartOptions"
                :series="this.chartData"
                height="100%"
                type="line"
                width="100%"
            ></apexchart>
          </div>
        </div>
      </div>
      <div
          class="h-1/6 w-full bg-slate-200 p-4 transition duration-300 dark:bg-slate-800"
      >
        <div class="flex flex-col">
          <div
              class="flex flex-row justify-between text-slate-900 transition duration-300 dark:font-normal dark:text-slate-50"
          >
            <p class="opacity-95">{{ parseInt(parsedData.humidity) }}%</p>

            <p class="opacity-95">{{ getRounded(parsedData.pressure / 1000) }} bar</p>
          </div>
        </div>

        <select
            v-model="timespan"
            class="mt-8 w-full appearance-none rounded-lg bg-slate-300 p-1 px-2 text-center text-slate-900 opacity-95 transition duration-300 focus:outline-0 dark:bg-slate-700 dark:text-slate-50"
        >
          <option value="current">Aktuell</option>
          <option value="5s">Letzten 5 Sekunden</option>
          <option value="30s">Letzten 30 Sekunden</option>
          <option value="1min">Letzte Minute</option>
          <option value="30min">Letzten 30 Minuten</option>
          <option value="1h">Letzte 1 Stunde</option>
          <option value="12h">Letzte 12 Stunden</option>
          <option value="1d">Letzter Tag</option>
          <option value="7d">Letzten 7 Tage</option>
        </select>
      </div>
    </div>
  </div>
</template>

<script>
import config from "./assets/config.json";

export default {
  data() {
    return {
      update: 0,
      parsedData: {},
      timespan: "current",
      darkMode: false,
      host: config["host-address"],
      drawer: false,
      chartOptions: {
        chart: {
          id: "average-chart",
          foreColor: "#000000",
        },
        colors: ["#8b5cf6", "#3b82f6"],
        xaxis: {
          categories: [],
        },
        yaxis: {
          decimalsInFloat: 1,
        },
        stroke: {
          curve: "smooth",
        },
      },
      rawChartData: {},
      chartData: [
        {
          name: "Temperatur",
          data: [],
        },
        {
          name: "Luftfeuchte",
          data: [],
        },
        {
          name: "Druck",
          data: [],
        },
      ],
    };
  },
  watch: {
    'chartOptions.xaxis.categories': {
      handler(newValue, oldValue) {
        if(newValue !== oldValue) {
          this.update++;
        }
      },
      deep: true
    },

    rawChartData(newData, oldData) {
      if (newData !== oldData) {
        this.createChartData();

      }

    },

    timespan(newTimespan, oldTimespan) {
      if (newTimespan !== oldTimespan) {
        this.getData();
      }
    },
  },
  created() {
    this.getData();
    this.startTimer();
  },

  mounted() {

  },

  beforeDestroy() {
    clearInterval(this.interval)
  },

  methods: {
    getRounded($value) {
      return parseFloat($value).toFixed(2)
    },

    startTimer() {
      this.interval = setInterval(() => this.getData(), 30000);
    },

    toggleDrawer() {
      this.drawer = !this.drawer;
    },

    toggleTheme() {
      this.darkMode = !this.darkMode;

      if (this.darkMode) {
        this.chartOptions.chart.foreColor = "#FFFFFF";
        this.update++;
      } else {
        this.chartOptions.chart.foreColor = "#000000";
        this.update++;
      }
    },

    getData() {
      fetch(
          "http://" + this.host + "/api.php?getTemp&timespan=" + this.timespan
      )
          .then((respone) => respone.json())
          .then((data) => (this.parsedData = data));
      fetch(
          "http://" + this.host + "/api.php?getAverage&timespan=" + this.timespan
      )
          .then((respone) => respone.json())
          .then((data) => (this.rawChartData = data));


    },

    createChartData() {


      let temp = [];
      let press = [];
      let humid = [];
      let time = [];
      for (let i = 0; i < this.rawChartData.length; i++) {
        let t = this.rawChartData[i].date.split(/[- :]/);
        time[i] = t[3] + ":" + t[4];
        temp[i] = this.rawChartData[i].temp;
        humid[i] = this.rawChartData[i].humidity;
        press[i] = this.rawChartData[i].pressure / 1000;
      }

      this.chartOptions.xaxis.categories = time;
      this.chartData[0].data = temp;
      this.chartData[1].data = humid;
      this.chartData[2].data = press;

    },
  },
};
</script>
