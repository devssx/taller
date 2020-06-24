<template>
  <el-row>
    <br />
    <el-row class="br bl bt bb row-header">
      <el-col :span="2">
        <h1 style="margin-top: 8px;">FECHA DIA</h1>
      </el-col>
      <el-col :span="18">
        <el-date-picker
          v-model="selectedDay"
          type="date"
          format="dd-MM-yyyy"
          placeholder="Seleccionar Día"
        ></el-date-picker>
        <el-button type="primary" icon="el-icon-search" @click="onSearch"></el-button>
        <dc-edit :selectedItem="newUser" :hideButton="true" ref="newItem"></dc-edit>
      </el-col>
      <el-col :span="4"></el-col>
    </el-row>

    <!-- TABLA -->
    <el-row class="br bl">
      <el-col :span="24">
        <el-table size="mini" :data="tableData" style="width: 100%" v-loading="loading">
          <el-table-column prop="name" label="Empleado"></el-table-column>
          <el-table-column align="center" label="Hora Entrada" width="120" prop="start">
            <template slot-scope="scope">{{ fixDate(scope.row.start) }}</template>
          </el-table-column>
          <el-table-column align="center" prop="cleaning" label="Limpieza" width="100"></el-table-column>
          <el-table-column label="Hora Desayuno" align="center">
            <el-table-column align="center" label="Inicio" width="100" prop="breakfast_start">
              <template slot-scope="scope">{{ fixDate(scope.row.breakfast_start) }}</template>
            </el-table-column>
            <el-table-column align="center" label="Fin" width="100" prop="breakfast_end">
              <template slot-scope="scope">{{ fixDate(scope.row.breakfast_end) }}</template>
            </el-table-column>
          </el-table-column>
          <el-table-column label="Hora Comida" align="center">
            <el-table-column align="center" label="Inicio" width="100" prop="lunch_start">
              <template slot-scope="scope">{{ fixDate(scope.row.lunch_start) }}</template>
            </el-table-column>
            <el-table-column align="center" label="Fin" width="100" prop="lunch_end">
              <template slot-scope="scope">{{ fixDate(scope.row.lunch_end) }}</template>
            </el-table-column>
          </el-table-column>
          <el-table-column align="center" prop="done" label="Cumplió" width="100"></el-table-column>
          <el-table-column prop="comment" label="Comentario"></el-table-column>
          <el-table-column align="center" label="Modificar" width="120">
            <template slot-scope="scope">
              <dc-edit :selectedItem="tableData[scope.$index]"></dc-edit>
            </template>
          </el-table-column>
        </el-table>
      </el-col>
    </el-row>
  </el-row>
</template>

<script>
export default {
  mounted: function() {
    this.$root.$on("refreshTable", this.refreshTable);
    this.onSearch();
  },
  methods: {
    formatDate(date) {
      var hours = date.getHours();
      var minutes = date.getMinutes();
      var ampm = hours >= 12 ? "pm" : "am";
      hours = hours % 12;
      hours = hours ? hours : 12;
      minutes = minutes < 10 ? "0" + minutes : minutes + "";
      return hours + ":" + minutes + " " + ampm;
    },
    fixDate(dt) {
      var dText = this.formatDate(new Date(dt));
      return dText == `12:00 am` ? `` : dText;
    },
    loadTable(url) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function(response) {
        $this.tableData = response.data;
        $this.loading = false;
      });
    },
    onSearch() {
      if (this.dayName(this.selectedDay) == `Domingo`) {
        this.$alert("El día seleccionado no es válido.", "Día no válido", {
          confirmButtonText: "OK",
          type: "error"
        });
        return;
      }

      var day = `${this.toFixedFormat(this.selectedDay, "yyyyMMdd")}`;
      var df = this.toFixedFormat(this.selectedDay, "yyyy-MM-dd");
      this.loadTable(`/api/cleaning/search?day=${day}&format=${df}`);
    },
    refreshTable() {
      if (this.dayName(this.selectedDay) == `Domingo`) {
        return;
      }

      var day = `${this.toFixedFormat(this.selectedDay, "yyyyMMdd")}`;
      var df = this.toFixedFormat(this.selectedDay, "yyyy-MM-dd");
      this.loadTable(`/api/cleaning/search?day=${day}&format=${df}`);
    }
  },
  data() {
    return {
      showDialog: false,
      selectedDay: new Date(),
      search: "",
      loading: true,
      tableData: [],
      newUser: {
        user_id: 1,
        start: "",
        cleaning: "",
        breakfast_start: "",
        breakfast_end: "",
        lunch_start: "",
        lunch_end: "",
        done: "No",
        comment: "",
        name: ""
      }
    };
  }
};
</script>
<style>
.bl {
  border-left: 1px solid #ebeef5;
}
.br {
  border-right: 1px solid #ebeef5;
}
.bt {
  border-top: 1px solid #ebeef5;
}
.bb {
  border-bottom: 1px solid #ebeef5;
}
.row-header {
  background-color: #f5f7fa;
  padding: 4px;
}
/* Ipad Pro */
@media only screen and (max-width: 1366px) {
  .content.el-main {
    padding: 5px;
  }
}
</style>
