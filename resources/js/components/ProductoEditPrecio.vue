<template>
    <div class="row my-1 border-bottom">
        <div class="col-md">
            <input type="hidden" name="empresas[]" :value="empresaId">
            <p class="text-right">
                <slot></slot>
            </p>
        </div>
        <div class="col-md text-center">
            {{ costo }}
        </div>
        <div class="col-md">
            <input type="text" class="form-control" @input="calcularNeto" v-model="ganancia">
        </div>
        <div class="col-md text-center">
            <input type="text" class="form-control" name="precios[]" v-model="neto">
        </div>
    </div>
</template>
<script>
export default {
    props: ['empresaId', 'precioCosto', 'ventaActual'],
    data() {
        return {
            costo: 0,
            ganancia: 0,
            neto: 0,
        }
    },
    methods: {
        calcularNeto: function() {
            this.neto = (parseFloat(this.costo) + parseFloat(this.costo * parseFloat(this.ganancia / 100))).toFixed(0);
        },
        calcularPorcentaje: function() {
            this.ganancia = (parseFloat(100 * this.venta) - parseFloat(119 * this.costo)) / parseFloat(1.19 * this.costo).toFixed(0);
            this.calcularNeto();
        }
    },
    created(){
        this.costo = this.precioCosto;
        this.neto = this.ventaActual;
    }
}
</script>
