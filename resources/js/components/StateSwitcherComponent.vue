  <template>
    <v-switch
      dense
      inset
      v-model="state"
      @change="changeState"
      :loading="loading"
      :disabled="!loading"
    ></v-switch>
  </template>
  <script>
  export default {
    props: {
      currentState: {
        type: Boolean,
        required: false
      },
      action: {
        type: String,
        required: true
      }
    },
    data() {
      return {
        state: false,
        loading: false
      };
    },
    created() {
      if (typeof this.currentState !== "undefined") {
        this.state = this.currentState;
      }
    },
    methods: {
      changeState() {
        const state = this.state;
        this.loading = true;
        axios
          .post(this.action, { state })
          .catch(function(error) {
            if (error.response) {
              console.log(error.response.data);
              console.log(error.response.status);
              console.log(error.response.headers);
            } else if (error.request) {
              console.log(error.request);
            } else {
              console.log("Error", error.message);
            }
            console.log(error.config);
          })
          .then(resp => {
            this.loading = false;
            if (resp.status != 200) {
              alert("No se pudo actualizar, intente mas tarde");
            }
          });
      }
    }
  };
  </script>
