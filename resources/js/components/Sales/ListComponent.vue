<template>
  <el-row>
    <el-col :span="24">
      <el-table
        v-loading="loading"
        :data="sales.data"
        size="small"
        class="table"
        stripe
        border
        style="width: 100%"
      >
        <el-table-column prop="id" label="#" width="50px">
          <template slot-scope="scope">{{ scope.row.id }}</template>
        </el-table-column>
        <el-table-column label="Carro">
          <template
            slot-scope="scope"
          >{{ scope.row.car[0].brand }} {{ scope.row.sale_services[0].year ? scope.row.sale_services[0].year : scope.row.car[0].start_year +"-"+ scope.row.car[0].end_year }}</template>
        </el-table-column>
        <el-table-column label="Servicios">
          <template slot-scope="scope">
            {{ scope.row.services.name }}
            <ul>
              <li
                v-for="(service, index) in scope.row.services"
                v-bind:key="index"
              >{{ service.name }}</li>
            </ul>
          </template>
        </el-table-column>
        <el-table-column label="Cliente">
          <template slot-scope="scope">{{ scope.row.client ? scope.row.client.name : '' }}</template>
        </el-table-column>
        <el-table-column label="Tipo">
          <template slot-scope="scope">{{status[scope.row.status]}}</template>
        </el-table-column>
        <el-table-column label="Total" align="right">
          <template slot-scope="scope">${{ formatPrice(scope.row.total) }}</template>
        </el-table-column>
        <el-table-column prop="created_at" label="Fecha"></el-table-column>
        <el-table-column width="180px" align="right">
          <template slot-scope="scope">
            <el-tooltip
              class="item"
              effect="dark"
              content="Crear cotización con la misma información"
              placement="top"
            >
              <el-button
                size="mini"
                icon="el-icon-document-copy"
                @click="goto('/sales/copy/' + scope.row.id)"
              ></el-button>
            </el-tooltip>
            <el-tooltip
              size="mini"
              class="item"
              effect="dark"
              content="Ver Cotización"
              placement="top"
              v-if="scope.row.status != 2"
            >
              <el-button
                size="mini"
                icon="el-icon-tickets"
                @click="goto('/sales/receipt/' + scope.row.id)"
              ></el-button>
            </el-tooltip>
            <el-tooltip
              class="item"
              effect="dark"
              content="Ver Recibo"
              placement="top"
              v-if="scope.row.status == 2"
            >
              <el-button size="mini" icon="el-icon-tickets" @click="goto('/sales/receipt/' + scope.row.id)"></el-button>
            </el-tooltip>
            <el-tooltip class="item" effect="dark" content="Cancelar" placement="top">
              <el-button
                size="mini"
                :disabled="scope.row.status > 2"
                type="danger"
                icon="el-icon-document-delete"
                @click="cancelOrder(scope.row, scope.$index)"
              ></el-button>
            </el-tooltip>
          </template>
        </el-table-column>
      </el-table>
      <div class="block" style="text-align: center;" v-if="sales.total > 10">
        <el-pagination
          layout="prev, pager, next"
          :page-size="10"
          :total="sales.total"
          @current-change="handleCurrentChange"
        ></el-pagination>
      </div>

      <confirm-sales></confirm-sales>
      <el-row type="flex" justify="end" style="opacity: 0;overflow: hidden;height: 50px;">
        <el-col :span="12">
          <img ref="receipt" src="/img/receipt.jpg" width="1200px" />
        </el-col>
        <el-col :span="12">
          <canvas ref="my-canvas"></canvas>
        </el-col>
      </el-row>
    </el-col>
  </el-row>
</template>

<script>
import { Printd } from "printd";

