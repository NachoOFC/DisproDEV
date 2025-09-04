<template>
  <form @submit="onSubmit" method="form.method">
    <div v-for="(item, index) in form.items" :key="index" class="form-group">
      <label for="item.name">{{item.label}}</label>
      <input v-if="item.type !== 'select'" class="form-control" v-model="formValues[item.name]" :type="item.type" :name="item.name"/>
      <select v-if="item.type === 'select'" :multiple="item.multiple" class="form-control" :name="item.name" v-model="formValues[item.name]">
        <option v-for="(option, index) in item.options" :key="index" :value="option.value">{{option.label}}</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
  </form>
</template>
<script>
import Swal from 'sweetalert2'
export default {
  props: {
    form: Object
  },
  data: function () {
    return {
      formValues: []
    }
  },
  computed: {

    /*
    * Funcion para serializar los inputs del formulario,
    * tomando como Keys los nombre en el prop Form, y 
    * como values los datos en formValues.
    *
    *  @returns Object
    */
    serialize: function () {
      let keys = this.form.items.map((value) => {
        return value.name;
      })
      let data = {}
      keys.forEach((value) => {
        data[value] = this.formValues[value]
      })
      return data
    }
  },
  methods: {
    onSubmit: function (e) {
      e.preventDefault()
      axios
        .post(this.form.action, this.serialize)
        .then(function (response) {
          var response = response.data;
          Swal.fire({
            title: response.meta.title,
            html: response.meta.msg,
            icon: 'success'
          })
        })
    }
  }
}
</script>
<style></style>
