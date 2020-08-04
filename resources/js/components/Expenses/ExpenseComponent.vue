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
          <el-col :span="24" align="end" style="padding-right: 5px;">$0.00</el-col>
        </el-row>
        <el-row class="br bb bl">
          <el-col :span="24" align="end" style="padding-right: 5px;">$0.00</el-col>
        </el-row>
        <el-row class="br bb bl">
          <el-col :span="24" align="end" style="padding-right: 5px;">$0.00</el-col>
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
          >${{ formatPrice(utilidadSinIva) }}</el-col>
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
            <el-table size="mini" border :data="tableData4" style="width: 100%" v-loading="loading">
              <el-table-column label="Nómina total" prop="concept"></el-table-column>
              <el-table-column align="right" label="Total" width="200" prop="total"></el-table-column>
              <el-table-column label="Opciones" header-align="center" align="center" width="120">
                <template slot-scope="scope">
                  <expense-edit :workshop="workshopId" :selectedItem="tableData4[scope.$index]"></expense-edit>
                </template>
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
  mounted: function() {
    this.$root.$on("refreshExpenses", this.onSearch);

    // busca por default en el taller donde trabaja este empleado
    if (this.myUser && this.myUser.length > 0) {
      this.workshopId = this.myUser[0].workshop_id;
    }

    this.prevDay = this.initDayOfWeekDate(this.selectedDay);
    let week = this.toFixedFormat(this.prevDay, "yyyyMMdd");
    this.newExpense.week = week;
  },
  methods: {
    addNew() {
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
    loadTable(url) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function(response) {
        $this.tableData1 = response.data.filter(d => d.type == 1);
        $this.tableData2 = response.data.filter(d => d.type == 2);
        $this.tableData3 = response.data.filter(d => d.type == 3);
        $this.tableData4 = response.data.filter(d => d.type == 4);
        $this.calculaTotales();
        $this.loading = false;
      });
    },
    onSearch() {
      var newDate = this.initDayOfWeekDate(this.selectedDay);
      this.prevDay = newDate;
      var start = `${this.toFixedFormat(newDate, "yyyy-MM-dd")} 00:00:00`;

      if (!this.workshopId) {
        this.$alert("Favor de seleccionar un taller.", "Taller no válido", {
          confirmButtonText: "OK",
          type: "warning"
        });
        return;
      }

      let week = this.toFixedFormat(this.prevDay, "yyyyMMdd");
      this.newExpense.week = week;
      this.loadTable(`/api/expenses?week=${week}&workshop=${this.workshopId}`);
    },
    calculaTotales() {
      this.totalSinIva = 0;
      this.totalCreditos = 0;
      
      this.tableData1.forEach(d => (this.totalSinIva += parseFloat(d.total)));
      this.tableData2.forEach(d => (this.totalSinIva += parseFloat(d.amount)));
      this.tableData3.forEach(d => (this.totalCreditos += parseFloat(d.amount)));
      this.tableData4.forEach(d => (this.totalSinIva += parseFloat(d.total)));
    }
  },
  data() {
    return {
      ingresosSinIva: 0,
      totalSinIva: 0,
      utilidadSinIva: 0,
      totalCreditos: 0,

      workshopId: 0,
      activeIndex: 0,
      selectedDay: new Date(),
      loading: false,
      tableData1: [],
      tableData2: [],
      tableData3: [],
      tableData4: [],
      newExpense: {
        week: 0,
        workshop: 0,
        type: 1,
        concept: "",
        amount: 0,
        iva: 0,
        total: 0
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
