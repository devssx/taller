<template>
  <el-tabs type="card" v-model="activeName" @tab-click="handleClick">
    <el-tab-pane v-if="showIncome" label="Ingresos" name="first">
      <income-component :workshops="workshops" :myUser="myUser" :multiWorkshop="multiWorkshop"></income-component>
    </el-tab-pane>
    <el-tab-pane label="Gastos" name="second">
      <expense-component :workshops="workshops" :myUser="myUser" :multiWorkshop="multiWorkshop"></expense-component>
    </el-tab-pane>
  </el-tabs>
</template>
<script>
export default {
  props: ["workshops", "myUser", "multiWorkshop", "showIncome"],
  data() {
    return {
      activeName: this.showIncome ? "first" : "second",
    };
  },
  methods: {
    handleClick(tab, event) {
      //console.log(tab, event);
    },
  },
};
</script>