<template>
  <span>
    <el-tooltip
      class="item"
      effect="dark"
      content="Editar contraseña de cancelación de recibos"
      placement="top"
    >
      <el-button type="text" icon="el-icon-lock" @click="dialogVisible = true"></el-button>
    </el-tooltip>
    <el-dialog
      style="text-align:left"
      title="Contraseña"
      :visible.sync="dialogVisible"
      width="400px"
      :before-close="handleClose"
    >
      <el-row>
        <el-col :span="24">
          <el-form
            :label-position="labelPosition"
            :rules="rules"
            :model="myPass"
            label-width="150px"
            ref="currentForm"
          >
            <el-form-item label="Contraseña" prop="password">
              <el-input v-model="myPass.password"></el-input>
            </el-form-item>
            <el-form-item label="Taller" prop="workshop">
              <el-select v-model="myPass.workshop" placeholder="Selecciona un taller">
                <el-option
                  v-for="w in workshops"
                  :key="w.id"
                  :label="w.name"
                  :value="w.id"
                >{{w.name}}</el-option>
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
  props: ["workshops", "myUser"],
  mounted: function () {
    // busca por default en el taller donde trabaja este empleado
    // if (this.myUser && this.myUser.length > 0) {
    //   this.myPass.workshop = this.myUser[0].workshop_id;
    // }
  },
  data() {
    return {
      dialogVisible: false,
      loading: false,
      labelPosition: "left",
      myPass: {
        name: "global",
        password: "",
        workshop: "",
      },
      rules: {
        password: [
          {
            required: true,
            message: "Campo Contraseña es obligatorio",
            trigger: "change",
          },
        ],
        workshop: [
          {
            required: true,
            message: "Campo Taller es obligatorio",
            trigger: "change",
          },
        ],
      },
    };
  },
  methods: {
    handleClose(done) {
      var $this = this;
      if ($this.myPass.password) {
        $this
          .$confirm("¿Estas seguro de no guardar la Contraseña?")
          .then((_) => {
            $this.cancel();
            done();
          })
          .catch((_) => {});
      } else {
        $this.cancel();
        done();
      }
    },
    cancel() {
      this.dialogVisible = false;
      this.loading = false;
      this.$refs.currentForm.resetFields();
    },
    save() {
      var $this = this;
      $this.$refs.currentForm.validate((valid) => {
        if (valid) {
          $this.loading = true;
          axios
            .post("/api/passmanager", $this.myPass)
            .then(function (response) {
              $this.$notify({
                title: "¡Exito!",
                message: "La contraseña fué editada correctamente",
                type: "success",
              });

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
