<template>
    <div class="btn-group">
        <button role="button" class="btn btn-primary" @click="generarGuia">Generar Guia de Despacho</button>
        <button role="button" class="btn btn-success" @click="despachar">Despachar</button>
        <button role="button" class="btn btn-danger" @click="eliminarDespacho">Eliminar</button>
    </div>
</template>
<script>
export default {
    props: ['actionGuia', 'actionDespachar', 'actionEliminar'],
    methods: {
        generarGuia: function() {
            let action = this.actionGuia;
            Swal.fire({
                title: '¿Desea generar la guia de despacho para estos Requerimientos?',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return axios
                        .post(action)
                        .then(response => {
                            return response.data;
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                Swal.fire({
                    title: result.value.meta.title,
                    text: result.value.meta.msg
                })
                // location.reload();
            })
        },
        despachar: function() {
            let action = this.actionDespachar;
            Swal.fire({
                title: 'Se van a despachar estos Requerimientos, ¿Esta seguro?',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return axios
                        .post(action)
                        .then(response => {
                            return response.data;
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                Swal.fire({
                    title: result.value.meta.title,
                    text: result.value.meta.msg
                })
                location.reload();
            })
        },
        eliminarDespacho: function() {
            let action = this.actionEliminar;
            Swal.fire({
                title: '¿Desea eliminar este despacho programado?',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return axios
                        .post(action)
                        .then(response => {
                            return response.data;
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                Swal.fire({
                    title: result.value.meta.title,
                    text: result.value.meta.msg
                })
                location.reload();
            })
        },
    },
}
</script>
