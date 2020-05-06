<template>
  <span>
    <el-tooltip effect="dark" content="Seleccionar Foto" placement="right">
      <button
        @click="dialogVisible=true"
        class="el-icon-camera"
        style="cursor:pointer;border:none;background-color:white;margin:4px;color:#409EFF;"
      ></button>
    </el-tooltip>

    <el-dialog
      title="Subir Imágen"
      :visible.sync="dialogVisible"
      width="40%"
      :before-close="handleClose"
    >
      <el-row>
        <el-col :span="24">
          <el-form :label-position="labelPosition" :model="item" label-width="150px" ref="itemForm">
            <el-form-item label="Foto del Artículo">
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
        <el-button type="primary" @click="save()" :loading="loading">Subir</el-button>
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
      labelPosition: "left"
    };
  },
  methods: {
    onSelected(file) {
      this.selectedFile = file;

      // validar size, tipo
      if (this.selectedFile.size > 500000) {
        this.$alert("Error la imagen debe ser menor a 512 KB", "Error", {
          confirmButtonText: "OK",
          type: "error"
        });

        // invalid size
        this.selectedFile = null;
      }
    },
    onPreview(imgData) {
      $("#preview").attr("src", imgData);
    },
    handleClose(done) {
      var $this = this;
      $this.cancel(true);
      done();
    },
    cancel(revert) {
      this.dialogVisible = false;
      this.loading = false;
      this.selectedFile = null;
      if (revert) this.$refs.itemForm.resetFields();
    },
    save() {
      var $this = this;
      $this.loading = true;

      if ($this.selectedFile) {
        // upload a file
        $this.$refs.uploader.submit(
          imgServerPath => {
            // success
            $this.item.image = imgServerPath;
            $this.cancel(false);
          },
          error => {
            $this.$notify({
              title: "Error!",
              message:
                "Ha ocurrido un error al intentar subir la imagen, intenta con otra",
              type: "error"
            });

            $this.loading = false;
          }
        );
      }
    }
  }
};
</script>
