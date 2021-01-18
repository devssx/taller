<template>
  <span>
    <el-tooltip class="item" effect="dark" content="Desactivar" placement="top">
      <el-button size="mini" icon="el-icon-close" type="danger" @click="dialogVisible = true"></el-button>
    </el-tooltip>
    <el-dialog title="Advertencia" :visible.sync="dialogVisible" width="30%" center>
      <span>
        ¿Desea desactivar el Taller
        <b>{{ workshop.name }}</b>?
      </span>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">Cancelar</el-button>
        <el-button type="danger" @click="open" :loading="loading">Confirmar</el-button>
      </span>
    </el-dialog>
  </span>
</template>
<script>
export default {
  props: ["workshop"],
  data() {
    return {
      dialogVisible: false,
      loading: false
    };
  },
  methods: {
    open() {
      var $this = this;
      $this
        .$confirm(
          "Esto desactivará el registro. ¿Desea continuar?",
          "Advertencia",
          {
            confirmButtonText: "OK",
            cancelButtonText: "Cancel",
            type: "warning"
          }
        )
        .then(() => {
          $this.loading = true;
          axios
            .delete("/api/workshop/" + $this.workshop.id)
            .then(function(response) {
              $this.$message({
                type: "success",
                message: "Taller desactivado"
              });
              $this.$root.$emit("refreshTable");
              $this.dialogVisible = false;
              $this.loading = false;
            })
            .catch(error => {
              $this.loading = false;
              if (error.response.data.errors) {
                var errors = error.response.data.errors;
                $this.$alert(errors[Object.keys(errors)[0]][0], "Error", {
                  confirmButtonText: "OK",
                  type: "error"
                });
              }
            });
        })
        .catch(() => {});
    }
  }
};
</script>
