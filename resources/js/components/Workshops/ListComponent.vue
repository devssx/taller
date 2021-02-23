<template>
  <el-row>
    <el-col :span="24">
      <el-table size="mini"
        v-loading="loading"
        :data="workshops"
        class="table"
        stripe
        border
        style="width: 100%"
      >
        <el-table-column prop="id" label="#" width="50px">
          <template slot-scope="scope">{{ scope.row.id }}</template>
        </el-table-column>
        <!-- <el-table-column prop="code" label="Código"></el-table-column> -->
        <el-table-column prop="name" label="Nombre"></el-table-column>
        <el-table-column prop="address" label="Encabezado"></el-table-column>
        <el-table-column prop="address" label="Cuenta / Teléfono"></el-table-column>
        <el-table-column prop="address" label="Sucursal 1"></el-table-column>
        <el-table-column prop="address" label="Sucursal 2"></el-table-column>
        <el-table-column prop="deleted" label="Activo">
          <template slot-scope="scope">{{ scope.row.deleted == 0 ? `Si` : `No` }}</template>
        </el-table-column>
        <el-table-column width="120px" header-align="right" align="right">
          <template slot-scope="scope">
            <edit-workshops :workshop="scope.row"></edit-workshops>
            <delete-workshops :workshop="scope.row"></delete-workshops>
          </template>
        </el-table-column>
      </el-table>

      <div class="block" style="text-align: center;" v-if="workshops.length > 10">
        <el-pagination
          layout="prev, pager, next"
          :page-size="10"
          :total="workshops.length"
          @current-change="handleCurrentChange"
        ></el-pagination>
      </div>
    </el-col>
  </el-row>
</template>

<script>
export default {
  mounted: function () {
    this.loadTable("/api/workshop");
    this.$root.$on("refreshTable", this.refreshTable);
  },
  methods: {
    loadTable(url) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function (response) {
        $this.workshops = response.data;
        $this.loading = false;
      });
    },
    refreshTable() {
      this.loadTable("/api/workshop");
    },
    handleCurrentChange(val) {
      this.page = val;
      this.refreshTable();
    },
  },
  data() {
    return {
      workshops: [],
      page: 1,
      loading: true,
    };
  },
};
</script>
