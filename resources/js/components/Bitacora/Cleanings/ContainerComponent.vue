<template>
  <el-tabs type="card" v-model="activeName" @tab-click="handleClick">
    <el-tab-pane label="Registro del Día" name="first">
      <daily-cleaning :workshops="workshops" :myUser="myUser" :multiWorkshop="multiWorkshop"></daily-cleaning>
    </el-tab-pane>
    <el-tab-pane label="Registro de la Semana" name="second">
      <weekly-cleaning :workshops="workshops" :myUser="myUser" :multiWorkshop="multiWorkshop"></weekly-cleaning>
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
    handleClick(tab, event) {
      //console.log(tab, event);
    },
  },
};
</script>