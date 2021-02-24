<template>
  <el-dialog
    :title="edit ? 'Editar recibo': 'Convertir a Recibo'"
    :visible.sync="dialogVisible"
    width="450px"
  >
    <el-row class="confirm">
      <el-col
        :span="24"
        style="margin-bottom: 10px;"
        v-if="!edit"
      >¿Quieres cambiar el estado la Orden de Servicio?</el-col>
      <el-col :span="24">
        <el-form ref="form" label-width="130px">
          <el-form-item label="Empleado:">
            <el-select v-model="user" placeholder="Empleado">
              <el-option v-for="user in users" :key="user.id" :label="user.name" :value="user.id"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Marca:">
            <el-input v-model="maker" maxlength="16"></el-input>
          </el-form-item>
          <el-form-item label="Modelo:">
            <el-input v-model="brand" maxlength="16"></el-input>
          </el-form-item>
          <el-form-item label="Año:">
            <el-input v-model="year" maxlength="4"></el-input>
          </el-form-item>
          <el-form-item label="Color:">
            <el-input v-model="color" maxlength="14"></el-input>
          </el-form-item>
          <el-form-item label="Próximo Servicio:">
            <el-date-picker
              v-model="next_service"
              type="date"
              format="dd-MM-yyyy"
              placeholder="Seleccionar Día"
            ></el-date-picker>
          </el-form-item>
          <el-form-item label="No. de Placa:">
            <el-input v-model="km" maxlength="10"></el-input>
          </el-form-item>
          <el-form-item label="Concepto:">
            <el-input type="textarea" v-model="concept" maxlength="300" :rows="2"></el-input>
          </el-form-item>
          <el-form-item label="Detalle:">
            <el-input type="textarea" v-model="details" maxlength="300" :rows="2"></el-input>
          </el-form-item>
          <el-form-item label="Garantía:">
            <el-input type="textarea" v-model="guaranty" maxlength="165" :rows="2"></el-input>
          </el-form-item>
          <el-form-item label="Total:">
            <el-input v-model="total" maxlength="18"></el-input>
          </el-form-item>
          <el-form-item label="Tipo de Servico:">
            <el-radio-group v-model="service_type">
              <el-radio :label="1" name="type">A/C</el-radio>
              <el-radio :label="2" name="type">Mecánico</el-radio>
              <el-radio :label="3" name="type">Eléctrico</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="Metodo de Pago:">
            <el-radio-group v-model="method">
              <el-radio :label="1" name="type" style="display:block">Efectivo</el-radio>
              <el-radio :label="2" name="type" style="display:block">Tarjeta de Crédito</el-radio>
              <el-radio :label="3" name="type" style="display:block">Tarjeta de Débito</el-radio>
              <el-radio :label="4" name="type" style="display:block">Cheque</el-radio>
              <el-radio :label="5" name="type" style="display:block">Transferencia</el-radio>
              <el-radio :label="6" name="type" style="display:block">Crédito</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="IVA:">
            <el-checkbox v-model="tax"></el-checkbox>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
    <span slot="footer" class="dialog-footer">
      <el-button @click="dialogVisible = false">No</el-button>
      <el-button type="primary" @click="save" :loading="loading">Si</el-button>
    </span>
  </el-dialog>
</template>

<script>
export default {
  data() {
    return {
      dialogVisible: false,
      edit: false,
      concept: "",
      maker: "",
      brand: "",
      year: "",
      color: "",
      next_service: "",
      km: "",
      details: "",
      guaranty: "",
      total: "",
      method: 1,
      service_type: 1,
      user: "",
      tax: false,
      users: [],
      sale: {},
      loading: false,
      newStatus: 0
    };
  },
  mounted: function() {
    var $this = this;

    axios.get("/api/users?all=1&role=Empleado").then(function(response) {
      $this.users = response.data;
    });

    this.$root.$on("confirmSale", this.openDialog);
    this.$root.$on("changeStatus", this.changeStatus);
  },
  methods: {
    changeStatus: function(sale, newStatus) {
      this.newStatus = newStatus;
      this.openDialog(sale);
    },
    openDialog: function(sale, edit = false) {
      this.dialogVisible = true;
      this.edit = edit;
      this.sale = sale;
      this.concept = sale.concept;
      this.maker = sale.maker ? sale.maker : sale.car[0].maker;
      this.brand = sale.brand ? sale.brand : sale.car[0].brand;
      this.year = sale.year ? sale.year : sale.sale_services[0].year;
      this.color = sale.color;
      this.next_service = sale.next_service;
      this.km = sale.km;
      this.details = sale.details;
      this.guaranty = sale.guaranty;
      this.tax = sale.tax == 1;
      this.method = sale.method > 1 ? parseInt(sale.method) : 1;
      this.service_type = sale.service_type > 1 ? parseInt(sale.service_type) : 1;
      this.user = sale.user_id;
      this.total = sale.total;
    },
    save: function() {
      var $this = this;
      $this.loading = true;
      axios
        .post("/api/sales/status", {
          id: $this.sale.id,
          status: $this.newStatus == 2 ? 2 : $this.sale.status,
          method: $this.method,
          service_type: $this.service_type,
          concept: $this.concept,
          maker: $this.maker,
          brand: $this.brand,
          year: $this.year,
          color: $this.color,
          next_service: $this.next_service,
          km: $this.km,
          total: $this.total,
          user: $this.user,
          details: $this.details,
          guaranty: $this.guaranty,
          tax: $this.tax
        })
        .then(function(response) {
          $this.dialogVisible = false;
          $this.loading = false;
          $this.$root.$emit("refreshTable");
          $this.$root.$emit("refreshReceipt", response.data.sale);
          $this.$message({
            type: "success",
            message: "Cambio de estatus exitoso"
          });
        });
    }
  }
};
</script>
<style lang="scss">
.confirm {
  .el-dialog__header {
    padding: 15px;
  }
  .el-dialog__body {
    padding: 10px 15px;
  }
  .el-form-item {
    margin-bottom: 10px;
  }
}
</style>
