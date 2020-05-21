<template>
  <el-row>
    <br />
    <el-row class="br bl bt bb row-header">
      <el-col :span="2">
        <h1 style="margin-top: 8px;">Día</h1>
      </el-col>
      <el-col :span="18">
        <el-date-picker
          v-model="selectedDay"
          type="date"
          format="dd-MM-yyyy"
          placeholder="Seleccionar Día"
        ></el-date-picker>
        <el-button type="primary" icon="el-icon-search" @click="onSearch"></el-button>
        <cars-edit :selectedItem="newUser" :hideButton="true" ref="newItem"></cars-edit>
        {{toFixedTime(selectedDay)}}
      </el-col>
      <el-col :span="4">
        <div style="float:right;">
          <el-button
            :disabled="!selectedDay"
            type="primary"
            icon="el-icon-plus"
            @click="addNewItem"
          >Nuevo</el-button>
        </div>
      </el-col>
    </el-row>

    <!-- TABLA -->
    <el-row class="br bl">
      <el-col :span="24">
        <el-table :data="tableData" style="width: 100%">
          <el-table-column prop="entrada" label="Entrada" width="100"></el-table-column>
          <el-table-column prop="marca" label="Marca" width="100"></el-table-column>
          <el-table-column prop="modelo" label="Modelo" width="100"></el-table-column>
          <el-table-column prop="year" label="Año" width="65"></el-table-column>
          <el-table-column prop="color" label="Color" width="100"></el-table-column>
          <el-table-column prop="cliente" label="Cliente" width="180"></el-table-column>
          <el-table-column prop="telefono" label="Teléfono" width="100"></el-table-column>
          <el-table-column prop="tecnico" label="Técnico" width="120"></el-table-column>
          <el-table-column prop="concepto" label="Diagnóstico" width="300"></el-table-column>
          <el-table-column prop="precio" label="Precio" width="120"></el-table-column>
          <el-table-column prop="autorizado" label="Autorizó" width="85"></el-table-column>
          <el-table-column prop="recibo" label="Recibo" width="85"></el-table-column>
          <el-table-column fixed="right" label="Opciones" width="120">
            <template>
              <el-button @click="handleClick" icon="el-icon-finished" type="text" size="small"></el-button>
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
    fixDate(dt) {
      return this.toFixedTime(new Date(dt));
    },
    loadTable(url) {},
    onSearch() {
      // this.loadTable(`/api/cleaning/search?start=${start}&end=${end}`);
    },
    addNewItem() {
      this.$refs.newItem.insertNewRow(this.selectedDay);
    },
    refreshTable() {},
    handleClick() {}
  },
  data() {
    return {
      showDialog: false,
      selectedDay: new Date(),
      search: "",
      loading: false,
      tableData: [],
      newUser: {
        user_id: 1,
        start: "",
        cleaning: "",
        breakfast_start: "",
        breakfast_end: "",
        lunch_start: "",
        lunch_end: "",
        done: "No",
        comment: "",
        name: ""
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
</style>
