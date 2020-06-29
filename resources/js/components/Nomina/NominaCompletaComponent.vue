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
      </el-col>
      <el-col :span="4" align="end">
        <el-button v-show="!editable" :disabled="!selectedDay" type="primary" @click="editable=true" icon="el-icon-edit">Editar</el-button>
        <el-button v-show="editable" :disabled="!selectedDay" type="primary" icon="el-icon-ok">Guardar</el-button>
      </el-col>
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
          <el-table-column label="Técnico" prop="employee"></el-table-column>
          <el-table-column align="right" label="Total A/C" width="180" prop="totalA">
            <template slot-scope="scope">{{ formatPrice(scope.row.totalA) }}</template>
          </el-table-column>
          <el-table-column align="right" label="Total Mecánica" width="180" prop="totalB">
            <template slot-scope="scope">{{ formatPrice(scope.row.totalB) }}</template>
          </el-table-column>
          <el-table-column align="right" label="Total Auto Eléctrico" width="180" prop="totalC">
            <template slot-scope="scope">{{ formatPrice(scope.row.totalC) }}</template>
          </el-table-column>
          <el-table-column align="right" label="Subtotal" width="180" prop="subtotal">
            <template slot-scope="scope">{{ formatPrice(scope.row.subtotal) }}</template>
          </el-table-column>
          <el-table-column align="right" label="Descuentos" width="180" prop="discounts">
            <template slot-scope="scope">{{ formatPrice(scope.row.discounts) }}</template>
          </el-table-column>
          <el-table-column align="right" label="Total" width="180" prop="total">
            <template slot-scope="scope">{{ formatPrice(scope.row.total) }}</template>
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
            <h4>${{formatPrice(total)}}</h4>
          </el-col>
        </el-row>
        <el-row class="br bl bb row-header">
          <el-col :span="4" style="margin-top: 7px;">
            <h4>Observaciones:</h4>
          </el-col>
          <el-col :span="20" class="row-headerb">
            <el-input v-show="editable" size="mini" v-model="comment" class="mycustom"></el-input>
            <label v-show="!editable" class="pr-10 text-mini">{{ comment }}</label>
          </el-col>
        </el-row>
      </el-col>
      <el-col :span="12" align="center">
        <el-row class="br bt bb row-header">
          <h3 style="color:#909399">Ingresos</h3>
        </el-row>
        <el-row>
          <bar-chart ref="myChart" :chartData="[]" style="height: 400px"></bar-chart>
        </el-row>
      </el-col>
    </el-row>
  </el-row>
</template>

<script>
export default {
  mounted: function() {
    // this.$root.$on("refreshTable", this.refreshTable);
  },
  methods: {
    loadTable(url) {
      const $this = this;
      $this.loading = true;
      axios.get(url).then(function(response) {
        $this.loading = false;

        // reset
        $this.employees = [];
        $this.tableData = [];
        $this.total = 0;

        $this.weekData = response.data;
        $this.weekData.data.forEach(sale => {
          var name = sale.user.name;
          if ($this.employees.filter(e => e == name).length == 0)
            $this.employees.push(name);
        });

        // calcula totales de cada empleado
        if ($this.employees.length > 0) {
          $this.employees.forEach(emp =>
            $this.tableData.push($this.getPayroll(emp, 0.07, 0.025, 0.07))
          );
        }

        // crear grafica
        var chart = $this.createChartData($this.tableData);
        $this.$refs.myChart.setData(chart);

        // nomina total
        $this.tableData.forEach(n => ($this.total += n.total));
      });
    },
    getPayroll(user, cAc, cMec, cElec) {
      let emp = {
        editable: false,
        employee: user,
        totalA: 0,
        totalB: 0,
        totalC: 0,
        comisionA: 0,
        comisionB: 0,
        comisionC: 0,
        subtotal: 0,
        discounts: 0,
        total: 0
      };

      // Carga totales de recibos tipo AC del empleado
      this.weekData.data.forEach(sale => {
        if (sale.user.name == user) {
          switch (sale.service_type) {
            case 3: // Servicio Electrico
              emp.totalC += parseFloat(sale.total);
              break;
            case 2: // Servicio Mecanico
              emp.totalB += parseFloat(sale.total);
              break;
            default:
              // Servicio AC
              emp.totalA += parseFloat(sale.total);
              break;
          }
        }
      });

      // Ganancias para el empleado
      emp.comisionA = emp.totalA * cAc;
      emp.comisionB = emp.totalB * cMec;
      emp.comisionC = emp.totalC * cElec;

      // subtotal (suma de cada comision servicio)
      emp.subtotal = emp.comisionA + emp.comisionB + emp.comisionC;

      // Total
      emp.total = emp.subtotal - emp.discounts;
      return emp;
    },
    onSearch() {
      // from saturday
      var newDate = this.initDayOfWeekDate(this.selectedDay);
      this.prevDay = newDate;
      var start = `${this.toFixedFormat(newDate, "yyyy-MM-dd")} 00:00:00`;
      this.loadTable(`/api/sales/searchReceiptByWeek?start=${start}`);
    },
    createChartData(data) {
      let chartData = [];
      let i = 0;
      data.forEach(e =>
        chartData.push({
          name: e.employee,
          value: e.total,
          color: this.getColor(i++)
        })
      );
      return chartData;
    }
  },
  data() {
    return {
      total: 0,
      editable: false,
      comment: "comentario",
      selectedDay: new Date(),
      loading: false,
      weekData: [],
      employees: [],
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

.row-headerb {
  background-color: white;
  padding: 4px;
}
</style>
