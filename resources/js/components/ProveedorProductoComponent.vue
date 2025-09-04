<template>
    <div>    <div class="form-row">
        <v-autocomplete
            outlined
            dense
            v-model="selectedProveedor"
            :items="proveedores"
            item-text="razon_social"
            item-value="id"
            label="Proveedor:"
            placeholder="Seleccione proveedor"
        ></v-autocomplete>
        <input name="proveedores_id" type="hidden" :value="selectedProveedor"/>
    </div>
    <div class="form-row">
        <v-autocomplete
            v-if="selectedProveedor != null"
            outlined
            dense
            v-model="selectedProducto"
            :items="productosFiltered"
            item-text="nombre"
            item-value="id"
            label="Producto:"
            placeholder="Seleccione producto"
        ></v-autocomplete>
        <input name="bidon_id" type="hidden" :value="selectedProducto"/>
    </div>
    </div></template>
<script>
export default {
    props: {
        getRoute: {
            type: String,
            default: '/proveedores/producto'
        }
    },
    computed: {
        productosFiltered() {
            if (this.productos.length > 0) {
                return this.productos.filter(
                    (producto) => producto.proveedor_id == this.selectedProveedor
                );
            }
            return []
        },
    },
    created() {
        this.getData();
    },
    data() {
        return {
            proveedores: [],
            productos: [],
            selectedProveedor: null,
            selectedProducto: null,
        };
    },
    watch: {
        selectedProveedor(val) {
            this.$emit("proveedor-changed", val)
        },
        selectedProducto(val) {
            this.$emit("producto-changed", val)
        }
    },
    methods: {
        getData() {
            axios.get(this.getRoute).then(resp => {
                this.proveedores = resp.data.proveedores;
                this.productos = resp.data.productos;
            })
        }
    },
};
</script>
