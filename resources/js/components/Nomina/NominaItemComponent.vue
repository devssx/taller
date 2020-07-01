<template>
  <div>
    <el-row class="row-header br bl bb bt">
      <el-col :span="2" align="center" class="header">Sábado</el-col>
      <el-col :span="2" align="center" class="header">Lunes</el-col>
      <el-col :span="2" align="center" class="header">Martes</el-col>
      <el-col :span="2" align="center" class="header">Miércoles</el-col>
      <el-col :span="2" align="center" class="header">Jueves</el-col>
      <el-col :span="2" align="center" class="header">Viernes</el-col>
      <el-col :span="2" align="center" class="header">Total</el-col>
      <el-col :span="1" align="center" class="header">%</el-col>
      <el-col :span="2" align="center" class="header">Comisión</el-col>
      <el-col :span="2" align="center" class="header">Sueldo</el-col>
      <el-col :span="2" align="center" class="header">Subtotal</el-col>
      <el-col :span="1" align="center" class="header">Desc.</el-col>
      <el-col :span="2" align="center" class="header">Total</el-col>
    </el-row>
    <el-row v-if="items.length == 0">
      <el-col :span="24" class="bl br bb hr text-mini empty-data" align="center">Sin Datos</el-col>
    </el-row>
    <el-row>
      <el-row v-for="(item, index) in items" v-bind:key="index">
        <el-col :span="2" align="end" class="bl br bb hr">
          <el-popover v-show="item.salesA.length > 0" placement="right" width="400" trigger="click">
            <el-table size="mini" :data="item.salesA">
              <el-table-column width="70" property="year" label="Año"></el-table-column>
              <el-table-column property="brand" label="Modelo"></el-table-column>
              <el-table-column width="100" property="total" label="Total" align="end"></el-table-column>
            </el-table>
            <label slot="reference" class="pr-10 text-mini inf">{{ formatPrice(item.saturday) }}</label>
          </el-popover>
          <label
            v-show="item.salesA.length == 0"
            class="pr-10 text-mini"
          >{{ formatPrice(item.saturday) }}</label>
        </el-col>
        <el-col :span="2" align="end" class="br bb hr">
          <el-popover v-show="item.salesB.length > 0" placement="right" width="350" trigger="click">
            <el-table size="mini" :data="item.salesB">
              <el-table-column width="70" property="year" label="Año"></el-table-column>
              <el-table-column property="brand" label="Modelo"></el-table-column>
              <el-table-column width="100" property="total" label="Total" align="end"></el-table-column>
            </el-table>
            <label slot="reference" class="pr-10 text-mini inf">{{ formatPrice(item.monday) }}</label>
          </el-popover>
          <label
            v-show="item.salesB.length == 0"
            class="pr-10 text-mini"
          >{{ formatPrice(item.monday) }}</label>
        </el-col>
        <el-col :span="2" align="end" class="br bb hr">
          <el-popover v-show="item.salesC.length > 0" placement="right" width="350" trigger="click">
            <el-table size="mini" :data="item.salesC">
              <el-table-column width="70" property="year" label="Año"></el-table-column>
              <el-table-column property="brand" label="Modelo"></el-table-column>
              <el-table-column width="100" property="total" label="Total" align="end"></el-table-column>
            </el-table>
            <label slot="reference" class="pr-10 text-mini inf">{{ formatPrice(item.tuesday) }}</label>
          </el-popover>
          <label
            v-show="item.salesC.length == 0"
            class="pr-10 text-mini"
          >{{ formatPrice(item.tuesday) }}</label>
        </el-col>
        <el-col :span="2" align="end" class="br bb hr">
          <el-popover v-show="item.salesD.length > 0" placement="right" width="350" trigger="click">
            <el-table size="mini" :data="item.salesD">
              <el-table-column width="70" property="year" label="Año"></el-table-column>
              <el-table-column property="brand" label="Modelo"></el-table-column>
              <el-table-column width="100" property="total" label="Total" align="end"></el-table-column>
            </el-table>
            <label slot="reference" class="pr-10 text-mini inf">{{ formatPrice(item.wednesday) }}</label>
          </el-popover>
          <label
            v-show="item.salesD.length == 0"
            class="pr-10 text-mini"
          >{{ formatPrice(item.wednesday) }}</label>
        </el-col>
        <el-col :span="2" align="end" class="br bb hr">
          <el-popover v-show="item.salesE.length > 0" placement="right" width="350" trigger="click">
            <el-table size="mini" :data="item.salesE">
              <el-table-column width="70" property="year" label="Año"></el-table-column>
              <el-table-column property="brand" label="Modelo"></el-table-column>
              <el-table-column width="100" property="total" label="Total" align="end"></el-table-column>
            </el-table>
            <label slot="reference" class="pr-10 text-mini inf">{{ formatPrice(item.thursday) }}</label>
          </el-popover>
          <label
            v-show="item.salesE.length == 0"
            class="pr-10 text-mini"
          >{{ formatPrice(item.thursday) }}</label>
        </el-col>
        <el-col :span="2" align="end" class="br bb hr">
          <el-popover v-show="item.salesF.length > 0" placement="right" width="350" trigger="click">
            <el-table size="mini" :data="item.salesF">
              <el-table-column width="70" property="year" label="Año"></el-table-column>
              <el-table-column property="brand" label="Modelo"></el-table-column>
              <el-table-column width="100" property="total" label="Total" align="end"></el-table-column>
            </el-table>
            <label slot="reference" class="pr-10 text-mini inf">{{ formatPrice(item.friday) }}</label>
          </el-popover>
          <label
            v-show="item.salesF.length == 0"
            class="pr-10 text-mini"
          >{{ formatPrice(item.friday) }}</label>
        </el-col>

        <el-col :span="2" align="end" class="br bb hr">
          <label class="pr-10 text-mini">{{ formatPrice(item.totalWeek) }}</label>
        </el-col>
        <el-col :span="1" align="end" class="br bb hr">
          <!-- % Comision -->
          <label
            v-show="item.isReadOnly"
            class="pr-10 text-mini"
          >{{ formatPrice(item.percentCommission) }}</label>
          <el-input
            @change="onChangeComision(item)"
            class="mycustom"
            size="mini"
            v-show="!item.isReadOnly"
            v-model="item.percentCommission"
          ></el-input>
        </el-col>
        <el-col :span="2" align="end" class="br bb hr">
          <label class="pr-10 text-mini">{{ formatPrice(item.commission) }}</label>
        </el-col>
        <el-col :span="2" align="end" class="br bb hr">
          <!-- Salary -->
          <label v-show="item.isReadOnly" class="pr-10 text-mini">{{ formatPrice(item.sueldo) }}</label>
          <el-input
            @change="onChangeSalary(item)"
            class="mycustom"
            size="mini"
            v-show="!item.isReadOnly"
            v-model="item.sueldo"
          ></el-input>
        </el-col>
        <el-col :span="2" align="end" class="br bb hr">
          <label class="pr-10 text-mini">{{ formatPrice(item.subtotal) }}</label>
        </el-col>
        <el-col :span="1" align="end" class="br bb hr">
          <!-- Editable Discounts -->
          <label v-show="item.isReadOnly" class="pr-10 text-mini">{{ formatPrice(item.discounts) }}</label>
          <el-input
            @change="onChangeDiscount(item)"
            class="mycustom"
            size="mini"
            v-show="!item.isReadOnly"
            v-model="item.discounts"
          ></el-input>
        </el-col>
        <el-col :span="2" align="end" class="br bb hr">
          <label class="pr-10 text-mini">{{ formatPrice(item.total) }}</label>
        </el-col>
      </el-row>
    </el-row>
  </div>
