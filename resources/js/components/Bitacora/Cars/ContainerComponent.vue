<template>
  <el-tabs type="card" v-model="activeName" @tab-click="handleClick">
    <el-tab-pane label="Registro del Día" name="first">
      <daily-cars :workshops="workshops" :myUser="myUser" :multiWorkshop="multiWorkshop"></daily-cars>
    </el-tab-pane>
    <el-tab-pane label="Registro de la Semana" name="second">
      <weekly-cars :workshops="workshops" :myUser="myUser" :multiWorkshop="multiWorkshop"></weekly-cars>
    </el-tab-pane>
  </el-tabs>
</template>
<script>
export default {
  props: ["workshops", "myUser", "multiWorkshop"],
  data() {
    return {
      activeName: "first",
    };
  },
  methods: {
    handleClick(tab, event) {},
  },
};
</script>