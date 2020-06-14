<template>
  <el-container>
    <confirm-sales></confirm-sales>
    <el-main class="content">
      <el-row v-if="currentSale">
        <el-col :span="24">
          <el-form inline label-position="right" label-width="80px" class="query-form" ref="form">
            <el-form-item style="float: right;">
              <el-button
                :disabled="!image1Loaded || !image2Loaded"
                v-if="currentSale.status < 2"
                icon="el-icon-edit"
                @click="convertToReceipt()"
              >Convertir a recibo</el-button>
              <el-button
                :disabled="!image1Loaded || !image2Loaded"
                v-if="currentSale.status == 2"
                icon="el-icon-edit"
                @click="openConfirm()"
              >Editar</el-button>
              <el-button
                :disabled="!image1Loaded || !image2Loaded"
                icon="el-icon-printer"
                @click="buildReceipt()"
              >Imprimir</el-button>
            </el-form-item>
            <br style="clear:both;" />
          </el-form>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="7" v-if="!currentSale">
          <el-row>
            <el-col>
              <create-clients :onCreateClient="onCreateNewClient"></create-clients>
            </el-col>
          </el-row>
          <el-form
            :rules="rules"
            :model="form"
            label-position="right"
            label-width="115px"
            class="query-form"
            ref="form"
          >
            <el-form-item label="Empleado" prop="user">
              <el-select
                filterable
                placeholder="Selecciona un Empleado"
                v-model="form.user"
                :disabled="currentSale != false"
              >
                <el-option v-for="user in users" :key="user.id" :label="user.name" :value="user.id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Cliente" prop="client">
              <el-select
                filterable
                placeholder="Selecciona un Cliente"
                v-model="form.client"
                :disabled="currentSale != false"
              >
                <el-option
                  v-for="client in clients"
                  :key="client.id"
                  :label="client.name"
                  :value="client.id"
                ></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Telefono" prop="phoenumber">
              <el-input style="width: 220px;" v-model="form.phonenumber"></el-input>
            </el-form-item>
            <el-form-item label="Concepto" prop="concept">
              <el-input style="width: 220px;" v-model="form.concept"></el-input>
            </el-form-item>

            <div v-if="order.receiptMode">
              <el-form-item label="Garantía" prop="guaranty">
                <el-input style="width: 220px;" v-model="form.guaranty"></el-input>
              </el-form-item>
              <el-form-item label="Detalles" prop="details">
                <el-input style="width: 220px;" v-model="form.details"></el-input>
              </el-form-item>
              <el-form-item label="Marca" prop="maker">
                <el-input style="width: 220px;" v-model="form.maker"></el-input>
              </el-form-item>
              <el-form-item label="Modelo" prop="brand">
                <el-input style="width: 220px;" v-model="form.brand"></el-input>
              </el-form-item>
              <el-form-item label="Año" prop="year">
                <el-input style="width: 220px;" v-model="form.year"></el-input>
              </el-form-item>
            </div>
            <el-form-item label="Color" prop="color">
              <el-input style="width: 220px;" v-model="form.color"></el-input>
            </el-form-item>
            <!-- Cambido a proximo sevicio -->
            <el-form-item label="Próximo servicio" prop="next_service">
              <el-date-picker
                v-model="form.next_service"
                type="date"
                format="dd-MM-yyyy"
                placeholder="Seleccionar Día"
              ></el-date-picker>
            </el-form-item>
            <el-form-item label="KM. de Ingreso" prop="km">
              <el-input style="width: 220px;" v-model="form.km"></el-input>
            </el-form-item>
            <div v-if="order.receiptMode">
              <el-form-item label="Tipo:">
                <el-radio-group v-model="service_type">
                  <el-radio :label="1" name="type">A/C</el-radio>
                  <el-radio :label="2" name="type">Eléctrico</el-radio>
                  <el-radio :label="3" name="type">Mecánico</el-radio>
                </el-radio-group>
              </el-form-item>
              <el-form-item label="MDP:">
                <el-radio-group v-model="method">
                  <el-radio :label="1" name="type" style="display:block">Efectivo</el-radio>
                  <el-radio :label="2" name="type" style="display:block">Cheque</el-radio>
                  <el-radio :label="3" name="type" style="display:block">Transferencia</el-radio>
                  <el-radio :label="4" name="type" style="display:block">Tarjeta de Crédito</el-radio>
                  <el-radio :label="5" name="type" style="display:block">Tarjeta de Débito</el-radio>
                </el-radio-group>
              </el-form-item>
              <el-form-item label="IVA:">
                <el-checkbox v-model="tax"></el-checkbox>
              </el-form-item>
            </div>
            <br />
          </el-form>
        </el-col>

        <el-col :span="currentSale? 18:16" :offset="currentSale? 3:1">
          <br />
          <el-card class="box-card">
            <el-row v-if="currentSale">
              <el-col :span="24">
                <el-row class="row-item">
                  <el-col>
                    <h3>Detalle General:</h3>
                  </el-col>
                </el-row>
                <el-row class="row-item">
                  <el-col :span="6" :offset="4">Folio:</el-col>
                  <el-col :span="4">{{getFolio(currentSale)}}</el-col>
                </el-row>
                <el-row class="row-item">
                  <el-col :span="6" :offset="4">Cliente:</el-col>
                  <el-col :span="4">{{currentSale.client.name}}</el-col>
                </el-row>
                <el-row class="row-item">
                  <el-col :span="6" :offset="4">Teléfono:</el-col>
                  <el-col :span="4">{{ currentSale.phonenumber || "No especificado" }}</el-col>
                </el-row>
                <el-row class="row-item">
                  <el-col :span="6" :offset="4">Ultimo Servicio:</el-col>
                  <el-col :span="4">{{currentSale.client.last_service || "No especificado" }}</el-col>
                </el-row>
                <el-row class="row-item">
                  <el-col :span="6" :offset="4">Concepto:</el-col>
                  <el-col :span="4">{{currentSale.client.concept || "No especificado" }}</el-col>
                </el-row>
                <el-row class="row-item">
                  <el-col :span="6" :offset="4">Garantía:</el-col>
                  <el-col :span="4">{{currentSale.client.guaranty || "No especificado" }}</el-col>
                </el-row>
              </el-col>
            </el-row>
            <br />
            <el-row>
              <el-col :span="24">
                <el-row>
                  <el-col :span="6">
                    <h3>{{ order.brand }}</h3>
                  </el-col>
                  <el-col :span="4">
                    <h3>{{ order.year }}</h3>
                  </el-col>
                </el-row>
                <el-row v-for="(service, index) in order.services" v-bind:key="index">
                  <el-col :span="22" :offset="2">
                    <h4>{{ service.label }}</h4>
                  </el-col>
                  <el-col :span="24">
                    <el-row
                      v-for="(item, index) in service.items"
                      v-bind:key="index"
                      class="row-item"
                    >
                      <el-col :span="6" :offset="4">{{ itemName (item) }}</el-col>
                      <el-col :span="4">{{ hiddenFormatPrice(itemPrice(item, service)) }}</el-col>
                    </el-row>
                    <el-row class="row-item" v-if="total != sumServiceTotal()">
                      <el-col :span="6" :offset="4">Otro</el-col>
                      <el-col :span="4">{{ hiddenFormatPrice(total - sumServiceTotal()) }}</el-col>
                    </el-row>
                    <br />
                  </el-col>
                </el-row>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="24">
                <hr />
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="2">
                <h4 v-if="showPrices">Total</h4>
              </el-col>
              <el-col :span="3" :offset="8">{{ hiddenFormatPrice(total) }}</el-col>
              <el-col>
                <div style="float: right;">
                  <el-button
                    @click="showPrices=!showPrices"
                    size="mini"
                    type="text"
                  >{{ textBtnInfo() }}</el-button>
                </div>
              </el-col>
            </el-row>
          </el-card>
        </el-col>
      </el-row>
      <el-row type="flex" justify="end" v-if="!currentSale">
        <el-col :span="5" style="text-align:right;">
          <br />
          <el-button type="secondary" @click="back()">Regresar</el-button>
          <el-button type="primary" @click="save()">Guardar</el-button>
        </el-col>
      </el-row>
      <el-row type="flex" justify="end" style="opacity: 0;overflow: hidden;height: 50px;">
        <el-col :span="8">
          <img ref="quotation" @load="image1Loaded=true" src="/img/receipt.jpg" width="1200px" />
        </el-col>
        <el-col :span="8">
          <img ref="receipt" @load="image2Loaded=true" src="/img/receipt2.jpg" width="1200px" />
        </el-col>
        <el-col :span="8">
          <canvas ref="my-canvas"></canvas>
        </el-col>
      </el-row>
    </el-main>
  </el-container>
