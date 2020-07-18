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
            <el-form-item label="Recibo" prop="sale_id">
              <el-select v-model="selectedItem.sale_id" filterable style="width:100%">
                <el-option
                  v-for="opt in options"
                  :key="opt.value"
                  :label="opt.label"
                  :value="opt.value"
                ></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Sulucionado" prop="solution">
              <el-select v-model="selectedItem.solution" filterable style="width:100%">
                <el-option
                  v-for="opt in options"
                  :key="opt.value"
                  :label="opt.label"
                  :value="opt.value"
                ></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Fecha" prop="new_date">
              <el-date-picker
                style="width:100%"
                v-model="selectedItem.new_date"
                type="date"
                format="dd-MM-yyyy"
                placeholder="Seleccionar Día"
              ></el-date-picker>
            </el-form-item>
            <el-form-item label="Empleado" prop="employee">
              <el-input v-model="selectedItem.employee"></el-input>
            </el-form-item>
            <el-form-item label="Comentario" prop="comment">
              <el-input v-model="selectedItem.comment"></el-input>
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
  props: ["selectedItem", "hideButton", "workshop"],
  mounted: function() {},
  data() {
    return {
      options: [
        {
          value: "Si",
          label: "Si"
        },
        {
          value: "No",
          label: "No"
        }
      ],

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
      // ASIGNACION
      var $this = this;

      if ($this.selectedItem.type == 0) {
        this.$alert(
          "Favor de seleccionar un tipo de gasto.",
          "Tipo no válido",
          {
            confirmButtonText: "OK",
            type: "warning"
          }
        );
        return;
      }

      if (!$this.selectedItem.concept) {
        this.$alert("Favor de ingresar concepto.", "Concepto no válido", {
          confirmButtonText: "OK",
          type: "warning"
        });
        return;
      }

      if (!$this.selectedItem.workshop) {
        $this.selectedItem.workshop = $this.workshop;
      }

      this.dialogVisible = false;
      this.loading = false;
      $this.$refs.currentForm.validate(valid => {
        if (valid) {
          $this.loading = true;
          axios
            .post("/api/expenses", $this.selectedItem)
            .then(function(response) {
              $this.$message({
                message: "El registro fué editado correctamente.",
                type: "success"
              });

              $this.$root.$emit("refreshExpenses");
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
