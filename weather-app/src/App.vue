<template>
  <div>

  </div>
</template>

<script>

export default {

  data() {
    return {
      parsedData: {}
    }
  },

  created() {
    this.interval = setInterval(() => this.getData(), 1000);
  },

  methods: {

    getData() {
      import ('weather_log.csv').then(function (log){
        console.log(log);
      } );



    }
  }


}
</script>
