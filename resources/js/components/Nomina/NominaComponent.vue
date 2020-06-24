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
          firstDayOfWeek="6"
          type="week"
          format="Week WW"
          placeholder="Seleccionar Semana"
        ></el-date-picker>
        <el-button type="primary" icon="el-icon-search" @click="onSearch"></el-button>
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
          <el-col :span="20" class="row-headerb">{{selectedEmployee}}</el-col>
        </el-row>
        <el-row class="br bb bl row-header">
          <el-col :span="4" style="margin-top: 7px;">
            <h4>Observación:</h4>
          </el-col>
          <el-col :span="20" class="row-headerb">{{comment}}</el-col>
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
        <el-table border size="mini" :data="tableACData" style="width: 100%" v-loading="loading">
          <el-table-column
            label="Sábado"
            width="100"
            header-align="center"
            align="right"
            prop="saturday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.saturday) }}</template>
          </el-table-column>
          <el-table-column
            label="Lunes"
            width="100"
            header-align="center"
            align="right"
            prop="monday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.monday) }}</template>
          </el-table-column>
          <el-table-column
            label="Martes"
            width="100"
            header-align="center"
            align="right"
            prop="tuesday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.tuesday) }}</template>
          </el-table-column>
          <el-table-column
            label="Miércoles"
            width="100"
            header-align="center"
            align="right"
            prop="wednesday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.wednesday) }}</template>
          </el-table-column>
          <el-table-column
            label="Jueves"
            width="100"
            header-align="center"
            align="right"
            prop="thursday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.thursday) }}</template>
          </el-table-column>
          <el-table-column
            label="Viernes"
            width="100"
            header-align="center"
            align="right"
            prop="friday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.friday) }}</template>
          </el-table-column>

          <el-table-column
            label="Total Semana"
            header-align="center"
            align="center"
            prop="totalWeek"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.totalWeek) }}</template>
          </el-table-column>
          <el-table-column label="% A/C" width="100" header-align="center" align="right" prop="ac"></el-table-column>
          <el-table-column
            label="Total Neto Comisiones"
            header-align="center"
            align="right"
            prop="commission"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.commission) }}</template>
          </el-table-column>
          <el-table-column
            label="Sueldo"
            width="100"
            header-align="center"
            align="right"
            prop="sueldo"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.sueldo) }}</template>
          </el-table-column>
          <el-table-column
            label="Sub Total"
            width="100"
            header-align="center"
            align="right"
            prop="subtotal"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.subtotal) }}</template>
          </el-table-column>
          <el-table-column
            label="Descuentos"
            width="100"
            header-align="center"
            align="right"
            prop="discounts"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.discounts) }}</template>
          </el-table-column>
          <el-table-column label="Total" header-align="center" align="right" prop="total">
            <template slot-scope="scope">{{ formatPrice(scope.row.total) }}</template>
          </el-table-column>

          <el-table-column align="center" label="Modificar" width="147">
            <template slot-scope="scope">
              <el-button type="text" size="mini">Editar</el-button>
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
        <el-table border size="mini" :data="tableMecData" style="width: 100%" v-loading="loading">
          <el-table-column
            label="Sábado"
            width="100"
            header-align="center"
            align="right"
            prop="saturday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.saturday) }}</template>
          </el-table-column>
          <el-table-column
            label="Lunes"
            width="100"
            header-align="center"
            align="right"
            prop="monday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.monday) }}</template>
          </el-table-column>
          <el-table-column
            label="Martes"
            width="100"
            header-align="center"
            align="right"
            prop="tuesday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.tuesday) }}</template>
          </el-table-column>
          <el-table-column
            label="Miércoles"
            width="100"
            header-align="center"
            align="right"
            prop="wednesday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.wednesday) }}</template>
          </el-table-column>
          <el-table-column
            label="Jueves"
            width="100"
            header-align="center"
            align="right"
            prop="thursday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.thursday) }}</template>
          </el-table-column>
          <el-table-column
            label="Viernes"
            width="100"
            header-align="center"
            align="right"
            prop="friday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.friday) }}</template>
          </el-table-column>

          <el-table-column
            label="Total Semana"
            header-align="center"
            align="center"
            prop="totalWeek"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.totalWeek) }}</template>
          </el-table-column>
          <el-table-column label="% Mecánica" width="100" header-align="center" align="right" prop="ac"></el-table-column>
          <el-table-column
            label="Total Neto Comisiones"
            header-align="center"
            align="right"
            prop="commission"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.commission) }}</template>
          </el-table-column>
          <el-table-column
            label="Sueldo"
            width="100"
            header-align="center"
            align="right"
            prop="sueldo"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.sueldo) }}</template>
          </el-table-column>
          <el-table-column
            label="Sub Total"
            width="100"
            header-align="center"
            align="right"
            prop="subtotal"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.subtotal) }}</template>
          </el-table-column>
          <el-table-column
            label="Descuentos"
            width="100"
            header-align="center"
            align="right"
            prop="discounts"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.discounts) }}</template>
          </el-table-column>
          <el-table-column label="Total" header-align="center" align="right" prop="total">
            <template slot-scope="scope">{{ formatPrice(scope.row.total) }}</template>
          </el-table-column>

          <el-table-column align="center" label="Modificar" width="147">
            <template slot-scope="scope">
              <el-button type="text" size="mini">Editar</el-button>
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
        <el-table border size="mini" :data="tableElecData" style="width: 100%" v-loading="loading">
          <el-table-column
            label="Sábado"
            width="100"
            header-align="center"
            align="right"
            prop="saturday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.saturday) }}</template>
          </el-table-column>
          <el-table-column
            label="Lunes"
            width="100"
            header-align="center"
            align="right"
            prop="monday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.monday) }}</template>
          </el-table-column>
          <el-table-column
            label="Martes"
            width="100"
            header-align="center"
            align="right"
            prop="tuesday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.tuesday) }}</template>
          </el-table-column>
          <el-table-column
            label="Miércoles"
            width="100"
            header-align="center"
            align="right"
            prop="wednesday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.wednesday) }}</template>
          </el-table-column>
          <el-table-column
            label="Jueves"
            width="100"
            header-align="center"
            align="right"
            prop="thursday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.thursday) }}</template>
          </el-table-column>
          <el-table-column
            label="Viernes"
            width="100"
            header-align="center"
            align="right"
            prop="friday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.friday) }}</template>
          </el-table-column>

          <el-table-column
            label="Total Semana"
            header-align="center"
            align="center"
            prop="totalWeek"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.totalWeek) }}</template>
          </el-table-column>
          <el-table-column label="% Eléctrico" width="100" header-align="center" align="right" prop="ac"></el-table-column>
          <el-table-column
            label="Total Neto Comisiones"
            header-align="center"
            align="right"
            prop="commission"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.commission) }}</template>
          </el-table-column>
          <el-table-column
            label="Sueldo"
            width="100"
            header-align="center"
            align="right"
            prop="sueldo"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.sueldo) }}</template>
          </el-table-column>
          <el-table-column
            label="Sub Total"
            width="100"
            header-align="center"
            align="right"
            prop="subtotal"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.subtotal) }}</template>
          </el-table-column>
          <el-table-column
            label="Descuentos"
            width="100"
            header-align="center"
            align="right"
            prop="discounts"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.discounts) }}</template>
          </el-table-column>
          <el-table-column label="Total" header-align="center" align="right" prop="total">
            <template slot-scope="scope">{{ formatPrice(scope.row.total) }}</template>
          </el-table-column>

          <el-table-column align="center" label="Modificar" width="147">
            <template slot-scope="scope">
              <el-button type="text" size="mini">Editar</el-button>
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
        <h4>Comisiones:</h4>
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
    this.$root.$on("refreshTable", this.refreshTable);
  },
  methods: {
    handleSelect(key, keyPath) {
      this.activeIndex = key;
      this.selectedEmployee = this.employees[key];
      let name = this.employees[key];
      this.tableACData = this.getEmployeeData(name, 1, 0.07, 1500.0);
      this.tableMecData = this.getEmployeeData(name, 2, 0.025, 0);
      this.tableElecData = this.getEmployeeData(name, 3, 0.025, 0);
    },
    eliminarRegistro(id) {},
    fixNumber(n) {
      return n < 10 ? "0" + n : n;
    },
    formatDate(date) {
      let hours = date.getHours();
      let minutes = date.getMinutes();
      let ampm = hours >= 12 ? "pm" : "am";
      hours = hours % 12;
      hours = hours ? hours : 12;
      minutes = minutes < 10 ? "0" + minutes : minutes + "";
      return hours + ":" + minutes + " " + ampm;
    },
    fixDate(dt) {
      return this.formatDate(new Date(dt));
    },
    loadTable(url) {
      const $this = this;
      $this.loading = true;
      axios.get(url).then(function(response) {
        $this.weekData = response.data;
        $this.loading = false;

        // (lista de empleados)
        $this.employees = [];
        $this.weekData.data.forEach(sale => {
          var name = sale.user.name;
          if ($this.employees.filter(e => e == name).length == 0)
            $this.employees.push(name);
        });

        // select first
        if ($this.employees.length > 0) $this.handleSelect("0");
      });
    },
    // Carga recibos tipo: 1: A/C, 2: Mecanica, 3: Electrico
    getEmployeeData(user, serviceType, comision, sueldo) {
      let week = {
        saturday: 0,
        monday: 0,
        tuesday: 0,
        wednesday: 0,
        thursday: 0,
        friday: 0,
        totalWeek: 0,
        percentCommission: comision,
        commission: 0,
        sueldo: sueldo,
        subtotal: 0,
        discounts: 0,
        total: 0
      };

      // Carga totales de recibos tipo serviceType del empleado
      let userSales = [];
      this.weekData.data.forEach(sale => {
        if (sale.service_type == serviceType && sale.user.name == user) {
          userSales.push(sale);
          switch (this.dayOfWeek(sale.created_at)) {
            case "Sábado": // sabado es menor a la fecha del dia lunes
              week.saturday += parseFloat(sale.total);
              break;
            case "Domingo": //  no deberia haber nada en domingo...
            case "Lunes":
              week.monday += parseFloat(sale.total);
              break;
            case "Martes":
              week.tuesday += parseFloat(sale.total);
              break;
            case "Miércoles":
              week.wednesday += parseFloat(sale.total);
              break;
            case "Jueves":
              week.thursday += parseFloat(sale.total);
              break;
            case "Viernes":
              week.friday += parseFloat(sale.total);
              break;
          }
        }
      });

      week.totalWeek =
        week.saturday +
        week.monday +
        week.tuesday +
        week.wednesday +
        week.thursday +
        week.friday;

      // comision
      week.commission = week.totalWeek * week.percentCommission;
      week.subtotal = week.commission + week.sueldo;
      week.total = week.subtotal - week.discounts;

      return [week];
    },
    onSearch() {
      this.prevDay = this.selectedDay;
      var start = `${this.toFixedFormat(
        this.selectedDay,
        "yyyy-MM-dd"
      )} 00:00:00`;
      this.loadTable(`/api/sales/searchReceiptByWeek?start=${start}`);
    },
    refreshTable() {
      // TODO
      //let currentDay = this.toFixedFormat(this.selectedDay, "yyyy-MM-dd");
      //let start = currentDay + " 00:00:00";
      //let end = currentDay + " 23:59:59";
      //this.loadTable(`/api/cleaning/search?start=${start}&end=${end}`);
    }
  },
  data() {
    return {
      comment: "N/A",
      selectedEmployee: "N/A",
      activeIndex: "0",
      employees: [],
      showDialog: false,
      weekData: [],
      selectedDay: new Date(),
      prevDay: new Date(),
      search: "",
      loading: false,
      tableACData: [], // Servicio A/C
      tableMecData: [], // Servicio Mecanico
      tableElecData: [] // servicio Electrico
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

.box-card .el-menu-item {
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
/* Ipad Pro */
@media only screen and (max-width: 1366px) {
  .content.el-main {
    padding: 5px;
  }
}
</style>
