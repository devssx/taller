<template>
  <el-tabs type="card" v-model="activeName" @tab-click="handleClick">
    <el-tab-pane label="Ingresos" name="first">
      <ingresos-component></ingresos-component>
    </el-tab-pane>
    <el-tab-pane label="Gastos" name="second">
      <gastos-component></gastos-component>
    </el-tab-pane>
  </el-tabs>
</template>
<script>
export default {
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