<template>
  <el-row>
    <br />
    <el-row class="br bl bt bb row-header">
      <el-col :span="2">
        <h1 style="margin-top: 8px;">Semana</h1>
      </el-col>
      <el-col :span="18">
        <el-date-picker
          v-model="selectedDay"
          :picker-options="pickerOptions"
          type="week"
          format="Week WW"
          placeholder="Seleccionar Semana"
        ></el-date-picker>
        <el-select width="150" v-model="workshopId" placeholder="Taller" v-if="multiWorkshop">
          <el-option v-for="w in workshops" :key="w.id" :label="w.name" :value="w.id">{{w.name}}</el-option>
        </el-select>
        <el-button
          type="primary"
          icon="el-icon-search"
          @click="onSearch"
          :disabled="!selectedDay || !workshopId"
        ></el-button>
      </el-col>
      <el-col :span="4" align="end"></el-col>
    </el-row>

    <br />
    <!-- TOTALES -->
    <el-row>
      <!-- Columna 1 -->
      <el-col :span="3">
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Ingresos sin Iva</h1>
          </el-col>
        </el-row>
        <el-row class="br bb bl">
          <el-col
            :span="24"
            align="end"
            style="padding-right: 5px;"
          >${{ formatPrice(ingresosSinIva) }}</el-col>
        </el-row>
        <br />
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Ingresos con Iva</h1>
          </el-col>
        </el-row>
        <el-row class="br bb bl">
          <el-col
            :span="24"
            align="end"
            style="padding-right: 5px;"
          >${{ formatPrice(ingresosConIva) }}</el-col>
        </el-row>
        <br />
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Iva de Ingresos</h1>
          </el-col>
        </el-row>
        <el-row class="br bb bl">
          <el-col
            :span="24"
            align="end"
            style="padding-right: 5px;"
          >${{ formatPrice(totalIvaIngresos) }}</el-col>
        </el-row>
        <br />
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Créditos</h1>
          </el-col>
        </el-row>
        <el-row class="br bb bl">
          <el-col :span="24" align="end" style="padding-right: 5px;">${{ formatPrice(totalCreIva) }}</el-col>
        </el-row>
      </el-col>
      <!-- EFECTIVO -->
      <el-col :span="6" :offset="1">
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Ingresos Efectivo</h1>
          </el-col>
        </el-row>
        <el-row class="br bl">
          <el-col :span="24">
            <el-table size="mini" :data="dataEfectivo" style="width: 100%" v-loading="loading">
              <el-table-column align="center" label="Recibo" prop="folio"></el-table-column>
              <el-table-column align="center" label="Total" prop="total">
                <template slot-scope="scope">{{ formatPrice(scope.row.total) }}</template>
              </el-table-column>
            </el-table>
          </el-col>
        </el-row>
      </el-col>
      <!-- Columna 3 -->
      <el-col :span="13" :offset="1">
        <!-- EFECTIVO + IVA -->
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Ingresos facturados Efectivo</h1>
          </el-col>
        </el-row>
        <el-row class="br bl">
          <el-col :span="24">
            <el-table size="mini" :data="dataIvaEfectivo" style="width: 100%" v-loading="loading">
              <el-table-column align="center" label="Recibo" width="100" prop="folio"></el-table-column>
              <el-table-column align="center" label="Factura" width="100" prop="factura"></el-table-column>
              <el-table-column align="center" label="Efectivo" prop="efectivo"></el-table-column>
              <el-table-column align="right" label="Imp. sin Iva" prop="importe" width="100">
                <template slot-scope="scope">{{ formatPrice(scope.row.importe) }}</template>
              </el-table-column>
              <el-table-column align="right" label="Iva" prop="iva">
                <template slot-scope="scope">{{ formatPrice(scope.row.iva) }}</template>
              </el-table-column>
              <el-table-column align="center" label="Total" prop="total">
                <template slot-scope="scope">{{ formatPrice(scope.row.total) }}</template>
              </el-table-column>
              <el-table-column label="Opciones" header-align="center" align="center">
                <template slot-scope="scope">
                  <income-edit :selectedItem="scope.row"></income-edit>
                </template>
              </el-table-column>
            </el-table>
          </el-col>
        </el-row>
        <br />
        <!-- Tabla 2 -->
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1
              style="color:#909399"
            >Ingresos facturados T. Débito / T. Crédito / Transferencia / Cheque</h1>
          </el-col>
        </el-row>
        <el-row class="br bl">
          <el-col :span="24">
            <el-table
              size="mini"
              :data="dataIvaDebitoCreditoCheque"
              style="width: 100%"
              v-loading="loading"
            >
              <el-table-column align="center" label="Recibo" prop="folio"></el-table-column>
              <el-table-column align="center" label="Factura" prop="factura"></el-table-column>
              <el-table-column align="center" label="Fecha Pago" prop="fecha" width="140"></el-table-column>
              <el-table-column align="right" label="Imp. sin Iva" prop="importe" width="100">
                <template slot-scope="scope">{{ formatPrice(scope.row.importe) }}</template>
              </el-table-column>
              <el-table-column align="right" label="Iva" prop="iva">
                <template slot-scope="scope">{{ formatPrice(scope.row.iva) }}</template>
              </el-table-column>
              <el-table-column align="center" label="Total" prop="total">
                <template slot-scope="scope">{{ formatPrice(scope.row.total) }}</template>
              </el-table-column>
              <el-table-column label="Opciones" header-align="center" align="center">
                <template slot-scope="scope">
                  <income-edit :selectedItem="scope.row"></income-edit>
                </template>
              </el-table-column>
            </el-table>
          </el-col>
        </el-row>
        <br />
        <!-- Tabla 3 -->
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Ingresos facturados A Crédito</h1>
          </el-col>
        </el-row>
        <el-row class="br bl">
          <el-col :span="24">
            <el-table size="mini" :data="dataIvaCredito" style="width: 100%" v-loading="loading">
              <el-table-column align="center" label="Recibo" prop="folio"></el-table-column>
              <el-table-column align="center" label="Factura" prop="factura"></el-table-column>
              <el-table-column align="center" label="Fecha Pago" prop="fecha" width="100"></el-table-column>
              <el-table-column align="right" label="Imp. sin Iva" prop="importe" width="100">
                <template slot-scope="scope">{{ formatPrice(scope.row.importe) }}</template>
              </el-table-column>
              <el-table-column align="right" label="Iva" prop="iva">
                <template slot-scope="scope">{{ formatPrice(scope.row.iva) }}</template>
              </el-table-column>
              <el-table-column align="center" label="Total" prop="total">
                <template slot-scope="scope">{{ formatPrice(scope.row.total) }}</template>
              </el-table-column>
              <el-table-column align="center" label="Pagado" prop="pago"></el-table-column>
              <el-table-column label="Opciones" header-align="center" align="center" width="100">
                <template slot-scope="scope">
                  <income-edit :selectedItem="scope.row" :method="6" :isCredit="true"></income-edit>
                </template>
              </el-table-column>
            </el-table>
          </el-col>
        </el-row>
      </el-col>
    </el-row>
  </el-row>
