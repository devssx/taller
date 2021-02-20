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
      <el-col :span="4" align="end">
        <el-button
          v-show="isReadOnly"
          @click="setEditMode(false)"
          :disabled="employees.length==0"
          type="primary"
          icon="el-icon-edit"
        >Editar</el-button>
        <el-button
          v-show="!isReadOnly"
          @click="setEditMode(true)"
          type="primary"
          icon="el-icon-check"
        >Guardar</el-button>
      </el-col>
    </el-row>

    <el-row class="br bl">
      <!-- Lista de empleados -->
      <el-col :span="6">
        <el-row>
          <el-col>
            <el-card class="box-card" shadow="never">
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
                  <span>{{employee.name}}</span>
                </el-menu-item>
              </el-menu>
            </el-card>
          </el-col>
        </el-row>
      </el-col>

      <!-- Header -->
      <el-col :span="18">
        <el-row class="br bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Resumen Nómina Técnico</h1>
          </el-col>
        </el-row>
        <el-row class="br bt bl row-header">
          <el-col :span="4" style="margin-top: 7px;">
            <h4>Nombre del Técnico:</h4>
          </el-col>
          <el-col :span="20" class="row-headerb">{{selectedEmployee}}</el-col>
        </el-row>
        <el-row class="br bb bl row-header">
          <el-col :span="4" style="margin-top: 7px;">
            <h4>Observación:</h4>
          </el-col>
          <el-col :span="20" class="row-headerb">
            <el-input
              type="textarea"
              :autosize="{ minRows: 2, maxRows: 4}"
              placeholder="Comentarios"
              :readonly="isReadOnly"
              v-model="comment"
            ></el-input>
          </el-col>
        </el-row>
      </el-col>
    </el-row>

    <br />
    <el-row class="br bt bl row-header">
      <el-col :span="24" align="center">
        <h1 style="color:#909399">A/C Automotríz</h1>
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="24" align="center">
        <payroll-item :items="tableACData"></payroll-item>
      </el-col>
    </el-row>

    <!-- TABLA -->
    <!-- <el-row v-if="false" class="br bl">
      <el-col :span="24">
        <el-table border size="mini" :data="tableACData" style="width: 100%" v-loading="loading">
          <el-table-column
            label="Sábado"
            width="100"
            header-align="center"
            align="right"
            prop="saturday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.saturday) }}</template>
          </el-table-column>
          <el-table-column
            label="Lunes"
            width="100"
            header-align="center"
            align="right"
            prop="monday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.monday) }}</template>
          </el-table-column>
          <el-table-column
            label="Martes"
            width="100"
            header-align="center"
            align="right"
            prop="tuesday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.tuesday) }}</template>
          </el-table-column>
          <el-table-column
            label="Miércoles"
            width="100"
            header-align="center"
            align="right"
            prop="wednesday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.wednesday) }}</template>
          </el-table-column>
          <el-table-column
            label="Jueves"
            width="100"
            header-align="center"
            align="right"
            prop="thursday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.thursday) }}</template>
          </el-table-column>
          <el-table-column
            label="Viernes"
            width="100"
            header-align="center"
            align="right"
            prop="friday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.friday) }}</template>
          </el-table-column>

          <el-table-column
            label="Total Semana"
            header-align="center"
            align="center"
            prop="totalWeek"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.totalWeek) }}</template>
          </el-table-column>
          <el-table-column
            label="% A/C"
            width="100"
            header-align="center"
            align="right"
            prop="percentCommission"
          ></el-table-column>
          <el-table-column
            label="Total Neto Comisiones"
            header-align="center"
            align="right"
            prop="commission"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.commission) }}</template>
          </el-table-column>
          <el-table-column
            label="Sueldo"
            width="100"
            header-align="center"
            align="right"
            prop="sueldo"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.sueldo) }}</template>
          </el-table-column>
          <el-table-column
            label="Sub Total"
            width="100"
            header-align="center"
            align="right"
            prop="subtotal"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.subtotal) }}</template>
          </el-table-column>
          <el-table-column
            label="Descuentos"
            width="100"
            header-align="center"
            align="right"
            prop="discounts"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.discounts) }}</template>
          </el-table-column>
          <el-table-column label="Total" header-align="center" align="right" prop="total">
            <template slot-scope="scope">{{ formatPrice(scope.row.total) }}</template>
          </el-table-column>

          !-- <el-table-column align="center" label="Modificar" width="147">
            <template slot-scope="scope">
              <el-button type="text" size="mini">Editar</el-button>
            </template>
          </el-table-column>--
        </el-table>
      </el-col>
    </el-row>-->

    <br />
    <el-row class="br bt bl row-header">
      <el-col :span="24" align="center">
        <h1 style="color:#909399">Mecánica Automotríz</h1>
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="24" align="center">
        <payroll-item :items="tableMecData"></payroll-item>
      </el-col>
    </el-row>
    <br />

    <!-- TABLA -->
    <!-- <el-row v-if="false" class="br bl">
      <el-col :span="24">
        <el-table border size="mini" :data="tableMecData" style="width: 100%" v-loading="loading">
          <el-table-column
            label="Sábado"
            width="100"
            header-align="center"
            align="right"
            prop="saturday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.saturday) }}</template>
          </el-table-column>
          <el-table-column
            label="Lunes"
            width="100"
            header-align="center"
            align="right"
            prop="monday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.monday) }}</template>
          </el-table-column>
          <el-table-column
            label="Martes"
            width="100"
            header-align="center"
            align="right"
            prop="tuesday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.tuesday) }}</template>
          </el-table-column>
          <el-table-column
            label="Miércoles"
            width="100"
            header-align="center"
            align="right"
            prop="wednesday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.wednesday) }}</template>
          </el-table-column>
          <el-table-column
            label="Jueves"
            width="100"
            header-align="center"
            align="right"
            prop="thursday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.thursday) }}</template>
          </el-table-column>
          <el-table-column
            label="Viernes"
            width="100"
            header-align="center"
            align="right"
            prop="friday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.friday) }}</template>
          </el-table-column>

          <el-table-column
            label="Total Semana"
            header-align="center"
            align="center"
            prop="totalWeek"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.totalWeek) }}</template>
          </el-table-column>
          <el-table-column
            label="% Mecánica"
            width="100"
            header-align="center"
            align="right"
            prop="percentCommission"
          ></el-table-column>
          <el-table-column
            label="Total Neto Comisiones"
            header-align="center"
            align="right"
            prop="commission"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.commission) }}</template>
          </el-table-column>
          <el-table-column
            label="Sueldo"
            width="100"
            header-align="center"
            align="right"
            prop="sueldo"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.sueldo) }}</template>
          </el-table-column>
          <el-table-column
            label="Sub Total"
            width="100"
            header-align="center"
            align="right"
            prop="subtotal"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.subtotal) }}</template>
          </el-table-column>
          <el-table-column
            label="Descuentos"
            width="100"
            header-align="center"
            align="right"
            prop="discounts"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.discounts) }}</template>
          </el-table-column>
          <el-table-column label="Total" header-align="center" align="right" prop="total">
            <template slot-scope="scope">{{ formatPrice(scope.row.total) }}</template>
          </el-table-column>

          -- <el-table-column align="center" label="Modificar" width="147">
            <template slot-scope="scope">
              <el-button type="text" size="mini">Editar</el-button>
            </template>
          </el-table-column>--
        </el-table>
      </el-col>
    </el-row>-->

    <br />
    <el-row class="br bt bl row-header">
      <el-col :span="24" align="center">
        <h1 style="color:#909399">Auto Eléctrico Automotríz</h1>
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="24" align="center">
        <payroll-item :items="tableElecData"></payroll-item>
      </el-col>
    </el-row>

    <!-- TABLA -->
    <!-- <el-row v-if="false" class="br bl">
      <el-col :span="24">
        <el-table border size="mini" :data="tableElecData" style="width: 100%" v-loading="loading">
          <el-table-column
            label="Sábado"
            width="100"
            header-align="center"
            align="right"
            prop="saturday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.saturday) }}</template>
          </el-table-column>
          <el-table-column
            label="Lunes"
            width="100"
            header-align="center"
            align="right"
            prop="monday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.monday) }}</template>
          </el-table-column>
          <el-table-column
            label="Martes"
            width="100"
            header-align="center"
            align="right"
            prop="tuesday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.tuesday) }}</template>
          </el-table-column>
          <el-table-column
            label="Miércoles"
            width="100"
            header-align="center"
            align="right"
            prop="wednesday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.wednesday) }}</template>
          </el-table-column>
          <el-table-column
            label="Jueves"
            width="100"
            header-align="center"
            align="right"
            prop="thursday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.thursday) }}</template>
          </el-table-column>
          <el-table-column
            label="Viernes"
            width="100"
            header-align="center"
            align="right"
            prop="friday"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.friday) }}</template>
          </el-table-column>

          <el-table-column
            label="Total Semana"
            header-align="center"
            align="center"
            prop="totalWeek"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.totalWeek) }}</template>
          </el-table-column>
          <el-table-column
            label="% Eléctrico"
            width="100"
            header-align="center"
            align="right"
            prop="percentCommission"
          ></el-table-column>
          <el-table-column
            label="Total Neto Comisiones"
            header-align="center"
            align="right"
            prop="commission"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.commission) }}</template>
          </el-table-column>
          <el-table-column
            label="Sueldo"
            width="100"
            header-align="center"
            align="right"
            prop="sueldo"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.sueldo) }}</template>
          </el-table-column>
          <el-table-column
            label="Sub Total"
            width="100"
            header-align="center"
            align="right"
            prop="subtotal"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.subtotal) }}</template>
          </el-table-column>
          <el-table-column
            label="Descuentos"
            width="100"
            header-align="center"
            align="right"
            prop="discounts"
          >
            <template slot-scope="scope">{{ formatPrice(scope.row.discounts) }}</template>
          </el-table-column>
          <el-table-column label="Total" header-align="center" align="right" prop="total">
            <template slot-scope="scope">{{ formatPrice(scope.row.total) }}</template>
          </el-table-column>

          !-- <el-table-column align="center" label="Modificar" width="147">
            <template slot-scope="scope">
              <el-button type="text" size="mini">Editar</el-button>
            </template>
          </el-table-column>--
        </el-table>
      </el-col>
    </el-row>-->

    <br />
    <!-- TOTALES -->
    <el-row class="br bt bl bb row-header">
      <el-col :span="4" style="margin-top: 7px;">
        <h4>Sueldo:</h4>
      </el-col>
      <el-col :span="20" class="row-headerb" align="end">
        <h4>${{formatPrice(salary)}}</h4>
      </el-col>
    </el-row>
    <el-row class="br bl bb row-header">
      <el-col :span="4" style="margin-top: 7px;">
        <h4>Comisiones:</h4>
      </el-col>
      <el-col :span="20" class="row-headerb" align="end">
        <h4>${{formatPrice(comissions)}}</h4>
      </el-col>
    </el-row>
    <el-row class="br bl bb row-header">
      <el-col :span="4" style="margin-top: 7px;">
        <h4>Descuentos:</h4>
      </el-col>
      <el-col :span="20" class="row-headerb" align="end">
        <h4>${{formatPrice(discounts)}}</h4>
      </el-col>
    </el-row>
    <el-row class="br bl bb row-header">
      <el-col :span="4" style="margin-top: 7px;">
        <h4>Total:</h4>
      </el-col>
      <el-col :span="20" class="row-headerb" align="end">
        <h4>${{formatPrice(total)}}</h4>
      </el-col>
    </el-row>
  </el-row>
