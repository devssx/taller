<template>
  <el-row>
    <br />
    <el-row class="br bl bt bb row-header">
      <el-col :span="2">
        <h1 style="margin-top: 8px;">Semana</h1>
      </el-col>
      <el-col :span="22">
        <el-date-picker
          v-model="selectedDay"
          type="week"
          format="Week WW"
          placeholder="Seleccionar Semana"
        ></el-date-picker>
        <el-button type="primary" icon="el-icon-search" @click="onSearch"></el-button>
      </el-col>
    </el-row>

    <!-- User list -->
    <el-row class="br bl">
      <!-- TABLA -->
      <el-col :span="24">
        <el-table :data="tableData" style="width: 100%" v-loading="loading">
          <el-table-column label="Día" width="120">
            <template slot-scope="scope">{{ dayOfWeek(scope.row.start) }}</template>
          </el-table-column>
          <el-table-column prop="entrada" label="Entrada" width="100"></el-table-column>
          <el-table-column prop="marca" label="Marca" width="100"></el-table-column>
          <el-table-column prop="modelo" label="Modelo" width="100"></el-table-column>
          <el-table-column prop="year" label="Año" width="65"></el-table-column>
          <el-table-column prop="color" label="Color" width="100"></el-table-column>
          <el-table-column prop="cliente" label="Cliente" width="300"></el-table-column>
          <el-table-column prop="telefono" label="Teléfono" width="100"></el-table-column>
          <el-table-column prop="tecnico" label="Técnico" width="120"></el-table-column>
          <el-table-column prop="concepto" label="Diagnóstico"></el-table-column>
          <el-table-column prop="precio" label="Precio" width="120"></el-table-column>
          <el-table-column prop="autorizado" label="Autorizó" width="85"></el-table-column>
          <el-table-column prop="recibo" label="Recibo" width="85"></el-table-column>
          <el-table-column label="Opciones" width="120">
            <template>
              <el-button icon="el-icon-finished" type="text" size="small"></el-button>
              <el-button size="small" icon="el-icon-edit" type="text"></el-button>
            </template>
          </el-table-column>
        </el-table>
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
      this.showWeekOfEmployee(this.employees[key]);
    },
    showWeekOfEmployee(name) {
      this.tableData = this.employeeData.filter(e => e.name == name);
    },
    fixDate(dt) {
      return this.formatDate(new Date(dt));
    },
    loadTable(url) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function(response) {
        $this.employeeData = response.data;
        $this.loading = false;

        // Para el panel izquierdo (lista de empleados)
        $this.employeeData.forEach(element => {
          var name = element.name;
          if ($this.employees.filter(e => e == name).length == 0)
            $this.employees.push(name);
        });
      });
    },
    onSearch() {
      // this.loadTable(`/api/cleaning/searchWeek?start=${start}`);
    },
    refreshTable(id) {
      // if (id) {
      //   var url = `/api/cleaning/search?id=${id}`;
      //   var $this = this;
      //   $this.loading = true;
      //   axios.get(url).then(function(response) {
      //     var data = response.data;
      //     if (data.length > 0) {
      //       for (var i = 0; i < $this.employeeData.length; i++) {
      //         // busca el item editado para actualizar en la tabla visual
      //         if ($this.employeeData[i].id == id) {
      //           $this.employeeData[i] = data[0];
      //           break;
      //         }
      //       }
      //     }
      //     $this.loading = false;
      //   });
      // }
    }
  },
  data() {
    return {
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
</style>
