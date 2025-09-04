<template>
  <v-card>
    <v-card-title>
      {{ title }}
      <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Buscar"
        single-line
        hide-details
      ></v-text-field>
    </v-card-title>
    <v-data-table
      :headers="headers"
      :items="items"
      :search="search"
      show-expand
      :single-expand="true"
    >
      <template v-slot:expanded-item="{ headers, item }">
        <td :colspan="headers.length">
          <v-list dense>
            <v-list-item v-if="getRoutes(item).length > 0">
              <div class="btn-group">
                <a
                  v-for="(action, indexA) in getRoutes(item)"
                  :key="indexA"
                  class="btn btn-outline-secondary"
                  :href="item[action]"
                  >{{ formatString(action) }}</a
                >
              </div>
            </v-list-item>
            <v-list-item
              v-for="(value, indexE) in getDetails(item)"
              :key="indexE"
            >
              <v-list-item-content class="align-end">
                <b>{{ formatString(value) }}:</b>
                <span v-html="item[value]"></span>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </td>
      </template>
      <template v-slot:item.actions="{ item }">
        <a :href="item.editRoute">
          <v-icon small class="mr-2">
            mdi-pencil
          </v-icon>
        </a>
        <v-icon small @click="openDlg(item)">
          mdi-delete
        </v-icon>
      </template>
    </v-data-table>
    <v-dialog v-model="dialog">
      <v-card>
        <v-card-title class="headline"
          >¿Esta seguro de borrar esta entrada?</v-card-title
        >

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn color="green darken-1" text @click="deleteItem">
            Aceptar
          </v-btn>

          <v-btn color="green darken-1" text @click="dialog = false">
            Cancelar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>
<script>
export default {
  props: {
    headers: {
      type: Array,
      default: function() {
        return [];
      }
    },
    items: {
      type: Array,
      required: true
    },
    title: {
      type: String,
      default: ""
    },
    imgIndex: {
      type: String,
      default: null
    },
    actions: {
      type: Array,
      default: function() {
        return [];
      }
    }
  },
  data() {
    return {
      search: "",
      dialog: false,
      toDeleteItem: null
    };
  },
  methods: {
    getRoutes(item) {
      return Object.keys(item).filter(
        key =>
          key.includes("Route") && key != "editRoute" && key != "deleteRoute"
      );
    },
    getDetails(item) {
      return Object.keys(item).filter(key => !key.includes("Route"));
    },
    deleteItem() {
      axios
        .delete(this.toDeleteItem.deleteRoute)
        .then(resp => console.log(resp.data))
        .finally(() => {
          this.deleteItem = null;
          this.dialog = false;
          window.location.reload();
        });
    },
    formatString(str) {
      return str
        .replace(/_/g, " ")
        .replace("Route", "")
        .replace(/^./, str[0].toUpperCase());
    },
    openDlg(item) {
      this.toDeleteItem = item;
      this.dialog = true;
    }
  }
};
</script>
