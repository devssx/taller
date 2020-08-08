<template>
  <span>
    <el-button
      icon="el-icon-coin"
      @click="dialogVisible = true"
      size="small"
      type="text"
    >Asignar Fondos</el-button>
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
            <el-form-item label="Importe" prop="amount">
              <el-input v-model="selectedItem.amount"></el-input>
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
  props: ["workshop", "week"],
  mounted: function () {},
  data() {
    return {
      dialogVisible: false,
      loading: false,
      labelPosition: "left",
      selectedItem: {
        amount: 0,
        workshop: 0,
      },
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

      if (isNaN($this.selectedItem.amount) || $this.selectedItem.amount == 0) {
        this.$alert("Importe debe ser mayor a 0.", "Importe no válido", {
          confirmButtonText: "OK",
          type: "warning",
        });
        return;
      }

      let selectedWeek = this.initDayOfWeekDate(this.week, 2);
      let thisWeek = this.initDayOfWeekDate(new Date(), 2);
      let sWeek = this.toFixedFormat(selectedWeek, "yyyyMMdd");
      let cWeek = this.toFixedFormat(thisWeek, "yyyyMMdd");

      // no dejar poner gastos en el futuro o pasado
      if (sWeek != cWeek) {
        this.$alert(
          "Favor de seleccionar la semana actual",
          "Semana no válida",
          {
            confirmButtonText: "OK",
            type: "warning",
          }
        );
        return;
      }

      this.selectedItem.workshop = this.workshop;
      this.selectedItem.week = cWeek;

      this.dialogVisible = false;
      this.loading = false;
      $this.$refs.currentForm.validate((valid) => {
        if (valid) {
          $this.loading = true;
          axios
            .post("/api/funds", $this.selectedItem)
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
