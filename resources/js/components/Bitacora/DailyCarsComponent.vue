<template>
  <el-row>
    <br />
    <el-row class="br bl bt bb row-header">
      <el-col :span="2">
        <h1 style="margin-top: 8px; padding-left:4px;">Día</h1>
      </el-col>
      <el-col :span="18">
        <el-date-picker
          v-model="selectedDay"
          type="date"
          format="dd-MM-yyyy"
          placeholder="Seleccionar Día"
        ></el-date-picker>
        <el-button type="primary" icon="el-icon-search" @click="onSearch"></el-button>
      </el-col>
      <el-col :span="4">
        <!-- <div style="float:right;">
          <el-button
            :disabled="!selectedDay"
            type="primary"
            icon="el-icon-plus"
            @click="addNewItem"
          >Nuevo</el-button>
        </div>-->
      </el-col>
    </el-row>

    <!-- TABLA -->
    <el-row class="br bl">
      <el-col :span="24">
        <el-table v-loading="loading" size="mini" :data="sales.data" style="width: 100%">
          <el-table-column label="Entrada" width="100">
            <template slot-scope="scope">{{ fixDate(scope.row.created_at) }}</template>
          </el-table-column>
          <el-table-column label="Marca" width="100">
            <template slot-scope="scope">{{ scope.row.car[0].maker }}</template>
          </el-table-column>
          <el-table-column label="Modelo" width="120">
            <template slot-scope="scope">{{ scope.row.car[0].brand }}</template>
          </el-table-column>
          <el-table-column label="Año" width="65">
            <template slot-scope="scope">{{ scope.row.year }}</template>
          </el-table-column>
          <el-table-column label="Color" width="100">
            <template slot-scope="scope">{{ scope.row.color }}</template>
          </el-table-column>
          <el-table-column label="Cliente" width="300">
            <template slot-scope="scope">{{ scope.row.client.name }}</template>
          </el-table-column>
          <el-table-column label="Teléfono" width="100">
            <template slot-scope="scope">{{ scope.row.client.phonenumber }}</template>
          </el-table-column>
          <el-table-column label="Técnico" width="120">
            <template slot-scope="scope">{{ scope.row.user.name }}</template>
          </el-table-column>
          <el-table-column label="Diagnóstico">
            <template slot-scope="scope">{{ scope.row.concept }}</template>
          </el-table-column>
          <el-table-column label="Precio" width="120" align="end">
            <template slot-scope="scope">{{ scope.row.total }}</template>
          </el-table-column>
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

    <!-- TOTALES -->
    <el-row class="br bl bb row-header">
      <el-col :span="4" style="margin-top: 7px;">
        <h5>Total Autorizados</h5>
      </el-col>
      <el-col :span="20" class="row-headerb">
        <h5>$0.00</h5>
      </el-col>
    </el-row>
    <el-row class="br bl bb row-header">
      <el-col :span="4" style="margin-top: 7px;">
        <h5>Total No Autorizados</h5>
      </el-col>
      <el-col :span="20" class="row-headerb">
        <h5>$0.00</h5>
      </el-col>
    </el-row>
  </el-row>
</template>

<script>
export default {
  mounted: function() {
    this.$root.$on("refreshTable", this.refreshTable);
    this.loadTable("/api/sales");
  },
  methods: {
    fixDate(dt) {
      return this.toFixedTime(new Date(dt));
    },
    loadTable(url) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function(response) {
        console.log(response);
        $this.sales = response.data;
        $this.oldSales = JSON.parse(JSON.stringify(response.data));
        $this.loading = false;
      });
    },
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
      sales: [],
      oldSales: [],
      loading: true,
      page: 1,
      selectedDay: new Date(),
      search: ""
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
