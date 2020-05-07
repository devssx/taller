<template>
  <el-row>
    <br />
    <el-row class="br bl bt bb row-header">
      <el-col :span="4"><h1 style="margin-top: 8px;">FECHA DIA</h1></el-col>
      <el-col :span="20">
        <el-date-picker
          v-model="value1"
          type="date"
          placeholder="Seleccionar Día"
        ></el-date-picker>
        <el-date-picker
          v-model="value1"
          type="week"
          format="Week WW"
          placeholder="Seleccionar Semana"
        ></el-date-picker>
      </el-col>
    </el-row>

    <!-- TABLA -->
    <el-row class="br bl">
      <el-col :span="24">
        <el-table :data="tableData" style="width: 100%">
          <el-table-column
            prop="date"
            label="ENTRADA"
            width="100"
          ></el-table-column>
          <el-table-column
            prop="name"
            label="MARCA"
            width="100"
          ></el-table-column>
          <el-table-column
            prop="state"
            label="MODELO"
            width="100"
          ></el-table-column>
          <el-table-column
            prop="city"
            label="AñO"
            width="65"
          ></el-table-column>
          <el-table-column
            prop="address"
            label="COLOR"
            width="100"
          ></el-table-column>
          <el-table-column
            prop="zip"
            label="CLIENTE"
            width="180"
          ></el-table-column>
          <el-table-column
            prop="zip"
            label="TELEFONO"
            width="100"
          ></el-table-column>
          <el-table-column
            prop="zip"
            label="TECNICO"
            width="120"
          ></el-table-column>
          <el-table-column
            prop="zip"
            label="DIAGNOSTICO CONCEPTO"
            width="300"
          ></el-table-column>
          <el-table-column
            prop="zip"
            label="PRECIO"
            width="120"
          ></el-table-column>
          <el-table-column
            prop="zip"
            label="AUTORIZO"
            width="100"
          ></el-table-column>
          <el-table-column
            prop="zip"
            label="RECIBO"
            width="100"
          ></el-table-column>

          <el-table-column fixed="right" label="Operaciones" width="120">
            <template>
              <el-button
                @click="handleClick"
                icon="el-icon-finished"
                type="text"
                size="small"
              ></el-button>
              <el-button
                size="small"
                icon="el-icon-edit"
                type="text"
              ></el-button>
            </template>
          </el-table-column>
        </el-table>

        <!-- PAGINACION -->
        <!-- <div class="block" style="text-align: center;" v-if="items.total > 10">
          <el-pagination
            layout="prev, pager, next"
            :page-size="10"
            :total="items.total"
            @current-change="handleCurrentChange"
          ></el-pagination>
        </div> -->
      </el-col>
    </el-row>

    <!-- TOTALES -->
    <el-row class="br bl bt">
      <el-col :span="6" class="row-header">TOTAL TRABAJOS AUTORIZADOS:</el-col>
      <el-col :span="18" style="padding: 4px;">$ 00.00</el-col>
    </el-row>
    <el-row class="br bl bt bb">
      <el-col :span="6" class="row-header">TOTAL TRABAJOS NO AUTORIZADOS:</el-col>
      <el-col :span="18" style="padding: 4px;">$ 00.00</el-col>
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
      value1: "",
      items: [],
      search: "",
      timeout: 0,
      page: 1,
      loading: true,

      tableData: [
        {
          date: "9:20 am",
          name: "Chervolet",
          state: "Malibu",
          city: "2015",
          address: "Verde",
          zip: "4565465465",
          tag: "Juan",
        },
        {
          date: "9:25 am",
          name: "Nissan",
          state: "Sentra",
          city: "2000",
          address: "Rojo",
          zip: "54654654",
          tag: "Juan",
        },
        {
          date: "9:26 am",
          name: "Mazda",
          state: "CRX",
          city: "2015",
          address: "Blanco",
          zip: "4546544254",
          tag: "Juan",
        },
        {
          date: "11:07 am",
          name: "Honda",
          state: "Civic",
          city: "2011",
          address: "Gris",
          zip: "68621548",
          tag: "Juan",
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
  background-color: #f2f2f2;
  padding: 4px;
}
</style>
