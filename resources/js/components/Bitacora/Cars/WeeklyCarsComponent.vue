<template>
  <el-row>
    <confirm-sales ref="weeklySaleEdit"></confirm-sales>
    <select-format name="weekly" ref="formatDialogWeekly"></select-format>
    <br />
    <el-row class="br bl bt bb row-header">
      <el-col :span="2">
        <h1 style="margin-top: 8px; padding-left: 4px">Semana</h1>
      </el-col>
      <el-col :span="18">
        <el-date-picker
          v-model="selectedDay"
          :picker-options="pickerOptions"
          type="week"
          format="Week WW"
          placeholder="Seleccionar Semana"
        ></el-date-picker>
        <el-select
          width="150"
          v-model="workshopId"
          placeholder="Taller"
          v-if="multiWorkshop"
        >
          <el-option
            v-for="w in workshops"
            :key="w.id"
            :label="w.name"
            :value="w.id"
          ></el-option>
        </el-select>
        <el-button
          type="primary"
          icon="el-icon-search"
          @click="onSearch"
          :disabled="!selectedDay || !workshopId"
        ></el-button>
      </el-col>
      <el-col :span="4"></el-col>
    </el-row>

    <!-- TABLA -->
    <el-row class="br bl">
      <el-col :span="24">
        <el-table
          v-loading="loading"
          size="mini"
          :data="sales.data"
          style="width: 100%"
        >
          <el-table-column label="Día" header-align="center" align="center">
            <template slot-scope="scope">{{
              dayOfWeek(new Date(scope.row.created_at))
            }}</template>
          </el-table-column>
          <el-table-column label="Entrada" header-align="center" align="center">
            <template slot-scope="scope">{{
              fixDate(scope.row.created_at)
            }}</template>
          </el-table-column>
          <el-table-column label="Marca">
            <template slot-scope="scope">{{ scope.row.maker }}</template>
          </el-table-column>
          <el-table-column label="Modelo">
            <template slot-scope="scope">{{ scope.row.brand }}</template>
          </el-table-column>
          <el-table-column
            label="Año"
            width="50"
            header-align="center"
            align="center"
          >
            <template slot-scope="scope">{{ scope.row.year }}</template>
          </el-table-column>
          <el-table-column label="Color" header-align="center" align="center">
            <template slot-scope="scope">{{ scope.row.color }}</template>
          </el-table-column>
          <el-table-column label="Cliente" width="150">
            <template slot-scope="scope">{{ scope.row.client.name }}</template>
          </el-table-column>
          <el-table-column label="Teléfono">
            <template slot-scope="scope">{{
              getPhoneNumber(scope.row)
            }}</template>
          </el-table-column>
          <el-table-column label="Técnico">
            <template slot-scope="scope">{{ scope.row.user.name }}</template>
          </el-table-column>
          <el-table-column label="Diagnóstico" width="150">
            <template slot-scope="scope">{{
              fixText(scope.row.concept, 16)
            }}</template>
          </el-table-column>
          <el-table-column label="Precio" header-align="right" align="right">
            <template slot-scope="scope">{{ scope.row.total }}</template>
          </el-table-column>
          <el-table-column
            label="Autorizó"
            header-align="center"
            align="center"
          >
            <template slot-scope="scope">{{ autorizo(scope.row) }}</template>
          </el-table-column>
          <el-table-column label="Recibo" header-align="center" align="center">
            <template slot-scope="scope">
              <el-button
                size="small"
                icon="el-icon-document"
                type="text"
                :disabled="!image1Loaded || !image2Loaded"
                @click="showReceipt(scope.row)"
                >{{
                  scope.row.status == 2 ? `Recibo` : `Cotización`
                }}</el-button
              >
            </template>
          </el-table-column>
          <el-table-column
            label="Opciones"
            header-align="center"
            align="center"
          >
            <template slot-scope="scope">
              <el-button
                size="small"
                icon="el-icon-edit"
                type="text"
                v-if="scope.row.status == 2"
                @click="editItem(scope.row)"
                >Editar</el-button
              >
              <el-button
                size="small"
                icon="el-icon-check"
                type="text"
                v-if="scope.row.status == 0"
                @click="convertToReceipt(scope.row)"
                >Autorizar</el-button
              >
            </template>
          </el-table-column>
        </el-table>
      </el-col>
    </el-row>

    <!-- TOTALES -->
    <el-row class="br bl bb row-header">
      <el-col :span="4" style="margin-top: 7px">
        <h4>Total Autorizados</h4>
      </el-col>
      <el-col :span="20" class="row-headerb" align="end">
        <h4>${{ formatPrice(autorizados()) }}</h4>
      </el-col>
    </el-row>
    <el-row class="br bl bb row-header">
      <el-col :span="4" style="margin-top: 7px">
        <h4>Total No Autorizados</h4>
      </el-col>
      <el-col :span="20" class="row-headerb" align="end">
        <h4>${{ formatPrice(noAutorizados()) }}</h4>
      </el-col>
    </el-row>

    <!-- Imagenes Recibos -->
    <el-row
      type="flex"
      justify="end"
      style="opacity: 0; overflow: hidden; height: 50px"
    >
      <el-col :span="8">
        <img
          ref="quotation"
          @load="image1Loaded = true"
          src="/img/receipt.jpg"
          width="1150px"
        />
      </el-col>
      <el-col :span="8">
        <img
          ref="receipt"
          @load="image2Loaded = true"
          src="/img/receipt2.jpg"
          width="1150px"
        />
      </el-col>
      <el-col :span="8">
        <canvas ref="my-canvas"></canvas>
      </el-col>
    </el-row>
  </el-row>
