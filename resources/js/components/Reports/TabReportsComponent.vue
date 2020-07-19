<template>
  <el-tabs type="card" v-model="activeName" @tab-click="handleClick">
    <el-tab-pane label="Ingresos y Utilidad" name="first">
      <utilidad-component :workshops="workshops" :myUser="myUser" :multiWorkshop="multiWorkshop"></utilidad-component>
    </el-tab-pane>
    <el-tab-pane label="Ingresos Por Técnicos" name="second">
      <tecnicos-component :workshops="workshops" :myUser="myUser" :multiWorkshop="multiWorkshop"></tecnicos-component>
    </el-tab-pane>
    <el-tab-pane label="Garantías" name="third">
      <garantias-component :workshops="workshops" :myUser="myUser" :multiWorkshop="multiWorkshop"></garantias-component>
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