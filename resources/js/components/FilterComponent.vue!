<template>
    <div>
        <v-select :options="optionData" v-model="selected" @input="setSelected"></v-select>
        <input type="hidden" :value="current" :name="name"/>
    </div>
</template>

<script>
export default {
    props: ['options', 'label', 'value', 'name'],
    computed: {
        optionData: function() {
            return this.options.map((option) => {
                return {label: option[this.label], value: option[this.value]};
            });
        }
    },
    methods: {
        setSelected: function() {
            this.current = this.selected.value;
        },
    },
    data() {
        return {
            selected: null,
            current: null
        }
    }
}
</script>
