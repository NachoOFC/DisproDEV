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
                                <div class="form-check">
                                    <input v-model="checks[i][j].asignacion" class="form-check-input" type="checkbox" :key="updater">
                                    <label class="form-check-label">
                                        {{ checks[i][j] ? 'Asignado' : 'Sin Asignar'}}
                                    </label>
                                </div>
                            </td>
                            <td><button @click="asignar(i, false)" class="btn btn-primary">Todos</button></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="row">Todos</th>
                            <td v-for="(index) in empresas.length"><button @click="asignar(index - 1, true)" class="btn btn-primary">Todos</button></td>
                            <td><button @click="asignar(null, true)" class="btn btn-primary">Todos</button></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['empresas', 'productos', 'action', 'asignaciones'],
    methods: {
        submit: function () {
            let self = this;
            let action = this.action;
            let data = this.checks;
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
                    console.log({action, data})
                    return axios
                        .post(self.action, {
                            asignacion: data
                        })
                        .then(response => {
                            return response.data
                        })
                        .catch(error => {
                            console.info(error.config);
                            Swal.showValidationMessage(
                                `Error realizando asignacion: ${error}`
                            )
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
        asignar: function (index, isEmpresa) {
            let title = '';
            let self = this;
            let i = index;
            let bool = isEmpresa;
            if (index === null) {
                title = '¿Desea asignar todos los productos a todas las empresas?';
            } else {
                if (isEmpresa) {
                    title = '¿Desea asignar todos los productos a la empresa ' + this.empresas[index].razon_social + '?'
                } else {
                    title = '¿Desea asignar todas las empresas al producto ' + this.productos[index].detalle + '?'
                }
            }
            Swal.fire({
                title: title,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    if (i === null) {
                        self.asignarTodos().then((result) => {
                            return result;
                        });
                    } else {
                        if (bool) {
                            self.asignarPorEmpresa(i).then(result => {
                                return result;
                            });
                        } else {
                            self.asignarPorProducto(i).then(result => {
                                return result;
                            });
                        }
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                Swal.fire({
                    title: 'Listo',
                    icon: 'success'
                })
            })
        },
        asignarTodos: async function() {
            for (var i = 0, len = this.productos.length; i < len; i++) {
                for (var j = 0, top = this.empresas.length; j < top; j++) {
                    this.checks[i][j] = true;
                }
            }
            this.updater += 1;
            return true;
        },
        asignarPorEmpresa: function(index) {
            for (var i = 0, len = this.productos.length; i < len; i++) {
                this.checks[i][index] = true;
            }
            this.updater += 1;
            return true;
        },
        asignarPorProducto: function(index) {
            for (var i = 0, len = this.empresas.length; i < len; i++) {
                this.checks[index][i] = true;
            }
            this.updater += 1;
            return true;
        }
    },
    data() {
        return {
            checks: [],
            updater: 0
        }
    },
    created() {
        this.checks = this.asignaciones;
        console.log(this.checks);
        this.updater++;
    }
}
</script>
