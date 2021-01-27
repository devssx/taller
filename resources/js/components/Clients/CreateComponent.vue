<template>
  <el-col :span="5">
    <el-button
      type="primary"
      icon="el-icon-circle-plus"
      @click="dialogVisible = true"
      :style="onCreateClient?'':'float:right;'"
    >Agregar a un Cliente</el-button>
    <el-dialog
      title="Agregar a un Cliente"
      :visible.sync="dialogVisible"
      width="40%"
      :before-close="handleClose"
    >
      <el-row>
        <el-col :span="24">
          <el-form
            :label-position="labelPosition"
            :rules="rules"
            :model="client"
            label-width="150px"
            ref="clientForm"
          >
            <el-form-item label="Nombre" prop="name">
              <el-input v-model="client.name" maxlength="28"></el-input>
            </el-form-item>
            <el-form-item label="Telefono" prop="phonenumber">
              <el-input v-model="client.phonenumber" maxlength="18"></el-input>
            </el-form-item>
            <el-form-item label="Telefono 2" prop="phonenumber2">
              <el-input v-model="client.phonenumber2" maxlength="18"></el-input>
            </el-form-item>
            <el-form-item label="Direccion" prop="address">
              <el-input type="textarea" v-model="client.address"></el-input>
            </el-form-item>
            <el-form-item label="E-mail" prop="email">
              <el-input v-model="client.email"></el-input>
            </el-form-item>
            <el-form-item label="Referencia" prop="reference">
              <el-input v-model="client.reference"></el-input>
            </el-form-item>
            <el-form-item label="Otra Información" prop="information">
              <el-input type="textarea" v-model="client.information"></el-input>
            </el-form-item>
            <el-form-item label="Observación" prop="details">
              <el-input type="textarea" v-model="client.details"></el-input>
            </el-form-item>
            <el-form-item label="Recordatorio" prop="reminder">
              <el-input type="textarea" v-model="client.reminder"></el-input>
            </el-form-item>
            <el-form-item label="Fecha Recordatorio" prop="reminder_date">
              <el-date-picker
                v-model="client.reminder_date"
                type="date"
                format="dd-MM-yyyy"
                placeholder="Seleccionar Día"
              ></el-date-picker>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
      <span slot="footer" class="dialog-footer">
        <el-button @click="cancel()">Cancelar</el-button>
        <el-button type="primary" @click="saveClient()" :loading="loading">Agregar</el-button>
      </span>
    </el-dialog>
  </el-col>
</template>
<script>
export default {
  props: ["onCreateClient"],
  data() {
    return {
      dialogVisible: false,
      loading: false,
      labelPosition: "left",
      client: {
        name: "",
        phonenumber: "",
        phonenumber2: "",
        email: "",
        address: "",
        reference: "",
        information: "",
        details: "",
        reminder: "",
        reminder_date: ""
      },
      rules: {
        name: [
          {
            required: true,
            message: "Campo Nombre es obligatorio",
            trigger: "change"
          }
        ]
      }
    };
  },
  methods: {
    handleClose(done) {
      var $this = this;
      if ($this.client.name) {
        $this
          .$confirm("¿Estas seguro de no guardar el Cliente?")
          .then(_ => {
            $this.cancel();
            done();
          })
          .catch(_ => {});
      } else {
        $this.cancel();
        done();
      }
    },
    cancel() {
      this.dialogVisible = false;
      this.loading = false;
      this.$refs.clientForm.resetFields();
    },
    saveClient() {
      var $this = this;
      $this.$refs.clientForm.validate(valid => {
        if (valid) {
          $this.loading = true;
          axios
            .post("/api/clients", $this.client)
            .then(function(response) {
              // Event create client
              if ($this.onCreateClient) $this.onCreateClient(response.data);

              $this.$notify({
                title: "¡Exito!",
                message: "Cliente fue agregado correctamente",
                type: "success"
              });
              $this.$root.$emit("refreshTable");
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
