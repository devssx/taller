<template>
  <span>
    <el-button
      icon="el-icon-edit"
      v-show="!hideButton"
      @click="onShow()"
      size="small"
      type="text"
    >Editar</el-button>
    <el-dialog
      style="text-align:left"
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
              <el-select
                style="width:100%"
                v-model="selectedItem.sale_id"
                filterable
                remote
                reserve-keyword
                placeholder="Busca por Recibo o Nombre"
                :remote-method="remoteMethod"
                :loading="loading"
              >
                <el-option
                  v-for="item in sale_options"
                  :key="item.id"
                  :label="item.label"
                  :value="item.id"
                ></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Solucionado" prop="solution">
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
      labelPosition: "left",
      sale_options: [],
      value: [],
      list: []
    };
  },
  methods: {
    onShow() {
      if (this.selectedItem.folio) this.remoteMethod(`REC${this.selectedItem.folio}`);
      this.dialogVisible = true;
    },
    remoteMethod(query) {
      var $this = this;

      if (query !== "") {
        $this.loading = true;
        var api = `/api/sales/search?search=${query}&workshop=${$this.workshop}`;

        if (query.toLowerCase().startsWith("rec")) {
          let id = query.substring(3);
          if (id.length > 0) {
            api = `/api/sales/searchById?id=${id}&workshop=${$this.workshop}`;
          } else {
            $this.loading = false;
            $this.sale_options = [];
            return;
          }
        }

        axios.get(api).then(function(response) {
          $this.loading = false;
          $this.sale_options = [];

          response.data.forEach(e => {
            var doneOn = new Date();
            try {
              doneOn = new Date(currentSale.done_on);
            } catch (ex) {
              doneOn = new Date();
            }

            let folio = `REC${$this.pad(e.folio, 5)}`;
            let fecha = `${$this.toFixedDateFormat(doneOn, "yyyy-MM-dd")}`;
            let label = `${fecha} - ${folio} - ${e.name}`;
            $this.sale_options.push({ label: label, id: e.id });
          });
        });
      } else {
        this.sale_options = [];
      }
    },
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

              $this.$root.$emit("refreshGuarantee");
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

