<template>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" @change="libreria" v-model="check">
        <label class="form-check-label">
            {{ (check) ? 'Eliminar de la libreria' : 'Agregar a la libreria' }}
        </label>
    </div>
</template>
<script>
import Swal from 'sweetalert2'

export default {
    props: ['action', 'library'],
    methods: {
        libreria: function () {
            if (this.check) {
                let self = this;
                Swal.fire({
                    title: '¿Deseas agregar este Requerimiento a la Libreria?',
                    text: 'Ingresa un nombre para la libreria',
                    input: 'text',
                    showCancelButton: true,
                    confirmButtonText: 'Agregar',
                    cancelButtonText: 'Cancelar',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Este campo no puede estar vacio'
                        }
                    },
                    showLoaderOnConfirm: true,
                    preConfirm: (value) => {
                        return axios
                            .put(this.action, {
                                requerimiento: this.requerimiento,
                                nombre: value,
                                libreria: true
                            })
                            .then( response => {
                                return response.data
                            })
                            .catch(error => {
                                Swal.showValidationMessage(
                                    `Error agregando a libreria: ${error}`
                                )
                            })
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    Swal.fire({
                        icon: 'success',
                        title: result.value.meta.title,
                        html: result.value.meta.msg
                    })
                })
            } else {
                let self = this;
                Swal.fire({
                    title: '¿Deseas eliminar este Requerimiento de la Libreria?',
                    showCancelButton: true,
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return axios
                            .put(this.action, {
                                requerimiento: this.requerimiento,
                                libreria: false
                            })
                            .then( response => {
                                return response.data
                            })
                            .catch(error => {
                                Swal.showValidationMessage(
                                    `Error eliminando de la libreria: ${error}`
                                )
                            })
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    Swal.fire({
                        icon: 'success',
                        title: result.value.meta.title,
                        html: result.value.meta.msg
                    })
                })
            }
        }
    },
    data() {
        return {
            check: false
        }
    },
    created() {
        if (this.library) {
            this.check = true;
        }
    }
}
</script>
<style></style>
