<template>
  <span>
    <el-button
      icon="el-icon-edit"
      v-show="!hideButton"
      @click="onShow()"
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
            <el-form-item label="Fecha de Pago" prop="fecha">
              <el-date-picker
                style="width:100%"
                v-model="selectedItem.fecha"
                type="date"
                format="dd-MM-yyyy"
                placeholder="Seleccionar Día"
              ></el-date-picker>
            </el-form-item>
            <el-form-item label="No. Factura" prop="factura">
              <el-input v-model="selectedItem.factura"></el-input>
            </el-form-item>
            <el-form-item label="Método de Pago" v-if="method">
              <el-select v-model="method" style="width:100%">
                <el-option
                  v-for="opt in options"
                  :key="opt.value"
                  :label="opt.label"
                  :value="opt.value"
                ></el-option>
              </el-select>
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
  props: ["selectedItem", "method", "isCredit", "hideButton"],
  mounted: function () {},
  data() {
    return {
      // 1 Efectivo
      // *2 Tarjeta de Crédito
      // *3 Tarjeta de Débito
      // *4 Cheque
      // *5 Transferencia
      // 6 Crédito

      options: [
        {
          label: "Efectivo",
          value: 1,
        },
        {
          label: "Tarjeta de Crédito",
          value: 3,
        },
        {
          label: "Tarjeta de Débito",
          value: 3,
        },
        {
          label: "Cheque",
          value: 4,
        },
        {
          label: "Transferencia",
          value: 5,
        },
        {
          label: "Crédito",
          value: 6,
        },
      ],
      dialogVisible: false,
      loading: false,
      labelPosition: "left",
    };
  },
  methods: {
    onShow() {
      this.dialogVisible = true;
    },
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
      var $this = this;
      let id = this.selectedItem.folio.substring(3);
      let fecha = this.selectedItem.fecha;
      let factura = this.selectedItem.factura;
      let changes = { id: id, bill: factura, paid_date: fecha };

      if (!fecha) {
        this.$alert("Favor de ingresar la fecha de pago", "fecha no válida", {
          confirmButtonText: "OK",
          type: "warning",
        });
        return;
      }

      if (!factura) {
        this.$alert("Favor de ingresar la factura", "factura no válida", {
          confirmButtonText: "OK",
          type: "warning",
        });
        return;
      }

      // Metodo de pago
      if (this.isCredit) {
        changes.method = this.method;

        if (isNaN(this.method) || this.method < 1 || this.method > 5) {
          this.$alert(
            "Favor de seleccionar un método de pago válido, distinto a crédito",
            "Método de pago no válido",
            {
              confirmButtonText: "OK",
              type: "warning",
            }
          );
          return;
        }
      }

      $this.dialogVisible = false;
      $this.loading = false;

      $this.$refs.currentForm.validate((valid) => {
        if (valid) {
          $this.loading = true;
          axios
            .post("/api/sales/editIncome", changes)
            .then(function (response) {
              $this.$message({
                message: "El registro fué editado correctamente.",
                type: "success",
              });

              $this.$root.$emit("refreshIncome");
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
