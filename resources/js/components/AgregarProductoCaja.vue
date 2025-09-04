<template>
<v-dialog
      v-model="dialog"
      width="500"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          color="red lighten-2"
          dark
          v-bind="attrs"
          v-on="on"
        >
          Agregar Productos
        </v-btn>
      </template>

      <v-card>
        <v-card-title class="headline grey lighten-2">
          Agregar Productos
        </v-card-title>


        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="1">
                <v-btn  color="green" @click="agregar">Agregar un Producto</v-btn>
              </v-col>
            </v-row>
            <v-row v-for="(item, index) in items" :key="index">
              <v-col cols="12" sm="8" md="8">
                <v-autocomplete label="Seleccione producto" outlined filled :items="productos" item-text="detalle" item-value="id" v-model="items[index].producto_id"></v-autocomplete>
              </v-col>
              <v-col cols="12" sm="4" md="4">
                <v-text-field label="Cantidad" outlined filled hint="Ej: 2.5" v-model="items[index].cantidad"></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>



        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            text
            @click="submit"
          >
            Aceptar
          </v-btn>
          <v-btn
            color="secondary"
            text
            @click="dialog = false"
          >
            Cancelar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
</template>
<script>
export default {
  props: {
    action: {
      type: String,
      required: true,
    },
    productos: {
      type: Array,
      required: true
    },
  },
  data() {
    return {
      dialog: false,
      items: []
    }
  },
  methods: {
    agregar() {
      this.items.push({
        producto_id: null,
        cantidad: null
      })
    },
    submit() {
      const productos = this.items;
      axios.post(this.action, {productos}).then(resp => {
        if (resp.status == 200) {
          window.location.reload()
        } else {
          window.alert("Ocurrio un error. Intente de nuevo mas tarde");
        }
      })
    }
  }
}
</script>