</template>

<script>
import { Printd } from "printd";
import { setTimeout } from "timers";

export default {
  props: ["sale"],
  data() {
    return {
      showPrices: false,
      image1Loaded: false,
      image2Loaded: false,
      method: 0,
      service_type: 0,
      tax: 0,
      order: {},
      currentSale: false,
      form: {
        client: "",
        user: "",
        phonenumber: "",
        concept: "",
        maker: "",
        brand: "",
        guaranty: "",
        details: "",
        year: "",
        color: "",
        next_service: "",
        km: ""
      },
      clients: [],
      users: [],
      rules: {
        user: [
          {
            required: true,
            message: "Empleado es obligatorio",
            trigger: "change"
          }
        ]
      },
      context: ""
    };
  },
  mounted() {
    var $this = this;
    this.$root.$on("refreshReceipt", this.refreshReceipt);
    if (localStorage.getItem("order")) {
      try {
        $this.order = JSON.parse(localStorage.getItem("order"));

        if ($this.order.receiptMode) {
          $this.form.maker = $this.order.car.maker;
          $this.form.brand = $this.order.car.brand;
          $this.form.year = $this.order.year;

          var services = $this.order.services.filter(s => s.warranty);
          $this.form.guaranty = services.length > 0 ? services[0].warranty : "";
        }
      } catch (e) {
        localStorage.removeItem("order");
      }
    }

    if ($this.sale) {
      $this.currentSale = $this.sale;
      var services = [];
      for (var i in $this.currentSale.services) {
        var items = [];
        for (var x in $this.currentSale.sale_services) {
          if ($this.currentSale.sale_services[x].item) {
            items.push({
              id: $this.currentSale.sale_services[x].item_id,
              name: $this.currentSale.sale_services[x].item.name,
              low_price: $this.currentSale.sale_services[x].price
            });
          }
        }
        services.push({
          id: $this.currentSale.services[i].id,
          label: $this.currentSale.services[i].name,
          items: items
        });
      }

      $this.order = {
        brand: $this.currentSale.car[0].brand,
        price: "low",
        services: services,
        year: $this.currentSale.sale_services[0].year
      };
    }

    axios.get("/api/clients?all=1").then(function(response) {
      $this.clients = response.data;
      if ($this.currentSale) {
        if ($this.currentSale.client) {
          $this.form.client = $this.currentSale.client.id;
        }
      }
    });

    axios.get("/api/users?all=1&role=Empleado").then(function(response) {
      $this.users = response.data;
      if ($this.currentSale) {
        $this.form.user = $this.currentSale.user.id;
      }
    });
  },
  methods: {
    getFolio(sale) {
      if (sale.status == 2) return "REC" + this.pad(sale.id, 5);
      else return "COT" + this.pad(sale.id, 5);
    },
    onCreateNewClient(newClient) {
      if (newClient.id) {
        this.clients.push(newClient);
        this.form.client = newClient.id;
        this.form.phonenumber = newClient.phonenumber;
      }
    },
    itemPrice(item, service) {
      // normal
      if (this.order.price) return item[this.order.price + "_price"];

      // multi price (low, mid, high)
      return item[service.selectedPrice + "_price"];
    },
    itemName(item) {
      if (item.name) {
        return item.name;
      }
      return item.item.name;
    },
    refreshReceipt(sale) {
      this.currentSale = sale;
    },
    convertToReceipt() {
      // convierte cotizacion a recibo
      this.$root.$emit("changeStatus", this.currentSale, 2);
    },
    openConfirm() {
      this.$root.$emit("confirmSale", this.currentSale, true);
    },
    textBtnInfo() {
      return this.showPrices ? `Ocultar Precios` : `Mostrar Precios`;
    },
    hiddenFormatPrice(value) {
      let val = (value / 1).toFixed(2);
      var price = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

      return this.showPrices ? `$${price}` : ``;
    },
    formatPrice(value) {
      let val = (value / 1).toFixed(2);
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },
    back() {
      const parsed = JSON.stringify(this.order);
      localStorage.setItem("order", parsed);
      window.location.href = "/sales/create?back=1";
    },
    buildReceipt() {
      // const COTIZACION = 0;
      // const PROCESO = 1;
      // const TERMINADO = 2;
      // const CANCELADO = 3;

      var canvas = this.$refs["my-canvas"];
      if (this.currentSale.status == 2) {
        this.createReceipt(this.currentSale, this.$refs["receipt"], canvas);
      } else {
        this.createQuotation(this.currentSale, this.$refs["quotation"], canvas);
      }
    },
    // Movido a commons.js
    // buildQuotation(currentSale) {
    //   // crea el recibo pdf
    //   var $this = this;

    //   $this.$refs["my-canvas"].width = $this.$refs["quotation"].width;
    //   $this.$refs["my-canvas"].height = $this.$refs["quotation"].height - 109;
    //   $this.context = $this.$refs["my-canvas"].getContext("2d");

    //   $this.context.clearRect(
    //     0,
    //     0,
    //     $this.$refs["my-canvas"].width,
    //     $this.$refs["my-canvas"].height
    //   );

    //   $this.context.fillStyle = "#FFFFFF";
    //   $this.context.fillRect(
    //     0,
    //     0,
    //     $this.$refs["my-canvas"].width,
    //     $this.$refs["my-canvas"].height
    //   );
    //   $this.context.drawImage(
    //     $this.$refs["quotation"],
    //     -30,
    //     -30,
    //     $this.$refs["quotation"].width,
    //     $this.$refs["quotation"].height
    //   );

    //   $this.context.font = "24px Calibri";
    //   $this.context.fillStyle = "red";
    //   $this.context.fillText("COT" + $this.pad(currentSale.id, 5), 935, 132); // FOLIO

    //   $this.context.fillStyle = "black";
    //   $this.context.fillText(
    //     new Date()
    //       .toLocaleDateString("es-ES", {
    //         year: "numeric",
    //         month: "2-digit",
    //         day: "2-digit"
    //       })
    //       .replace("/", "-")
    //       .replace("/", "-"),
    //     930,
    //     190
    //   );

    //   $this.context.font = "16px Calibri";

    //   // cliente
    //   if (currentSale.client) {
    //     $this.context.fillText(currentSale.client.name, 160, 305); // Cliente
    //     $this.context.fillText(
    //       currentSale.phonenumber
    //         ? currentSale.phonenumber
    //         : currentSale.client.phonenumber,
    //       160,
    //       330
    //     );
    //   } else if (currentSale.phonenumber) {
    //     $this.context.fillText(currentSale.phonenumber, 160, 330); // tel
    //   }

    //   $this.context.fillText(currentSale.user.name, 160, 355); // tecnico

    //   $this.context.fillText(
    //     currentSale.maker ? currentSale.maker : currentSale.car[0].maker,
    //     640,
    //     305
    //   );
    //   $this.context.fillText(
    //     currentSale.brand ? currentSale.brand : currentSale.car[0].brand,
    //     640,
    //     330
    //   );
    //   $this.context.fillText(
    //     currentSale.year ? currentSale.year : currentSale.sale_services[0].year,
    //     640,
    //     355
    //   );
    //   $this.context.fillText(currentSale.color, 960, 305);
    //   $this.context.fillText(currentSale.km, 960, 330);
    //   $this.context.fillText(currentSale.next_service, 980, 355);

    //   // $this.context.fillText("Negro Mate", 960, 305);
    //   // $this.context.fillText("125,000", 960, 330);
    //   // $this.context.fillText("25/04/2021", 980, 355);

    //   currentSale.total = parseFloat(currentSale.total);

    //   // Cantidad
    //   $this.context.fillText("1", 90, 420);

    //   // Descripcion
    //   if (currentSale.concept) {
    //     var concept = currentSale.concept.match(/.{1,60}/g);
    //     for (var x = 0; x < concept.length; x++) {
    //       $this.context.fillText(concept[x].toUpperCase(), 160, 420 + x * 32);
    //     }
    //   }

    //   // Importe
    //   $this.context.fillText(
    //     "$" + $this.formatPrice(currentSale.total),
    //     960,
    //     420
    //   );

    //   // Descripcion (repetida)
    //   if (currentSale.details) {
    //     var details = currentSale.details.match(/.{1,60}/g);
    //     for (var x = 0; x < details.length; x++) {
    //       $this.context.fillText(
    //         details[x].toUpperCase(),
    //         160,
    //         420 + 32 * concept.length + x * 32
    //       );
    //     }
    //   }

    //   // Lineas
    //   for (var x = 0; x < 19; x++) {
    //     $this.context.strokeStyle = "black";
    //     $this.context.beginPath();
    //     $this.context.moveTo(40, 430 + x * 31);
    //     $this.context.lineTo(1100, 430 + x * 31);
    //     $this.context.lineWidth = 1;
    //     $this.context.stroke();
    //   }

    //   var y = 1040;
    //   var h = 26;
    //   // Subtotal
    //   $this.context.fillText(
    //     "$" + $this.formatPrice(currentSale.total),
    //     960,
    //     y
    //   );
    //   y += h;

    //   // IVA
    //   if (currentSale.tax) {
    //     $this.context.fillText(
    //       "$" + $this.formatPrice(currentSale.total * 0.08),
    //       960,
    //       y
    //     );
    //   } else {
    //     $this.context.fillText("$0", 960, y);
    //   }
    //   y += h;

    //   // Total
    //   if (currentSale.tax) {
    //     $this.context.fillText(
    //       "$" + $this.formatPrice(currentSale.total + currentSale.total * 0.08),
    //       960,
    //       y
    //     );
    //   } else {
    //     $this.context.fillText(
    //       "$" + $this.formatPrice(currentSale.total),
    //       960,
    //       y
    //     );
    //   }
    //   y += h;

    //   // Garantia
    //   if (currentSale.guaranty) {
    //     //$this.context.font = "24px Calibri";
    //     $this.context.fillText(currentSale.guaranty, 160, 1040);
    //   }

    //   // metodo de pago
    //   if (currentSale.method) {
    //     $this.context.fillText(currentSale.method, 620, 1040);
    //   }

    //   // validado por...
    //   if (currentSale.validator) {
    //     $this.context.fillText(currentSale.validator, 255, 1115);
    //   }

    //   // observaciones (56 char por linea)
    //   if (currentSale.comments) {
    //     var comment = currentSale.comments.match(/.{1,56}/g);
    //     for (var i = 0; i < comment.length; i++) {
    //       $this.context.fillText(
    //         comment[i].toUpperCase().trim(),
    //         645,
    //         1115 + i * 18
    //       );
    //     }
    //   }

    //   var img = document.createElement("img");
    //   img.src = $this.$refs["my-canvas"].toDataURL("image/jpeg");
    //   img.width = $this.$refs["my-canvas"].width;
    //   img.height = $this.$refs["my-canvas"].height;

    //   const d = new Printd();
    //   d.print(img);
    // },
    // buildReceipt() {
    //   // const COTIZACION = 0;
    //   // const PROCESO = 1;
    //   // const TERMINADO = 2;
    //   // const CANCELADO = 3;
    //   if (this.currentSale.status != 2) {
    //     this.buildQuotation(this.currentSale);
    //     return;
    //   }

    //   var $this = this;
    //   $this.$refs["my-canvas"].width = $this.$refs["receipt"].width;
    //   $this.$refs["my-canvas"].height = $this.$refs["receipt"].height - 109;
    //   $this.context = $this.$refs["my-canvas"].getContext("2d");

    //   $this.context.clearRect(
    //     0,
    //     0,
    //     $this.$refs["my-canvas"].width,
    //     $this.$refs["my-canvas"].height
    //   );

    //   $this.context.fillStyle = "#FFFFFF";
    //   $this.context.fillRect(
    //     0,
    //     0,
    //     $this.$refs["my-canvas"].width,
    //     $this.$refs["my-canvas"].height
    //   );
    //   $this.context.drawImage(
    //     $this.$refs["receipt"],
    //     -30,
    //     -30,
    //     $this.$refs["receipt"].width,
    //     $this.$refs["receipt"].height
    //   );

    //   $this.context.font = "24px Calibri";
    //   $this.context.fillStyle = "red";
    //   $this.context.fillText(
    //     "REC" + $this.pad($this.currentSale.id, 5),
    //     920,
    //     105
    //   );
    //   $this.context.fillText(
    //     "REC" + $this.pad($this.currentSale.id, 5),
    //     920,
    //     105 + 678
    //   );

    //   $this.context.fillStyle = "black";
    //   $this.context.fillText(
    //     new Date()
    //       .toLocaleDateString("es-ES", {
    //         year: "numeric",
    //         month: "2-digit",
    //         day: "2-digit"
    //       })
    //       .replace("/", "-")
    //       .replace("/", "-"),
    //     920,
    //     155
    //   );
    //   $this.context.fillText(
    //     new Date()
    //       .toLocaleDateString("es-ES", {
    //         year: "numeric",
    //         month: "2-digit",
    //         day: "2-digit"
    //       })
    //       .replace("/", "-")
    //       .replace("/", "-"),
    //     920,
    //     155 + 678
    //   );

    //   // Lineas
    //   for (var x = 0; x < 8; x++) {
    //     $this.context.strokeStyle = "black";
    //     $this.context.beginPath();
    //     $this.context.moveTo(40, 360 + x * 30);
    //     $this.context.lineTo(1100, 360 + x * 30);
    //     $this.context.moveTo(40, 360 + x * 30 + 678);
    //     $this.context.lineTo(1100, 360 + x * 30 + 678);
    //     $this.context.lineWidth = 1;
    //     $this.context.stroke();
    //   }

    //   $this.context.font = "16px Calibri";
    //   // MDP
    //   if ($this.currentSale.method) {
    //     $this.context.fillText(
    //       $this.currentSale.method == 1 ? "Efectivo" : "Electrónico",
    //       630,
    //       615
    //     );
    //     $this.context.fillText(
    //       $this.currentSale.method == 1 ? "Efectivo" : "Electrónico",
    //       630,
    //       615 + 678
    //     );
    //   }

    //   if ($this.currentSale.client) {
    //     $this.context.fillText($this.currentSale.client.name, 160, 240);
    //     $this.context.fillText($this.currentSale.client.name, 160, 240 + 678);
    //     $this.context.fillText(
    //       $this.currentSale.phonenumber
    //         ? $this.currentSale.phonenumber
    //         : $this.currentSale.client.phonenumber,
    //       160,
    //       265
    //     );
    //     $this.context.fillText(
    //       $this.currentSale.phonenumber
    //         ? $this.currentSale.phonenumber
    //         : $this.currentSale.client.phonenumber,
    //       160,
    //       265 + 678
    //     );
    //   } else if ($this.currentSale.phonenumber) {
    //     $this.context.fillText($this.currentSale.phonenumber, 160, 265);
    //     $this.context.fillText($this.currentSale.phonenumber, 160, 265 + 678);
    //   }

    //   $this.context.fillText($this.currentSale.user.name, 160, 290);
    //   $this.context.fillText($this.currentSale.user.name, 160, 290 + 678);

    //   $this.context.fillText($this.currentSale.maker, 662, 240);
    //   $this.context.fillText($this.currentSale.maker, 662, 240 + 678);
    //   $this.context.fillText($this.currentSale.brand, 662, 265);
    //   $this.context.fillText($this.currentSale.brand, 662, 265 + 678);
    //   $this.context.fillText($this.currentSale.year, 662, 290);
    //   $this.context.fillText($this.currentSale.year, 662, 290 + 678);
    //   $this.context.fillText($this.currentSale.color, 960, 240);
    //   $this.context.fillText($this.currentSale.color, 960, 240 + 678);
    //   $this.context.fillText($this.currentSale.km, 960, 265);
    //   $this.context.fillText($this.currentSale.km, 960, 265 + 678);
    //   $this.context.fillText($this.currentSale.next_service, 1010, 290);
    //   $this.context.fillText($this.currentSale.next_service, 1010, 290 + 678);

    //   $this.currentSale.total = parseFloat($this.currentSale.total);

    //   $this.context.fillText("1", 100, 350);
    //   $this.context.fillText("1", 100, 350 + 678);
    //   if ($this.currentSale.concept) {
    //     var concept = $this.currentSale.concept.match(/.{1,60}/g);
    //     for (var x = 0; x < concept.length; x++) {
    //       $this.context.fillText(concept[x].toUpperCase(), 160, 350 + x * 32);
    //       $this.context.fillText(
    //         concept[x].toUpperCase(),
    //         160,
    //         350 + 678 + x * 32
    //       );
    //     }
    //   }
    //   $this.context.fillText(
    //     "$" + $this.formatPrice($this.currentSale.total),
    //     950,
    //     350
    //   );
    //   $this.context.fillText(
    //     "$" + $this.formatPrice($this.currentSale.total),
    //     950,
    //     350 + 678
    //   );

    //   // Detalles
    //   if ($this.currentSale.details) {
    //     var details = $this.currentSale.details.match(/.{1,60}/g);
    //     for (var x = 0; x < details.length; x++) {
    //       $this.context.fillText(
    //         details[x].toUpperCase(),
    //         160,
    //         350 + 32 * concept.length + x * 32
    //       );
    //       $this.context.fillText(
    //         details[x].toUpperCase(),
    //         160,
    //         350 + 678 + 32 * concept.length + x * 32
    //       );
    //     }
    //   }

    //   $this.context.fillText(
    //     "$" + $this.formatPrice($this.currentSale.total),
    //     950,
    //     615
    //   );
    //   $this.context.fillText(
    //     "$" + $this.formatPrice($this.currentSale.total),
    //     950,
    //     615 + 678
    //   );
    //   if ($this.currentSale.tax) {
    //     $this.context.fillText(
    //       "$" + $this.formatPrice($this.currentSale.total * 0.08),
    //       950,
    //       640
    //     );
    //     $this.context.fillText(
    //       "$" + $this.formatPrice($this.currentSale.total * 0.08),
    //       950,
    //       640 + 678
    //     );
    //   } else {
    //     $this.context.fillText("$0", 950, 640);
    //     $this.context.fillText("$0", 950, 640 + 678);
    //   }

    //   if ($this.currentSale.tax) {
    //     $this.context.fillText(
    //       "$" +
    //         $this.formatPrice(
    //           $this.currentSale.total + $this.currentSale.total * 0.08
    //         ),
    //       950,
    //       668
    //     );
    //     $this.context.fillText(
    //       "$" +
    //         $this.formatPrice(
    //           $this.currentSale.total + $this.currentSale.total * 0.08
    //         ),
    //       950,
    //       668 + 678
    //     );
    //   } else {
    //     $this.context.fillText(
    //       "$" + $this.formatPrice($this.currentSale.total),
    //       950,
    //       668
    //     );
    //     $this.context.fillText(
    //       "$" + $this.formatPrice($this.currentSale.total),
    //       950,
    //       668 + 678
    //     );
    //   }

    //   if ($this.currentSale.guaranty) {
    //     $this.context.font = "24px Calibri";
    //     $this.context.fillText($this.currentSale.guaranty, 170, 620);
    //     $this.context.fillText($this.currentSale.guaranty, 170, 620 + 678);
    //   }

    //   var img = document.createElement("img");
    //   img.src = $this.$refs["my-canvas"].toDataURL("image/jpeg");
    //   img.width = $this.$refs["my-canvas"].width;
    //   img.height = $this.$refs["my-canvas"].height;

    //   const d = new Printd();
    //   d.print(img);
    // },
    pad(number, size) {
      var s = String(number);
      while (s.length < (size || 2)) {
        s = "0" + s;
      }
      return s;
    },
    sumServiceTotal() {
      var total = 0;
      const order = this.order;

      for (var i in order.services) {
        var serviceSelectedPrice = order.services[i].selectedPrice;

        for (var x in order.services[i].items) {
          // individual price
          if (serviceSelectedPrice) {
            if (order.services[i].items[x][serviceSelectedPrice + "_price"]) {
              total += parseFloat(
                order.services[i].items[x][serviceSelectedPrice + "_price"]
              );
            }
          } else {
            if (order.services[i].items[x][order.price + "_price"]) {
              total += parseFloat(
                order.services[i].items[x][order.price + "_price"]
              );
            }
          }
        }
      }

      return total;
    },
    save() {
      var $this = this;
      $this.$refs.form.validate(valid => {
        if (valid) {
          $this.order.user = $this.form.user;
          if ($this.form.client) {
            $this.order.client = $this.form.client;
          }

          $this.order.phonenumber = $this.form.phonenumber;
          $this.order.concept = $this.form.concept;
          $this.order.color = $this.form.color;
          $this.order.next_service = $this.form.next_service;
          $this.order.km = $this.form.km;
          $this.order.total = $this.total;

          // create a receipt
          if ($this.order.receiptMode) {
            $this.order.status = 2;
            $this.order.maker = $this.form.maker;
            $this.order.brand = $this.form.brand;
            $this.order.year = $this.form.year;
            $this.order.details = $this.form.details;
            $this.order.guaranty = $this.form.guaranty;
            $this.order.method = $this.method;
            $this.order.service_type = $this.service_type;
            $this.order.tax = $this.tax;

            if ($this.service_type == 0) {
              $this.$alert(
                "Favor de seleccionar el tipo de servicio.",
                "Tipo de Servicio",
                {
                  confirmButtonText: "OK",
                  type: "warning"
                }
              );
              return;
            }
          }

          axios
            .post("/api/sales", $this.order)
            .then(function(response) {
              $this.$notify({
                title: "¡Exito!",
                message: "La Orden de Servicio fue agregada correctamente",
                type: "success"
              });
              setTimeout(function() {
                localStorage.removeItem("order");
                window.location.href = "/sales";
              }, 1500);
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
        }
      });
    }
  },
  computed: {
    total() {
      if (this.order.length == 0) {
        return 0;
      }

      if (this.currentSale) {
        return parseFloat(this.currentSale.total);
      }

      var total = 0;
      const order = this.order;

      for (var i in order.services) {
        var serviceSelectedPrice = order.services[i].selectedPrice;

        for (var x in order.services[i].items) {
          // individual price
          if (serviceSelectedPrice) {
            if (order.services[i].items[x][serviceSelectedPrice + "_price"]) {
              total += parseFloat(
                order.services[i].items[x][serviceSelectedPrice + "_price"]
              );
            }
          } else {
            if (order.services[i].items[x][order.price + "_price"]) {
              total += parseFloat(
                order.services[i].items[x][order.price + "_price"]
              );
            }
          }
        }
      }

      return total;
    }
  }
};
</script>

<style lang="scss">
.box-card {
  max-height: 600px;
  overflow-y: auto;
}
.row-item:nth-child(odd) {
  background-color: #f2f2f2;
}
.el-form-item {
  margin-bottom: 4px;
}
</style>
