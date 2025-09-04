<template>
  <main class="container-fluid">
    <v-stepper v-model="currentStep">
      <v-stepper-header>
        <v-stepper-step :complete="currentStep > 1" step="1">
          <strong>Paso 1: Descargar el formato1</strong>
        </v-stepper-step>
        <v-divider></v-divider>
        <v-stepper-step :complete="currentStep > 2" step="2">
          <strong>Paso 2: Cargar el formato</strong>
        </v-stepper-step>
        <v-divider></v-divider>
        <v-stepper-step :complete="currentStep > 3" step="3">
          <strong>Paso 3: Confirmar Pedido</strong>
        </v-stepper-step>
      </v-stepper-header>
      <v-stepper-items>
        <v-stepper-content step="1">
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-md-4">
                <v-btn
                  :color="validation.formato ? 'success' : 'warning'"
                  @click="makeExcelFile"
                  >Descargar Formato</v-btn
                >
              </div>
            </div>
          </div>

          <div class="row justify-content-around">
            <div class="col-md-2">
              <v-btn color="primary" @click="validateStep(1)">Continuar</v-btn>
            </div>
          </div>
        </v-stepper-content>
        <v-stepper-content step="2">
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-md-4">
                <label for>Ingrese el archivo excel (XLSX):</label>
                <input
                  class="form-control"
                  type="file"
                  @change="validateFile"
                />
              </div>
            </div>
            <div
              class="row"
              v-if="
                !validation.file.status && validation.file.errors.length > 0
              "
            >
              <div class="col-md alert alert-danger">
                <ul>
                  <li v-for="error in validation.file.errors">
                    <span v-if="(error.error = 'required')">
                      Para la linea {{ error.row }} falta el encabezado
                      {{ error.column }}
                    </span>
                    <span v-else>
                      Linea: {{ error.row }} - Columna:
                      {{ error.column }} Error: {{ error.error }} - Valor:
                      {{ error.value }}
                    </span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="row justify-content-around">
            <div class="col-md-2">
              <v-btn @click="currentStep = 1">Regresar</v-btn>
            </div>
            <div class="col-md-2">
              <v-btn color="primary" @click="validateStep(2)">Continuar</v-btn>
            </div>
          </div>
        </v-stepper-content>
        <v-stepper-content step="3">
          <div class="container">
            <div class="row">
              <div class="col-md">
                <ul>
                  <li>
                    <b>Empresa:</b>
                    {{ empresa.razon_social }}
                  </li>
                  <li>
                    <b>Centro:</b>
                    {{ centro.nombre }}
                  </li>
                  <li>
                    <b>Fecha del Requerimiento:</b>
                    {{ fechaRequerimiento }}
                  </li>
                  <li>
                    <b>Numero del Requerimiento:</b>
                    {{ numeroRequerimiento }}
                  </li>
                  <li>
                    <b>Nombre del Requerimiento:</b>
                    {{ nombreRequerimiento }}
                  </li>
                </ul>
              </div>
            </div>
            <v-divider></v-divider>
            <div class="row justify-content-center">
              <div class="col-md">
                <v-simple-table fixed-header height="400" dense>
                  <template v-slot:default>
                    <thead>
                      <tr>
                        <th>SKU</th>
                        <th>Detalle</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="producto in fileProductos" :key="producto.sku">
                        <td>{{ producto.sku }}</td>
                        <td>{{ getDetalleBySKU(producto.sku) }}</td>
                        <td>{{ getVentaBySKU(producto.sku) }}</td>
                        <td>{{ producto.cantidad }}</td>
                        <td>
                          {{ getVentaBySKU(producto.sku) * producto.cantidad }}
                        </td>
                      </tr>
                    </tbody>
                  </template>
                </v-simple-table>
              </div>
            </div>
            <v-divider></v-divider>
            <div class="row justify-content-end">
              <div class="col-md-5">
                <ul>
                  <li>
                    <b>Cantidad de Productos:</b>
                    {{ fileProductos.length }}
                  </li>
                  <li>
                    <b>Total del Requerimiento:</b>
                    {{ totalRequerimiento }}
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <div class="row justify-content-around">
            <div class="col-md-2">
              <v-btn
                :loading="loading"
                :disabled="loading"
                @click="currentStep = 2"
                >Regresar</v-btn
              >
            </div>
            <div class="col-md-2">
              <v-btn
                :loading="loading"
                :disabled="loading"
                color="success"
                @click="save"
                >Confirmar y Solicitar</v-btn
              >
            </div>
          </div>
        </v-stepper-content>
      </v-stepper-items>
    </v-stepper>
    <aside class="row justify-content-around mt-4">
      <div class="col-md-6 alert alert-info">
        <strong>{{ context }}</strong>
      </div>
    </aside>
  </main>
</template>
<script>
import readXlsxFile from "read-excel-file";
import XLSX from "xlsx";

