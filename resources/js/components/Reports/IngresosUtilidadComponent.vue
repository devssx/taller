<template>
  <el-row>
    <br />
    <el-row class="br bl bt bb row-header">
      <el-col :span="2">
        <h1 style="margin-top: 8px;">Año</h1>
      </el-col>
      <el-col :span="18">
        <el-date-picker v-model="selectedYear" type="year" placeholder="Seleccionar Año"></el-date-picker>
        <el-select width="150" v-model="workshopId" placeholder="Taller" :disabled="!multiWorkshop">
          <el-option v-for="w in workshops" :key="w.id" :label="w.name" :value="w.id">{{w.name}}</el-option>
        </el-select>
        <el-button type="primary" icon="el-icon-search" @click="onSearch"></el-button>
      </el-col>
      <el-col :span="4" align="end"></el-col>
    </el-row>

    <br />
    <!-- TOTALES -->
    <el-row>
      <!-- Columna 1 -->
      <el-col :span="8">
        <el-row class="br bb bl">
          <bar-chart ref="chartIngresos" :chartData="[]"></bar-chart>
        </el-row>
        <el-row class="br bb bl">
          <bar-chart ref="chartGastos" :chartData="[]"></bar-chart>
        </el-row>
        <el-row class="br bb bl">
          <bar-chart ref="chartUtilidad" :chartData="[]"></bar-chart>
        </el-row>
      </el-col>

      <el-col :span="15" :offset="1">
        <!-- Tabla Ingresos -->
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Ingresos sin IVA</h1>
          </el-col>
        </el-row>
        <el-row class="br bl">
          <el-col :span="8">
            <el-table size="mini" :data="incomeWeeklyData" style="width: 100%" v-loading="loading">
              <el-table-column label="Semanal" align="center">
                <el-table-column label="Semana" prop="fecha" align="center"></el-table-column>
                <el-table-column align="right" label="Monto" prop="monto">
                  <template slot-scope="scope">{{ formatPrice(scope.row.monto) }}</template>
                </el-table-column>
              </el-table-column>
            </el-table>
          </el-col>
          <el-col :span="8">
            <el-table size="mini" :data="incomeMonthlyData" style="width: 100%" v-loading="loading">
              <el-table-column label="Mensual" align="center">
                <el-table-column label="Mes" prop="fecha" align="center"></el-table-column>
                <el-table-column align="right" label="Monto" prop="monto">
                  <template slot-scope="scope">{{ formatPrice(scope.row.monto) }}</template>
                </el-table-column>
              </el-table-column>
            </el-table>
          </el-col>
          <el-col :span="8">
            <el-table
              size="mini"
              :data="incomeYearlyData"
              border
              style="width: 100%"
              v-loading="loading"
            >
              <el-table-column label="Anual" align="center">
                <el-table-column label="Año" prop="fecha" align="center"></el-table-column>
                <el-table-column align="right" label="Monto" prop="monto">
                  <template slot-scope="scope">{{ formatPrice(scope.row.monto) }}</template>
                </el-table-column>
              </el-table-column>
            </el-table>
          </el-col>
        </el-row>
        <br />
        <!-- Tabla Gastos -->
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Gastos sin IVA</h1>
          </el-col>
        </el-row>
        <el-row class="br bl">
          <el-col :span="8">
            <el-table size="mini" :data="tableData" style="width: 100%" v-loading="loading">
              <el-table-column label="Semanal" align="center">
                <el-table-column label="Semana" prop="fecha" align="center"></el-table-column>
                <el-table-column align="right" label="Monto" prop="monto">
                  <template slot-scope="scope">{{ formatPrice(scope.row.monto) }}</template>
                </el-table-column>
              </el-table-column>
            </el-table>
          </el-col>
          <el-col :span="8">
            <el-table size="mini" :data="tableData" style="width: 100%" v-loading="loading">
              <el-table-column label="Mensual" align="center">
                <el-table-column label="Mes" prop="fecha" align="center"></el-table-column>
                <el-table-column align="right" label="Monto" prop="monto">
                  <template slot-scope="scope">{{ formatPrice(scope.row.monto) }}</template>
                </el-table-column>
              </el-table-column>
            </el-table>
          </el-col>
          <el-col :span="8">
            <el-table size="mini" :data="tableData" style="width: 100%" v-loading="loading">
              <el-table-column label="Anual" align="center">
                <el-table-column label="Año" prop="fecha" align="center"></el-table-column>
                <el-table-column align="right" label="Monto" prop="monto">
                  <template slot-scope="scope">{{ formatPrice(scope.row.monto) }}</template>
                </el-table-column>
              </el-table-column>
            </el-table>
          </el-col>
        </el-row>
        <br />
        <!-- Tabla 3 -->
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Utilidad sin IVA</h1>
          </el-col>
        </el-row>
        <el-row class="br bl">
          <el-col :span="8">
            <el-table size="mini" :data="tableData" style="width: 100%" v-loading="loading">
              <el-table-column label="Semanal" align="center">
                <el-table-column label="Semana" prop="fecha" align="center"></el-table-column>
                <el-table-column align="right" label="Monto" prop="monto">
                  <template slot-scope="scope">{{ formatPrice(scope.row.monto) }}</template>
                </el-table-column>
              </el-table-column>
            </el-table>
          </el-col>
          <el-col :span="8">
            <el-table size="mini" :data="tableData" style="width: 100%" v-loading="loading">
              <el-table-column label="Mensual" align="center">
                <el-table-column label="Mes" prop="fecha" align="center"></el-table-column>
                <el-table-column align="right" label="Monto" prop="monto">
                  <template slot-scope="scope">{{ formatPrice(scope.row.monto) }}</template>
                </el-table-column>
              </el-table-column>
            </el-table>
          </el-col>
          <el-col :span="8">
            <el-table size="mini" :data="tableData" style="width: 100%" v-loading="loading">
              <el-table-column label="Anual" align="center">
                <el-table-column label="Año" prop="fecha" align="center"></el-table-column>
                <el-table-column align="right" label="Monto" prop="monto">
                  <template slot-scope="scope">{{ formatPrice(scope.row.monto) }}</template>
                </el-table-column>
              </el-table-column>
            </el-table>
          </el-col>
        </el-row>
      </el-col>
    </el-row>
  </el-row>
