<template>
  <span>
    <el-button v-if="!hideButton" @click="dialogVisible = true" size="small" type="text">Editar</el-button>
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
            <!-- Selectoer de usurio (Modo insertar nuevo registro) -->
            <el-form-item v-if="!selectedItem.id" label="Nombre" prop="name">
              <el-select v-model="selectedItem.user_id" filterable style="width:100%">
                <el-option v-for="opt in users" :key="opt.id" :label="opt.name" :value="opt.id"></el-option>
              </el-select>
            </el-form-item>

            <el-form-item v-if="selectedItem.id" label="Empleado" prop="name">
              <el-input v-model="selectedItem.name" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="Hora Entrada" prop="start">
              <el-time-picker
                style="width:100%"
                arrow-control
                v-model="entrada"
                :picker-options="{
                  selectableRange: '04:00:00 - 18:00:00',
                }"
                placeholder="Entrada"
              ></el-time-picker>
            </el-form-item>
            <el-form-item label="Limpieza" prop="cleaning">
              <el-input v-model="selectedItem.cleaning"></el-input>
            </el-form-item>
            <el-form-item label="Hora de Desayuno">
              <el-time-picker
                style="width:100%"
                is-range
                arrow-control
                v-model="breakfast"
                range-separator="A"
                start-placeholder="Inicio"
                end-placeholder="Fin"
              ></el-time-picker>
            </el-form-item>
            <el-form-item label="Hora de Comida">
              <el-time-picker
                style="width:100%"
                is-range
                arrow-control
                v-model="lunch"
                range-separator="A"
                start-placeholder="Inicio"
                end-placeholder="Fin"
              ></el-time-picker>
            </el-form-item>
            <el-form-item label="Cumplió" prop="done">
              <el-select v-model="selectedItem.done" filterable style="width:100%">
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
  props: ["selectedItem", "hideButton"],
  mounted: function() {
    // evento
    //this.$root.$on("insertNewRow", this.insertNewRow);
    this.loadUsers("/api/users");
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
    loadUsers(url) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function(response) {
        $this.users = response.data.data;
        $this.loading = false;
      });
    },
    insertNewRow(currentDay) {
      // Llamado desde el boton 'Nuevo'
      this.dialogVisible = true;
      this.selectedItem.start = currentDay;
      this.selectedItem.breakfast_start = currentDay;
      this.selectedItem.breakfast_end = currentDay;
      this.selectedItem.lunch_start = currentDay;
      this.selectedItem.lunch_end = currentDay;

      this.entrada = currentDay;
      this.breakfast = [currentDay, currentDay];
      this.lunch = [currentDay, currentDay];
    },
    fixNumber(n) {
      return n < 10 ? "0" + n : n;
    },
    toFixedFormat(dt) {
      // 2020-05-15 15:00:00
      var yyyy = dt.getFullYear();
      var MM = this.fixNumber(dt.getMonth() + 1);
      var dd = this.fixNumber(dt.getDate());

      var hh = this.fixNumber(dt.getHours());
      var mm = this.fixNumber(dt.getMinutes());
      var ss = this.fixNumber(dt.getSeconds());

      // Implementar mas formatos usando un switch si es necesario
      return `${yyyy}-${MM}-${dd} ${hh}:${mm}:${ss}`;
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
      var $this = this;
      $this.selectedItem.start = this.toFixedFormat(this.entrada);
      $this.selectedItem.breakfast_start = this.toFixedFormat(
        this.breakfast[0]
      );
      $this.selectedItem.breakfast_end = this.toFixedFormat(this.breakfast[1]);
      $this.selectedItem.lunch_start = this.toFixedFormat(this.lunch[0]);
      $this.selectedItem.lunch_end = this.toFixedFormat(this.lunch[1]);

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
