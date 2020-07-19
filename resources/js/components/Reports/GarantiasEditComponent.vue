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
            <el-form-item label="Empleado">
              <el-select v-model="selectedItem.employee" placeholder="Empleado" style="width:100%">
                <el-option
                  v-for="user in users"
                  :key="user.id"
                  :label="user.name"
                  :value="user.name"
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
  props: ["selectedItem", "hideButton", "workshop"],
  mounted: function() {
    var $this = this;

    axios.get("/api/users?all=1&role=Empleado").then(function(response) {
      $this.users = response.data;
    });
  },
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
      users: [],
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

      //this.selectedItem.sale_id = 1;

      console.log($this.selectedItem);
      this.dialogVisible = false;
      this.loading = false;
      $this.$refs.currentForm.validate(valid => {
        if (valid) {
          $this.loading = true;
          axios
            .post("/api/guarantee", $this.selectedItem)
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
