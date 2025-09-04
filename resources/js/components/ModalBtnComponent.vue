<template>
    <div>
        <button v-if="button" class="btn btn-primary" @click="showModal">
            <slot></slot>
        </button>
        <a v-if="!button" href="#" @click="showModal">
            <slot></slot>
        </a>
    </div>
</template>
<script>
import Swal from 'sweetalert2'

export default {
    props: {
        title: {
            type: String,
            default: ''
        },
        message: {
        },
        button: {
            type: Boolean,
            default: true
        }
    },
    computed: {
        htmlMessage: function () {
            var msg;
            for (var i = 0; i < this.message.length; i++) {
                if (this.message[i].type === "Object") {
                    var keys = this.message[i].keys;
                    msg += '<p class="container text-justify">'
                    keys.forEach(element => {
                        msg += '<b>' + (element.charAt(0).toUpperCase() + element.slice(1)).replace("_", " ") + '</b>: ' + this.message[i].data[element] + '<br />'
                    })
                    msg += '<br />'
                    msg += '</p>'

                }
                if (this.message[i].type === "Array") {
                    var keys = this.message[i].keys
                    var data = this.message[i].data
                    msg += '<div class="container table-responsive">'
                    msg += '<table id="datatable" class="table table-sm text-xs"><thead><tr>'

                    keys.forEach(key => {
                        msg += '<th [scope="col"]>';
                        if (key === 'pivot') {
                            msg += ((this.message[i].pivot).charAt(0).toUpperCase() + (this.message[i].pivot).slice(1)).replace("_", " ")
                        } else if (key === 'total') {
                            msg += 'Subtotal'
                        } else {
                            msg += (key.charAt(0).toUpperCase() + key.slice(1)).replace("_", " ")
                        }
                        msg += '</th>';

                    })
                    msg += '</tr></thead>'

                    msg += '<tbody>'

                    data.forEach(element => {
                        msg += '<tr>'

                        keys.forEach(key => {
                            msg += '<td class="text-left">';
                            if (key === 'pivot') {
                                msg += element.pivot[this.message[i].pivot]
                            } else if (key === 'total') {
                                msg += this.formatMoney(parseFloat(element.pivot.cantidad) *
                                    parseFloat(element.precio)
                                )                            } else {
                                    msg += element[key]
                                }

                        })
                        msg += '</tr>'

                    })
                    msg += '</tr></tbody></table>'
                    msg += '</div>'
                }
            }
            return msg.replace('undefined', '')
        }
    },
    methods: {
        showModal: function() {
            Swal.fire({
                title: this.title,
                html: this.htmlMessage,
            })
        },
        formatMoney: function(amount, decimalCount = 0, decimal = ".", thousands = ",") {
            try {
                decimalCount = Math.abs(decimalCount);
                decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

                const negativeSign = amount < 0 ? "-" : "";

                let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
                let j = (i.length > 3) ? i.length % 3 : 0;

                return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
            } catch (e) {
                console.log(e)
            }
        }
    }
}
</script>
<style></style>
