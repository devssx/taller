<template>
  <el-row syle="margin-top:2px;">
    <el-row v-for="(item, index) in items" v-bind:key="index" type="flex" align="middle">
      <el-col :span="4">
        <label class="el-form-item__label">{{ itemName(item) }}</label>
      </el-col>
      <el-col :span="4">
        <!-- <el-input class="price" v-model="item.price" @change="changeBase(item, index)"></el-input> -->
        <el-input-number
          v-model="item.priceUSD"
          @change="refreshItem(index)"
          controls-position="right"
          :precision="2"
          :step="0.5"
          :min="0"
          style="width:100%"
        ></el-input-number>
      </el-col>
      <el-col :span="4">
        <el-input-number
          v-model="item.price"
          controls-position="right"
          :precision="2"
          :step="0.5"
          :min="0"
          :disabled="true"
          style="width:100%"
        ></el-input-number>
      </el-col>
      <el-col :span="3" class="item-price">
        <!-- <el-input size="mini" class="price" v-model="item.low_price" @change="onChangePrice()"></el-input> -->
        <label>${{ formatPrice(item.low_price) }}</label>
      </el-col>
      <el-col :span="3" class="item-price">
        <!-- <el-input
          size="mini"
          class="price"
          maxlength="2"
          v-model="item.mid_price"
          @change="changePercentage('mid', item, index)"
        ></el-input>-->
        <label>${{ formatPrice(item.mid_price) }}</label>
      </el-col>
      <el-col :span="3" class="item-price">
        <!-- <el-input
          size="mini"
          class="price"
          maxlength="2"
          v-model="item.mid_price"
          @change="changePercentage('mid', item, index)"
        ></el-input>-->
        <label>${{ formatPrice(item.high_price) }}</label>
      </el-col>
      <el-col :span="3" class="item-price">
        <el-button size="mini" type="danger" icon="el-icon-delete" @click="deleteItem(index)"></el-button>
      </el-col>
    </el-row>
  </el-row>
</template>

<script>
export default {
  props: ["items", "updatePrices", "onPriceChange"],
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
    refreshItem(value) {
      console.log(value);
      this.onPriceChange();
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

.item-price {
  text-align: right;
  line-height: 18px;
}
</style>
