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
          type="date"
          format="dd-MM-yyyy"
          placeholder="Seleccionar Semana"
        ></el-date-picker>
        <el-button type="primary" icon="el-icon-search" @click="onSearch"></el-button>
        <dc-edit :selectedItem="newUser" :hideButton="true" ref="newItem"></dc-edit>
      </el-col>
      <el-col :span="4" align="end"></el-col>
    </el-row>

    <br />
    <!-- TOTALES -->
    <el-row>
      <!-- Columna 1 -->
      <el-col :span="4">
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Fondo</h1>
          </el-col>
        </el-row>
        <el-row class="br bb bl">
          <el-col :span="24" align="end">$0.00</el-col>
        </el-row>
        <el-row class="br bb bl">
          <el-col :span="24" align="end">$0.00</el-col>
        </el-row>
        <el-row class="br bb bl">
          <el-col :span="24" align="end">$0.00</el-col>
        </el-row>
        <br />
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Ingresos sin Iva</h1>
          </el-col>
        </el-row>
        <el-row class="br bb bl">
          <el-col :span="24" align="end">$0.00</el-col>
        </el-row>
        <br />
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Gastos sin Iva</h1>
          </el-col>
        </el-row>
        <el-row class="br bb bl">
          <el-col :span="24" align="end">$0.00</el-col>
        </el-row>
        <br />
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Utilidad sin Iva</h1>
          </el-col>
        </el-row>
        <el-row class="br bb bl">
          <el-col :span="24" align="end">$0.00</el-col>
        </el-row>
        <br />
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Ingresos Pend. Créditos</h1>
          </el-col>
        </el-row>
        <el-row class="br bb bl">
          <el-col :span="24" align="end">$0.00</el-col>
        </el-row>
      </el-col>

      <!-- Columna 2 -->
      <el-col :span="19" :offset="1">
        <!-- Tabla 1 -->
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Gastos no facturados</h1>
          </el-col>
        </el-row>
        <el-row class="br bl">
          <el-col :span="24">
            <el-table size="mini" :data="tableData" style="width: 100%" v-loading="loading">
              <el-table-column align="center" label="Concepto"></el-table-column>
              <el-table-column align="center" label="Total" width="200"></el-table-column>
            </el-table>
          </el-col>
        </el-row>
        <br />
        <!-- Tabla 2 -->
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Gastos en efectivo facturados</h1>
          </el-col>
        </el-row>
        <el-row class="br bl">
          <el-col :span="24">
            <el-table size="mini" :data="tableData" style="width: 100%" v-loading="loading">
              <el-table-column align="center" label="Concepto"></el-table-column>
              <el-table-column align="right" label="Importe"></el-table-column>
              <el-table-column align="right" label="Iva"></el-table-column>
              <el-table-column align="center" label="Total" width="200"></el-table-column>
            </el-table>
          </el-col>
        </el-row>
        <br />
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Gastos a crédito facturados</h1>
          </el-col>
        </el-row>
        <el-row class="br bl">
          <el-col :span="24">
            <el-table size="mini" :data="tableData" style="width: 100%" v-loading="loading">
              <el-table-column align="center" label="Concepto"></el-table-column>
              <el-table-column align="right" label="Importe"></el-table-column>
              <el-table-column align="right" label="Iva"></el-table-column>
              <el-table-column align="center" label="Total" width="200"></el-table-column>
            </el-table>
          </el-col>
        </el-row>
        <br />
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Empleados</h1>
          </el-col>
        </el-row>
        <el-row class="br bl">
          <el-col :span="24">
            <el-table size="mini" :data="tableData" style="width: 100%" v-loading="loading">
              <el-table-column align="center" label="Nómina total"></el-table-column>
              <el-table-column align="center" label="Total" width="200"></el-table-column>
            </el-table>
          </el-col>
        </el-row>
      </el-col>
    </el-row>
  </el-row>
</template>

<script>
export default {
  mounted: function() {
    var today = this.toFixedFormat(new Date(), "yyyy-MM-dd") + " 00:00:00";
    this.loadTable("/api/cleaning/search?today=" + today);
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
    toFixedFormat(dt, format) {
      // 2020-05-15 15:00:00
      if (!dt) dt = new Date();
      var yyyy = dt.getFullYear();
      var MM = this.fixNumber(dt.getMonth() + 1);
      var dd = this.fixNumber(dt.getDate());

      var hh = this.fixNumber(dt.getHours());
      var mm = this.fixNumber(dt.getMinutes());
      var ss = this.fixNumber(dt.getSeconds());

      switch (format) {
        case "yyyy-MM-dd":
          return `${yyyy}-${MM}-${dd}`;
      }

      return `${yyyy}-${MM}-${dd} ${hh}:${mm}:${ss}`;
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
      var start = `${this.toFixedFormat(
        this.selectedDay,
        "yyyy-MM-dd"
      )} 00:00:00`;

      var end = `${this.toFixedFormat(
        this.selectedDay,
        "yyyy-MM-dd"
      )} 23:59:59`;

      this.loadTable(`/api/cleaning/search?start=${start}&end=${end}`);
    },
    addUserInfo() {
      this.$refs.newItem.insertNewRow(this.selectedDay);
    },
    refreshTable() {
      var currentDay = this.toFixedFormat(this.selectedDay, "yyyy-MM-dd");
      var start = currentDay + " 00:00:00";
      var end = currentDay + " 23:59:59";
      this.loadTable(`/api/cleaning/search?start=${start}&end=${end}`);
    }
  },
  data() {
    return {
      activeIndex: 0,
      employees: ["Salomon", "Juanito", "Julio", "Alma"],
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
.row-headerb {
  background-color: white;
  padding: 4px;
}
</style>
