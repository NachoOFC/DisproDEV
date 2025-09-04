<template>
  <div class="container">
    <div class="row my-4 justify-content-around align-items-stretch">
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title font-black border-bottom">Datos Empresa:</h5>
            <div class="row">
              <div class="col-4 text-right"><b>Razon Social:</b></div>
              <div class="col">{{ empresa.razon_social }}</div>
            </div>
            <div class="row">
              <div class="col-4 text-right"><b>RUT:</b></div>
              <div class="col">{{ empresa.rut }}</div>
            </div>
            <div class="row">
              <div class="col-4 text-right"><b>Direccion:</b></div>
              <div class="col">{{ empresa.direccion }}</div>
            </div>
            <div class="row">
              <div class="col-4 text-right"><b>Giro:</b></div>
              <div class="col">{{ empresa.giro }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title font-black border-bottom">Datos Centro:</h5>
            <div class="row">
              <div class="col-4 text-right"><b>Nombre:</b></div>
              <div class="col">{{ centro.nombre }}</div>
            </div>
            <div class="row">
              <div class="col-4 text-right"><b>Direccion:</b></div>
              <div class="col">{{ centro.direccion }}</div>
            </div>
            <div class="row">
              <div class="col-4 text-right"><b>Comuna:</b></div>
              <div class="col">{{ centro.comuna }}</div>
            </div>
            <div class="row">
              <div class="col-4 text-right"><b>Ciudad:</b></div>
              <div class="col">{{ centro.ciudad }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title font-black border-bottom">Requerimiento:</h5>
            <div class="row">
              <div class="col-3 text-right"><b>Nombre:</b></div>
              <div class="col">{{ nombre }}</div>
            </div>
            <div class="row my-2">
              <button @click="solicitar" class="mx-auto btn btn-success">
                Solicitar
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-3">
      <div class="col-md-12">
        <fieldset class="border p-2">
          <legend class="text-sm font-black">Agregar Producto</legend>
          <div class="form-row justify-content-around align-items-center">
            <div class="form-group col-md-4">
              <label>Seleccione el Producto:</label>
              <v-select :options="optionData" v-model="selected"></v-select>
              <small class="text-muted"
                >Busque y seleccione el producto a agregar al pedido</small
              >
            </div>
            <div class="form-group col-md-2">
              <label>Precio Unitario:</label>
              <input
                type="text"
                class="form-control-plaintext"
                disabled
                :value="formatCurrency(precioUnt)"
              />
              <small class="text-muted">Precio Unitario en Pesos ($)</small>
            </div>
            <div class="form-group col-md-2">
              <label>Cantidad:</label>
              <input
                type="text"
                class="form-control form-control-sm"
                maxlength="13"
                v-model="cantidad"
              />
              <small class="text-muted"
                >Ingrese la cantidad del Producto. Ej: 2.5</small
              >
            </div>
            <div class="col-md-3">
              <button
                type="button"
                class="btn btn-primary"
                @click="agregarProducto"
              >
                Agregar
              </button>
            </div>
          </div>
        </fieldset>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 table-responsive">
        <table class="table table-sm table-bordered table-striped">
          <thead>
            <th class="text-center" scope="col">SKU</th>
            <th class="text-center" scope="col">Detalle</th>
            <th class="text-center" scope="col">Cantidad</th>
            <th class="text-center" scope="col">Precio Unitario ($)</th>
            <th class="text-center" scope="col">Subtotal ($)</th>
            <th class="text-center" scope="col">Accion</th>
          </thead>
          <tbody>
            <tr v-for="(producto, index) in productos" :key="producto.id">
              <td class="text-center">{{ producto.sku }}</td>
              <td>{{ producto.detalle }}</td>
              <td class="text-center">{{ producto.cantidad }}</td>
              <td class="text-right">{{ formatCurrency(producto.precio) }}</td>
              <td class="text-right">
                {{ formatCurrency(producto.subtotal) }}
              </td>
              <td>
                <div class="btn-group">
                  <button
                    @click="editarCantidad(index)"
                    class="btn btn-primary"
                  >
                    Editar Cantidad
                  </button>
                  <button
                    @click="eliminarProducto(index)"
                    class="btn btn-danger"
                  >
                    Eliminar
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <th scope="row">Total:</th>
              <td :key="productos.length">$ {{ formatCurrency(total) }}</td>
            </tr>
            <tr>
              <th scope="row">N° Productos:</th>
              <td>{{ nroProductos }}</td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: [
    "indexProductos",
    "empresa",
    "centro",
    "nombre",
    "libreria",
    "action"
  ],
  data() {
    return {
      selected: null,
      cantidad: 0,
      productos: []
    };
  },
  computed: {
    optionData: function() {
      return this.indexProductos.map(producto => {
        return { label: producto.detalle, value: producto };
      });
    },
    precioUnt: function() {
      if (this.selected === null) {
        return 0;
      } else {
        return this.selected.value.venta;
      }
    },
    total: function() {
      if (this.productos.length > 0) {
        let totales = this.productos.map(producto => producto.subtotal);
        return totales.reduce((acc, sub) => acc + sub);
      } else {
        return 0;
      }
    },
    nroProductos: function() {
      return this.productos.length;
    }
  },
  methods: {
    formatCurrency: function(value) {
      return Math.round(parseFloat(value))
        .toString()
        .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    },
    agregarProducto: function() {
      let self = this;
      let assert = true;
      if (this.selected === null) {
        assert = false;
        Swal.fire("¡Error!", "¡Debes seleccionar un Producto!", "error");
      }
      if (this.productos.length > 0) {
        assert =
          this.productos.findIndex(
            producto => producto.sku === self.selected.value.sku
          ) === -1;
        if (assert == false) {
          Swal.fire("¡Error!", "¡Ya agregaste ese producto!", "error");
        }
      }
      if (this.cantidad.includes(",")) {
        assert = false;
        Swal.fire(
          "¡Error!",
          "¡Utilice puntos en ves de comas en la cantidad. Ej: 2.5!",
          "error"
        );
      }
      if (isNaN(parseFloat(this.cantidad))) {
        assert = false;
        Swal.fire(
          "¡Error!",
          "¡La cantidad debe ser un numero. Ej: 2.5!",
          "error"
        );
      }
      if (parseFloat(this.cantidad) <= 0) {
        assert = false;
        Swal.fire(
          "¡Error!",
          "¡La cantidad no puede ser menor o igual a 0. Ej: 2.0!",
          "error"
        );
      }
      if (assert) {
        this.productos.push({
          id: this.selected.value.id,
          sku: this.selected.value.sku,
          detalle: this.selected.value.detalle,
          cantidad: this.cantidad,
          precio: this.selected.value.venta,
          subtotal: this.cantidad * this.selected.value.venta
        });
        this.cantidad = 0;
        this.selected = null;
      }
    },
    editarCantidad: async function(index) {
      let original = this.productos[index].cantidad;
      const { value: cantidad } = await Swal.fire({
        title: "Ingrese la cantidad",
        input: "text",
        inputValue: original,
        showCancelButton: true,
        inputValidator: value => {
          if (!value) {
            return "Debes ingresar un numero";
          }
          if (isNaN(value)) {
            return "Debes ingresar un numero";
          }
          if (value <= 0) {
            return "La cantidad no puede ser menor o igual a 0";
          }
        }
      });
      if (cantidad) {
        this.productos[index].cantidad = cantidad;
        this.productos[index].subtotal =
          cantidad * this.productos[index].precio;
      }
    },
    eliminarProducto: function(index) {
      let self = this;
      Swal.fire({
        title: "¿Estas seguro?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar"
      }).then(result => {
        if (result.value) {
          self.productos.splice(index, 1);
          Swal.fire("¡Producto Eliminado!");
        }
      });
    },
    solicitar: function() {
      let noProductos = this.nroProductos;
      let total = this.formatCurrency(this.total);
      let action = this.action;
      let data = this.productos;
      Swal.fire({
        title: "¿Desea enviar este pedido?",
        text: `Hay ${noProductos} productos, por un total de $ ${total}`,
        showCancelButton: true,
        confirmButtonText: "Enviar",
        cancelButtonText: "Cancelar",
        showLoaderOnConfirm: true,
        preConfirm: () => {
          return axios
            .put(this.action, { pedido: data })
            .then(response => {
              return response.data;
            })
            .catch(error => {
              Swal.showValidationMessage(`Ocurrio un error: ${error}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then(result => {
        Swal.fire(result.value.meta.title, result.value.meta.msg, "success");
      });
    }
  },
  created() {
    if (!(this.libreria === null)) {
      let data = this.libreria;
      this.productos = [...data];
    }
  }
};
</script>
