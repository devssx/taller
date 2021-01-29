<template>
  <el-col :span="5">
    <el-dialog
      title="Seleccionar Formato"
      :visible.sync="dialogVisible"
      width="400px"
      :before-close="handleClose"
    >
      <el-row>
        <el-col :span="24">
          <el-select style="width: 100%" v-model="value" placeholder="Formato">
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value"
              :disabled="item.disabled"
            >
            </el-option>
          </el-select>
        </el-col>
      </el-row>
      <span slot="footer" class="dialog-footer">
        <el-button @click="cancel()">Cancelar</el-button>
        <el-button type="primary" @click="onSelect()">Aceptar</el-button>
      </span>
    </el-dialog>
  </el-col>
</template>
<script>
export default {
  props: ["name"],
  data() {
    return {
      options: [
        {
          value: "0",
          label: "Concepto",
        },
        {
          value: "1",
          label: "Lista de artículos sin precio",
        },
        {
          value: "2",
          label: "Lista de artículos con precio",
        },
      ],
      value: "0",
      dialogVisible: false,
      loading: false,
      labelPosition: "left",
    };
  },
  methods: {
    showDialog() {
      this.dialogVisible = true;
    },
    handleClose(done) {
      this.dialogVisible = false;
    },
    cancel() {
      this.value = "0";
      this.dialogVisible = false;
    },
    onSelect() {
      let $this = this;
      this.dialogVisible = false;
      var optionValue = parseInt(this.value);
      this.value = "0";

      setTimeout(function () {
        $this.$root.$emit("selectedFormat", optionValue, $this.name);
      }, 500);
    },
  },
};
</script>
