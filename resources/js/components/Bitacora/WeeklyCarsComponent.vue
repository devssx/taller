<template>
  <el-row>
    <confirm-sales></confirm-sales>
    <br />
    <el-row class="br bl bt bb row-header">
      <el-col :span="2">
        <h1 style="margin-top: 8px; padding-left:4px;">Semana</h1>
      </el-col>
      <el-col :span="18">
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
      <el-col :span="4"></el-col>
    </el-row>

    <!-- TABLA -->
    <el-row class="br bl">
      <el-col :span="24">
        <el-table v-loading="loading" size="mini" :data="sales.data" style="width: 100%">
          <el-table-column label="Día" header-align="center" align="center">
            <template slot-scope="scope">{{ dayOfWeek(new Date(scope.row.created_at)) }}</template>
          </el-table-column>
          <el-table-column label="Entrada" header-align="center" align="center">
            <template slot-scope="scope">{{ fixDate(scope.row.created_at) }}</template>
          </el-table-column>
          <el-table-column label="Marca">
            <template slot-scope="scope">{{ scope.row.car[0].maker }}</template>
          </el-table-column>
          <el-table-column label="Modelo">
            <template slot-scope="scope">{{ scope.row.car[0].brand }}</template>
          </el-table-column>
          <el-table-column label="Año" width="50" header-align="center" align="center">
            <template slot-scope="scope">{{ scope.row.year }}</template>
          </el-table-column>
          <el-table-column label="Color" header-align="center" align="center">
            <template slot-scope="scope">{{ scope.row.color }}</template>
          </el-table-column>
          <el-table-column label="Cliente" width="150">
            <template slot-scope="scope">{{ scope.row.client.name }}</template>
          </el-table-column>
          <el-table-column label="Teléfono">
            <template slot-scope="scope">{{ scope.row.client.phonenumber }}</template>
          </el-table-column>
          <el-table-column label="Técnico">
            <template slot-scope="scope">{{ scope.row.user.name }}</template>
          </el-table-column>
          <el-table-column label="Diagnóstico" width="150">
            <template slot-scope="scope">{{ fixText(scope.row.concept, 16) }}</template>
          </el-table-column>
          <el-table-column label="Precio" header-align="right" align="right">
            <template slot-scope="scope">{{ scope.row.total }}</template>
          </el-table-column>
          <el-table-column label="Autorizó" header-align="center" align="center">
            <template slot-scope="scope">{{autorizo(scope.row)}}</template>
          </el-table-column>
          <el-table-column label="Recibo" header-align="center" align="center">
            <template slot-scope="scope">
              <el-button
                size="small"
                icon="el-icon-document"
                type="text"
                :disabled="!image1Loaded || !image2Loaded"
                @click="showReceipt(scope.row)"
              >{{scope.row.status==2?`Recibo`: `Cotización`}}</el-button>
            </template>
          </el-table-column>
          <el-table-column label="Opciones" header-align="center" align="center">
            <template slot-scope="scope">
              <el-button
                size="small"
                icon="el-icon-edit"
                type="text"
                v-if="scope.row.status == 2"
                @click="editItem(scope.row)"
              >Editar</el-button>
              <el-button
                size="small"
                icon="el-icon-check"
                type="text"
                v-if="scope.row.status == 0"
                @click="convertToReceipt(scope.row)"
              >Autorizar</el-button>
            </template>
          </el-table-column>
        </el-table>
      </el-col>
    </el-row>

    <!-- TOTALES -->
    <el-row class="br bl bb row-header">
      <el-col :span="4" style="margin-top: 7px;">
        <h4>Total Autorizados</h4>
      </el-col>
      <el-col :span="20" class="row-headerb" align="end">
        <h4>${{formatPrice(autorizados())}}</h4>
      </el-col>
    </el-row>
    <el-row class="br bl bb row-header">
      <el-col :span="4" style="margin-top: 7px;">
        <h4>Total No Autorizados</h4>
      </el-col>
      <el-col :span="20" class="row-headerb" align="end">
        <h4>${{formatPrice(noAutorizados())}}</h4>
      </el-col>
    </el-row>

    <!-- Imagenes Recibos -->
    <el-row type="flex" justify="end" style="opacity: 0;overflow: hidden;height: 50px;">
      <el-col :span="8">
        <img ref="quotation" @load="image1Loaded=true" src="/img/receipt.jpg" width="1200px" />
      </el-col>
      <el-col :span="8">
        <img ref="receipt" @load="image2Loaded=true" src="/img/receipt2.jpg" width="1200px" />
      </el-col>
      <el-col :span="8">
        <canvas ref="my-canvas"></canvas>
      </el-col>
    </el-row>
  </el-row>
</template>

<script>
export default {
  props: ["workshops"],
  mounted: function() {
    this.$root.$on("refreshTable", this.refreshTable);
    this.searchSales(this.selectedDay);
  },
  methods: {
    showReceipt(item) {
      // const COTIZACION = 0;
      // const PROCESO = 1;
      // const TERMINADO = 2;
      // const CANCELADO = 3;

      var canvas = this.$refs["my-canvas"];
      if (item.status == 2) {
        this.createReceipt(item, this.$refs["receipt"], canvas);
      } else {
        this.createQuotation(item, this.$refs["quotation"], canvas);
      }
    },
    convertToReceipt(item) {
      // convierte cotizacion a recibo
      this.$root.$emit("changeStatus", item, 2);
    },
    editItem(item) {
      this.$root.$emit("confirmSale", item, true);
    },
    onSearch() {
      if (!this.selectedDay) {
        return;
      }

      this.searchSales(this.selectedDay);
    },
    searchSales(day) {
      this.prevDay = day;
      var start = `${this.toFixedFormat(day, "yyyy-MM-dd")} 00:00:00`;
      this.loadTable(`/api/sales/searchByWeek?start=${start}`);
    },
    autorizo(item) {
      return item.status == 2 ? `Si` : `No`;
    },
    autorizados() {
      var total = 0.0;
      if (this.sales.data) {
        var selection = this.sales.data.filter(i => i.status == 2);
        selection.forEach(s => (total += parseFloat(s.total)));
      }
      return total;
    },
    noAutorizados() {
      var total = 0.0;
      if (this.sales.data) {
        var selection = this.sales.data.filter(i => i.status != 2);
        selection.forEach(s => (total += parseFloat(s.total)));
      }
      return total;
    },
    fixDate(dt) {
      return this.toFixedTime(new Date(dt));
    },
    loadTable(url) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function(response) {
        $this.sales = response.data;
        $this.oldSales = JSON.parse(JSON.stringify(response.data));
        $this.loading = false;
      });
    },
    refreshTable() {
      this.searchSales(this.prevDay);
    }
  },
  data() {
    return {
      workshopId: "",
      image1Loaded: false,
      image2Loaded: false,
      sales: [],
      oldSales: [],
      loading: false,
      page: 1,
      selectedDay: this.initDayOfWeekDate(),
      prevDay: this.initDayOfWeekDate(),
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

/* Ipad Pro */
@media only screen and (max-width: 1366px) {
  .content.el-main {
    padding: 5px;
  }
}
</style>
