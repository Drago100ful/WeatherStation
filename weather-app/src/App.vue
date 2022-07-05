<template>
  <div :class="darkMode ? 'dark' : ''">
    <div
      class="flex h-screen w-screen flex-col transition duration-300 dark:text-slate-50"
    >
      <div
        class="flex flex-row items-center bg-slate-50 p-4 transition duration-300 dark:border-b dark:border-b-slate-700 dark:bg-slate-900"
      >
        <button @click="toggleTheme">
          <svg
            class="h-8 w-8 text-slate-500 hover:text-slate-900 transition dark:text-slate-50 duration-300"
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
      </div>

      <div class="h-full transition duration-300 dark:text-slate-50">
        <div
          class="flex h-5/6 h-full flex-col justify-center bg-slate-100 drop-shadow transition duration-300 dark:bg-slate-900"
        >
          <div class="w-full">
            <h1
              class="text-center text-7xl font-semibold text-slate-900 opacity-95 transition duration-300 dark:text-slate-50"
            >
              {{ parsedData.temp }}°
            </h1>
          </div>
        </div>
        <div
          class="h-1/6 w-full bg-slate-200 p-4 transition duration-300 dark:bg-slate-800"
        >
          <div class="flex flex-col">
            <div
              class="flex flex-row justify-between text-slate-900 transition duration-300 dark:font-normal dark:text-slate-50"
            >
              <p class="opacity-95">{{ parsedData.humidity }}%</p>
              <p class="opacity-95">
                {{ parseInt(parsedData.altitude) + 54 }}m
              </p>
              <p class="opacity-95">{{ parsedData.pressure / 1000 }} bar</p>
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
  </div>
</template>

<script>
export default {
  data() {
    return {
      parsedData: {},
      timespan: "current",
      darkMode: false,
      options: [
        "Aktuell",
        "Letzten 5 Sekunden",
        "Letzten 30 Sekunden",
        "Letzte Minute",
        "Letzte 30 Minuten",
        "Letzte Stunde",
        "Letzten 12 Stunden",
        "Letzter Tag",
        "Letzten 7 Tage",
      ],
    };
  },
  watch: {
    timespan(newTimespan, oldTimespan) {
      if (newTimespan !== oldTimespan) {
        this.getData();
      }
    },
  },
  created() {
    this.getData();
    this.interval = setInterval(() => this.getData(), 5000);
  },

  methods: {
    toggleTheme() {
      this.darkMode = !this.darkMode;
    },

    getData() {
      fetch(
        "http://127.0.0.1/api.php?getTemp&timespan=" +
          this.timespan
      )
        .then((respone) => respone.json())
        .then((data) => (this.parsedData = data));
    },
  },
};
</script>
