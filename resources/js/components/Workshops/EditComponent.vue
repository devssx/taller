<template>
  <span>
    <el-tooltip class="item" effect="dark" content="Editar" placement="top">
      <el-button size="mini" icon="el-icon-edit" @click="onShow"></el-button>
    </el-tooltip>
    <el-dialog style="text-align: center;"
      title="Editar Taller"
      :visible.sync="dialogVisible"
      width="40%"
      :before-close="handleClose"
    >
      <el-row>
        <el-col :span="24">
          <el-form
            :label-position="labelPosition"
            :rules="rules"
            :model="workshop"
            label-width="150px"
            ref="currentForm"
          >
            <el-form-item label="Nombre" prop="name">
              <el-input v-model="workshop.name"></el-input>
            </el-form-item>
            <el-form-item label="Encabezado" prop="address">
              <el-input v-model="workshop.address" maxlength="130"></el-input>
            </el-form-item>
            <el-form-item label="Cuenta" prop="account">
              <el-input v-model="workshop.account" maxlength="57"></el-input>
            </el-form-item>
            <el-form-item label="Teléfono" prop="phone">
              <el-input v-model="workshop.phone" maxlength="57"></el-input>
            </el-form-item>
            <el-form-item label="Sucursal 1" prop="sucursal1">
              <el-input v-model="workshop.sucursal1" maxlength="96"></el-input>
            </el-form-item>
            <el-form-item label="Sucursal 2" prop="sucursal2">
              <el-input v-model="workshop.sucursal2" maxlength="110"></el-input>
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
  props: ["workshop"],
  beforeUpdate: function () {

  },
  data() {
    return {
      dialogVisible: false,
      loading: false,
      labelPosition: "left",
      rules: {
        name: [
          {
            required: true,
            message: "Campo Nombre es obligatorio",
            trigger: "change",
          },
        ],
        address: [
          {
            required: true,
            message: "Campo Encabezado es obligatorio",
            trigger: "change",
          },
        ],
        account: [
          {
            required: true,
            message: "Campo Cuentas es obligatorio",
            trigger: "change",
          },
        ],
        phone: [
          {
            required: true,
            message: "Campo Telefonos es obligatorio",
            trigger: "change",
          },
        ],
      },
    };
  },
  methods: {
    onShow() {
      this.dialogVisible = true;
    },
    handleClose(done) {
      var $this = this;
      $this.cancel();
      done();
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
            .post("/api/workshop", $this.workshop)
            .then(function (response) {
              $this.$notify({
                title: "¡Exito!",
                message: "El Taller fué editado correctamente",
                type: "success",
              });
              $this.$root.$emit("refreshTable");
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
