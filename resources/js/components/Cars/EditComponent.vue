<template>
  <span>
    <el-tooltip class="item" effect="dark" content="Editar" placement="top">
      <el-button icon="el-icon-edit" @click="dialogVisible = true"></el-button>
    </el-tooltip>
    <el-dialog
      title="Editar un Carro"
      :visible.sync="dialogVisible"
      width="40%"
      :before-close="handleClose"
    >
      <el-row>
        <el-col :span="24">
          <el-form
            :label-position="labelPosition"
            :rules="rules"
            :model="car"
            label-width="150px"
            ref="carForm"
          >
            <el-form-item label="Marca del Carro" prop="maker">
              <el-input v-model="car.maker"></el-input>
            </el-form-item>
            <el-form-item label="Modelo" prop="brand">
              <el-input v-model="car.brand"></el-input>
            </el-form-item>
            <el-form-item label="Motor" prop="motor">
              <el-input v-model="car.motor"></el-input>
            </el-form-item>
            <!-- Image select -->
            <el-form-item label="Foto" prop="image">
              <el-col :span="21">
                <el-input v-model="car.image"></el-input>
              </el-col>
              <el-col :span="3">
                <file-upload
                  ref="uploader"
                  :onSelected="onSelected"
                  :onPreview="onPreview"
                  action="/files/uploadCar"
                ></file-upload>
              </el-col>
            </el-form-item>
            <el-form-item :label="'Año (' + car.start_year + '-' + car.end_year + ')'">
              <el-slider
                v-model="car.year"
                range
                show-stops
                :min="1999"
                :max="2019"
                @change="changeYear"
              ></el-slider>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
      <!-- Preview -->
      <el-row v-if="selectedFile" type="flex" align="middle" style="text-align: center;">
        <el-col :span="24">
          <img id="preview" style="width:256px;" />
        </el-col>
      </el-row>
      <el-row
        v-if="!selectedFile && car.image!=''"
        type="flex"
        align="middle"
        style="text-align: center;"
      >
        <el-col :span="24">
          <el-image style="width:256px;" :src="car.image"></el-image>
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
  props: ["car"],
  data() {
    return {
      selectedFile: null,
      dialogVisible: false,
      loading: false,
      labelPosition: "left",
      rules: {
        maker: [
          {
            required: true,
            message: "Campo Marca es obligatorio",
            trigger: "change"
          }
        ],
        brand: [
          {
            required: true,
            message: "Campo Modelo es obligatorio",
            trigger: "change"
          }
        ],
        motor: [
          {
            required: true,
            message: "Campo Motor es obligatorio",
            trigger: "change"
          }
        ]
      }
    };
  },
  watch: {
    car: function() {
      this.car.year = [this.car.start_year, this.car.end_year];
    }
  },
  methods: {
    onSelected(file) {
      this.selectedFile = file;
      this.car.image = this.selectedFile.name;

      // size 1Mb
      if (this.selectedFile.size > 1000000) {
        this.$alert(
          "Tamaño excedido, la imagen debe ser menor a 1 Mb",
          "Error",
          {
            confirmButtonText: "OK",
            type: "error"
          }
        );

        // invalid size
        this.selectedFile = null;
        this.car.image = "";
      }
    },
    onPreview(imgData) {
      $("#preview").attr("src", imgData);
    },
    handleClose(done) {
      var $this = this;
      if ($this.car.brand) {
        $this
          .$confirm("¿Estas seguro de no guardar el Carro?")
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
      this.$refs.carForm.resetFields();
      this.selectedFile = null;
    },
    save() {
      var $this = this;
      $this.$refs.carForm.validate(valid => {
        //console.log($this.selectedFile);
        if (valid) {
          $this.loading = true;

          if ($this.selectedFile) {
            // upload a file
            $this.$refs.uploader.submit(
              imgServerPath => {
                // success
                $this.car.image = imgServerPath;
                $this.saveCar();
              },
              error => {
                //console.log(error);
                $this.$notify({
                  title: "Error!",
                  message:
                    "Ha ocurrido un error al intentar subir la imagen, intenta con otra",
                  type: "error"
                });

                $this.loading = false;
              }
            );
          } else {
            $this.saveCar();
          }
        } else {
          return false;
        }
      });
    },
    saveCar() {
      var $this = this;
      $this.$refs.carForm.validate(valid => {
        if (valid) {
          $this.loading = true;
          axios
            .post("/api/cars", $this.car)
            .then(function(response) {
              $this.$notify({
                title: "¡Exito!",
                message: "El Carro fue editado correctamente",
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
    },
    changeYear(value) {
      this.car.start_year = value[0];
      this.car.end_year = value[1];
    }
  }
};
</script>