</template>

<script>
export default {
  props: ["items"],
  mounted: function() {},
  methods: {
    onChangeDiscount(item) {
      if (isNaN(item.discounts) || !item.discounts) item.discounts = 0;
      this.computeTotal(item);
    },
    onChangeComision(item) {
      if (isNaN(item.percentCommission) || !item.percentCommission)
        item.percentCommission = 0;
      this.computeTotal(item);
    },
    onChangeSalary(item) {
      if (isNaN(item.sueldo) || !item.sueldo) item.sueldo = 0;
      this.computeTotal(item);
    },
    computeTotal(item) {
      item.commission = parseFloat(item.totalWeek) * parseFloat(item.percentCommission);
      item.subtotal = parseFloat(item.commission) + parseFloat(item.sueldo);
      item.total = parseFloat(item.subtotal) - parseFloat(item.discounts);
      this.$root.$emit("refreshTotal");
    }
  }
};
</script>

<style lang="scss">
.pr-10 {
  margin-right: 10px;
}

.pl-10 {
  margin-left: 10px;
}

.text-mini {
  margin-top: 5px;
  color: #606266;
  font-size: 12px;
}

.empty-data {
  color: #909399;
  padding: 5px;
}

.hr {
  height: 28px;
}

.mycustom {
  border: 0;
}

.mycustom .el-input__inner {
  border: 0;
  border-radius: 0;
  background: #efffcf;
  border-bottom: 1px solid #f0f0f0;
  text-align: right;
}

.header {
  font-weight: bold;
  color: #909399;
  font-size: 0.85rem;
}
.inf {
  cursor: pointer;
}
</style>