</template>

<script>
export default {
  props: ["workshops", "myUser", "multiWorkshop"],
  mounted: function () {
    this.$root.$on("refreshTable", this.refreshTable);
    this.$root.$on("selectedFormat", this.selectedFormat);

    // busca por default en el taller donde trabaja este empleado
    if (this.myUser && this.myUser.length > 0) {
      if (!this.multiWorkshop) this.workshopId = this.myUser[0].workshop_id;
    }
  },
  methods: {
    getPhoneNumber(row) {
      if (row.phonenumber) return row.phonenumber;

      return row.client.phonenumber;
    },
    selectedFormat(format, name, parts) {
      let $this = this;
      if (name != "weekly") return;

      if (this.currentSale.parts != parts) {
        this.currentSale.parts = parts;

        axios
          .post("/api/sales/updateParts", {
            id: $this.currentSale.id,
            parts: parts,
          })
          .then(function (response) {
            $this.print(format);
          })
          .catch((error) => {});
      } else {
        $this.print(format);
      }
    },
    print(format) {
      // const COTIZACION = 0;
      // const PROCESO = 1;
      // const TERMINADO = 2;
      // const CANCELADO = 3;
      var canvas = this.$refs["my-canvas"];
      if (this.currentSale.status == 2) {
        this.createReceipt(
          this.currentSale,
          this.$refs["receipt"],
          canvas,
          format
        );
      } else {
        this.createQuotation(
          this.currentSale,
          this.$refs["quotation"],
          canvas,
          format
        );
      }
    },
    showReceipt(item) {
      this.currentSale = item;
      this.$refs.formatDialogWeekly.showDialog(this.currentSale.parts);
    },
    convertToReceipt(item) {
      // convierte cotizacion a recibo
      // this.$root.$emit("changeStatus", item, 2);
      this.$refs.weeklySaleEdit.changeStatus(item, 2);
    },
    editItem(item) {
      //this.$root.$emit("confirmSale", item, true);
      this.$refs.weeklySaleEdit.openDialog(item, true);
    },
    onSearch() {
      if (!this.selectedDay) {
        return;
      }

      this.searchSales();
    },
    searchSales() {
      var newDate = this.initDayOfWeekDate(this.selectedDay, 2);
      var start = this.toFixedFormat(newDate, "yyyyMMdd");

      this.loadTable(
        `/api/sales/searchByWeek?start=${start}&workshop=${this.workshopId}`
      );
    },
    autorizo(item) {
      return item.status == 2 ? `Si` : `No`;
    },
    autorizados() {
      var total = 0.0;
      if (this.sales.data) {
        var selection = this.sales.data.filter((i) => i.status == 2);
        selection.forEach((s) => (total += parseFloat(s.total)));
      }
      return total;
    },
    noAutorizados() {
      var total = 0.0;
      if (this.sales.data) {
        var selection = this.sales.data.filter((i) => i.status != 2);
        selection.forEach((s) => (total += parseFloat(s.total)));
      }
      return total;
    },
    fixDate(dt) {
      return this.toFixedTime(new Date(dt));
    },
    loadTable(url) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function (response) {
        $this.sales = response.data;
        $this.oldSales = JSON.parse(JSON.stringify(response.data));
        $this.loading = false;
      });
    },
    refreshTable() {
      this.searchSales();
    },
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
      selectedDay: null,
      search: "",
      pickerOptions: {
        firstDayOfWeek: 6,
      },
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

/* Ipad Pro */
@media only screen and (max-width: 1366px) {
  .content.el-main {
    padding: 5px;
  }
}
</style>
