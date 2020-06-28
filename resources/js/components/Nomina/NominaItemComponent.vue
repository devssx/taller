<template>
  <div>
    <el-row class="row-header br bl bb bt">
      <el-col :span="4" align="center" class="header">Empleado</el-col>
      <el-col :span="3" align="center" class="header">Total A/C</el-col>
      <el-col :span="3" align="center" class="header">Total Mecánica</el-col>
      <el-col :span="3" align="center" class="header">Total Eléctrico</el-col>
      <el-col :span="3" align="center" class="header">Subtotal</el-col>
      <el-col :span="3" align="center" class="header">Descuentos</el-col>
      <el-col :span="3" align="center" class="header">Total</el-col>
      <el-col :span="2" align="center" class="header">Options</el-col>
    </el-row>
    <el-row v-if="items.length == 0">
      <el-col :span="24" class="bl br bb hr text-mini empty-data" align="center">Sin Datos</el-col>
    </el-row>
    <el-row>
      <el-row v-for="(item, index) in items" v-bind:key="index">
        <el-col :span="4" class="bl br bb hr">
          <label class="pl-10 text-mini">{{ item.employee }}</label>
        </el-col>
        <el-col :span="3" class="br bb hr" align="end">
          <el-popover
            placement="top-start"
            title="Title"
            width="200"
            trigger="hover"
            content="this is content, this is content, this is content"
          >
            <label slot="reference" class="pr-10 text-mini">{{ formatPrice(item.totalA) }}</label>
          </el-popover>
        </el-col>
        <el-col :span="3" class="br bb hr" align="end">
          <label class="pr-10 text-mini">{{ formatPrice(item.totalB)}}</label>
        </el-col>
        <el-col :span="3" class="br bb hr" align="end">
          <label class="pr-10 text-mini">{{ formatPrice(item.totalC)}}</label>
        </el-col>
        <el-col :span="3" class="br bb hr" align="end">
          <label class="pr-10 text-mini">{{ formatPrice(item.subtotal) }}</label>
        </el-col>
        <el-col :span="3" class="br bb hr" align="end">
          <el-input
            v-show="item.editable"
            @change="onChangeDiscount(item)"
            size="mini"
            v-model="item.discounts"
            class="mycustom"
          ></el-input>
          <label v-show="!item.editable" class="pr-10 text-mini">{{ item.discounts }}</label>
        </el-col>
        <el-col :span="3" class="br bb hr" align="end">
          <label class="pr-10 text-mini">${{ formatPrice(item.total) }}</label>
        </el-col>
        <el-col :span="2" class="br bb hr" align="center">
          <el-button
            v-show="!item.editable"
            @click="item.editable=!item.editable"
            icon="el-icon-edit"
            type="text"
            size="mini"
          >Editar</el-button>
          <el-button
            v-show="item.editable"
            icon="el-icon-check"
            @click="item.editable=false"
            type="text"
            size="mini"
          >Guardar</el-button>
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