</template>

<script>
export default {
  props: ["workshops", "myUser", "multiWorkshop"],
  mounted: function () {
    this.$root.$on("refreshTotal", this.refreshTotal);

    // busca por default en el taller donde trabaja este empleado
    if (this.myUser && this.myUser.length > 0) {
      if (!this.multiWorkshop) this.workshopId = this.myUser[0].workshop_id;
    }
  },
  methods: {
    setEditMode(readOnly, dontSave) {
      this.isReadOnly = readOnly;
      this.tableACData[0].isReadOnly = readOnly;
      this.tableMecData[0].isReadOnly = readOnly;
      this.tableElecData[0].isReadOnly = readOnly;
      if (!dontSave && readOnly) {
        this.saveWeek(
          this.userID,
          this.tableACData[0],
          this.tableMecData[0],
          this.tableElecData[0]
        );

        if (!this.workshopId) {
          this.$alert("Favor de seleccionar un taller.", "Taller no válido", {
            confirmButtonText: "OK",
            type: "warning",
          });
          return;
        }

        let week = this.toFixedFormat(this.prevDay, "yyyyMMdd");
        this.loadPayroll(
          `/api/payroll?week=${week}&workshop=${this.workshopId}`,
          false
        );
      }
    },
    saveWeek(userID, weekAc, weekMec, weekEle) {
      const $this = this;
      $this.loading = true;

      // weekID
      let week = this.toFixedFormat($this.prevDay, "yyyyMMdd");

      // Necesario para reportes
      let end = this.endPeriodo($this.prevDay);
      let weekNumb = this.getWeekOfDate(end);
      let startFormat = this.toFixedFormat($this.prevDay, "yyyy-MM-dd");
      let endFormat = this.toFixedFormat(end, "yyyy-MM-dd");

      if (!this.workshopId) {
        this.$alert("Favor de seleccionar un taller.", "Taller no válido", {
          confirmButtonText: "OK",
          type: "warning",
        });
        return;
      }

      this.confirmar(
        `Esta acción guadará la nómina del empleado: ${this.selectedEmployee} ¿Desea continuar?`,
        () => {
          // save totals
          axios
            .post("/api/payroll/save", {
              // Periodo Info
              number: weekNumb,
              start: startFormat,
              end: endFormat,

              week: week,
              workshop: $this.workshopId,
              userID: userID,
              comment: $this.comment,
              // AC
              totalWeekAc: weekAc.totalWeek,
              commissionAc: weekAc.percentCommission,
              discountsAc: weekAc.discounts,
              salaryAc: weekAc.sueldo,
              totalAc: weekAc.total,
              // Mecanico
              totalWeekMec: weekMec.totalWeek,
              commissionMec: weekMec.percentCommission,
              discountsMec: weekMec.discounts,
              salaryMec: weekMec.sueldo,
              totalMec: weekMec.total,
              // Electrico
              totalWeekEle: weekEle.totalWeek,
              commissionEle: weekEle.percentCommission,
              discountsEle: weekEle.discounts,
              salaryEle: weekEle.sueldo,
              totalEle: weekEle.total,
            })
            .then(function (response) {
              $this.$message({
                message: "El registro fué editado correctamente.",
                type: "success",
              });

              $this.loading = false;
              $this.onSearch();
            })
            .catch((error) => {
              if (error.response.data.errors) {
                var errors = error.response.data.errors;
                $this.$alert(errors[Object.keys(errors)[0]][0], "Error", {
                  confirmButtonText: "OK",
                  type: "error",
                });
              }
              $this.loading = false;
            });
        }
      );
    },
    handleSelect(key, keyPath) {
      this.activeIndex = key;
      this.selectedEmployee = this.employees[key].name;
      this.userID = this.employees[key].id;
      let name = this.employees[key].name;

      this.comment = "";
      if (this.weekComments.length > 0) {
        if (
          this.weekComments.filter((e) => e.user_id == this.userID).length > 0
        )
          this.comment = this.weekComments.filter(
            (e) => e.user_id == this.userID
          )[0].comment;
      }

      // commission percentages
      let com1 = 0.07;
      let com2 = 0.07;
      let com3 = 0.07;
      let salary1 = 0.0;
      let salary2 = 0.0;
      let salary3 = 0.0;
      let discount1 = 0.0;
      let discount2 = 0.0;
      let discount3 = 0.0;

      // buscar week y user id
      const myWeek = this.weekPayroll.filter((p) => p.user_id == this.userID);
      myWeek.forEach((dato) => {
        if (dato.type == "1") {
          com1 = parseFloat(dato.comission);
          salary1 = parseFloat(dato.salary);
          discount1 = parseFloat(dato.discount);
        }
        if (dato.type == "2") {
          com2 = parseFloat(dato.comission);
          salary2 = parseFloat(dato.salary);
          discount2 = parseFloat(dato.discount);
        }
        if (dato.type == "3") {
          com3 = parseFloat(dato.comission);
          salary3 = parseFloat(dato.salary);
          discount3 = parseFloat(dato.discount);
        }
      });

      this.tableACData = this.getEmployeeData(
        name,
        1,
        com1,
        salary1,
        discount1
      );
      this.tableMecData = this.getEmployeeData(
        name,
        2,
        com2,
        salary2,
        discount2
      );
      this.tableElecData = this.getEmployeeData(
        name,
        3,
        com3,
        salary3,
        discount3
      );
      this.isReadOnly = true;
      this.setEditMode(true, true);
      this.refreshTotal();
    },
    refreshTotal() {
      this.computeTotal(
        this.tableACData[0],
        this.tableMecData[0],
        this.tableElecData[0]
      );
    },
    eliminarRegistro(id) {},
    fixNumber(n) {
      return n < 10 ? "0" + n : n;
    },
    setSales(data) {
      const $this = this;
      $this.weekData = data.d;

      // reset
      $this.salary = 0;
      $this.comissions = 0;
      $this.discounts = 0;
      $this.total = 0;

      // (lista de empleados)
      $this.employees = [];
      $this.tableACData = [];
      $this.tableMecData = [];
      $this.tableElecData = [];
      $this.weekData.data.forEach((sale) => {
        var name = sale.user.name;
        if ($this.employees.filter((e) => e.name == name).length == 0)
          $this.employees.push(sale.user);
      });

      // select first
      if ($this.employees.length > 0) $this.handleSelect("0");
    },
    loadPayroll(url, loadSales) {
      const $this = this;
      $this.weekPayroll = [];
      $this.weekComments = [];

      axios.get(url).then(function (response) {
        $this.weekPayroll = response.data.payroll;
        $this.weekComments = response.data.comments;

        if (loadSales) {
          let sales = response.data.sales;
          $this.setSales(sales);
        }
      });
    },
    // Carga recibos tipo: 1: A/C, 2: Mecanica, 3: Electrico
    getEmployeeData(user, serviceType, comision, sueldo, discount) {
      let week = {
        salesA: [],
        salesB: [],
        salesC: [],
        salesD: [],
        salesE: [],
        salesF: [],
        saturday: 0,
        monday: 0,
        tuesday: 0,
        wednesday: 0,
        thursday: 0,
        friday: 0,
        totalWeek: 0,
        percentCommission: comision,
        commission: 0,
        sueldo: sueldo,
        subtotal: 0,
        discounts: discount,
        total: 0,
        isReadOnly: true,
      };

      // Carga totales de recibos tipo serviceType del empleado
      let userSales = [];
      this.weekData.data.forEach((sale) => {
        if (sale.service_type == serviceType && sale.user.name == user) {
          userSales.push(sale);
          switch (this.dayOfWeek(sale.done_on)) {
            case "Sábado": // sabado es menor a la fecha del dia lunes
              week.saturday += parseFloat(sale.total);
              week.salesA.push(sale);
              break;
            case "Domingo": //  no deberia haber nada en domingo...
            case "Lunes":
              week.monday += parseFloat(sale.total);
              week.salesB.push(sale);
              break;
            case "Martes":
              week.tuesday += parseFloat(sale.total);
              week.salesC.push(sale);
              break;
            case "Miércoles":
              week.wednesday += parseFloat(sale.total);
              week.salesD.push(sale);
              break;
            case "Jueves":
              week.thursday += parseFloat(sale.total);
              week.salesE.push(sale);
              break;
            case "Viernes":
              week.friday += parseFloat(sale.total);
              week.salesF.push(sale);
              break;
          }
        }
      });

      week.totalWeek =
        week.saturday +
        week.monday +
        week.tuesday +
        week.wednesday +
        week.thursday +
        week.friday;

      // comision
      week.commission = week.totalWeek * week.percentCommission;
      week.subtotal = week.commission + week.sueldo;
      week.total = week.subtotal - week.discounts;

      return [week];
    },
    onSearch() {
      this.selectedEmployee = "N/A";
      this.comment = "";
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

      // weekID
      let week = this.toFixedFormat(this.prevDay, "yyyyMMdd");
      this.loadPayroll(
        `/api/payroll?week=${week}&workshop=${this.workshopId}&start=${start}`,
        true
      );
    },
    computeTotal(tableAc, tableMec, tableElec) {
      this.salary =
        parseFloat(tableAc.sueldo) +
        parseFloat(tableMec.sueldo) +
        parseFloat(tableElec.sueldo);
      this.comissions =
        parseFloat(tableAc.commission) +
        parseFloat(tableMec.commission) +
        parseFloat(tableElec.commission);
      this.discounts =
        parseFloat(tableAc.discounts) +
        parseFloat(tableMec.discounts) +
        parseFloat(tableElec.discounts);
      this.total =
        parseFloat(tableAc.total) +
        parseFloat(tableMec.total) +
        parseFloat(tableElec.total);

      this.total = this.total.toFixed(2);
    },
  },
  data() {
    return {
      pickerOptions: {
        // disabledDate(time) {
        //   return time.getTime() > Date.now();
        // },
        firstDayOfWeek: 6,
      },

      workshopId: "",
      isReadOnly: true,
      total: 0,
      discounts: 0,
      comissions: 0,
      salary: 0,
      selectedEmployee: "N/A",
      comment: "",
      userID: -1,
      activeIndex: "0",
      employees: [],
      showDialog: false,
      weekData: [],
      weekPayroll: [],
      weekComments: [],
      selectedDay: null,
      prevDay: null,
      search: "",
      loading: false,
      tableACData: [], // Servicio A/C
      tableMecData: [], // Servicio Mecanico
      tableElecData: [], // servicio Electrico
    };
  },
};
</script>
<style>
.box-card {
  min-height: 90px;
  max-height: 102px;
  overflow-y: auto;
}

.el-card__body {
  padding: 0 !important;
}

.box-card .el-menu-item {
  height: 24px !important;
  line-height: 24px !important;
}

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
