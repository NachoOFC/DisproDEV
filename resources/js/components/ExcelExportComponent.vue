<template>
  <v-btn @click="makeExcelFile">Exportar a Excel</v-btn>
</template>
<script>
import XLSX from "xlsx";
export default {
  props: {
    filename: {
      type: String,
      required: true
    },
    dataRoute: {
      type: String,
      required: false
    },
    data: {
      type: Array,
      required: false
    }
  },
  created() {
    if (typeof this.dataRoute != "undefined") {
      axios.get(this.dataRoute).then(resp => {
        this.aoa = resp.data;
      });
    } else {
      this.aoa = data;
    }
  },
  data() {
    return {
      aoa: []
    };
  },
  methods: {
    makeExcelFile() {
      let wb = XLSX.utils.book_new();
      wb.props = {
        Title: this.filename,
        Author: "Mline",
        CreatedDate: new Date()
      };

      wb.SheetNames.push(this.filename);
      let ws = XLSX.utils.aoa_to_sheet(this.aoa);
      wb.Sheets[this.filename] = ws;

      XLSX.writeFile(wb, `${this.filename}.xlsx`);
    }
  }
};
</script>