</template>

<script>
export default {
  props: ["workshops", "myUser", "multiWorkshop"],
  mounted: function () {
    if (this.myUser && this.myUser.length > 0) {
      this.workshopId = this.myUser[0].workshop_id;
    }

    this.loadTable(
      `http://127.0.0.1:8000/api/report/income?workshop=1&start=2020-01-01&end=2020-12-31`,
      2020
    );
  },
  methods: {
    loadTable(url, year) {
      var $this = this;
      $this.loading = true;
      $this.incomeWeeklyData = [];
      $this.incomeMonthlyData = [];
      $this.incomeYearlyData = [];

      axios.get(url).then(function (response) {
        $this.parseIncome(response.data, year);
        $this.loading = false;
      });
    },
    parseIncome(data, year) {
      var totalAnual = 0.0;

      // Iterar Informacion
      data.forEach((s) => {
        // Recibo Pagado
        var monto = parseFloat(s.total);

        // Semanal
        var week = this.getWeekOfDate(new Date(s.done_on));
        var ws = this.incomeWeeklyData.filter((w) => w.fecha == week);
        if (ws.length > 0) {
          ws[0].monto += monto;
        } else {
          this.incomeWeeklyData.push({ fecha: week, monto: monto });
        }

        // Mensual
        var month = this.monthName(new Date(s.done_on));
        var ms = this.incomeMonthlyData.filter((m) => m.fecha == month);
        if (ms.length > 0) {
          ms[0].monto += monto;
        } else {
          this.incomeMonthlyData.push({ fecha: month, monto: monto });
        }

        // Anual
        totalAnual += monto;
      });

      // tabla anual
      this.incomeYearlyData.push({ fecha: year, monto: totalAnual });

      // Crear Graficas
      var ingresos = this.createChartData(this.incomeWeeklyData);
      this.$refs.chartIngresos.setData(ingresos);
      this.$refs.chartGastos.setData(ingresos);
      this.$refs.chartUtilidad.setData(ingresos);
    },
    createChartData(data) {
      let chartData = [];
      let i = 0;
      data.forEach((e) =>
        chartData.push({
          name: e.fecha,
          value: e.monto,
          color: this.getColor(i++),
        })
      );
      return chartData;
    },
    onSearch() {},
    refreshTable() {},
  },
  data() {
    return {
      workshopId: "",
      showDialog: false,
      selectedYear: new Date(),
      search: "",
      loading: false,
      tableData: [],
      incomeWeeklyData: [],
      incomeMonthlyData: [],
      incomeYearlyData: [],
    };
  },
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
