/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");
import * as Tabs from "vue-slim-tabs";
import Swal from "sweetalert2";
import Chart from "chart.js";
import vSelect from "vue-select";
import Vuetify from "vuetify";

var Vue = (window.Vue = require("vue"));
Vue.use(Vuetify);

Vue.component("v-select", vSelect);
Vue.component(
  "agregar-producto-caja",
  require("./components/AgregarProductoCaja.vue").default
);
Vue.component(
  "form-component",
  require("./components/FormComponent.vue").default
);
Vue.component(
  "delete-btn-component",
  require("./components/DeleteBtnComponent.vue").default
);
Vue.component(
  "modal-btn-component",
  require("./components/ModalBtnComponent.vue").default
);
Vue.component(
  "dropdown-component",
  require("./components/DropdownComponent.vue").default
);
Vue.component(
  "create-presupuesto-component",
  require("./components/CreatePresupuestoComponent.vue").default
);
Vue.component(
  "agregar-libreria-component",
  require("./components/AgregarLibreriaComponent.vue").default
);
Vue.component(
  "validar-pedidos-component",
  require("./components/ValidarPedidosComponent.vue").default
);
Vue.component(
  "despachar-component",
  require("./components/DespacharComponent.vue").default
);
Vue.component(
  "filterable-select-component",
  require("./components/FilterableSelectComponent.vue").default
);
Vue.component(
  "filter-select-component",
  require("./components/FilterComponent.vue").default
);
Vue.component(
  "currency-input-component",
  require("./components/CurrencyInputComponent.vue").default
);
Vue.component(
  "producto-edit-precio",
  require("./components/ProductoEditPrecio.vue").default
);
Vue.component(
  "crear-requerimiento-component",
  require("./components/CrearRequerimientoComponent.vue").default
);
Vue.component(
  "guia-despacho-index",
  require("./components/GuiaDespachoIndex.vue").default
);
Vue.component(
  "guia-despacho-show",
  require("./components/GuiaDespachoShow.vue").default
);
Vue.component(
  "index-component",
  require("./components/IndexComponent.vue").default
);
Vue.component(
  "proveedor-producto-component",
  require("./components/ProveedorProductoComponent.vue").default
);
Vue.component("autoselect", require("./components/Autoselect.vue").default);

Vue.component(
  "requerimiento-generation-component",
  require("./components/RequerimientoGenerationComponent.vue").default
);

Vue.component(
  "recepcion-requerimiento-component",
  require("./components/RecepcionRequerimientoComponent.vue").default
);

Vue.component(
  "state-switcher-component",
  require("./components/StateSwitcherComponent.vue").default
);

Vue.component(
  "excel-export-component",
  require("./components/ExcelExportComponent.vue").default
);

Vue.component(
  "concepto-component",
  require("./components/ConceptoComponent.vue").default
);

Vue.use(Tabs);

const app = new Vue({
  el: "#app",
  vuetify: new Vuetify()
});

$(document).ready(function() {
  $("#datatable").DataTable({
    dom: "Blfrtip",
    buttons: ["copy", "csv", "excel", "pdf"],
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, "Todos"]
    ],
    language: {
      url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    }
  });

  $("#datatable-requerimiento").DataTable({
    paging: false,
    scrollY: 400,
    deferRender: true,
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, "Todos"]
    ],
    language: {
      url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    }
  });

  $("#asignar-table").DataTable({
    paging: false,
    ordering: false,
    scrollY: 400,
    deferRender: true,
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, "Todos"]
    ],
    language: {
      url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    }
  });

  $("#datatable-presupuesto").DataTable({
    dom: "Blfrtip",
    order: [[0, "asc"]],
    buttons: ["copy", "csv", "excel", "pdf"],
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, "Todos"]
    ],
    language: {
      url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    }
  });
});
