<template>
  <el-tabs type="card" v-model="activeName" @tab-click="handleClick">
    <el-tab-pane label="Por Técnico" name="first">
      <nomina-general></nomina-general>
    </el-tab-pane>
    <el-tab-pane label="Completa" name="second">
      <nomina-completa></nomina-completa>
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