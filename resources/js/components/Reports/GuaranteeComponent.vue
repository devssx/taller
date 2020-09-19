<template>
  <el-row>
    <br />
    <el-row class="br bl bt bb row-header">
      <el-col :span="2">
        <h1 style="margin-top: 8px;">Año</h1>
      </el-col>
      <el-col :span="18">
        <el-date-picker v-model="selectedYear" type="year" placeholder="Seleccionar Año"></el-date-picker>
        <el-select width="150" v-model="workshopId" placeholder="Taller" :v-if="multiWorkshop">
          <el-option v-for="w in workshops" :key="w.id" :label="w.name" :value="w.id">{{w.name}}</el-option>
        </el-select>
        <el-button
          type="primary"
          icon="el-icon-search"
          @click="onSearch"
          :disabled="!selectedYear || !workshopId"
        ></el-button>
      </el-col>
      <el-col :span="4" align="end">
        <el-button type="primary" icon="el-icon-plus" @click="addNew">Nueva</el-button>
        <guarantee-edit
          :workshop="workshopId"
          :selectedItem="newItem"
          :hideButton="true"
          ref="newGuarantee"
        ></guarantee-edit>
      </el-col>
    </el-row>

    <br />
    <!-- TOTALES -->
    <el-row>
      <!-- Columna 2 -->
      <el-col :span="24">
        <!-- Tabla 1 -->
        <el-row class="br bt bl row-header">
          <el-col :span="24" align="center">
            <h1 style="color:#909399">Garantías del Año</h1>
          </el-col>
        </el-row>
        <el-row class="br bl">
          <el-col :span="24">
            <el-table size="mini" :data="tableData" style="width: 100%" v-loading="loading">
              <el-table-column align="center" label="No" width="120" prop="id">
                <template slot-scope="scope">{{pad(scope.row.id,5)}}</template>
              </el-table-column>
              <el-table-column align="center" label="Fecha trabajo" prop="done_on"></el-table-column>
              <el-table-column align="center" label="Fecha retrabajo" prop="new_date"></el-table-column>
              <el-table-column align="center" label="No. Recibo" width="120" prop="sale_id">
                <template slot-scope="scope">REC{{pad(scope.row.sale_id,5)}}</template>
              </el-table-column>
              <el-table-column align="center" label="Técnico" prop="employee"></el-table-column>
              <el-table-column align="center" label="Se Solucionó" prop="solution"></el-table-column>
              <el-table-column
                align="center"
                label="Motivo de Garantía / Comentario"
                width="400"
                prop="comment"
              ></el-table-column>
              <el-table-column label="Opciones" header-align="center" align="center" width="120">
                <template slot-scope="scope">
                  <guarantee-edit :workshop="workshopId" :selectedItem="scope.row"></guarantee-edit>
                </template>
              </el-table-column>
            </el-table>
          </el-col>
        </el-row>
      </el-col>
    </el-row>
    <br />
    <!-- TOTALES -->
    <el-row>
      <el-col :span="12">
        <el-row class="br bt bl bb row-header">
          <el-col :span="16" style="margin-top: 7px;">
            <h4>Cantidad de garantías recibidas en el año:</h4>
          </el-col>
          <el-col :span="8" class="row-headerb" align="end">
            <h4>{{cantidad}}</h4>
          </el-col>
        </el-row>
      </el-col>
      <el-col :span="16"></el-col>
    </el-row>
    <el-row>
      <el-col :span="12">
        <el-row class="br bt bl bb row-header">
          <el-col :span="16" style="margin-top: 7px;">
            <h4>Importe total de garantías recibidas en el año:</h4>
          </el-col>
          <el-col :span="8" class="row-headerb" align="end">
            <h4>${{formatPrice(total)}}</h4>
          </el-col>
        </el-row>
      </el-col>
      <el-col :span="16"></el-col>
    </el-row>
  </el-row>
</template>

<script>
export default {
  props: ["workshops", "myUser", "multiWorkshop"],
  mounted: function () {
    // busca por default en el taller donde trabaja este empleado
    if (this.myUser && this.myUser.length > 0) {
      if (!this.multiWorkshop) {
        this.workshopId = this.myUser[0].workshop_id;
        let year = this.selectedYear.getFullYear();
        this.loadTable(
          `/api/guarantee?year=${year}&workshop=${this.workshopId}`
        );
      }
    }

    this.$root.$on("refreshGuarantee", this.refreshTable);
  },
  methods: {
    addNew() {
      let year = this.selectedYear.getFullYear();
      this.newItem.workshop = this.workshopId;
      this.newItem.year = year;
      this.newItem.sale_id = "";
      this.newItem.employee = "";
      this.newItem.new_date = new Date();
      this.newItem.solution = "";
      this.newItem.comment = "";

      this.$refs.newGuarantee.dialogVisible = true;
    },
    loadTable(url) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function (response) {
        $this.tableData = response.data;
        $this.loading = false;

        $this.total = 0;
        $this.cantidad = $this.tableData.length;

        $this.tableData.forEach((s) => ($this.total += parseFloat(s.total)));
      });
    },
    onSearch() {
      if (!this.selectedYear) {
        this.$alert("Favor de seleccionar el año.", "Año no válido", {
          confirmButtonText: "OK",
          type: "warning",
        });
        return;
      }

      if (!this.workshopId) {
        this.$alert("Favor de seleccionar un taller.", "Taller no válido", {
          confirmButtonText: "OK",
          type: "warning",
        });
        return;
      }

      let year = this.selectedYear.getFullYear();
      this.loadTable(`/api/guarantee?year=${year}&workshop=${this.workshopId}`);
    },
    refreshTable() {
      let year = this.selectedYear.getFullYear();
      this.loadTable(`/api/guarantee?year=${year}&workshop=${this.workshopId}`);
    },
  },
  data() {
    return {
      total: 0,
      cantidad: 0,
      workshopId: "",
      showDialog: false,
      selectedYear: new Date(),
      search: "",
      loading: false,
      tableData: [],
      newItem: {
        workshop_id: "",
        sale_id: "",
        year: "",
        employee: "",
        new_date: new Date(),
        solution: "",
        comment: "",
      },
    };
  },
};
</script>
<style>
.bl {
  border-left: 1px solid #ebeef5;
}
.br {
  border-right: 1px solid #ebeef5;
}
.bt {
  border-top: 1px solid #ebeef5;
}
.bb {
  border-bottom: 1px solid #ebeef5;
}
.row-header {
  background-color: #f5f7fa;
  padding: 4px;
}
.row-headerb {
  background-color: white;
  padding: 4px;
}

/* Ipad Pro */
@media only screen and (max-width: 1366px) {
  .content.el-main {
    padding: 5px;
  }
  .select-ipad {
    width: 170px;
  }

  .el-form-item {
    margin-bottom: 4px;
  }

  .query-form {
    margin-top: 0;
    padding-top: 4px;
    background-color: #f2f2f2;
  }
}

/* Ipad */
@media only screen and (max-width: 1024px) {
  .content.el-main {
    padding: 5px;
  }
  .select-ipad {
    width: 130px;
  }

  .el-form-item {
    margin-bottom: 4px;
  }

  .query-form {
    margin-top: 0;
    padding-top: 4px;
    background-color: #f2f2f2;
  }
}
</style>
