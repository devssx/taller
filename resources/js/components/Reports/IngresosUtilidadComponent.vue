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
          <bar-chart></bar-chart>
        </el-row>
        <el-row class="br bb bl">
          <bar-chart></bar-chart>
        </el-row>
        <el-row class="br bb bl">
          <bar-chart></bar-chart>
        </el-row>
      </el-col>

      <!-- Columna 2 -->
      <el-col :span="15" :offset="1">
        <!-- Tabla 1 -->
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Ingresos sin IVA</h1>
          </el-col>
        </el-row>
        <el-row class="br bl">
          <el-col :span="24">
            <el-table :data="tableData" style="width: 100%" v-loading="loading">
              <el-table-column label="Semanal" align="center">
                <el-table-column align="center" label="Fecha" width="150">
                  <template slot-scope="scope">{{ fixDate(scope.row.lunch_start) }}</template>
                </el-table-column>
                <el-table-column align="center" label="Monto">
                  <template slot-scope="scope">{{ fixDate(scope.row.lunch_end) }}</template>
                </el-table-column>
              </el-table-column>
              <el-table-column label="Mensual" align="center">
                <el-table-column align="center" label="Fecha" width="150">
                  <template slot-scope="scope">{{ fixDate(scope.row.lunch_start) }}</template>
                </el-table-column>
                <el-table-column align="center" label="Monto">
                  <template slot-scope="scope">{{ fixDate(scope.row.lunch_end) }}</template>
                </el-table-column>
              </el-table-column>
              <el-table-column label="Anual" align="center">
                <el-table-column align="center" label="Fecha" width="150">
                  <template slot-scope="scope">{{ fixDate(scope.row.lunch_start) }}</template>
                </el-table-column>
                <el-table-column align="center" label="Monto">
                  <template slot-scope="scope">{{ fixDate(scope.row.lunch_end) }}</template>
                </el-table-column>
              </el-table-column>
            </el-table>
          </el-col>
        </el-row>
        <br />
        <!-- Tabla 2 -->
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Gastos sin IVA</h1>
          </el-col>
        </el-row>
        <el-row class="br bl">
          <el-col :span="24">
            <el-table :data="tableData" style="width: 100%" v-loading="loading">
              <el-table-column label="Semanal" align="center">
                <el-table-column align="center" label="Fecha" width="150">
                  <template slot-scope="scope">{{ fixDate(scope.row.lunch_start) }}</template>
                </el-table-column>
                <el-table-column align="center" label="Monto">
                  <template slot-scope="scope">{{ fixDate(scope.row.lunch_end) }}</template>
                </el-table-column>
              </el-table-column>
              <el-table-column label="Mensual" align="center">
                <el-table-column align="center" label="Fecha" width="150">
                  <template slot-scope="scope">{{ fixDate(scope.row.lunch_start) }}</template>
                </el-table-column>
                <el-table-column align="center" label="Monto">
                  <template slot-scope="scope">{{ fixDate(scope.row.lunch_end) }}</template>
                </el-table-column>
              </el-table-column>
              <el-table-column label="Anual" align="center">
                <el-table-column align="center" label="Fecha" width="150">
                  <template slot-scope="scope">{{ fixDate(scope.row.lunch_start) }}</template>
                </el-table-column>
                <el-table-column align="center" label="Monto">
                  <template slot-scope="scope">{{ fixDate(scope.row.lunch_end) }}</template>
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
          <el-col :span="24">
            <el-table :data="tableData" style="width: 100%" v-loading="loading">
              <el-table-column label="Semanal" align="center">
                <el-table-column align="center" label="Fecha" width="150">
                  <template slot-scope="scope">{{ fixDate(scope.row.lunch_start) }}</template>
                </el-table-column>
                <el-table-column align="center" label="Monto">
                  <template slot-scope="scope">{{ fixDate(scope.row.lunch_end) }}</template>
                </el-table-column>
              </el-table-column>
              <el-table-column label="Mensual" align="center">
                <el-table-column align="center" label="Fecha" width="150">
                  <template slot-scope="scope">{{ fixDate(scope.row.lunch_start) }}</template>
                </el-table-column>
                <el-table-column align="center" label="Monto">
                  <template slot-scope="scope">{{ fixDate(scope.row.lunch_end) }}</template>
                </el-table-column>
              </el-table-column>
              <el-table-column label="Anual" align="center">
                <el-table-column align="center" label="Fecha" width="150">
                  <template slot-scope="scope">{{ fixDate(scope.row.lunch_start) }}</template>
                </el-table-column>
                <el-table-column align="center" label="Monto">
                  <template slot-scope="scope">{{ fixDate(scope.row.lunch_end) }}</template>
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

    console.log(this.getWeekOfDate(new Date()));
  },
  methods: {
    loadTable(url) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function (response) {
        $this.tableData = response.data;
        $this.loading = false;
      });
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
