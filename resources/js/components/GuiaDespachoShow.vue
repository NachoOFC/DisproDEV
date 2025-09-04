<template>
  <div>
    <v-expansion-panels>
      <v-expansion-panel
        v-for="(guia, i) in guias"
        :key="guia.created_at"
      >
        <v-expansion-panel-header
        >
          <strong>Guia de Despacho Folio: {{ guia.folio }}</strong>
        </v-expansion-panel-header
        >
        <v-expansion-panel-content>

          <v-btn @click="dlgProductos(guia)">Ver Productos</v-btn>
          <v-btn :loading="loader" :disabled="loader" @click="descargarGuia(guia)">Generar PDF</v-btn>

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
    guias: {
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
