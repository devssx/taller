<template>
  <el-row>
    <el-row>
      <el-col :span="6">FECHA DIA</el-col>
      <el-col :span="18">
        <el-date-picker v-model="value1" type="date" placeholder="Seleccionar Día"></el-date-picker>
        <el-date-picker
          v-model="value1"
          type="week"
          format="Week WW"
          placeholder="Seleccionar Semana"
        ></el-date-picker>
      </el-col>
    </el-row>

    <!-- TABLA -->
    <el-row>
      <el-col :span="24">
        <el-table :data="tableData" style="width: 100%">
          <el-table-column prop="date" label="HORA DE ENTRADA" width="160"></el-table-column>
          <el-table-column prop="name" label="MARCA" width="120"></el-table-column>
          <el-table-column prop="state" label="MODELO" width="120"></el-table-column>
          <el-table-column prop="city" label="AñO" width="120"></el-table-column>
          <el-table-column prop="address" label="COLOR" width="120"></el-table-column>
          <el-table-column prop="zip" label="NOMBRE DEL CLIENTE" width="200"></el-table-column>
          <el-table-column prop="zip" label="TELEFONO" width="120"></el-table-column>
          <el-table-column prop="zip" label="TECNICO" width="120"></el-table-column>
          <el-table-column prop="zip" label="DIAGNOSTICO CONCEPTO" width="300"></el-table-column>
          <el-table-column prop="zip" label="PRECIO" width="120"></el-table-column>
          <el-table-column prop="zip" label="AUTORIZO" width="120"></el-table-column>
          <el-table-column prop="zip" label="RECIBO" width="120"></el-table-column>

          <el-table-column fixed="right" label="Operaciones" width="120">
            <template>
              <el-button @click="handleClick" type="text" size="small">Detalle</el-button>
              <el-button type="text" size="small">Editar</el-button>
            </template>
          </el-table-column>
        </el-table>

        <!-- PAGINACION -->
        <div class="block" style="text-align: center;" v-if="items.total > 10">
          <el-pagination
            layout="prev, pager, next"
            :page-size="10"
            :total="items.total"
            @current-change="handleCurrentChange"
          ></el-pagination>
        </div>
      </el-col>
    </el-row>

    <!-- TOTALES -->
    <el-row>
      <el-col :span="6">TOTAL TRABAJOS AUTORIZADOS:</el-col>
      <el-col :span="18">$ 00.00</el-col>
    </el-row>
    <el-row>
      <el-col :span="6">TOTAL TRABAJOS NO AUTORIZADOS:</el-col>
      <el-col :span="18">$ 00.00</el-col>
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
    }
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
    }
  },
  data() {
    return {
      value1: "",
      items: [],
      search: "",
      timeout: 0,
      page: 1,
      loading: true,

      tableData: [
        {
          date: "2016-05-03",
          name: "Tom",
          state: "California",
          city: "Los Angeles",
          address: "No. 189, Grove St, Los Angeles",
          zip: "CA 90036",
          tag: "Home"
        },
        {
          date: "2016-05-02",
          name: "Tom",
          state: "California",
          city: "Los Angeles",
          address: "No. 189, Grove St, Los Angeles",
          zip: "CA 90036",
          tag: "Office"
        },
        {
          date: "2016-05-04",
          name: "Tom",
          state: "California",
          city: "Los Angeles",
          address: "No. 189, Grove St, Los Angeles",
          zip: "CA 90036",
          tag: "Home"
        },
        {
          date: "2016-05-01",
          name: "Tom",
          state: "California",
          city: "Los Angeles",
          address: "No. 189, Grove St, Los Angeles",
          zip: "CA 90036",
          tag: "Office"
        }
      ]
    };
  }
};
</script>
