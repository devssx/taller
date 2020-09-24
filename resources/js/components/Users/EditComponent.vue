<template>
  <span>
    <el-tooltip class="item" effect="dark" content="Editar" placement="top">
      <el-button icon="el-icon-edit" @click="onShow"></el-button>
    </el-tooltip>
    <el-dialog
      title="Editar Usuario"
      :visible.sync="dialogVisible"
      width="40%"
      :before-close="handleClose"
    >
      <el-row>
        <el-col :span="24">
          <el-form
            :label-position="labelPosition"
            :rules="rules"
            :model="user"
            label-width="150px"
            ref="userForm"
          >
            <el-form-item label="Nombre" prop="name">
              <el-input v-model="user.name"></el-input>
            </el-form-item>
            <el-form-item v-show="!noEmail" label="Correo Electronico" prop="email">
              <el-input v-model="user.email"></el-input>
            </el-form-item>
            <el-form-item v-show="!noEmail" label="Contraseña" prop="password">
              <el-input v-model="user.password"></el-input>
            </el-form-item>
            <el-form-item label="Rol" prop="role">
              <el-select v-model="user.role" placeholder="Selecciona rol" @change="changeRole">
                <el-option
                  v-for="role in roles"
                  :key="role.id"
                  :label="role.name"
                  :value="role.id"
                >{{role.name}}</el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Taller" prop="taller">
              <el-select
                v-model="taller"
                placeholder="Selecciona un taller"
                @change="changeWorkshop"
              >
                <el-option v-for="w in workshops" :key="w.id" :label="w.name" :value="w.name"></el-option>
              </el-select>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
      <span slot="footer" class="dialog-footer">
        <el-button @click="cancel()">Cancelar</el-button>
        <el-button type="primary" @click="saveUser()" :loading="loading">Guardar</el-button>
      </span>
    </el-dialog>
  </span>
</template>
<script>
export default {
  props: ["roles", "user", "workshops"],
  beforeUpdate: function () {
    if (!this.user.role) {
      this.user.role = 0;
      if (this.user.roles.length > 0) {
        this.user.role = this.user.roles[0].id;
        let name = this.user.roles[0].name;
        this.noEmail =
          name == "Limpieza" ||
          name == "Empleado" ||
          this.user.email.includes("@empty.com");
      }
    }
  },
  data() {
    return {
      taller: "",
      auxMail: "",
      noEmail: false,
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
        email: [
          {
            required: true,
            message: "Campo Correo Electronico es obligatorio",
            trigger: "change",
          },
          {
            type: "email",
            message: "Correo Electronico invalido",
            trigger: "change",
          },
        ],
        role: [
          {
            required: true,
            message: "Campo Rol es obligatorio",
            trigger: "change",
          },
        ],
      },
    };
  },
  methods: {
    onShow() {
      this.dialogVisible = true;
      var ws = this.workshops.filter((w) => w.id == this.user.workshop_id);
      if (ws.length > 0) this.taller = ws[0].name;
    },
    changeWorkshop(value) {
      this.taller = value;
      var ws = this.workshops.filter((w) => w.name == value);
      if (ws.length > 0) this.user.workshop_id = ws[0].id;
      console.log(this.user.workshop_id);
    },
    onChangeRol(value) {
      let rs = this.roles.filter((r) => r.id == value);
      if (rs.length > 0) {
        this.noEmail = rs[0].name == "Limpieza" || rs[0].name == "Empleado";

        if (!this.user.email.includes("@empty.com"))
          this.auxMail = this.user.email;

        if (this.noEmail) {
          var numb = Math.floor(Math.random() * 1000000);
          var nb = this.pad(numb, 7);
          this.user.email = `e${nb}@empty.com`;
          this.user.password = Math.floor(Math.random() * 1000000);
        } else {
          if (!this.auxMail.includes("@empty.com"))
            this.user.email = this.auxMail;
          this.user.password = "";
        }
      }
    },
    handleClose(done) {
      var $this = this;
      if ($this.user.name || $this.user.email || $this.user.password) {
        $this
          .$confirm("¿Estas seguro de no guardar el Usuario?")
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
    changeRole(value) {
      this.user.role = value;
      this.onChangeRol(value);
      this.$forceUpdate();
    },
    cancel() {
      this.dialogVisible = false;
      this.loading = false;
      let name = this.user.roles[0].name;
      this.noEmail =
        name == "Limpieza" ||
        name == "Empleado" ||
        this.user.email.includes("@empty.com");
      this.$refs.userForm.resetFields();
    },
    saveUser() {
      var $this = this;

      if (!this.taller) {
        $this.$notify({
          title: "Error",
          message: "El taller no es válido.",
          type: "error",
        });
        return;
      }

      $this.$refs.userForm.validate((valid) => {
        if (valid) {
          $this.loading = true;
          axios
            .post("/api/users", $this.user)
            .then(function (response) {
              $this.$notify({
                title: "¡Exito!",
                message: "El Usuario fue editado correctamente",
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
