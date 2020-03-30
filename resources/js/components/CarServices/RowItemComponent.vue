<template>
  <el-row>
    <el-row v-for="(item, index) in items" v-bind:key="index">
      <el-col :span="4">
        <label class="el-form-item__label">{{ itemName(item) }}</label>
      </el-col>
      <el-col :span="4">
        <el-input size="mini" class="price" v-model="item.price" @change="changeBase(item, index)"></el-input>
      </el-col>
      <el-col :span="4">
        <el-input
          class="price"
          size="mini"
          maxlength="2"
          v-model="item.low"
          @change="changePercentage('low', item, index)"
        ></el-input>
      </el-col>
      <el-col :span="4">
        <el-input size="mini" class="price" v-model="item.low_price" @change="onChangePrice()"></el-input>
      </el-col>
      <el-col :span="4">
        <el-input
          size="mini"
          class="price"
          maxlength="2"
          v-model="item.mid"
          @change="changePercentage('mid', item, index)"
        ></el-input>
      </el-col>
      <el-col :span="4">
        <el-button size="mini" type="danger" icon="el-icon-delete" @click="deleteItem(index)"></el-button>
      </el-col>
    </el-row>
  </el-row>
</template>

<script>
export default {
  props: ["items", "updatePrices"],
  mounted: function() {},
  methods: {
    formatPrice(value) {
      let val = (value / 1).toFixed(2);
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },
    deleteItem(value) {
      this.items.splice(value, 1);
      this.onChangePrice();
    },
    changeBase(item, index) {
      this.items[index].low_price = item.price;
      this.items[index].mid_price = item.price;
      this.items[index].high_price = item.price;
      this.onChangePrice();
    },
    changePercentage(price, item, index) {
      if (this.updatePrices) {
        for (var x = 0; x < this.items.length; x++) {
          if (this.items[x][price] != 0) {
            this.items[x][price + "_price"] =
              parseInt(this.items[x].price) +
              (this.items[x].price * item[price]) / 100;
            this.items[x][price] = item[price];
          }
        }
      } else {
        this.items[index][price + "_price"] =
          parseInt(item.price) + (item.price * item[price]) / 100;
      }
      this.onChangePrice();
    },
    onChangePrice() {
      this.$forceUpdate();
      this.$root.$refs.create.$forceUpdate();
    },
    itemName(item) {
      if (item.name) {
        return item.name;
      }
      return item.item.name;
    }
  }
};
</script>

<style lang="scss">
.priceSelected {
  background: rgb(242, 242, 242);
  label {
    line-height: 37px;
  }
}
.el-form-item__label {
  text-align: left;
  line-height: 18px;
}
</style>
