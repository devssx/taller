<template>
  <el-tabs type="card" v-model="activeName" @tab-click="handleClick">
    <el-tab-pane label="Ingresos y Utilidad" name="first">
      <utilidad-component></utilidad-component>
    </el-tab-pane>
    <el-tab-pane label="Ingresos Por Técnicos" name="second">
      <tecnicos-component></tecnicos-component>
    </el-tab-pane>
    <el-tab-pane label="Garantías" name="third">
      <garantias-component></garantias-component>
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