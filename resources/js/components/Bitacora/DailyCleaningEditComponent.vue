<template>
  <span>
    <el-button @click="dialogVisible = true" size="small" type="text">Editar</el-button>
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
              <el-input v-model="selectedItem.employee" :disabled="true"></el-input>
            </el-form-item>

            <el-form-item label="Hora Entrada" prop="start">
              <el-time-picker
                arrow-control
                v-model="entrada"
                :picker-options="{
                  selectableRange: '06:00:00 - 18:00:00',
                }"
                placeholder="Entrada"
              ></el-time-picker>
            </el-form-item>
            <el-form-item label="Limpieza" prop="cleaning">
              <el-input v-model="selectedItem.cleaning"></el-input>
            </el-form-item>
            <el-form-item label="Hoda de Desayuno">
              <template>
                <el-time-picker
                  is-range
                  arrow-control
                  v-model="value1"
                  range-separator="A"
                  start-placeholder="Inicio"
                  end-placeholder="Fin"
                ></el-time-picker>
              </template>
            </el-form-item>
            <el-form-item label="Hora de Comida">
              <template>
                <el-time-picker
                  is-range
                  arrow-control
                  v-model="value2"
                  range-separator="A"
                  start-placeholder="Inicio"
                  end-placeholder="Fin"
                ></el-time-picker>
              </template>
            </el-form-item>
            <el-form-item label="Cumplio" prop="done">
              <el-select v-model="selectedItem.done" filterable>
                <el-option
                  v-for="opt in options"
                  :key="opt.value"
                  :label="opt.label"
                  :value="opt.value"
                ></el-option>
              </el-select>
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
  props: ["selectedItem"],
  mounted: function() {
    this.$root.$on("showEditor", this.showEditor);
  },
  data() {
    return {
      entrada: new Date(this.selectedItem.start),

      value1: [new Date(this.selectedItem.breakfast_start), new Date(this.selectedItem.breakfast_end)],
      value2: [new Date(this.selectedItem.lunch_start), new Date(this.selectedItem.lunch_end)],

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

      //selectedItem: null,
      dialogVisible: false,
      loading: false,
      labelPosition: "left"
    };
  },
  methods: {
    fixNumber(n) {
      return n < 10 ? "0" + n : n;
    },
    toFixedFormat(dt) {
      // 2020-05-15 15:00:00
      var yyyy = dt.getFullYear();
      var MM = this.fixNumber(dt.getMonth() + 1);
      var dd = this.fixNumber(dt.getDay());

      var hh = this.fixNumber(dt.getHours());
      var mm = this.fixNumber(dt.getMinutes());
      var ss = this.fixNumber(dt.getSeconds());
      
      // Implementar mas formatos usando un switch si es necesario
      return `${yyyy}-${MM}-${dd} ${hh}:${mm}:${ss}`;
    },
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
      
      // ASIGNACION
      this.selectedItem.start = this.toFixedFormat(this.entrada);
      this.selectedItem.breakfast_start = this.toFixedFormat(this.value1[0]);
      this.selectedItem.breakfast_end = this.toFixedFormat(this.value1[1]);
      this.selectedItem.lunch_start = this.toFixedFormat(this.value2[0]);
      this.selectedItem.lunch_end = this.toFixedFormat(this.value2[1]);

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
    }
  }
};
</script>