export default {
  props: {
    formatoDownload: {
      type: String,
      required: true
    },
    productos: {
      type: Array,
      required: true
    },
    empresa: {
      type: Object,
      required: true
    },
    centro: {
      type: Object,
      required: true
    },
    nombreRequerimiento: {
      type: String,
      required: true
    },
    numeroRequerimiento: {
      type: String,
      required: true
    },
    storeRoute: {
      type: String,
      required: true
    }
  },
  computed: {
    totalRequerimiento() {
      let acc = 0;
      for (let i = 0; i < this.fileProductos.length; i++) {
        acc +=
          this.getVentaBySKU(this.fileProductos[i].sku) *
          this.fileProductos[i].cantidad;
      }
      return acc;
    },
    fechaRequerimiento() {
      const date = new Date();
      return date.getFullYear() + "-" + date.getMonth() + "-" + date.getDate();
    },
    context() {
      switch (this.currentStep) {
        case 1:
          return "Para avanzar al paso 2 se debe descargar el formato excel que contiene la lista de los productos disponibles, debe ingresar la cantidad correspondiente al producto, dejando vacio o en 0 los productos que no le interesen.";
          break;
        case 2:
          return "Para avanzar al paso 3 se debe cargar el formato descargado en el paso anterior una vez ingresadas las cantidades de los productos que este solicitando, el sistema validara la lista, y de encontrarse un problema le informara para que puede corregir y cargar de nuevo el archivo.";
          break;
        case 3:
          return "Aqui puede confirmar los productos del requerimiento, en caso de que quiera cambiar alguna cantidad o producto puede Regresar al paso anterior y cargar de nuevo el formato excel; de estar todo correcto puede hacer la solicitud del requerimiento.";
          break;
      }
    }
  },
  data() {
    return {
      currentStep: 1,
      validation: {
        formato: false,
        file: {
          status: false,
          errors: []
        }
      },
      fileProductos: [],
      fileExcel: null,
      isEditing: false,
      loading: false
    };
  },
  methods: {
    validateStep(currentStep) {
      switch (currentStep) {
        case 1:
          if (this.validation.formato) {
            this.currentStep = 2;
          }
          break;
        case 2:
          if (this.validation.file.status && this.fileProductos.length > 0) {
            this.currentStep = 3;
          }
          break;
      }
    },
    validateFile(input) {
      const file = input.target.files[0];
      const schema = {
        SKU: {
          prop: "sku",
          type: value => {
            if (!this.validateSKU(value)) {
              throw new Error("SKU INVALIDO");
            } else {
              return value;
            }
          },
          required: true
        },
        CANTIDAD: {
          prop: "cantidad",
          type: value => {
            const cantidad = parseFloat(value);
            if (cantidad === NaN || cantidad < 0) {
              throw new Error("CANTIDAD INVALIDA");
            } else {
              return cantidad;
            }
          },
          required: true
        }
      };
      readXlsxFile(file, { schema }).then(({ rows, errors }) => {
        if (errors.length > 0) {
          this.validation.file.status = false;
          this.validation.file.errors = errors;
        } else {
          this.validation.file.status = true;
          this.fileProductos = rows.filter(row => row.cantidad > 0);
        }
      });
    },
    validateSKU(sku) {
      return this.productos.some(producto => producto.sku == sku);
    },
    getProductoBySKU(sku) {
      return this.productos.find(producto => producto.sku == sku);
    },
    getDetalleBySKU(sku) {
      const producto = this.getProductoBySKU(sku);
      if (producto != undefined) {
        return producto.detalle;
      }
      return "";
    },
    getVentaBySKU(sku) {
      const producto = this.getProductoBySKU(sku);
      if (producto != undefined) {
        return producto.venta;
      }
      return 1;
    },
    save() {
      const productos = this.fileProductos;
      this.loading = true;
      axios
        .post(this.storeRoute, { productos })
        .catch(function(error) {
          if (error.response) {
            // The request was made and the server responded with a status code
            // that falls out of the range of 2xx
            console.log(error.response.data);
            console.log(error.response.status);
            console.log(error.response.headers);
            alert(error.response.data);
          } else if (error.request) {
            // The request was made but no response was received
            // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
            // http.ClientRequest in node.js
            console.log(error.request);
            alert(error.request);
          } else {
            // Something happened in setting up the request that triggered an Error
            console.log("Error", error.message);
          }
          this.loading = false;
        })
        .then(resp => {
          if (resp.status == 201) {
            alert("Guardado Exitosamente");
            this.loading = false;
            window.location.href = resp.data;
          }
        });
    },
    makeExcelFile() {
      let wb = XLSX.utils.book_new();
      wb.props = {
        Title: "Productos Disponibles",
        Subject: "Requerimiento para Compass",
        Author: "Mline",
        CreatedDate: new Date()
      };
      wb.SheetNames.push("Productos");
      const productos = this.productos.filter(
        producto => !parseInt(producto.reemplazo)
      );

      let rows = [
        [
          "SKU",
          "FAMILIA",
          "DETALLE",
          "MARCA",
          "FORMATO",
          "P. UNIT",
          "CANTIDAD",
          "SUBTOTAL",
          "TOTAL=",
          { f: "=SUMA(H:H)" }
        ]
      ];
      productos.forEach((product, index) => {
        rows.push([
          product.sku,
          product.familia,
          product.detalle,
          product.marca,
          product.formato,
          product.venta,
          0,
          { f: `=(F${index + 2}*G${index + 2})` }
        ]);
      });

      let ws = XLSX.utils.aoa_to_sheet(rows);
      wb.Sheets["Productos"] = ws;

      XLSX.writeFile(wb, "formato.xlsx");
      this.validation.formato = true;
    }
  }
};
</script>
