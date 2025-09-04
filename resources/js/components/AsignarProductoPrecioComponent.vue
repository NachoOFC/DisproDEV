<template>
    <div class="container">
        <div class="row">
            <div class="col-md-2 mx-auto">
                <button @click="submit" class="btn btn-block btn-success">Guardar</button>
            </div>
        </div>
        <div class="row">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-sm" id="asignar-table">
                    <thead>
                        <tr>
                            <th rowspan="2" scope="col">Producto</th>
                            <th scope="row" :colspan="empresas.length" class="text-center">Empresas</th>
                            <th rowspan="2" scope="col">Todos</th>
                        </tr>
                        <tr>
                            <th scope="col" v-for="(empresa, index) in empresas">{{ empresa.razon_social }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(producto, i) in productos">
                            <th scope="row">{{ producto.detalle }}</th>
                            <td v-for="(empresa, j) in empresas.length" :key="empresa.id">
                            <currency-input-component
                                v-if="!(precios[i][j].precio === false)"
                                v-model="precios[i][j].precio"
                                class="border form-control-sm"
                                :key="updater"
                                >
                            </currency-input-component>
                            </td>
                            <td><input @change="actualizarPrecios($event, i)" class="border form-control-sm" type="text"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</template>
<script charset="utf-8">
import InputComponent from './CurrencyInputComponent.vue';
export default {
    components: {
        InputComponent: 'currency-input-component'
    },
    props: ['empresas', 'productos', 'preciosActual', 'action'],
    methods: {
        submit: function () {
            let self = this;
            let data = this.precios;
            Swal.fire({
                title: '¿Desea guardar esta asignacion?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return axios.post(self.action, { asignacion: data})
                        .then((response) => {
                            return response.data;
                        })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                Swal.fire({
                    title: result.meta.title,
                    text: result.meta.msg,
                    icon: 'success'
                });
            });
        },
        actualizarPrecios: async function(evt, index) {
            let value = evt.target.value;
            for (var i = 0, len = this.empresas.length; i < len; i++) {
                this.precios[index][i] = value;
            }
            return this.updater += 1;
        }
    },
    data() {
        return {
            precios: [],
            updater: 0
        }
    },
    created() {
        this.precios = this.preciosActual;
    }
}
</script>
