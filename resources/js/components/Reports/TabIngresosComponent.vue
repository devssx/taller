<template>
  <el-tabs type="card" v-model="activeName" @tab-click="handleClick">
    <el-tab-pane label="Ingresos" name="first">
      <ingresos-component :workshops="workshops" :myUser="myUser" :multiWorkshop="multiWorkshop"></ingresos-component>
    </el-tab-pane>
    <el-tab-pane label="Gastos" name="second">
      <gastos-component :workshops="workshops" :myUser="myUser" :multiWorkshop="multiWorkshop"></gastos-component>
    </el-tab-pane>
  </el-tabs>
</template>
<script>
export default {
  props: ["workshops", "myUser", "multiWorkshop"],
  data() {
    return {
      activeName: "first"
    };
  },
  methods: {
    handleClick(tab, event) {
      //console.log(tab, event);
    }
  }
};
</script>