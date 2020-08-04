<template>
  <el-tabs type="card" v-model="activeName" @tab-click="handleClick">
    <el-tab-pane label="Por Técnico" name="first">
      <payroll-general :workshops="workshops" :myUser="myUser" :multiWorkshop="multiWorkshop"></payroll-general>
    </el-tab-pane>
    <el-tab-pane label="Completa" name="second">
      <payroll-full :workshops="workshops" :myUser="myUser" :multiWorkshop="multiWorkshop"></payroll-full>
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