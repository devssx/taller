<template>
  <el-tabs type="card" v-model="activeName" @tab-click="handleClick">
    <el-tab-pane label="Ingresos y Utilidad" name="first">
      <utility-component :workshops="workshops" :myUser="myUser" :multiWorkshop="multiWorkshop"></utility-component>
    </el-tab-pane>
    <el-tab-pane label="Ingresos Por Técnicos" name="second">
      <employee-component :workshops="workshops" :myUser="myUser" :multiWorkshop="multiWorkshop"></employee-component>
    </el-tab-pane>
    <el-tab-pane label="Garantías" name="third">
      <guarantee-component :workshops="workshops" :myUser="myUser" :multiWorkshop="multiWorkshop"></guarantee-component>
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