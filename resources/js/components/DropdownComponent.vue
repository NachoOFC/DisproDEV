<template>
  <div class="dropdown">
    <button click="myFunction" class="dropbtn">Dropdown</button>
    <div v-if="show" id="myDropdown" class="dropdown-content">
      <input type="text" v-model="search"  @input="filterFunction">
      <a v-for="(index, item) in links" :key="index" :href="item.href">{{item.label}}</a>
    </div>
  </div>
</template>
<script>
export default {
  props: ['links'],
  data() {
    return {
      show: false,
      search: null
    }
  },
  methods: {
    myFunction: function () {
      this.show = true
    },
    filterFunction: function () {
      filter = this.search.toUpperCase();
      div = document.getElementById("myDropdown");
      a = div.getElementsByTagName("a");
      for (var i = 0; i < a.length; i++) {
        txtValue = a[i].textContent || a[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          a[i].style.display = "";
        } else {
          a[i].style.display = "none";
        }
      }
    },
  }
}
</script>
<style></style>
