<template>
    <section>
        <v-autocomplete
            :items="items"
            :item-text="itemText"
            :item-value="itemValue"
            :label="label"
            :multiple="multiple"
            v-model="data"
        ></v-autocomplete>
        <input
            type="text"
            :name="name"
            :value="data"
        />
    </section>
</template>
<script>
 export default {
     props: {
         items: {
             type: Array,
             required: true
         },
         itemText: {
             type: String,
             required: true
         },
         itemValue: {
             type: String,
             required: true
         },
         name: {
             type: String,
             required: true
         },
         multiple: {
             type: Boolean,
             default: false
         },
         label: {
             type: String,
             default: ''
         },
     },
     data() {
         return {
             data: null,
         }
     }
 }
</script>
