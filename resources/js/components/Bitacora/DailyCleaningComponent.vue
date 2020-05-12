<template>
  <el-row>
    <br />
    <el-row class="br bl bt bb row-header">
      <el-col :span="2"><h1 style="margin-top: 8px;">FECHA DIA</h1></el-col>
      <el-col :span="22">
        <el-date-picker
          v-model="selectedDay"
          type="date"
          format="dd-MM-yyyy"
          placeholder="Seleccionar Día"
        ></el-date-picker>
        <el-button
          type="primary"
          icon="el-icon-search"
          ></el-button
        >
      </el-col>
    </el-row>

    <!-- TABLA -->
    <el-row class="br bl">
      <el-col :span="24">
        <el-table :data="tableData" style="width: 100%">
          <el-table-column
            prop="employee"
            label="Empleado"
            width="300"
          ></el-table-column>
          <el-table-column
            align="center"
            prop="horaEntrada"
            label="Hora Entrada"
            width="120"
          ></el-table-column>
          <el-table-column
            align="center"
            prop="limpieza"
            label="Limpieza"
            width="150"
          ></el-table-column>
          <el-table-column label="Hora Desayuno" align="center">
            <el-table-column
              align="center"
              prop="horaDesayunoEntrada"
              label="Entrada"
              width="120"
            >
            </el-table-column>
            <el-table-column
              align="center"
              prop="horaDesayunoSalida"
              label="Salida"
              width="120"
            >
            </el-table-column>
          </el-table-column>
          <el-table-column label="Hora Comida" align="center">
            <el-table-column
              align="center"
              prop="horaComidaEntrada"
              label="Entrada"
              width="120"
            >
            </el-table-column>
            <el-table-column
              prop="horaComidaSalida"
              label="Salida"
              width="120"
              align="center"
            >
            </el-table-column>
          </el-table-column>
          <el-table-column
            align="center"
            prop="cumplio"
            label="Cumplió"
            width="120"
          ></el-table-column>
          <el-table-column
            prop="comentario"
            label="Comentario"
            width="300"
          ></el-table-column>
          <el-table-column fixed="right" label="Modificar" width="120">
            <template slot-scope="scope">
              <el-button
                size="small"
                icon="el-icon-edit"
                type="text"
              ></el-button>
              <el-button
                @click="deleteRow(scope.$index, tableData)"
                type="text"
                size="small"
              >
                Eliminar
              </el-button>
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
    this.loadTable("/api/clients");

    this.$root.$on("refreshTable", this.refreshTable);
  },
  methods: {
    deleteRow(index, rows) {
      rows.splice(index, 1);
    },
    handleClick() {
      console.log("click");
    },
    loadTable(url) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function(response) {
        $this.items = response.data;
        $this.loading = false;
      });
    },
    refreshTable() {
      this.loadTable(
        "/api/clients?page=" + this.page + "&search=" + this.search
      );
    },
    handleCurrentChange(val) {
      this.page = val;
      this.refreshTable();
    },
  },
  watch: {
    search: function() {
      var $this = this;
      if ($this.timeout) {
        clearTimeout($this.timeout);
      }
      $this.timeout = setTimeout(function() {
        $this.loadTable(
          "/api/clients?page=" + $this.page + "&search=" + $this.search
        );
      }, 1000);
    },
  },
  data() {
    return {
      selectedDay: "",
      items: [],
      search: "",
      loading: true,

      tableData: [
        {
          employee: "Juan Medina Lopez",
          horaEntrada: "6:59 am",
          limpieza: "N/A",
          horaDesayunoEntrada: "11:00 am",
          horaDesayunoSalida: "10:00 am",
          horaComidaEntrada: "4:00 pm",
          horaComidaSalida: "3:30 pm",
          cumplio: "Si",
          comentario: "",
        },
        {
          employee: "Alma Delia Lopez Sandoval",
          horaEntrada: "6:59 am",
          limpieza: "N/A",
          horaDesayunoEntrada: "11:00 am",
          horaDesayunoSalida: "10:00 am",
          horaComidaEntrada: "4:00 pm",
          horaComidaSalida: "3:30 pm",
          cumplio: "Si",
          comentario: "",
        },
        {
          employee: "Juana Lopez Madrid",
          horaEntrada: "6:55 am",
          limpieza: "N/A",
          horaDesayunoEntrada: "11:00 am",
          horaDesayunoSalida: "10:00 am",
          horaComidaEntrada: "4:40 pm",
          horaComidaSalida: "3:30 pm",
          cumplio: "No",
          comentario: "Tardo comprando comida.",
        },
      ],
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
</style>
