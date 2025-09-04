<template>
  <v-stepper v-model="currentStep" non-linear vertical>
    <template v-for="(guiaDespacho, index) in guiasDespacho">
      <v-stepper-step editable :step="index">
        {{ guiaDespacho.fecha }}
        - {{ guiaDespacho.nombre_centro }} - {{ guiaDespacho.folio }}
      </v-stepper-step>
      <v-divider></v-divider>
      <v-stepper-content :step="index">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md">
              <v-simple-table fixed-header height="700">
                <template v-slot:default>
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>NOMBRE</th>
                      <th>CANTIDAD</th>
                      <th>ESTADO</th>
                      <th>RECIBIDO</th>
                      <th>OBSERVACION</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="(producto,
                      indexProductos) in guiaDespacho.productos"
                      :key="indexProductos"
                      :class="getContextClass(index, indexProductos)"
                    >
                      <td>{{ producto.sku }}</td>
                      <td>{{ producto.detalle }}</td>
                      <td>{{ producto.pivot.real }}</td>
                      <td>
                        <select
                          class="form-control"
                          v-model="producto.pivot.tipo_observacion_id"
                        >
                          <option
                            v-for="observacion in observaciones"
                            :value="observacion.id"
                            :key="
                              `${index}-${indexProductos}-${observacion.id}`
                            "
                            >{{ observacion.nombre }}</option
                          >
                        </select>
                        <small>{{ getObservacionLabel(producto) }}</small>
                      </td>
                      <td>
                        <input
                          class="form-control w-16"
                          :disabled="observacionRequiresInput(producto)"
                          v-model="producto.pivot.cantidad_recibido"
                          name
                          type="number"
                          value
                        />
                      </td>
                      <td>
                        <textarea
                          v-model="producto.pivot.comentario_centro"
                          class="form-control"
                        ></textarea>
                      </td>
                    </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </div>
          </div>
          <div class="row justify-content-around">
            <div class="col-md-2">
              <v-btn color="primary" @click="currentStep = ++index"
                >Continuar</v-btn
              >
            </div>
          </div>
        </div>
      </v-stepper-content>
    </template>
    <v-stepper-step editable :step="lastStep">
      <strong>Recibir Pedidos</strong>
    </v-stepper-step>
    <v-divider></v-divider>
    <v-stepper-content :step="lastStep">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md">
            <strong>Los siguientes productos seran rechazados:</strong>
          </div>
        </div>
        <div class="row">
          <div class="col-md">
            <v-simple-table fixed-header length="700">
              <template v-slot:default>
                <thead>
                  <tr>
                    <th>Folio Guia</th>
                    <th>Detalle</th>
                    <th>Motivo</th>
                    <th>Cantidad</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(summary, index) in summary" :key="index">
                    <td>{{ summary.guia.folio }}</td>
                    <td>{{ summary.product.detalle }}</td>
                    <td>
                      {{
                        getObservacionById(
                          summary.product.pivot.tipo_observacion_id
                        )
                      }}
                    </td>
                    <td>{{ summary.product.pivot.cantidad_recibido }}</td>
                  </tr>
                </tbody>
              </template>
            </v-simple-table>
          </div>
        </div>
        <div class="row justify-content-around">
          <div class="col-md-2">
            <v-btn @click="save" color="success">Aceptar y Finalizar</v-btn>
          </div>
        </div>
      </div>
    </v-stepper-content>
  </v-stepper>
</template>
<script>
export default {
  props: {
    guiasDespacho: {
      type: Array,
      required: true
    },
    storeRoute: {
      type: String,
      required: true
    },
    observaciones: {
      type: Array,
      required: true
    }
  },
  mounted() {
    for (let i = 0; i < this.guiasDespacho.length; i++) {
      var lista = this.guiasDespacho[i].productos;
      this.productosEditados.push(lista);
    }
  },
  computed: {
    lastStep() {
      return this.guiasDespacho.length;
    }
  },
  watch: {
    currentStep() {
      this.summary = this.summaryObservacion();
    }
  },
  data() {
    return {
      currentStep: 0,
      productosEditados: [],
      tiposObservaciones: [],
      summary: undefined
    };
  },
  methods: {
    getContextClass(indexGuias, indexProductos) {
      const rechazado = this.productosEditados[indexGuias][indexProductos]
        .rechazado;

      if (rechazado) {
        return "table-warning";
      } else {
        return "";
      }
    },
    summaryObservacion() {
      let summary = [];

      for (const guia of this.guiasDespacho) {
        for (const product of guia.productos) {
          if (product.pivot.tipo_observacion_id > 1) {
            summary.push({ guia, product });
          }
        }
      }

      return summary;
    },
    getObservacionLabel(producto) {
      const observacion = this.observaciones.find(
        observacion => observacion.id == producto.pivot.tipo_observacion_id
      );

      if (observacion !== undefined) {
        return observacion.estado;
      }
      return "";
    },
    observacionRequiresInput(producto) {
      const ID_REQUIRES_INPUT = [3, 4, 5, 6, 7];

      return !ID_REQUIRES_INPUT.includes(producto.pivot.tipo_observacion_id);
    },
    validateStep(indexGuia) {
      if (this.productosEditados.length > 0 && indexGuia > -1) {
        return this.productosEditados[indexGuia].some(
          producto => producto.rechazado && producto.motivo == ""
        );
      }
      return true;
    },
    getFolioByProductId(productId) {
      const guia = this.guiasDespacho.find(guia =>
        guia.productos.some(producto => producto.id == productId)
      );

      if (guia != undefined) {
        return guia.folio;
      }
      return "";
    },
    getGuiaIdByProductId(productId) {
      const guia = this.guiasDespacho.find(guia =>
        guia.productos.some(producto => producto.id == productId)
      );

      if (guia != undefined) {
        return guia.id;
      }
      return false;
    },
    getObservacionById(id) {
      const observacion = this.observaciones.find(
        observacion => observacion.id == id
      );

      if (observacion !== undefined) {
        return observacion.nombre;
      }
      return "S/I";
    },
    save() {
      let rechazados = this.summaryObservacion();
      axios
        .post(this.storeRoute, { rechazados })
        .catch(function(error) {
          if (error.response) {
            // The request was made and the server responded with a status code
            // that falls out of the range of 2xx
            console.log(error.response.data);
            console.log(error.response.status);
            console.log(error.response.headers);
          } else if (error.request) {
            // The request was made but no response was received
            // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
            // http.ClientRequest in node.js
            console.log(error.request);
          } else {
            // Something happened in setting up the request that triggered an Error
            console.log("Error", error.message);
          }
          console.log(error.config);
        })
        .then(resp => {
          if (resp.status == 201) {
            alert("Guardado exitosamente");
            window.location.href = resp.data;
          }
        });
    }
  }
};
</script>
