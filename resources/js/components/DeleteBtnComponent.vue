<template>
  <button class="btn btn-danger" @click="onDelete">
    <i class="fas fa-times"></i>
  </button>
</template>
<script>
import Swal from 'sweetalert2'

export default {
  props: ['action', 'csrf'],
  methods: {
    onDelete: function () {
      Swal.fire({
        title: '¿Estas seguro?',
        text: 'Esta accion no se puede revertir',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, borrar',
        cancelButtonText: 'Cancelar'
      }).then( (result) => {
        if (result.value) {
          axios
            .delete(this.action)
            .then( (response) => {
              var response = response.data;
              Swal.fire({
                title: response.meta.title,
                html: response.meta.msg,
                icon: 'success'
              }).then(response => {
                location.reload()
              })
            })
        }
      })
    }
  }
}
</script>
<style></style>
