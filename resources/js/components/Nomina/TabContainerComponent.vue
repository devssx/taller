<template>
  <el-tabs type="card" v-model="activeName" @tab-click="handleClick">
    <el-tab-pane label="Por Técnico" name="first">
      <nomina-general :workshops="workshops" :myUser="myUser" :multiWorkshop="multiWorkshop"></nomina-general>
    </el-tab-pane>
    <el-tab-pane label="Completa" name="second">
      <nomina-completa :workshops="workshops" :myUser="myUser" :multiWorkshop="multiWorkshop"></nomina-completa>
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