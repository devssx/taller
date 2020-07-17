<template>
  <span>
    <el-button
      icon="el-icon-edit"
      v-show="!hideButton"
      @click="dialogVisible = true"
      size="small"
      type="text"
    >Editar</el-button>
    <el-dialog
      title="Editar Información"
      :visible.sync="dialogVisible"
      width="30%"
      :before-close="handleClose"
    >
      <el-row>
        <el-col :span="24">
          <el-form
            v-if="selectedItem"
            :label-position="labelPosition"
            :model="selectedItem"
            label-width="180px"
            ref="currentForm"
          >
            <el-form-item label="Tipo" prop="type">
              <el-select v-model="selectedItem.type" filterable style="width:100%">
                <el-option
                  v-for="opt in options"
                  :key="opt.value"
                  :label="opt.label"
                  :value="opt.value"
                ></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Concepto" prop="concept">
              <el-input v-model="selectedItem.concept"></el-input>
            </el-form-item>
            <el-form-item label="Importe" prop="amount" v-show="selectedItem.type==2||selectedItem.type==3">
              <el-input v-model="selectedItem.amount"></el-input>
            </el-form-item>
            <el-form-item label="Iva" prop="iva" v-show="selectedItem.type==2||selectedItem.type==3">
              <el-input v-model="selectedItem.iva"></el-input>
            </el-form-item>
            <el-form-item label="Total" prop="total">
              <el-input v-model="selectedItem.total"></el-input>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
      <span slot="footer" class="dialog-footer">
        <el-button @click="cancel()">Cancelar</el-button>
        <el-button type="primary" @click="save()" :loading="loading">Guardar</el-button>
      </span>
    </el-dialog>
  </span>
</template>
<script>
export default {
  props: ["selectedItem", "hideButton"],
  mounted: function() {
  },
  data() {
    return {
      users: [],
      entrada: new Date(this.selectedItem.start),

      breakfast: [
        new Date(this.selectedItem.breakfast_start),
        new Date(this.selectedItem.breakfast_end)
      ],
      lunch: [
        new Date(this.selectedItem.lunch_start),
        new Date(this.selectedItem.lunch_end)
      ],

      options: [
        {
          value: 1,
          label: "Gastos no facturados"
        },
        {
          value: 2,
          label: "Gastos en efectivo facturados"
        },
        {
          value: 3,
          label: "Gastos a crédito facturados"
        },
        {
          value: 4,
          label: "Empleados"
        }
      ],

      //selectedItem: null,
      dialogVisible: false,
      loading: false,
      labelPosition: "left"
    };
  },
  methods: {
    handleClose(done) {
      this.cancel();
      done();
    },
    cancel() {
      this.dialogVisible = false;
      this.loading = false;
      this.$refs.currentForm.resetFields();
    },
    save() {
      this.dialogVisible = false;
      this.loading = false;

      // ASIGNACION
      var $this = this;

      $this.$refs.currentForm.validate(valid => {
        if (valid) {
          $this.loading = true;
          axios
            .post("/api/cleaning", $this.selectedItem)
            .then(function(response) {
              $this.$message({
                message: "El registro fué editado correctamente.",
                type: "success"
              });

              $this.$root.$emit("refreshTable", response.data);
              $this.cancel();
            })
            .catch(error => {
              if (error.response.data.errors) {
                var errors = error.response.data.errors;
                $this.$alert(errors[Object.keys(errors)[0]][0], "Error", {
                  confirmButtonText: "OK",
                  type: "error"
                });
              }
              $this.loading = false;
            });
        } else {
          return false;
        }
      });
    }
  }
};
</script>