</template>

<script>
export default {
  props: ["workshops", "myUser", "multiWorkshop"],
  mounted: function () {
    this.$root.$on("refreshIncome", this.onSearch);

    // busca por default en el taller donde trabaja este empleado
    if (this.myUser && this.myUser.length > 0) {
      if (!this.multiWorkshop) this.workshopId = this.myUser[0].workshop_id;
    }
  },
  methods: {
    loadTable(url) {
      const $this = this;
      $this.loading = true;
      axios.get(url).then(function (response) {
        $this.weekData = response.data.d;
        $this.loading = false;

        // reset
        $this.ingresosSinIva = 0;
        $this.ingresosConIva = 0;
        $this.totalSinIva = 0;
        $this.totalEfeIva = 0;
        $this.totalCreIva = 0;
        $this.totalIvaIngresos = 0;
        $this.dataEfectivo = [];
        $this.dataIvaEfectivo = [];
        $this.dataIvaDebitoCreditoCheque = [];
        $this.dataIvaCredito = [];

        // sales
        var errors = [];
        $this.weekData.data.forEach((sale) => {
          $this.evaluateSale(sale, errors);
        });

        if (errors.length > 0) {
          var ingresos = "";
          errors.forEach((ing) => (ingresos += ing + " "));
          $this.$alert(
            `Algunos ingresos no tienen IVA. Favor de revisar los recibos: ${ingresos}`,
            "Ingresos sin IVA",
            {
              confirmButtonText: "OK",
              type: "warning",
            }
          );
          return;
        }
      });
    },
    evaluateSale(sale, errors) {
      // 1 Efectivo
      // *2 Tarjeta de Crédito
      // *3 Tarjeta de Débito
      // *4 Cheque
      // *5 Transferencia
      // 6 Crédito

      // Con Iva
      if (sale.tax == 1) {
        let folio = "REC" + this.pad(sale.id, 5);
        let importe = parseFloat(sale.total);
        let iva = importe * 0.08;
        let total = importe + iva;

        // sin contar creditos
        if (sale.method != 6) {
          this.ingresosSinIva += importe;
          this.ingresosConIva += total;

          // total de iva
          this.totalIvaIngresos += iva;
        }

        switch (sale.method) {
          case 2:
          case 3:
          case 4:
          case 5:
            // debito/credito
            this.totalEfeIva += total;
            this.dataIvaDebitoCreditoCheque.push({
              folio: folio,
              factura: sale.bill,
              fecha: sale.paid_date || sale.done_on,
              importe: importe,
              iva: iva,
              total: total,
            });
            break;
          case 6:
            // credito
            this.totalCreIva += importe;
            this.dataIvaCredito.push({
              folio: folio,
              factura: sale.bill,
              fecha: sale.paid_date,
              importe: importe,
              iva: iva,
              total: total,
              pago: "NO",
            });
            break;
          default:
            // efectivo
            this.totalEfeIva += total;
            this.dataIvaEfectivo.push({
              folio: folio,
              factura: sale.bill,
              efectivo: "EFECTIVO",
              importe: importe,
              iva: iva,
              total: total,
            });
            break;
        }
      } else {
        // Sin Iva
        let folio = "REC" + this.pad(sale.id, 5);

        // Sin contar creditos
        if (sale.method != 6) {
          let importe = parseFloat(sale.total);
          this.ingresosSinIva += importe;
          this.ingresosConIva += importe;
        }

        switch (sale.method) {
          case 2:
          case 3:
          case 4:
          case 5:
            // debito/credito (Warn: NO IVA!)
            this.totalEfeIva += parseFloat(sale.total);
            this.dataIvaDebitoCreditoCheque.push({
              folio: folio,
              factura: "N/A",
              fecha: "N/A",
              importe: sale.total,
              iva: 0,
              total: sale.total,
            });
            console.warn(`Recibo sin iva ${folio}`);
            errors.push(folio);
            break;
          case 6:
            // credito (Warn: NO IVA!)
            this.totalCreIva += parseFloat(sale.total);
            this.dataIvaCredito.push({
              folio: folio,
              factura: "N/A",
              efectivo: "N/A",
              importe: sale.total,
              iva: 0,
              total: sale.total,
              pago: "NO",
            });
            console.warn(`Recibo sin iva ${folio}`);
            errors.push(folio);
            break;
          default:
            this.dataEfectivo.push({ folio: folio, total: sale.total });
            this.totalSinIva += parseFloat(sale.total);
            break;
        }
      }
    },
    onSearch() {
      if (!this.selectedDay) {
        this.$alert("Favor de seleccionar una semana.", "Semana no válida", {
          confirmButtonText: "OK",
          type: "warning",
        });
        return;
      }

      var newDate = this.initDayOfWeekDate(this.selectedDay, 2);
      this.prevDay = newDate;
      var start = `${this.toFixedFormat(newDate, "yyyy-MM-dd")} 00:00:00`;

      if (!this.workshopId) {
        this.$alert("Favor de seleccionar un taller.", "Taller no válido", {
          confirmButtonText: "OK",
          type: "warning",
        });
        return;
      }

      this.loadTable(
        `/api/sales/searchReceiptByWeekAndCredits?start=${start}&workshop=${this.workshopId}`
      );
    },
  },
  data() {
    return {
      ingresosSinIva: 0,
      ingresosConIva: 0,
      totalSinIva: 0,
      totalEfeIva: 0,
      totalCreIva: 0,
      totalIvaIngresos: 0,
      workshopId: "",
      prevDay: null,
      selectedDay: null,
      search: "",
      loading: false,
      dataEfectivo: [],
      dataIvaEfectivo: [],
      dataIvaDebitoCreditoCheque: [],
      dataIvaCredito: [],
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
</style>
