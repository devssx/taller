<template>
  <el-row>
    <br />
    <el-row class="br bl bt bb row-header">
      <el-col :span="2">
        <h1 style="margin-top: 8px;">SEMANA</h1>
      </el-col>
      <el-col :span="22">
        <el-date-picker
          v-model="selectedDay"
          type="week"
          format="Week WW"
          placeholder="Seleccionar Semana"
        ></el-date-picker>
        <el-select width="150" v-model="workshopId" placeholder="Selecciona un taller">
          <el-option v-for="w in workshops" :key="w.id" :label="w.name" :value="w.id">{{w.name}}</el-option>
        </el-select>
        <el-button type="primary" icon="el-icon-search" @click="onSearch"></el-button>
      </el-col>
    </el-row>

    <!-- User list -->
    <el-row class="br bl">
      <el-col :span="4">
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
      </el-col>

      <!-- TABLA -->
      <el-col :span="20">
        <el-table size="mini" :data="tableData" style="width: 100%" v-loading="loading">
          <el-table-column label="Día" width="75">
            <template slot-scope="scope">{{ dayOfWeek(scope.row.start) }}</template>
          </el-table-column>
          <el-table-column align="center" label="Hora Entrada" width="110" prop="start">
            <template slot-scope="scope">{{ fixDate(scope.row.start) }}</template>
          </el-table-column>
          <el-table-column align="center" prop="cleaning" label="Limpieza" width="110"></el-table-column>
          <el-table-column label="Hora Desayuno" align="center">
            <el-table-column align="center" label="Inicio" width="100" prop="breakfast_start">
              <template slot-scope="scope">{{ fixDate(scope.row.breakfast_start) }}</template>
            </el-table-column>
            <el-table-column align="center" label="Fin" width="100" prop="breakfast_end">
              <template slot-scope="scope">{{ fixDate(scope.row.breakfast_end) }}</template>
            </el-table-column>
          </el-table-column>
          <el-table-column label="Hora Comida" align="center">
            <el-table-column align="center" label="Inicio" width="100" prop="lunch_start">
              <template slot-scope="scope">{{ fixDate(scope.row.lunch_start) }}</template>
            </el-table-column>
            <el-table-column align="center" label="Fin" width="100" prop="lunch_end">
              <template slot-scope="scope">{{ fixDate(scope.row.lunch_end) }}</template>
            </el-table-column>
          </el-table-column>
          <el-table-column align="center" prop="done" label="Cumplió" width="80"></el-table-column>
          <el-table-column prop="comment" label="Comentario"></el-table-column>
          <el-table-column align="center" label="Modificar" width="100">
            <template slot-scope="scope">
              <dc-edit :selectedItem="tableData[scope.$index]"></dc-edit>
            </template>
          </el-table-column>
        </el-table>
      </el-col>
    </el-row>
  </el-row>
</template>

<script>
export default {
  props: ["workshops"],
  mounted: function() {
    this.$root.$on("refreshTable", this.refreshTable);
  },
  methods: {
    handleSelect(key, keyPath) {
      this.activeIndex = key;
      this.showWeekOfEmployee(this.employees[key]);
    },
    showWeekOfEmployee(name) {
      this.tableData = this.employeeData.filter(e => e.name == name);
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
      var dText = this.formatDate(new Date(dt));
      return dText == `12:00 am` ? `` : dText;
    },
    loadTable(url) {
      var $this = this;
      $this.loading = true;
      $this.tableData = [];
      $this.employees = [];

      axios.get(url).then(function(response) {
        $this.employeeData = response.data;
        $this.loading = false;

        // Para el panel izquierdo (lista de empleados)
        $this.employeeData.forEach(element => {
          var name = element.name;
          if ($this.employees.filter(e => e == name).length == 0)
            $this.employees.push(name);
        });

        // seleccionar primero
        if ($this.employeeData.length > 0)
          $this.showWeekOfEmployee($this.employeeData[0].name);
      });
    },
    onSearch() {
      var newDate = this.initDayOfWeekDate(this.selectedDay);
      var day = `${this.toFixedFormat(newDate, "yyyy-MM-dd")}`;
      this.loadTable(`/api/cleaning/searchWeek?start=${day}`);
    },
    refreshTable(item) {
      if (item && item.id) {
        for (var i = 0; i < this.employeeData.length; i++) {
          // busca el item editado para actualizar en la tabla visual
          if (this.employeeData[i].id == item.id) {
            var newObject = new Object();
            newObject.created_at = this.employeeData[i].created_at;
            newObject.day = this.employeeData[i].day;
            newObject.id = this.employeeData[i].id;
            newObject.name = this.employeeData[i].name;
            newObject.updated_at = this.employeeData[i].updated_at;
            newObject.user_id = this.employeeData[i].user_id;
            newObject.start = item.start;
            newObject.breakfast_start = item.breakfast_start;
            newObject.breakfast_end = item.breakfast_end;
            newObject.lunch_start = item.lunch_start;
            newObject.lunch_end = item.lunch_end;
            newObject.cleaning = item.cleaning;
            newObject.comment = item.comment;
            newObject.done = item.done;
            this.employeeData[i] = newObject;
            this.showWeekOfEmployee(newObject.name);
            break;
          }
        }
      }
    }
  },
  data() {
    return {
      workshopId: "",
      activeIndex: "0",
      showDialog: false,
      selectedDay: new Date(),
      search: "",
      loading: false,
      employeeData: [],
      tableData: [],
      employees: []
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
/* Ipad Pro */
@media only screen and (max-width: 1366px) {
  .content.el-main {
    padding: 5px;
  }
}
</style>
