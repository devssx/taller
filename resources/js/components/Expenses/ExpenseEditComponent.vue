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
      style="text-align:left"
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
            <el-form-item
              label="Importe"
              prop="amount"
              v-show="selectedItem.type==2||selectedItem.type==3"
            >
              <el-input v-model="selectedItem.amount"></el-input>
            </el-form-item>
            <el-form-item
              label="Iva"
              prop="iva"
              v-show="selectedItem.type==2||selectedItem.type==3"
            >
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
  props: ["selectedItem", "hideButton", "workshop", "funds", "expense"],
  mounted: function () {},
  data() {
    return {
      options: [
        {
          value: 1,
          label: "Gastos no facturados",
        },
        {
          value: 2,
          label: "Gastos en efectivo facturados",
        },
        {
          value: 3,
          label: "Gastos a crédito facturados",
        },
      ],

      dialogVisible: false,
      loading: false,
      labelPosition: "left",
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
            type: "warning",
          }
        );
        return;
      }

      if (!$this.selectedItem.concept) {
        this.$alert("Favor de ingresar concepto.", "Concepto no válido", {
          confirmButtonText: "OK",
          type: "warning",
        });
        return;
      }

      if ($this.selectedItem.type == 2 || $this.selectedItem.type == 3) {
        if (isNaN($this.selectedItem.iva) || $this.selectedItem.iva == 0) {
          this.$alert("Iva no puede ser 0.", "Iva no válido", {
            confirmButtonText: "OK",
            type: "warning",
          });
          return;
        }

        if (
          isNaN($this.selectedItem.amount) ||
          $this.selectedItem.amount == 0
        ) {
          this.$alert("Importe no puede ser 0.", "Importe no válido", {
            confirmButtonText: "OK",
            type: "warning",
          });
          return;
        }

        let total =
          parseFloat($this.selectedItem.amount) +
          parseFloat($this.selectedItem.iva);
        if (parseFloat($this.selectedItem.total) != total) {
          this.$alert(
            "El importe total no coincide con la suma Importe + Iva",
            "Total Incorrecto.",
            {
              confirmButtonText: "OK",
              type: "warning",
            }
          );
          return;
        }
      } else {
        $this.selectedItem.amount = 0;
        $this.selectedItem.iva = 0;
      }

      if (isNaN($this.selectedItem.total) || $this.selectedItem.total == 0) {
        this.$alert("Total no puede ser 0.", "Total no válido", {
          confirmButtonText: "OK",
          type: "warning",
        });
        return;
      }

      if (!$this.selectedItem.workshop) {
        $this.selectedItem.workshop = $this.workshop;
      }

      let balance = $this.funds - $this.expense;
      if ($this.selectedItem.amount > balance || $this.selectedItem.total > balance) {
        this.$alert("Importe supera los fondos.", "Importe no válido", {
          confirmButtonText: "OK",
          type: "warning",
        });
        return;
      }

      this.confirmar(
        `¿Esta seguro que desea agregar el gasto: ${this.selectedItem.concept}?`,
        this.finish
      );
    },
    finish() {
      var $this = this;
      this.dialogVisible = false;
      this.loading = false;
      $this.$refs.currentForm.validate((valid) => {
        if (valid) {
          $this.loading = true;
          axios
            .post("/api/expenses", $this.selectedItem)
            .then(function (response) {
              $this.$message({
                message: "El registro fué editado correctamente.",
                type: "success",
              });

              $this.$root.$emit("refreshExpenses");
              $this.cancel();
            })
            .catch((error) => {
              if (error.response.data.errors) {
                var errors = error.response.data.errors;
                $this.$alert(errors[Object.keys(errors)[0]][0], "Error", {
                  confirmButtonText: "OK",
                  type: "error",
                });
              }
              $this.loading = false;
            });
        } else {
          return false;
        }
      });
    },
  },
};
</script>
