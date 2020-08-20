<template>
  <span>
    <el-tooltip class="item" effect="dark" content="Editar" placement="top">
      <el-button size="mini" icon="el-icon-edit" @click="dialogVisible = true"></el-button>
    </el-tooltip>
    <el-dialog
      title="Editar un Artículo"
      :visible.sync="dialogVisible"
      width="40%"
      :before-close="handleClose"
    >
      <el-row>
        <el-col :span="24">
          <el-form
            :label-position="labelPosition"
            :rules="rules"
            :model="item"
            label-width="150px"
            ref="itemForm"
          >
            <el-form-item label="Nombre del Artículo" prop="name">
              <el-input v-model="item.name" :disabled="true"></el-input>
            </el-form-item>
            <el-form-item label="Foto del Artículo" prop="image">
              <el-col :span="21">
                <el-input v-model="item.image"></el-input>
              </el-col>
              <el-col :span="3">
                <file-upload
                  ref="uploader"
                  :onSelected="onSelected"
                  :onPreview="onPreview"
                  action="/files/uploadItem"
                ></file-upload>
              </el-col>
            </el-form-item>
            <el-form-item label="Descripción" prop="description">
              <el-input type="textarea" v-model="item.description"></el-input>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
      <el-row v-if="selectedFile" type="flex" align="middle" style="text-align: center;">
        <el-col :span="24">
          <img id="preview" style="width:256px;" />
        </el-col>
      </el-row>
      <el-row
        v-if="!selectedFile && item.image!=''"
        type="flex"
        align="middle"
        style="text-align: center;"
      >
        <el-col :span="24">
          <el-image style="width:256px;" :src="item.image"></el-image>
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
  props: ["item"],
  data() {
    return {
      selectedFile: null,
      dialogVisible: false,
      loading: false,
      labelPosition: "left",
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
    onSelected(file) {
      this.selectedFile = file;
      this.item.image = this.selectedFile.name;

      // validar size, tipo
      if (this.selectedFile.size > 500000) {
        this.$alert("Error la imagen debe ser menor a 512 KB", "Error", {
          confirmButtonText: "OK",
          type: "error"
        });

        // invalid size
        this.selectedFile = null;
        this.item.image = "";
      }
    },
    onPreview(imgData) {
      $("#preview").attr("src", imgData);
    },
    handleClose(done) {
      var $this = this;
      if ($this.item.name) {
        $this
          .$confirm("¿Estas seguro de no guardar el Artículo?")
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
      this.$refs.itemForm.resetFields();
      this.selectedFile = null;
    },
    save() {
      var $this = this;
      $this.$refs.itemForm.validate(valid => {
        //console.log($this.selectedFile);
        if (valid) {
          $this.loading = true;

          if ($this.selectedFile) {
            // upload a file
            $this.$refs.uploader.submit(
              imgServerPath => {
                // success
                $this.item.image = imgServerPath;
                $this.saveItem();
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
            $this.saveItem();
          }
        } else {
          return false;
        }
      });
    },
    saveItem() {
      var $this = this;
      $this.$refs.itemForm.validate(valid => {
        if (valid) {
          console.log($this.item);
          axios
            .post("/api/updateItem", $this.item)
            .then(function(response) {
              $this.$notify({
                title: "¡Exito!",
                message: "Articulo fue editado correctamente",
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
