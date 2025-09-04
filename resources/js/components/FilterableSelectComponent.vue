<template>
    <div>
        <v-select :options="optionData" v-model="selected" @input="setSelected"></v-select>
        <input type="hidden" :value="nuevoProducto" name="nuevoProducto"/>
    </div>
</template>

<script>
export default {
    props: ['options'],
    computed: {
        optionData: function() {
            return this.options.map((producto) => {
                return {label: producto.detalle, value: producto.id};
            });
        }
    },
    methods: {
        setSelected: function() {
            this.nuevoProducto = this.selected.value;
        },
    },
    data() {
        return {
            selected: null,
            nuevoProducto: null
        }
    }
}
</script>
