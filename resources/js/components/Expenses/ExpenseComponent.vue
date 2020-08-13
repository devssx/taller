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
          :picker-options="pickerOptions"
          type="week"
          format="Week WW"
          placeholder="Seleccionar Semana"
        ></el-date-picker>
        <el-select width="150" v-model="workshopId" placeholder="Taller" :disabled="!multiWorkshop">
          <el-option v-for="w in workshops" :key="w.id" :label="w.name" :value="w.id">{{w.name}}</el-option>
        </el-select>
        <el-button type="primary" icon="el-icon-search" @click="onSearch"></el-button>
      </el-col>
      <el-col :span="4" align="end">
        <el-button type="primary" icon="el-icon-plus" @click="addNew">Nuevo</el-button>
        <expense-edit
          :workshop="workshopId"
          :selectedItem="newExpense"
          :funds="funds"
          :expense="totalSinIva"
          :hideButton="true"
          ref="newItem"
        ></expense-edit>
      </el-col>
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
          <el-col :span="24" align="end" style="padding-right: 5px;">${{formatPrice(funds) }}</el-col>
        </el-row>
        <el-row class="br bb bl">
          <el-col :span="24" align="end" style="padding-right: 5px;">${{formatPrice(totalSinIva) }}</el-col>
        </el-row>
        <el-row class="br bb bl">
          <el-col
            :span="24"
            align="end"
            style="padding-right: 5px;"
          >${{formatPrice(funds-totalSinIva) }}</el-col>
        </el-row>
        <el-row>
          <el-col :span="24" align="end">
            <fund-edit :workshop="workshopId" :week="selectedDay" v-show="addFunds"></fund-edit>
          </el-col>
        </el-row>
        <br />
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Ingresos sin Iva</h1>
          </el-col>
        </el-row>
        <el-row class="br bb bl">
          <el-col
            :span="24"
            align="end"
            style="padding-right: 5px;"
          >${{ formatPrice(ingresosSinIva) }}</el-col>
        </el-row>
        <br />
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Gastos sin Iva</h1>
          </el-col>
        </el-row>
        <el-row class="br bb bl">
          <el-col :span="24" align="end" style="padding-right: 5px;">${{ formatPrice(totalSinIva) }}</el-col>
        </el-row>
        <br />
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Utilidad sin Iva</h1>
          </el-col>
        </el-row>
        <el-row class="br bb bl">
          <el-col
            :span="24"
            align="end"
            style="padding-right: 5px;"
          >${{ formatPrice(ingresosSinIva - totalSinIva) }}</el-col>
        </el-row>
        <br />
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Ingresos Pend. Créditos</h1>
          </el-col>
        </el-row>
        <el-row class="br bb bl">
          <el-col
            :span="24"
            align="end"
            style="padding-right: 5px;"
          >${{ formatPrice(totalCreditos) }}</el-col>
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
            <el-table size="mini" border :data="tableData1" style="width: 100%" v-loading="loading">
              <el-table-column label="Concepto" prop="concept"></el-table-column>
              <el-table-column align="right" label="Total" width="200" prop="total"></el-table-column>
              <el-table-column label="Opciones" header-align="center" align="center" width="120">
                <template slot-scope="scope">
                  <expense-edit :workshop="workshopId" :selectedItem="tableData1[scope.$index]"></expense-edit>
                </template>
              </el-table-column>
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
            <el-table size="mini" border :data="tableData2" style="width: 100%" v-loading="loading">
              <el-table-column label="Concepto" prop="concept"></el-table-column>
              <el-table-column align="right" label="Importe" prop="amount"></el-table-column>
              <el-table-column align="right" label="Iva" prop="iva"></el-table-column>
              <el-table-column align="right" label="Total" width="200" prop="total"></el-table-column>
              <el-table-column label="Opciones" header-align="center" align="center" width="120">
                <template slot-scope="scope">
                  <expense-edit :workshop="workshopId" :selectedItem="tableData2[scope.$index]"></expense-edit>
                </template>
              </el-table-column>
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
            <el-table size="mini" border :data="tableData3" style="width: 100%" v-loading="loading">
              <el-table-column label="Concepto" prop="concept"></el-table-column>
              <el-table-column align="right" label="Importe" prop="amount"></el-table-column>
              <el-table-column align="right" label="Iva" prop="iva"></el-table-column>
              <el-table-column align="right" label="Total" width="200" prop="total"></el-table-column>
              <el-table-column label="Opciones" header-align="center" align="center" width="120">
                <template slot-scope="scope">
                  <expense-edit :workshop="workshopId" :selectedItem="tableData3[scope.$index]"></expense-edit>
                </template>
              </el-table-column>
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
            <el-table
              size="mini"
              border
              :data="dataPayroll"
              style="width: 100%"
              v-loading="loading"
            >
              <el-table-column label="Nómina total" prop="concept"></el-table-column>
              <el-table-column label="Semana" prop="week" align="center"></el-table-column>
              <el-table-column label="Inicio" prop="start" align="center"></el-table-column>
              <el-table-column label="Fin" prop="end" align="center"></el-table-column>
              <el-table-column align="right" label="Total" width="200" prop="total"></el-table-column>
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
    this.$root.$on("refreshExpenses", this.onSearch);

    // busca por default en el taller donde trabaja este empleado
    if (this.myUser && this.myUser.length > 0) {
      this.workshopId = this.myUser[0].workshop_id;
    }

    this.prevDay = this.initDayOfWeekDate(this.selectedDay, 2);
    let week = this.toFixedFormat(this.prevDay, "yyyyMMdd");
    this.newExpense.week = week;
    this.getFunds(`/api/funds?week=${week}&workshop=${this.workshopId}`);
  },
  methods: {
    getFunds(url) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function (response) {
        if (response.data.length > 0) {
          $this.addFunds = false;
          $this.funds = parseFloat(response.data[0].amount);
        } else {
          $this.addFunds = true;
        }

        $this.loading = false;
        $this.onSearch();
      });
    },
    addNew() {
      let selectedWeek = this.initDayOfWeekDate(this.selectedDay, 2);
      let thisWeek = this.initDayOfWeekDate(new Date(), 2);
      let sWeek = this.toFixedFormat(selectedWeek, "yyyyMMdd");
      let cWeek = this.toFixedFormat(thisWeek, "yyyyMMdd");

      // no dejar poner gastos en el futuro o pasado
      if (sWeek != cWeek) {
        this.$alert(
          "Favor de seleccionar la semana actual",
          "Semana no válida",
          {
            confirmButtonText: "OK",
            type: "warning",
          }
        );
        return;
      }

      let week = this.toFixedFormat(this.prevDay, "yyyyMMdd");
      this.newExpense.workshop = this.workshopId;
      this.newExpense.week = week;
      this.newExpense.type = 1;
      this.newExpense.concept = "";
      this.newExpense.amount = 0;
      this.newExpense.iva = 0;
      this.newExpense.total = 0;
      this.$refs.newItem.dialogVisible = true;
    },
    loadPayroll(url, newDate) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function (response) {
        $this.dataPayroll = [];

        if (response.data.length > 0) {
          let nominaSemanal = 0;
          response.data.forEach((n) => (nominaSemanal += parseFloat(n.total)));

          let end = $this.endPeriodo(newDate);
          $this.dataPayroll.push({
            concept: "Nómina Semanal",
            start: $this.toFixedFormat(newDate, "yyyy-MM-dd"),
            end: $this.toFixedFormat(end, "yyyy-MM-dd"),
            week: $this.getWeekOfDate(end),
            total: nominaSemanal,
          });
        }

        $this.calculaTotales();
        $this.loading = false;
      });
    },
    loadTable(url, week, workshop, newDate, start) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function (response) {
        $this.tableData1 = response.data.filter((d) => d.type == 1);
        $this.tableData2 = response.data.filter((d) => d.type == 2);
        $this.tableData3 = response.data.filter((d) => d.type == 3);

        // carga nomina de la semana
        $this.loadPayroll(
          `/api/payroll?workshop=${workshop}&week=${week}`,
          newDate
        );

        // carga ingresos de la semana
        $this.loadIncome(
          `/api/sales/searchReceiptByWeekAndCredits?start=${start}&workshop=${workshop}`
        );
      });
    },
    loadIncome(url) {
      const $this = this;
      $this.loading = true;

      $this.ingresosSinIva = 0;
      $this.totalCreditos = 0;

      axios.get(url).then(function (response) {
        $this.weekData = response.data.d;
        $this.loading = false;
        // sales
        $this.weekData.data.forEach((sale) => {
          let importe = parseFloat(sale.total);

          if (sale.method == 6) {
            $this.totalCreditos += importe;
          } else {
            $this.ingresosSinIva += importe;
          }
        });
      });
    },
    onSearch() {
      if (!this.selectedDay) {
        this.$alert("Favor de seleccionar una semana.", "Semana no válida", {
          confirmButtonText: "OK",
          type: "warning",
        });
        return;
      }

      if (!this.workshopId) {
        this.$alert("Favor de seleccionar un taller.", "Taller no válido", {
          confirmButtonText: "OK",
          type: "warning",
        });
        return;
      }

      var newDate = this.initDayOfWeekDate(this.selectedDay, 2);
      var start = `${this.toFixedFormat(newDate, "yyyy-MM-dd")} 00:00:00`;
      this.prevDay = newDate;

      let week = this.toFixedFormat(this.prevDay, "yyyyMMdd");
      this.newExpense.week = week;

      this.loadTable(
        `/api/expenses?week=${week}&workshop=${this.workshopId}`,
        week,
        this.workshopId,
        newDate,
        start
      );
    },
    calculaTotales() {
      this.totalSinIva = 0;

      this.tableData1.forEach((d) => {
        this.totalSinIva += parseFloat(d.total);
      });
      this.tableData2.forEach((d) => {
        this.totalSinIva += parseFloat(d.amount);
      });
      this.tableData3.forEach((d) => {
        this.totalSinIva += parseFloat(d.amount);
      });

      this.dataPayroll.forEach((d) => {
        this.totalSinIva += parseFloat(d.total);
      });
    },
  },
  data() {
    return {
      ingresosSinIva: 0,
      totalSinIva: 0,
      totalCreditos: 0,
      addFunds: false,
      funds: 0,

      workshopId: 0,
      activeIndex: 0,
      selectedDay: new Date(),
      loading: false,
      tableData1: [],
      tableData2: [],
      tableData3: [],
      dataPayroll: [],
      pickerOptions: {
        firstDayOfWeek: 6,
      },
      newExpense: {
        week: 0,
        workshop: 0,
        type: 1,
        concept: "",
        amount: 0,
        iva: 0,
        total: 0,
      },
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
