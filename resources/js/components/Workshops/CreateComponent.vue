<template>
  <el-col :span="5">
    <el-button
      type="primary"
      size="mini"
      icon="el-icon-circle-plus"
      @click="dialogVisible = true"
      style="float: right"
      >Agregar un Taller</el-button
    >
    <el-dialog
      title="Agregar un Taller"
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
            ref="workshopForm"
          >
            <el-form-item label="Nombre" prop="name">
              <el-input v-model="workshop.name"></el-input>
            </el-form-item>
            <el-form-item label="Encabezado" prop="address">
              <el-input
                v-model="workshop.address"
                maxlength="130"
              ></el-input>
            </el-form-item>
            <el-form-item label="Cuentas" prop="account">
              <el-input
                v-model="workshop.account"
                maxlength="57"
              ></el-input>
            </el-form-item>
            <el-form-item label="Teléfonos" prop="phone">
              <el-input
                v-model="workshop.phone"
                maxlength="57"
              ></el-input>
            </el-form-item>
            <el-form-item label="Sucursal 1" prop="sucursal1">
              <el-input
                v-model="workshop.sucursal1"
                maxlength="96"
              ></el-input>
            </el-form-item>
            <el-form-item label="Sucursal 2" prop="sucursal2">
              <el-input
                v-model="workshop.sucursal2"
                maxlength="110"
              ></el-input>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
      <span slot="footer" class="dialog-footer">
        <el-button @click="cancel()">Cancelar</el-button>
        <el-button type="primary" @click="save()" :loading="loading"
          >Agregar</el-button
        >
      </span>
    </el-dialog>
  </el-col>
</template>
<script>
export default {
  mounted: function () {},
  data() {
    return {
      dialogVisible: false,
      loading: false,
      labelPosition: "left",
      workshop: {
        name: "",
        address: "",
        account: "",
        phone: "",
        sucursal1: "",
        sucursal2: "",
        code: "",
        deleted: "",
      },
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
    handleClose(done) {
      var $this = this;
      $this.cancel();
      done();
    },
    cancel() {
      this.dialogVisible = false;
      this.loading = false;
      this.$refs.workshopForm.resetFields();
    },
    save() {
      var $this = this;
      $this.$refs.workshopForm.validate((valid) => {
        if (valid) {
          $this.loading = true;
          axios
            .post("/api/workshop", $this.workshop)
            .then(function (response) {
              $this.$notify({
                title: "¡Exito!",
                message: "El Taller fue agregado correctamente",
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
