<template>
  <div>
    <v-expansion-panels>
      <v-expansion-panel
        v-for="(requerimiento, i) in requerimientos"
        :key="requerimiento.created_at"
      >
        <v-expansion-panel-header
          ><a :href="requerimiento.showRoute">{{
            requerimiento.nombre
          }}</a></v-expansion-panel-header
        >
        <v-expansion-panel-content>
          <strong>Guias de Despacho</strong>
          <v-simple-table>
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-left">Folio</th>
                  <th class="text-left">Productos</th>
                  <th class="text-left">PDF</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="guia in requerimiento.guias_despacho" :key="guia.created_at">
                  <td>{{ guia.folio }}</td>
                  <td>
                    <v-btn @click="dlgProductos(guia)">Ver Productos</v-btn>
                  </td>
                  <td>
                    <v-btn :loading="loader" :disabled="loader" @click="descargarGuia(guia)">Generar PDF</v-btn>
                  </td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>

    <v-dialog v-model="dlg">
      <v-card>
        <v-card-title>Lista de Productos</v-card-title>
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="products"
            :items-per-page="10"
            class="elevation-1"
          ></v-data-table>
        </v-card-text>
        <v-card-actions>
          <v-btn color="green darken-1" text @click="closeDlg">Cerrar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
export default {
  props: {
    requerimientos: {
      type: Array,
      required: true
    },
  },
  data() {
    return {
      dlg: false,
      headers: [
        { text: "SKU", value: "sku" },
        { text: "Detalle", value: "detalle" },
        { text: "Cantidad", value: "pivot.real" },
      ],
      products: [],
      loader: false,
    }
  },
  methods: {
    dlgProductos(guia) {
      this.dlg = true;
      this.products = guia.productos;
    },
    descargarGuia(guia) {
      this.loader = true
      axios.get(guia.showRoute).then((resp) => {
        this.loader = false
        window.open(resp.data.imagenLink, "_blank");
      });
    },
    closeDlg() {
      this.dlg = false;
      this.products = [];
    },
  }
};
</script>
