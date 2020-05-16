<template>
  <el-row>
    <br />
    <el-row class="br bl bt bb row-header">
      <el-col :span="2">
        <h1 style="margin-top: 8px;">FECHA DIA</h1>
      </el-col>
      <el-col :span="22">
        <el-date-picker
          v-model="selectedDay"
          type="date"
          format="dd-MM-yyyy"
          placeholder="Seleccionar Día"
        ></el-date-picker>
        <el-button type="primary" icon="el-icon-search"></el-button>
      </el-col>
    </el-row>

    <!-- TABLA -->
    <el-row class="br bl">
      <el-col :span="24">
        <el-table :data="tableData" style="width: 100%" v-loading="loading">
          <el-table-column prop="user_id" label="Empleado" width="295"></el-table-column>
          <el-table-column align="center" label="Hora Entrada" width="120" prop="start">
            <template slot-scope="scope">{{ fixDate(scope.row.start) }}</template>
          </el-table-column>
          <el-table-column align="center" prop="cleaning" label="Limpieza" width="150"></el-table-column>
          <el-table-column label="Hora Desayuno" align="center">
            <el-table-column align="center" label="Inicio" width="120" prop="breakfast_start">
              <template slot-scope="scope">{{ fixDate(scope.row.breakfast_start) }}</template>
            </el-table-column>
            <el-table-column align="center" label="Fin" width="120" prop="breakfast_end">
              <template slot-scope="scope">{{ fixDate(scope.row.breakfast_end) }}</template>
            </el-table-column>
          </el-table-column>
          <el-table-column label="Hora Comida" align="center">
            <el-table-column align="center" label="Inicio" width="120" prop="lunch_start">
              <template slot-scope="scope">{{ fixDate(scope.row.lunch_start) }}</template>
            </el-table-column>
            <el-table-column align="center" label="Fin" width="120" prop="lunch_end">
              <template slot-scope="scope">{{ fixDate(scope.row.lunch_end) }}</template>
            </el-table-column>
          </el-table-column>
          <el-table-column align="center" prop="done" label="Cumplió" width="120"></el-table-column>
          <el-table-column prop="comment" label="Comentario" width="290"></el-table-column>
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
    this.loadTable("/api/cleaning?all=1");
    // this.$root.$on("refreshTable", this.refreshTable);
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
      return this.formatDate(new Date(dt));
    },
    loadTable(url) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function(response) {
        $this.tableData = response.data;
        $this.loading = false;
      });
    },
    testSearch() {
      this.$root.$emit("showEditor", null);
    },
    deleteRow(index, rows) {
      this.$root.$emit("showEditor", rows[index]);
      //rows.splice(index, 1);
    },
    handleClick() {
      console.log("click");
    },
    refreshTable() {
      this.loadTable(
        "/api/clients?page=" + this.page + "&search=" + this.search
      );
    },
    handleCurrentChange(val) {
      this.page = val;
      this.refreshTable();
    }
  },
  watch: {
    search: function() {
      var $this = this;
      if ($this.timeout) {
        clearTimeout($this.timeout);
      }
      $this.timeout = setTimeout(function() {
        $this.loadTable(
          "/api/clients?page=" + $this.page + "&search=" + $this.search
        );
      }, 1000);
    }
  },
  data() {
    return {
      showDialog: false,
      selectedDay: "",
      items: [],
      search: "",
      loading: true,
      tableData: []
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
</style>
