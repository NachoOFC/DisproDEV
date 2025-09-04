<template>
  <main>
    <table class="table table-sm">
      <thead>
        <tr class="w-full">
          <th>SKU</th>
          <th>NOMBRE</th>
          <th>DESPACHADO</th>
          <th>RECIBIDO</th>
          <th>OBSERVACION</th>
          <th>NOTA CREDITO</th>
          <th v-if="generaReclamos">RECLAMAR</th>
          <th>RECEPCION</th>
          <th>RECLAMO</th>
          <th>COMPASS</th>
          <th>ACEPTAR</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(producto, indexProductos) in productos"
          :key="indexProductos"
        >
          <td class="w-2">{{ producto.sku }}</td>
          <td>{{ producto.detalle }}</td>
          <td>{{ producto.pivot.real }}</td>
          <td>{{ producto.pivot.cantidad_recibido }}</td>
          <td class="w-48">
            <select
              :disabled="generaReclamos"
              class="form-control"
              v-model="producto.pivot.tipo_observacion_id"
            >
              <option
                v-for="observacion in observaciones"
                :value="observacion.id"
                :key="`${indexProductos}-${observacion.id}`"
                >{{ observacion.nombre }}</option
              >
            </select>
            <small>{{ getObservacionLabel(producto) }}</small>
          </td>
          <td>
            <article class="form-check">
              <input
                :disabled="generaReclamos"
                class="form-check-input"
                type="radio"
                v-model="producto.pivot.genera_nc"
                :name="`genera_nc-${producto.id}`"
                value="1"
              />
              <label class="form-check-label" for>SI</label>
            </article>
            <article class="form-check">
              <input
                :disabled="generaReclamos"
                class="form-check-input"
                type="radio"
                v-model="producto.pivot.genera_nc"
                :name="`genera_nc-${producto.id}`"
                value="0"
              />
              <label class="form-check-label" for>NO</label>
            </article>
          </td>
          <td v-if="generaReclamos">
            <button
              class="btn btn-warning"
              @click="onGenerarReclamoClick(producto)"
            >
              GENERAR RECLAMO
            </button>
          </td>
          <td>
            <textarea
              class="form-control"
              disabled
              v-model="producto.pivot.comentario_centro"
            >
            </textarea>
          </td>
          <td>
            <textarea
              class="form-control"
              disabled
              v-model="producto.pivot.comentario_reclamo"
            >
            </textarea>
          </td>
          <td>
            <textarea
              class="form-control"
              :disabled="generaReclamos"
              v-model="producto.pivot.observacion"
            >
            </textarea>
          </td>

          <td>
            <button
              :disabled="generaReclamos"
              :class="[
                'btn',
                producto.pivot.liquidado != null ? 'btn-warning' : 'btn-success'
              ]"
              @click="onSaveProduct(producto)"
            >
              <span v-if="producto.pivot.liquidado != null">ACTUALIZAR</span>
              <span v-else>GUARDAR</span>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="flex flex-row justify-around" v-if="!generaReclamos">
      <button @click="onActualizacionClick()" class="btn btn-outline-info">
        ENVIAR ACTUALIZACION
      </button>

      <button @click="onGuardarTodoClick()" class="btn btn-outline-success">
        GUARDAR TODO
      </button>
    </div>
  </main>
</template>
<script>
export default {
  props: {
    guiaDespacho: {
      type: Object,
      required: true
    },
    observaciones: {
      type: Array,
      required: true
    },
    productos: {
      type: Array,
      required: true
    },
    storeRoute: {
      type: String,
      required: false
    },
    massiveRoute: {
      type: String,
      required: false
    },
    generaReclamos: {
      type: Boolean,
      required: false,
      default: false
    },
    reclamoRoute: {
      type: String,
      required: false
    },
    actualizacionRoute: {
      type: String,
      required: false
    }
  },
  methods: {
    getObservacionLabel(producto) {
      const observacion = this.observaciones.find(
        observacion => observacion.id == producto.pivot.tipo_observacion_id
      );

      if (observacion !== undefined) {
        return observacion.estado;
      }
      return "";
    },
    getRoute(id) {
      if (this.generaReclamos) {
        if (this.reclamoRoute === undefined) return "";
        return `${this.reclamoRoute}/${id}`;
      }
      return `${this.storeRoute}`;
    },
    onSaveProduct(producto) {
      axios
        .post(this.getRoute(producto.id), { producto })
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
          if (resp.status == 200) {
            alert("Guardado exitosamente");
          }
        });
    },
    onGuardarTodoClick() {
      const productos = this.productos;
      axios
        .post(this.massiveRoute, this.productos)
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
          if (resp.status == 200) {
            alert("Guardado exitosamente");
          }
        });
    },
    onGenerarReclamoClick(producto) {
      const sku = producto.sku;
      const detalle = producto.detalle;
      let observacion = this.observaciones.find(
        observacion => observacion.id == producto.pivot.tipo_observacion_id
      );

      if (observacion !== undefined) {
        observacion = "";
      } else {
        observacion = observacion.nombre;
      }
      const notaCredito = producto.pivot.genera_nc ? "SI" : "NO";
      Swal.fire({
        title: "Se generara un reclamo para el siguiente producto:",
        html: `<b>${sku} ${detalle}</b>, ${observacion}, <br /> el cual genera nota de credito: ${notaCredito}. <br /> Puede incluir un mensaje adicional:`,
        input: "text",
        showCancelButton: true,
        cancelButtonText: "Cancelar"
      }).then(result => {
        if (result.isConfirmed) {
          const message = result.value;
          axios
            .post(this.getRoute(producto.id), { message })
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
              if (resp.status == 200) {
                alert("Se envio el reclamo exitosamente");
              }
            });
        }
      });
    },
    onActualizacionClick() {
      alert("Se enviará un correo al cliente.");
      axios
        .post(this.actualizacionRoute)
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
          if (resp.status == 200) {
            alert("Correo enviado");
          }
        });
    }
  }
};
</script>
