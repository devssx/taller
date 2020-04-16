<template>
  <el-row syle="margin-top:2px;">
    <!--<el-row v-for="(item, index) in items" v-bind:key="index">
      <el-col :span="1">
        <button
          class="el-icon-close"
          @click="deleteItem(index)"
          style="cursor:pointer;border:none;background-color:white;"
        ></button>
      </el-col>
      <el-col :span="5">
        <label class="el-form-item__label">{{ item.name }}</label>
      </el-col>
      <el-col :span="3">
        <el-input class="price" v-model="item.price" @change="changeBase(item, index)"></el-input>
      </el-col>
      <el-col :span="2" style="text-align:center;" v-bind:class="{ priceSelected: price == 'low'}">
        <el-input
          class="percentage"
          maxlength="2"
          v-model="item.low"
          @change="changePercentage('low', item, index)"
        ></el-input>
      </el-col>
      <el-col :span="3" style="text-align:center;" v-bind:class="{ priceSelected: price == 'low'}">
        <el-input class="price" v-model="item.low_price" @change="onChangePrice()"></el-input>
      </el-col>
      <el-col :span="2" style="text-align:center;" v-bind:class="{ priceSelected: price == 'mid'}">
        <el-input
          class="percentage"
          maxlength="2"
          v-model="item.mid"
          @change="changePercentage('mid', item, index)"
        ></el-input>
      </el-col>
      <el-col :span="3" style="text-align:center;" v-bind:class="{ priceSelected: price == 'mid'}">
        <el-input class="price" v-model="item.mid_price" @change="onChangePrice()"></el-input>
      </el-col>
      <el-col :span="2" style="text-align:center;" v-bind:class="{ priceSelected: price == 'high'}">
        <el-input
          class="percentage"
          maxlength="2"
          v-model="item.high"
          @change="changePercentage('high', item, index)"
        ></el-input>
      </el-col>
      <el-col :span="3" style="text-align:center;" v-bind:class="{ priceSelected: price == 'high'}">
        <el-input class="price" v-model="item.high_price" @change="onChangePrice()"></el-input>
      </el-col>
    </el-row>
    -->
    <el-row
      v-for="(item, index) in items"
      v-bind:key="index"
      type="flex"
      align="middle"
      class="br bl bt"
    >
      <el-col :span="1">
        <button
          v-if="!isReadOnly"
          class="el-icon-close"
          @click="deleteItem(index)"
          style="cursor:pointer;border:none;background-color:white;margin:4px;"
        ></button>
      </el-col>
      <el-col :span="6">
        <label class="el-form-item__label">{{ itemName(item) }}</label>
      </el-col>
      <el-col :span="3">
        <el-input
          :readonly="isReadOnly"
          size="mini"
          class="price"
          v-model="item.usd_price"
          @change="changeUSDPrice(item, index)"
        ></el-input>
      </el-col>
      <el-col :span="2">
        <el-input
          :readonly="isReadOnly"
          size="mini"
          class="price"
          v-model="item.price"
          @change="changeBase(item, index)"
        ></el-input>
      </el-col>
      <el-col :span="2">
        <el-input
          :readonly="isReadOnly"
          size="mini"
          class="percentage"
          v-model="item.low"
          @change="changePercentage('low', item, index)"
        ></el-input>
      </el-col>
      <el-col :span="2">
        <el-input :readonly="isReadOnly" size="mini" class="price" v-model="item.low_price"></el-input>
      </el-col>
      <el-col :span="2">
        <el-input
          :readonly="isReadOnly"
          size="mini"
          class="percentage"
          v-model="item.mid"
          @change="changePercentage('mid', item, index)"
        ></el-input>
      </el-col>
      <el-col :span="2">
        <el-input :readonly="isReadOnly" size="mini" class="price" v-model="item.mid_price"></el-input>
      </el-col>
      <el-col :span="2">
        <el-input
          size="mini"
          class="percentage"
          v-model="item.high"
          :readonly="isReadOnly"
          @change="changePercentage('high', item, index)"
        ></el-input>
      </el-col>
      <el-col :span="2">
        <el-input :readonly="isReadOnly" size="mini" class="price" v-model="item.high_price"></el-input>
      </el-col>
    </el-row>
  </el-row>
</template>

<script>
export default {
  props: ["items", "updatePrices", "tdc", "isReadOnly"],
  mounted: function() {},
  methods: {
    changeUSDPrice(item, index) {
      item.usd_price = parseFloat(item.usd_price);
      item.price = item.usd_price * this.tdc;
      this.changeBase(item, index);
    },
    refreshPrices(currentValue) {
      if (isNaN(currentValue)) return;

      // change all prices (USD*TDC)
      for (var i = 0; i < this.items.length; i++) {
        var item = this.items[i];
        item.price = item.usd_price * currentValue;
        this.changeBase(item, i);
      }
    },
    changeGlobalPrecentage(currentValue, percentage) {
      if (isNaN(currentValue)) return;

      // change all percentages
      for (var i = 0; i < this.items.length; i++) {
        var item = this.items[i];
        item[percentage] = currentValue; // change the property [low,mid,high]
        this.changePercentage(percentage, item, i, true);
      }
    },
    formatPrice(value) {
      let val = (value / 1).toFixed(2);
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },
    deleteItem(value) {
      this.items.splice(value, 1);
      this.onChangePrice();
    },
    changeBase(item, index) {
      // this.items[index].low_price = item.price;
      // this.items[index].mid_price = item.price;
      // this.items[index].high_price = item.price;

      var low = this.items[index].low;
      var mid = this.items[index].mid;
      var high = this.items[index].high;

      if (this.items[index].price != 0) {
        this.items[index].low_price =
          parseInt(item.price) + (item.price * low) / 100;
        this.items[index].mid_price =
          parseInt(item.price) + (item.price * mid) / 100;
        this.items[index].high_price =
          parseInt(item.price) + (item.price * high) / 100;
      }

      this.onChangePrice();
    },
    changePercentage(price, item, index, isGlobal) {
      if (this.updatePrices || isGlobal) {
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
