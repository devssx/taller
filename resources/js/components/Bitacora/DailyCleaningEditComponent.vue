<template>
  <span>
    <el-button @click="dialogVisible = true" size="small" type="text"
      >Editar</el-button
    >
    <el-dialog
      title="Editar Información"
      :visible.sync="dialogVisible"
      width="40%"
      :before-close="handleClose"
    >
      <el-row>
        <el-col :span="24">
          <el-form
            v-if="selectedItem"
            :label-position="labelPosition"
            :model="selectedItem"
            label-width="150px"
            ref="currentForm"
          >
            <el-form-item label="Empleado" prop="employee">
              <el-input
                v-model="selectedItem.employee"
                :disabled="true"
              ></el-input>
            </el-form-item>
            <el-form-item label="Hora Entrada" prop="horaEntrada">
              <el-time-select
                placeholder="Start time"
                v-model="selectedItem.horaEntrada"
                :picker-options="{
                  start: '05:00',
                  step: '00:05',
                  end: '19:00',
                }"
              >
              </el-time-select>
            </el-form-item>
            <el-form-item label="Limpieza" prop="limpieza">
              <el-input v-model="selectedItem.limpieza"></el-input>
            </el-form-item>
            <el-form-item label="Hoda de Desayuno">
              <template>
                <el-time-select
                  placeholder="Start time"
                  v-model="selectedItem.horaDesayunoSalida"
                  :picker-options="{
                    start: '08:00',
                    step: '00:05',
                    end: '12:00',
                  }"
                >
                </el-time-select>
                <el-time-select
                  placeholder="End time"
                  v-model="selectedItem.horaDesayunoEntrada"
                  :picker-options="{
                    start: '08:00',
                    step: '00:05',
                    end: '14:00',
                    minTime: selectedItem.horaDesayunoSalida,
                  }"
                >
                </el-time-select>
              </template>
            </el-form-item>
            <el-form-item label="Hora de Comida">
              <template>
                <el-time-select
                  placeholder="Start time"
                  v-model="selectedItem.horaComidaSalida"
                  :picker-options="{
                    start: '08:00',
                    step: '00:05',
                    end: '12:00',
                  }"
                >
                </el-time-select>
                <el-time-select
                  placeholder="End time"
                  v-model="selectedItem.horaComidaEntrada"
                  :picker-options="{
                    start: '08:00',
                    step: '00:05',
                    end: '14:00',
                    minTime: selectedItem.horaComidaSalida,
                  }"
                >
                </el-time-select>
              </template>
            </el-form-item>
            <el-form-item label="Cumplio" prop="cumplio">
              <el-input v-model="selectedItem.cumplio"></el-input>
            </el-form-item>
            <el-form-item label="Comentario" prop="comentario">
              <el-input v-model="selectedItem.comentario"></el-input>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
      <span slot="footer" class="dialog-footer">
        <el-button @click="cancel()">Cancelar</el-button>
        <el-button type="primary" @click="save()" :loading="loading"
          >Guardar</el-button
        >
      </span>
    </el-dialog>
  </span>
</template>
<script>
export default {
  props: ["selectedItem"],
  mounted: function() {
    this.$root.$on("showEditor", this.showEditor);
  },
  data() {
    return {
      //selectedItem: null,
      dialogVisible: false,
      loading: false,
      labelPosition: "left",
    };
  },
  methods: {
    showEditor(item) {
      this.selectedItem = item;
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
      this.dialogVisible = false;
      this.loading = false;

      // var $this = this;
      // $this.$refs.carForm.validate((valid) => {
      //   if (valid) {
      //     $this.loading = true;
      //     axios
      //       .post("/api/cars", $this.car)
      //       .then(function(response) {
      //         $this.$notify({
      //           title: "¡Exito!",
      //           message: "El Carro fue editado correctamente",
      //           type: "success",
      //         });
      //         $this.$root.$emit("refreshTable");
      //         $this.cancel();
      //       })
      //       .catch((error) => {
      //         if (error.response.data.errors) {
      //           var errors = error.response.data.errors;
      //           $this.$alert(errors[Object.keys(errors)[0]][0], "Error", {
      //             confirmButtonText: "OK",
      //             type: "error",
      //           });
      //         }
      //         $this.loading = false;
      //       });
      //   } else {
      //     return false;
      //   }
      // });
    },
  },
};
</script>
