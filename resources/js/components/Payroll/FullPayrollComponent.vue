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
          :picker-options="pickerOptions"
          placeholder="Seleccionar Semana"
        ></el-date-picker>
        <el-select width="150" v-model="workshopId" placeholder="Taller" :v-if="multiWorkshop">
          <el-option v-for="w in workshops" :key="w.id" :label="w.name" :value="w.id">{{w.name}}</el-option>
        </el-select>
        <el-button
          type="primary"
          icon="el-icon-search"
          @click="onSearch"
          :disabled="!selectedDay || !workshopId"
        ></el-button>
      </el-col>
      <el-col :span="4" align="end">
        <el-button
          v-show="isReadOnly"
          :disabled="employees.length==0"
          type="primary"
          @click="isReadOnly=false"
          icon="el-icon-edit"
        >Editar</el-button>
        <el-button
          v-show="!isReadOnly"
          @click="saveComment"
          type="primary"
          icon="el-icon-check"
        >Guardar</el-button>
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
          <el-table-column align="right" label="Sueldo" width="180" prop="salary">
            <template slot-scope="scope">{{ formatPrice(scope.row.salary) }}</template>
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
            <h4>Comentarios:</h4>
          </el-col>
          <el-col :span="20" class="row-headerb">
            <el-input
              type="textarea"
              :disabled="isReadOnly"
              :autosize="{ minRows: 2, maxRows: 4}"
              placeholder="Comentarios"
              v-model="comment.comment"
            ></el-input>
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
  props: ["workshops", "myUser", "multiWorkshop"],
  mounted: function () {
    // this.$root.$on("refreshTable", this.refreshTable);

    // busca por default en el taller donde trabaja este empleado
    if (this.myUser && this.myUser.length > 0) {
      if (!this.multiWorkshop) this.workshopId = this.myUser[0].workshop_id;
    }
  },
  methods: {
    setSales(data) {
      const $this = this;

      // reset
      $this.employees = [];
      $this.tableData = [];
      $this.total = 0;

      $this.weekData = data.d;
      $this.comment = data.c;

      $this.weekData.data.forEach((sale) => {
        var name = sale.user.name;
        if ($this.employees.filter((e) => e.name == name).length == 0)
          $this.employees.push(sale.user);
      });

      // calcula totales de cada empleado
      if ($this.employees.length > 0) {
        $this.employees.forEach((emp) => {
          let com1 = 0.07;
          let com2 = 0.07;
          let com3 = 0.07;
          let salary = 0.0;
          let discounts = 0.0;

          // buscar week y user id
          const myWeek = $this.weekPayroll.filter((p) => p.user_id == emp.id);
          myWeek.forEach((dato) => {
            if (dato.type == "1") {
              com1 = parseFloat(dato.comission);
              salary += parseFloat(dato.salary);
              discounts += parseFloat(dato.discount);
            }
            if (dato.type == "2") {
              com2 = parseFloat(dato.comission);
              salary += parseFloat(dato.salary);
              discounts += parseFloat(dato.discount);
            }
            if (dato.type == "3") {
              com3 = parseFloat(dato.comission);
              salary += parseFloat(dato.salary);
              discounts += parseFloat(dato.discount);
            }
          });

          $this.tableData.push(
            $this.getPayroll(emp.name, com1, com2, com3, salary, discounts)
          );
        });
      }

      // crear grafica
      var chart = $this.createChartData($this.tableData);
      $this.$refs.myChart.setData(chart);

      // nomina total
      $this.tableData.forEach((n) => ($this.total += n.total));
    },
    loadPayroll(url) {
      const $this = this;
      $this.weekPayroll = [];

      axios.get(url).then(function (response) {
        $this.weekPayroll = response.data.payroll;
        let sales = response.data.sales;
        $this.setSales(sales);
      });
    },
    getPayroll(user, cAc, cMec, cElec, salary, discounts) {
      let emp = {
        isReadOnly: false,
        employee: user,
        totalA: 0,
        totalB: 0,
        totalC: 0,
        comisionA: 0,
        comisionB: 0,
        comisionC: 0,
        subtotal: 0,
        salary: salary,
        discounts: discounts,
        total: 0,
      };

      // Carga totales de recibos tipo AC del empleado
      this.weekData.data.forEach((sale) => {
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
      emp.subtotal = emp.comisionA + emp.comisionB + emp.comisionC + salary;

      // Total
      emp.total = emp.subtotal - emp.discounts;
      return emp;
    },
    onSearch() {
      var newDate = this.initDayOfWeekDate(this.selectedDay, 2);
      this.prevDay = newDate;
      var start = this.toFixedFormat(newDate, "yyyyMMdd");

      this.loadPayroll(
        `/api/payroll?week=${start}&workshop=${this.workshopId}&start=${start}`
      );
    },
    saveComment() {
      const $this = this;
      $this.loading = true;
      $this.isReadOnly = true;

      var newDate = this.initDayOfWeekDate(this.selectedDay, 2);
      let week = `${this.toFixedFormat(newDate, "yyyyMMdd")}`;

      if (!this.workshopId) {
        this.$alert("Favor de seleccionar un taller.", "Taller no válido", {
          confirmButtonText: "OK",
          type: "warning",
        });
        return;
      }

      axios
        .post("/api/payroll/comment", {
          week: week,
          workshop: $this.workshopId,
          comment: $this.comment.comment,
          total: $this.total,
        })
        .then(function (response) {
          $this.$message({
            message: "El registro fué editado correctamente.",
            type: "success",
          });

          $this.loading = false;
        })
        .catch((error) => {
          if (error.response.data.errors) {
            var errors = error.response.data.errors;
            $this.$alert(errors[Object.keys(errors)[0]][0], "Error", {
              confirmButtonText: "OK",
              type: "error",
            });
          }
          $this.loading = false;
        });
    },
    createChartData(data) {
      let chartData = [];
      let i = 0;
      data.forEach((e) =>
        chartData.push({
          name: e.employee,
          value: e.total,
          color: this.getColor(i++),
        })
      );
      return chartData;
    },
  },
  data() {
    return {
      pickerOptions: {
        // disabledDate(time) {
        //   return time.getTime() > Date.now();
        // },
        firstDayOfWeek: 6,
      },
      total: 0,
      workshopId: "",
      isReadOnly: true,
      comment: { comment: "" },
      selectedDay: null,
      loading: false,
      weekData: [],
      employees: [],
      tableData: [],
      weekPayroll: [],
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
