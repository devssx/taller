<template>
  <el-row>
    <br />
    <el-row class="br bl bt bb row-header">
      <el-col :span="2">
        <h1 style="margin-top: 8px;">Semana</h1>
      </el-col>
      <el-col :span="18">
        <el-date-picker
          v-model="selectedDay"
          firstDayOfWeek="3"
          type="week"
          format="Week WW"
          placeholder="Seleccionar Semana"
        ></el-date-picker>
        <el-button type="primary" icon="el-icon-search" @click="onSearch"></el-button>
        <dc-edit :selectedItem="newUser" :hideButton="true" ref="newItem"></dc-edit>
      </el-col>
      <el-col :span="4" align="end"></el-col>
    </el-row>

    <br />
    <el-row class="br bt bl row-header">
      <el-col :span="24" align="center">
        <h1 style="color:#909399">Resumen Nómina Técnicos</h1>
      </el-col>
    </el-row>

    <!-- TABLA -->
    <el-row class="br bl">
      <el-col :span="24">
        <el-table border size="mini" :data="tableData" style="width: 100%" v-loading="loading">
          <el-table-column label="Técnico"></el-table-column>
          <el-table-column align="right" label="Total A/C" width="200"></el-table-column>
          <el-table-column align="right" label="Total Mecánica" width="200"></el-table-column>
          <el-table-column align="right" label="Total Auto Eléctrico" width="200"></el-table-column>
          <el-table-column align="right" label="Subtotal" width="200"></el-table-column>
          <el-table-column align="right" label="Descuentos" width="200"></el-table-column>
          <el-table-column align="right" label="Total" width="200"></el-table-column>
          <el-table-column align="center" label="Modificar" width="200">
            <template slot-scope="scope">
              <el-button
                @click="eliminarRegistro(tableData[scope.$index].id)"
                style="margin-left:16px"
                size="small"
                type="text"
              >Eliminar</el-button>
            </template>
          </el-table-column>
        </el-table>
      </el-col>
    </el-row>

    <br />
    <!-- TOTALES -->
    <el-row>
      <el-col :span="12">
        <el-row class="br bt bl bb row-header">
          <el-col :span="4" style="margin-top: 7px;">
            <h4>Total Nómina:</h4>
          </el-col>
          <el-col :span="20" class="row-headerb">
            <h4>$0.00</h4>
          </el-col>
        </el-row>
        <el-row class="br bl bb row-header">
          <el-col :span="4" style="margin-top: 7px;">
            <h4>Observaciones:</h4>
          </el-col>
          <el-col :span="20" class="row-headerb">TEXTO</el-col>
        </el-row>
      </el-col>
      <el-col :span="12">
        <bar-chart ref="dashboardChart"></bar-chart>
      </el-col>
    </el-row>
  </el-row>
</template>

<script>
export default {
  mounted: function() {
    // this.loadTable("/api/cleaning/search?today=" + today);
    this.$root.$on("refreshTable", this.refreshTable);
  },
  methods: {
    handleSelect(key, keyPath) {
      this.activeIndex = key;
    },
    eliminarRegistro(id) {
      const $this = this;
      $this
        .$confirm(
          "Esto eliminará permanentemente el registro. ¿Desea continuar?",
          "Advertencia",
          {
            confirmButtonText: "Si",
            cancelButtonText: "Cancelar",
            type: "warning"
          }
        )
        .then(() => {
          $this.loading = true;
          axios
            .delete("/api/cleaning/" + id)
            .then(function(response) {
              $this.$message({
                type: "success",
                message: "Registro eliminado"
              });

              $this.tableData = $this.tableData.filter(item => item.id != id);
              $this.loading = false;
            })
            .catch(error => {
              $this.loading = false;
              if (error.response.data.errors) {
                var errors = error.response.data.errors;
                $this.$alert(errors[Object.keys(errors)[0]][0], "Error", {
                  confirmButtonText: "OK",
                  type: "error"
                });
              }
            });
        })
        .catch(() => {});
    },
    fixNumber(n) {
      return n < 10 ? "0" + n : n;
    },
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
    onSearch() {
      //this.loadTable(`/api/cleaning/search?start=${start}&end=${end}`);
    },
    addUserInfo() {
      this.$refs.newItem.insertNewRow(this.selectedDay);
    },
    refreshTable() {
      // var currentDay = this.toFixedFormat(this.selectedDay, "yyyy-MM-dd");
      // var start = currentDay + " 00:00:00";
      // var end = currentDay + " 23:59:59";
      // this.loadTable(`/api/cleaning/search?start=${start}&end=${end}`);
    }
  },
  data() {
    return {
      activeIndex: 0,
      employees: ["Salomon", "Juanito", "Julio", "Alma"],
      showDialog: false,
      selectedDay: new Date(),
      search: "",
      loading: false,
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
.row-headerb {
  background-color: white;
  padding: 4px;
}
</style>
