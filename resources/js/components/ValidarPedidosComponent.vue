<template>
    <div>
        <button :class="color" @click="showModal">
            <slot></slot>
        </button>
    </div>
</template>
<script>
import Swal from 'sweetalert2'

export default {
    props: {
        pedido: {
            type: Number,
            default: null
        },
        validacion: {
            type: Boolean,
            default: true
        },
        action: {
            type: String
        },
        todos: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        color: function() {
            if (this.validacion) {
                if (this.todos) {
                    return {'btn': true, 'btn-primary': true}
                } else {
                    return {'btn': true, 'btn-success': true}
                }
            } else {
                if (this.todos) {
                    return {'btn': true, 'btn-danger': true}
                } else {
                    return {'btn': true, 'btn-warning': true}
                }
            }
        }
    },
    methods: {
        showModal: function() {
            if (this.validacion) {
                if (this.todos) {
                    this.validarTodos();
                } else {
                    this.validar(this.pedido)
                }
            } else {
                if (this.todos) {
                    this.rechazarTodos();
                } else {
                    this.rechazar(this.pedido)
                }
            }
        },
        validarTodos: function() {
            let self = this
            Swal.fire({
                title: '¿Aceptar todos los pedidos?',
                text: 'Se van a aceptar todos los pedidos pendientes',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return axios
                        .post(self.action, {})
                        .then(response => {
                            return response.data
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Error aceptando todo: ${error}`
                            )
                        })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                Swal.fire({
                    icon: 'success',
                    title: result.title,
                    text: result.msg
                })
            }).finally(() => {
                location.reload();
            })

        },
        rechazarTodos: function() {
            let self = this
            Swal.fire({
                title: '¿Rechazar todos los pedidos?',
                text: 'Ingrese el motivo',
                input: 'text',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Debe ingresar un motivo'
                    }
                },
                showLoaderOnConfirm: true,
                preConfirm: (value) => {
                    return axios.post(self.action, {requerimiento: self.pedido, motivo: value})
                        .then(response => {
                            return response.data
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Error rechazando todo: ${error}`
                            )
                        })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                Swal.fire({
                    title: result.title,
                    text: result.msg
                })
            }).finally(() => {
                location.reload();
            })

        },
        validar: function(){
            let self = this
                console.log(this.action)
            Swal.fire({
                title: '¿Aceptar este pedido?',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return axios.post(self.action, {requerimiento: self.pedido})
                        .then(response => {
                            return response.data
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Error aceptando: ${error}`
                            )
                        })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                Swal.fire({
                    title: result.title,
                    text: result.msg
                })
            }).finally(() => {
                location.reload();
            })

        },
        rechazar: function(){
            let self = this
            Swal.fire({
                title: '¿Rechazar este Pedido?',
                text: 'Ingrese el motivo',
                input: 'text',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Debe ingresar un motivo'
                    }
                },
                showLoaderOnConfirm: true,
                preConfirm: (value) => {
                    return axios.post(self.action, {requerimiento: self.pedido, observaciones: value})
                        .then(response => {
                            return response.data
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Error rechazando: ${error}`
                            )
                        })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                Swal.fire({
                    title: result.title,
                    text: result.msg
                })
            }).finally(() => {
                location.reload();
            })
        }
    }
}
</script>
<style></style>
