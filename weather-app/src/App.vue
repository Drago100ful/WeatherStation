<template>
  <div>

  </div>
</template>

<script>

export default {

  data() {
    return {
      parsedData: {},
      timespan: "5"
    }
  },

  created() {
    this.getData();
    this.interval = setInterval(() => this.getData(), 5000);
  },

  methods: {

    getData() {
      fetch("https://127.0.0.1/weathertest/api.php?getTemp&timespan=" + this.timespan)
          .then(respone => respone.json())
          .then(data => (this.parsedData = data))
    }
  }


}
</script>
