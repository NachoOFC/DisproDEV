<template>
    <div>
        <button class="btn btn-primary" @click="showModal"><i class="fas fa-undo"></i></button>
    </div>
</template>
<script>
export default {
    data() {
        return {
            option: null
        }
    },
    methods: {
        showModal: function () {
            axios.get(this.action).then( response => (this.option = response));
            console.log(this.option);
            const { value: producto } = Swal.fire({
                title: 'Escoge el Producto',
                html: 
                '<vSelect id="swal-input1" :options="' + this.option + '">' +
                '<input id="swal-input2" class="swal2-input">',
                focusConfirm: false,
                preConfirm: () => {
                    return [
                        document.getElementById('swal-input1').value,
                        document.getElementById('swal-input2').value
                    ]
                }

            });

            console.log(producto);
        }
    }
}
</script>
