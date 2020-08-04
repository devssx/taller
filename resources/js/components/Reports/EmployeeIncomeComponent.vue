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
      <el-col :span="24">
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Ingreso bruto por técnico mensual y anual</h1>
          </el-col>
        </el-row>
      </el-col>
    </el-row>

    <el-row class="br bl">
      <el-col :span="24">
        <el-table size="mini" :data="tableData" style="width: 100%" v-loading="loading">
          <el-table-column align="left" label="NOMBRE" width="200" prop="name"></el-table-column>
          <el-table-column align="center" label="ENERO" prop="jan"></el-table-column>
          <el-table-column align="center" label="FEBRERO" prop="feb"></el-table-column>
          <el-table-column align="center" label="MARZO" prop="mar"></el-table-column>
          <el-table-column align="center" label="ABRIL" prop="apr"></el-table-column>
          <el-table-column align="center" label="MAYO" prop="may"></el-table-column>
          <el-table-column align="center" label="JUNIO" prop="jun"></el-table-column>
          <el-table-column align="center" label="JULIO" prop="jul"></el-table-column>
          <el-table-column align="center" label="AGOSTO" prop="aug"></el-table-column>
          <el-table-column align="center" label="SEPT" prop="sep"></el-table-column>
          <el-table-column align="center" label="OCT" prop="oct"></el-table-column>
          <el-table-column align="center" label="NOV" prop="nov"></el-table-column>
          <el-table-column align="center" label="DICIEMBRE" prop="dec"></el-table-column>
          <el-table-column align="center" label="TOTAL" prop="total">
            <template slot-scope="scope">${{formatPrice(scope.row.total)}}</template>
          </el-table-column>
        </el-table>
      </el-col>
    </el-row>

    <BR />
    <el-row>
      <el-col :span="8">
        <bar-chart ref="myChartMensual" :chartData="[]"></bar-chart>
      </el-col>
      <el-col :span="8">
        <bar-chart ref="myChartAnual" :chartData="[]"></bar-chart>
      </el-col>
      <el-col :span="8"></el-col>
    </el-row>
  </el-row>
</template>

<script>
export default {
  props: ["workshops", "myUser", "multiWorkshop"],
  mounted: function () {
    this.loadUsers("/api/users");

    if (this.myUser && this.myUser.length > 0) {
      this.workshopId = this.myUser[0].workshop_id;
    }
  },
  methods: {
    loadUsers(url) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function (response) {
        $this.users = response.data.data;
        $this.loading = false;
      });
    },
    handleSelect(key, keyPath) {
      this.activeIndex = key;
    },
    loadTable(url) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function (response) {
        $this.tableData = [];
        response.data.forEach((n) => {
          // new
          if ($this.tableData.filter((u) => u.user == n.user_id).length == 0) {
            let tecnico = "Desconocido";
            var tecnicos = $this.users.filter((emp) => emp.id == n.user_id);
            if (tecnicos.length > 0) tecnico = tecnicos[0].name;

            var newUser = {
              user: n.user_id,
              name: tecnico,
              jan: 0,
              feb: 0,
              mar: 0,
              apr: 0,
              may: 0,
              jun: 0,
              jul: 0,
              aug: 0,
              sep: 0,
              oct: 0,
              nov: 0,
              dec: 0,
              total: 0,
            };
            $this.tableData.push(newUser);
          }

          var user = $this.tableData.filter((u) => u.user == n.user_id)[0];
          user.total += parseFloat(n.total);

          switch ($this.getMonthOfDate(n.week)) {
            case "01":
              user.jan += parseFloat(n.total);
              break;
            case "02":
              user.feb += parseFloat(n.total);
              break;
            case "03":
              user.mar += parseFloat(n.total);
              break;
            case "04":
              user.apr += parseFloat(n.total);
              break;
            case "05":
              user.may += parseFloat(n.total);
              break;
            case "06":
              user.jun += parseFloat(n.total);
              break;
            case "07":
              user.jul += parseFloat(n.total);
              break;
            case "08":
              user.aug += parseFloat(n.total);
              break;
            case "09":
              user.sep += parseFloat(n.total);
              break;
            case "10":
              user.oct += parseFloat(n.total);
              break;
            case "11":
              user.nov += parseFloat(n.total);
              break;
            case "12":
              user.dec += parseFloat(n.total);
              break;
          }
        });

        $this.createCharts($this.tableData);
        $this.loading = false;
      });
    },
    createCharts(data) {
      let i = 0;
      let chartAnual = [];
      let chartMensual = [];

      data.forEach((e) => {
        chartAnual.push({
          name: e.name,
          value: e.total,
          color: this.getColor(i),
        });

        chartMensual.push({
          name: e.name,
          value: e.total / 12,
          color: this.getColor(i++),
        });
      });

      this.$refs.myChartAnual.setData(chartAnual);
      this.$refs.myChartMensual.setData(chartMensual);
    },
    onSearch() {
      if (!this.workshopId) {
        this.$alert("Favor de seleccionar un taller.", "Taller no válido", {
          confirmButtonText: "OK",
          type: "warning",
        });
        return;
      }

      let year = this.selectedYear.getFullYear();
      this.loadTable(
        `/api/payroll/select?workshop=${this.workshopId}&year=${year}`
      );
    },
  },
  data() {
    return {
      workshopId: "",
      activeIndex: 0,
      selectedYear: new Date(),
      search: "",
      loading: true,
      tableData: [],
      users: [],
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