export default {
  props: ["workshops", "myUser"],
  mounted: function () {
    this.$root.$on("refreshTable", this.refreshTable);

    // busca por default en el taller donde trabaja este empleado
    if (this.myUser && this.myUser.length > 0) {
      this.workshopId = this.myUser[0].workshop_id;
      this.loadTable(`/api/sales?workshop=${this.workshopId}`);
    }
  },
  methods: {
    refreshTable() {
      this.loadTable(
        `/api/sales?workshop=${this.workshopId}&page=` + this.page
      );
    },
    loadTable(url) {
      var $this = this;
      $this.loading = true;
      axios.get(url).then(function (response) {
        $this.sales = response.data;
        $this.oldSales = JSON.parse(JSON.stringify(response.data));
        $this.loading = false;
      });
    },
    formatPrice(value) {
      let val = (value / 1).toFixed(2);
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },
    handleCurrentChange(val) {
      this.page = val;
      this.refreshTable();
    },
    covrterToReceipt(sale, index) {
      sale.status = 2;
      this.handleChangeStatus(index);
    },
    cancelOrder(sale, index) {
      const $this = this;

      this.checkPassword(this.workshopId, (result) => {
        if (result == "success") {
          sale.status = 3;
          $this.handleChangeStatus(index);
        } else {
          $this.$message.error(result);
        }
      });
    },
    handleChangeStatus(index) {
      var $this = this;
      var message = $this.$createElement(
        "p",
        null,
        "¿Quieres cambiar el estado la Orden de Servicio?"
      );

      if ($this.sales.data[index].status == 2) {
        $this.$root.$emit("confirmSale", $this.sales.data[index]);
        $this.sales = JSON.parse(JSON.stringify($this.oldSales));
        return false;
      }

      $this
        .$msgbox({
          title: "Cambiar orden de estado",
          message: message,
          showCancelButton: true,
          confirmButtonText: "Si",
          cancelButtonText: "No",
        })
        .then(() => {
          axios
            .post("api/sales/status", {
              id: $this.sales.data[index].id,
              status: $this.sales.data[index].status,
            })
            .then(function (response) {
              $this.oldSales = JSON.parse(JSON.stringify($this.sales));
            });
          $this.$message({
            type: "success",
            message: "Cambio de estatus exitoso",
          });
        })
        .catch((_) => {
          $this.sales = JSON.parse(JSON.stringify($this.oldSales));
        });
    },
    goto: function (link) {
      window.location.href = link;
    },
    pad(number, size) {
      var s = String(number);
      while (s.length < (size || 2)) {
        s = "0" + s;
      }
      return s;
    },
    /* Movido a Receipt
    buildReceipt(currentSale) {
      // crea el recibo pdf
      var $this = this;

      $this.$refs["my-canvas"].width = $this.$refs["receipt"].width;
      $this.$refs["my-canvas"].height = $this.$refs["receipt"].height - 109;
      $this.context = $this.$refs["my-canvas"].getContext("2d");

      $this.context.clearRect(
        0,
        0,
        $this.$refs["my-canvas"].width,
        $this.$refs["my-canvas"].height
      );

      $this.context.fillStyle = "#FFFFFF";
      $this.context.fillRect(
        0,
        0,
        $this.$refs["my-canvas"].width,
        $this.$refs["my-canvas"].height
      );
      $this.context.drawImage(
        $this.$refs["receipt"],
        -30,
        -30,
        $this.$refs["receipt"].width,
        $this.$refs["receipt"].height
      );

      $this.context.font = "24px Calibri";
      $this.context.fillStyle = "red";
      $this.context.fillText($this.pad(currentSale.id, 5), 960, 132); // FOLIO

      $this.context.fillStyle = "black";
      $this.context.fillText(
        new Date()
          .toLocaleDateString("es-ES", {
            year: "numeric",
            month: "2-digit",
            day: "2-digit"
          })
          .replace("/", "-")
          .replace("/", "-"),
        930,
        190
      );

      $this.context.font = "16px Calibri";

      // cliente
      if (currentSale.client) {
        $this.context.fillText(currentSale.client.name, 160, 305); // Cliente
        $this.context.fillText(
          currentSale.phonenumber
            ? currentSale.phonenumber
            : currentSale.client.phonenumber,
          160,
          330
        );
      } else if (currentSale.phonenumber) {
        $this.context.fillText(currentSale.phonenumber, 160, 330); // tel
      }

      $this.context.fillText(currentSale.user.name, 160, 355); // tecnico

      $this.context.fillText(
        currentSale.maker ? currentSale.maker : currentSale.car[0].maker,
        640,
        305
      );
      $this.context.fillText(
        currentSale.brand ? currentSale.brand : currentSale.car[0].brand,
        640,
        330
      );
      $this.context.fillText(
        currentSale.year ? currentSale.year : currentSale.sale_services[0].year,
        640,
        355
      );
      $this.context.fillText(currentSale.color, 960, 305);
      $this.context.fillText(currentSale.km, 960, 330);
      $this.context.fillText(currentSale.last_service, 980, 355);

      // $this.context.fillText("Negro Mate", 960, 305);
      // $this.context.fillText("125,000", 960, 330);
      // $this.context.fillText("25/04/2021", 980, 355);

      currentSale.total = parseFloat(currentSale.total);

      // Cantidad
      $this.context.fillText("1", 90, 420);

      // Descripcion
      if (currentSale.concept) {
        var concept = currentSale.concept.match(/.{1,60}/g);
        for (var x = 0; x < concept.length; x++) {
          $this.context.fillText(concept[x].toUpperCase(), 160, 420 + x * 32);
        }
      }

      // Importe
      $this.context.fillText(
        "$" + $this.formatPrice(currentSale.total),
        960,
        420
      );

      // Descripcion (repetida)
      if (currentSale.details) {
        var details = currentSale.details.match(/.{1,60}/g);
        for (var x = 0; x < details.length; x++) {
          $this.context.fillText(
            details[x].toUpperCase(),
            160,
            420 + 32 * concept.length + x * 32
          );
        }
      }

      // Lineas
      for (var x = 0; x < 19; x++) {
        $this.context.strokeStyle = "black";
        $this.context.beginPath();
        $this.context.moveTo(40, 430 + x * 31);
        $this.context.lineTo(1100, 430 + x * 31);
        $this.context.lineWidth = 1;
        $this.context.stroke();
      }

      var y = 1040;
      var h = 26;
      // Subtotal
      $this.context.fillText(
        "$" + $this.formatPrice(currentSale.total),
        960,
        y
      );
      y += h;

      // IVA
      if (currentSale.tax) {
        $this.context.fillText(
          "$" + $this.formatPrice(currentSale.total * 0.08),
          960,
          y
        );
      } else {
        $this.context.fillText("$0", 960, y);
      }
      y += h;

      // Total
      if (currentSale.tax) {
        $this.context.fillText(
          "$" + $this.formatPrice(currentSale.total + currentSale.total * 0.08),
          960,
          y
        );
      } else {
        $this.context.fillText(
          "$" + $this.formatPrice(currentSale.total),
          960,
          y
        );
      }
      y += h;

      // Garantia
      if (currentSale.guaranty) {
        //$this.context.font = "24px Calibri";
        $this.context.fillText(currentSale.guaranty, 160, 1040);
      }

      // metodo de pago
      if (currentSale.method) {
        $this.context.fillText(currentSale.method, 620, 1040);
      }

      // validado por...
      if (currentSale.validator) {
        $this.context.fillText(currentSale.validator, 255, 1115);
      }

      // observaciones (56 char por linea)
      if (currentSale.comments) {
        var comment = currentSale.comments.match(/.{1,56}/g);
        for (var i = 0; i < comment.length; i++) {
          $this.context.fillText(
            comment[i].toUpperCase().trim(),
            645,
            1115 + i * 18
          );
        }
      }

      var img = document.createElement("img");
      img.src = $this.$refs["my-canvas"].toDataURL("image/jpeg");
      img.width = $this.$refs["my-canvas"].width;
      img.height = $this.$refs["my-canvas"].height;

      const d = new Printd();
      d.print(img);
    }*/
  },
  data() {
    return {
      workshopId: 0,
      sales: [],
      oldSales: [],
      status: ["Cotización", "En Proceso", "Recibo", "Cancelado"],
      loading: true,
      page: 1,
    };
  },
};
</script>
<style lang="scss">
.cotizacion {
  color: #909399;
  input {
    color: #909399;
  }
}
.enproceso {
  color: #e6a23c;
  input {
    color: #e6a23c;
  }
}
.terminado {
  color: #67c23a;
  input {
    color: #67c23a;
  }
}
.cancelado {
  color: #f56c6c;
  input {
    color: #f56c6c;
  }
}
</style>