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
      <el-col :span="4" align="end">
        <el-button :disabled="!selectedDay" type="primary" icon="el-icon-edit">Modificar</el-button>
      </el-col>
    </el-row>

    <el-row class="br bl">
      <!-- Lista de empleados -->
      <el-col :span="6">
        <el-row>
          <el-col>
            <el-card class="box-card" shadow="never">
              <span v-if="employees.length == 0" style="padding:8px;">No hay información</span>

              <el-menu
                :default-active="activeIndex"
                class="el-menu-vertical-demo"
                @select="handleSelect"
                v-for="(employee, i) in employees"
                v-bind:key="i"
              >
                <el-menu-item :index="i+''">
                  <i class="el-icon-user"></i>
                  <span>{{employee}}</span>
                </el-menu-item>
              </el-menu>
            </el-card>
          </el-col>
        </el-row>
      </el-col>

      <!-- Header -->
      <el-col :span="18">
        <el-row class="br bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Resumen Nómina Técnico</h1>
          </el-col>
        </el-row>
        <el-row class="br bt bl row-header">
          <el-col :span="4" style="margin-top: 7px;">
            <h4>Nombre del Técnico:</h4>
          </el-col>
          <el-col :span="20" class="row-headerb">Jorge Sepulveda</el-col>
        </el-row>
        <el-row class="br bb bl row-header">
          <el-col :span="4" style="margin-top: 7px;">
            <h4>Observación:</h4>
          </el-col>
          <el-col :span="20" class="row-headerb">No le den bono</el-col>
        </el-row>
      </el-col>
    </el-row>

    <br />
    <el-row class="br bt bl row-header">
      <el-col :span="24" align="center">
        <h1 style="color:#909399">A/C Automotríz</h1>
      </el-col>
    </el-row>

    <!-- TABLA -->
    <el-row class="br bl">
      <el-col :span="24">
        <el-table size="mini" :data="tableData" style="width: 100%" v-loading="loading">
          <el-table-column label="Sabado" width="100"></el-table-column>
          <el-table-column label="Lunes" width="100"></el-table-column>
          <el-table-column label="Martes" width="100"></el-table-column>
          <el-table-column label="Miercoles" width="100"></el-table-column>
          <el-table-column label="Jueves" width="100"></el-table-column>
          <el-table-column label="Viernes" width="100"></el-table-column>

          <el-table-column label="Total Semana"></el-table-column>
          <el-table-column label="% A/C" width="100"></el-table-column>
          <el-table-column label="Total Neto Comisiones"></el-table-column>
          <el-table-column label="Sueldo" width="100"></el-table-column>
          <el-table-column label="Sub Total" width="100"></el-table-column>
          <el-table-column label="Descuentos" width="100"></el-table-column>
          <el-table-column label="Total"></el-table-column>

          <el-table-column align="center" label="Modificar" width="147">
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
    <el-row class="br bt bl row-header">
      <el-col :span="24" align="center">
        <h1 style="color:#909399">Mecánica Automotríz</h1>
      </el-col>
    </el-row>

    <!-- TABLA -->
    <el-row class="br bl">
      <el-col :span="24">
        <el-table size="mini" :data="tableData" style="width: 100%" v-loading="loading">
          <el-table-column label="Sabado" width="100"></el-table-column>
          <el-table-column label="Lunes" width="100"></el-table-column>
          <el-table-column label="Martes" width="100"></el-table-column>
          <el-table-column label="Miercoles" width="100"></el-table-column>
          <el-table-column label="Jueves" width="100"></el-table-column>
          <el-table-column label="Viernes" width="100"></el-table-column>

          <el-table-column label="Total Semana"></el-table-column>
          <el-table-column label="% A/C" width="100"></el-table-column>
          <el-table-column label="Total Neto Comisiones"></el-table-column>
          <el-table-column label="Sueldo" width="100"></el-table-column>
          <el-table-column label="Sub Total" width="100"></el-table-column>
          <el-table-column label="Descuentos" width="100"></el-table-column>
          <el-table-column label="Total"></el-table-column>

          <el-table-column align="center" label="Modificar" width="147">
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
    <el-row class="br bt bl row-header">
      <el-col :span="24" align="center">
        <h1 style="color:#909399">Auto Eléctrico Automotríz</h1>
      </el-col>
    </el-row>

    <!-- TABLA -->
    <el-row class="br bl">
      <el-col :span="24">
        <el-table size="mini" :data="tableData" style="width: 100%" v-loading="loading">
          <el-table-column label="Sabado" width="100"></el-table-column>
          <el-table-column label="Lunes" width="100"></el-table-column>
          <el-table-column label="Martes" width="100"></el-table-column>
          <el-table-column label="Miercoles" width="100"></el-table-column>
          <el-table-column label="Jueves" width="100"></el-table-column>
          <el-table-column label="Viernes" width="100"></el-table-column>

          <el-table-column label="Total Semana"></el-table-column>
          <el-table-column label="% A/C" width="100"></el-table-column>
          <el-table-column label="Total Neto Comisiones"></el-table-column>
          <el-table-column label="Sueldo" width="100"></el-table-column>
          <el-table-column label="Sub Total" width="100"></el-table-column>
          <el-table-column label="Descuentos" width="100"></el-table-column>
          <el-table-column label="Total"></el-table-column>

          <el-table-column align="center" label="Modificar" width="147">
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
    <el-row class="br bt bl bb row-header">
      <el-col :span="4" style="margin-top: 7px;">
        <h4>Sueldo:</h4>
      </el-col>
      <el-col :span="20" class="row-headerb" align="end">
        <h4>$0.00</h4>
      </el-col>
    </el-row>
    <el-row class="br bl bb row-header">
      <el-col :span="4" style="margin-top: 7px;">
        <h4>Comiciones:</h4>
      </el-col>
      <el-col :span="20" class="row-headerb" align="end">
        <h4>$0.00</h4>
      </el-col>
    </el-row>
    <el-row class="br bl bb row-header">
      <el-col :span="4" style="margin-top: 7px;">
        <h4>Descuentos:</h4>
      </el-col>
      <el-col :span="20" class="row-headerb" align="end">
        <h4>$0.00</h4>
      </el-col>
    </el-row>
    <el-row class="br bl bb row-header">
      <el-col :span="4" style="margin-top: 7px;">
        <h4>Total:</h4>
      </el-col>
      <el-col :span="20" class="row-headerb" align="end">
        <h4>$0.00</h4>
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
.box-card {
  min-height: 90px;
  max-height: 102px;
  overflow-y: auto;
}
.el-card__body {
  padding: 0 !important;
}
.el-menu-item {
  height: 24px !important;
  line-height: 24px !important;
}
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
