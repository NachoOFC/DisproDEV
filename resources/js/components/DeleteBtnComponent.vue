<template>
  <button class="btn btn-danger" @click="onDelete" :title="titleAttr" :aria-label="ariaLabelAttr" type="button">
    <i class="fas fa-times" aria-hidden="true"></i>
    <span class="sr-only">{{ titleAttr }}</span>
  </button>
</template>
<script>
import Swal from 'sweetalert2'

export default {
  props: {
    action: { type: String, required: true },
    csrf: { type: String, required: false },
    title: { type: String, default: 'Eliminar' },
    ariaLabel: { type: String, default: null }
  },
  computed: {
    titleAttr() {
      return this.title || 'Eliminar'
    },
    ariaLabelAttr() {
      return this.ariaLabel || this.titleAttr
    }
  },
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
