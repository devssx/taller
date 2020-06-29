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
          <el-popover placement="right" width="400" trigger="hover">
            <el-table size="mini" :data="item.salesA">
              <el-table-column width="70" property="year" label="Año"></el-table-column>
              <el-table-column property="brand" label="Modelo"></el-table-column>
              <el-table-column width="100" property="total" label="Total" align="end"></el-table-column>
            </el-table>
            <label slot="reference" class="pr-10 text-mini">{{ formatPrice(item.saturday) }}</label>
          </el-popover>
        </el-col>
        <el-col :span="2" align="end" class="br bb hr">
          <el-popover placement="right" width="350" trigger="hover">
            <el-table size="mini" :data="item.salesB">
              <el-table-column width="70" property="year" label="Año"></el-table-column>
              <el-table-column property="brand" label="Modelo"></el-table-column>
              <el-table-column width="100" property="total" label="Total" align="end"></el-table-column>
            </el-table>
            <label slot="reference" class="pr-10 text-mini">{{ formatPrice(item.monday) }}</label>
          </el-popover>
        </el-col>
        <el-col :span="2" align="end" class="br bb hr">
          <el-popover placement="right" width="350" trigger="hover">
            <el-table size="mini" :data="item.salesC">
              <el-table-column width="70" property="year" label="Año"></el-table-column>
              <el-table-column property="brand" label="Modelo"></el-table-column>
              <el-table-column width="100" property="total" label="Total" align="end"></el-table-column>
            </el-table>
            <label slot="reference" class="pr-10 text-mini">{{ formatPrice(item.tuesday) }}</label>
          </el-popover>
        </el-col>
        <el-col :span="2" align="end" class="br bb hr">
          <el-popover placement="right" width="350" trigger="hover">
            <el-table size="mini" :data="item.salesF">
              <el-table-column width="70" property="year" label="Año"></el-table-column>
              <el-table-column property="brand" label="Modelo"></el-table-column>
              <el-table-column width="100" property="total" label="Total" align="end"></el-table-column>
            </el-table>
            <label slot="reference" class="pr-10 text-mini">{{ formatPrice(item.wednesday) }}</label>
          </el-popover>
        </el-col>
        <el-col :span="2" align="end" class="br bb hr">
          <el-popover placement="right" width="350" trigger="hover">
            <el-table size="mini" :data="item.salesE">
              <el-table-column width="70" property="year" label="Año"></el-table-column>
              <el-table-column property="brand" label="Modelo"></el-table-column>
              <el-table-column width="100" property="total" label="Total" align="end"></el-table-column>
            </el-table>
            <label slot="reference" class="pr-10 text-mini">{{ formatPrice(item.thursday) }}</label>
          </el-popover>
        </el-col>
        <el-col :span="2" align="end" class="br bb hr">
          <el-popover placement="right" width="350" trigger="hover">
            <el-table size="mini" :data="item.salesF">
              <el-table-column width="70" property="year" label="Año"></el-table-column>
              <el-table-column property="brand" label="Modelo"></el-table-column>
              <el-table-column width="100" property="total" label="Total" align="end"></el-table-column>
            </el-table>
            <label slot="reference" class="pr-10 text-mini">{{ formatPrice(item.friday) }}</label>
          </el-popover>
        </el-col>

        <el-col :span="2" align="end" class="br bb hr">
          <label class="pr-10 text-mini">{{ formatPrice(item.totalWeek) }}</label>
        </el-col>
        <el-col :span="1" align="end" class="br bb hr">
          <label class="pr-10 text-mini">{{ formatPrice(item.percentCommission) }}</label>
        </el-col>
        <el-col :span="2" align="end" class="br bb hr">
          <label class="pr-10 text-mini">{{ formatPrice(item.commission) }}</label>
        </el-col>
        <el-col :span="2" align="end" class="br bb hr">
          <label class="pr-10 text-mini">{{ formatPrice(item.sueldo) }}</label>
        </el-col>
        <el-col :span="2" align="end" class="br bb hr">
          <label class="pr-10 text-mini">{{ formatPrice(item.subtotal) }}</label>
        </el-col>
        <el-col :span="1" align="end" class="br bb hr">
          <label class="pr-10 text-mini">{{ formatPrice(item.discounts) }}</label>
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
      item.total = item.subtotal - item.discounts;
    },
    changeBase(item, index) {
      this.items[index].low_price = item.price;
      this.items[index].mid_price = item.price;
      this.items[index].high_price = item.price;
      this.onChangePrice();
    },
    onChangePrice() {
      this.$forceUpdate();
      this.$root.$refs.create.$forceUpdate();
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
  background: #8cc5ff;
  border-bottom: 1px solid #f0f0f0;
  text-align: right;
}

.header {
  font-weight: bold;
  color: #909399;
  font-size: 0.85rem;
}
</style>
