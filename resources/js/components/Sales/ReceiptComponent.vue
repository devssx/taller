<template>
  <el-container>
    <confirm-sales></confirm-sales>
    <select-format ref="formatDialog"></select-format>
    <el-main class="content">
      <el-row v-if="currentSale">
        <el-col :span="24">
          <el-form
            inline
            label-position="right"
            label-width="80px"
            class="query-form"
            ref="form"
          >
            <el-form-item style="float: right">
              <el-button
                :disabled="!image1Loaded || !image2Loaded"
                v-if="currentSale.status < 2"
                icon="el-icon-edit"
                @click="convertToReceipt()"
                >Convertir a recibo</el-button
              >
              <el-button
                :disabled="!image1Loaded || !image2Loaded"
                v-if="currentSale.status == 2"
                icon="el-icon-edit"
                @click="openConfirm()"
                >Editar</el-button
              >
              <el-button
                :disabled="!image1Loaded || !image2Loaded"
                icon="el-icon-printer"
                @click="buildReceipt()"
                >Imprimir</el-button
              >
            </el-form-item>
            <br style="clear: both" />
          </el-form>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="7" v-if="!currentSale">
          <el-row>
            <el-col>
              <create-clients
                :onCreateClient="onCreateNewClient"
                :workshops="workshops"
                :currentUser="me"
              ></create-clients>
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
            <!-- Invisible taller debido al bug de hostgator (le pone el id en lugar del texto, visualmente esta mal) -->
            <!-- <el-form-item label="Taller" prop="workshop">
              <el-select :disabled="true" v-model="form.workshop">
                <el-option v-for="w in workshops" :key="w.id" :label="w.name" :value="w.id"></el-option>
              </el-select>
            </el-form-item> -->

            <el-form-item label="Empleado" prop="user">
              <el-select
                filterable
                placeholder="Selecciona un Empleado"
                v-model="form.user"
                :disabled="currentSale != false"
              >
                <el-option
                  v-for="user in users"
                  :key="user.id"
                  :label="user.name"
                  :value="user.id"
                ></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Cliente" prop="client">
              <el-select
                filterable
                @change="onChangeClient"
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
              <el-input
                style="width: 220px"
                v-model="form.phonenumber"
                maxlength="18"
              ></el-input>
            </el-form-item>
            <el-form-item label="Concepto" prop="concept">
              <el-input
                style="width: 220px"
                type="textarea"
                v-model="form.concept"
                maxlength="300"
                :rows="2"
              ></el-input>
            </el-form-item>
            <div>
              <el-form-item label="Garantía" prop="guaranty">
                <el-input
                  style="width: 220px"
                  type="textarea"
                  v-model="form.guaranty"
                  maxlength="165"
                  :rows="2"
                ></el-input>
              </el-form-item>
              <el-form-item label="Detalles" prop="details">
                <el-input
                  style="width: 220px"
                  type="textarea"
                  v-model="form.details"
                  maxlength="300"
                  :rows="2"
                ></el-input>
              </el-form-item>
              <el-form-item label="Marca" prop="maker">
                <el-input
                  style="width: 220px"
                  v-model="form.maker"
                  maxlength="16"
                ></el-input>
              </el-form-item>
              <el-form-item label="Modelo" prop="brand">
                <el-input
                  style="width: 220px"
                  v-model="form.brand"
                  maxlength="16"
                ></el-input>
              </el-form-item>
              <el-form-item label="Año" prop="year">
                <el-input
                  style="width: 220px"
                  v-model="form.year"
                  maxlength="4"
                ></el-input>
              </el-form-item>
            </div>
            <el-form-item label="Color" prop="color">
              <el-input
                style="width: 220px"
                v-model="form.color"
                maxlength="14"
              ></el-input>
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
            <el-form-item
              label="Recordatorio:"
              prop="reminder"
              v-show="form.next_service"
            >
              <el-input
                style="width: 220px"
                type="textarea"
                v-model="form.reminder"
                maxlength="120"
                :rows="2"
              ></el-input>
            </el-form-item>
            <el-form-item label="No. de Placa" prop="km">
              <el-input
                style="width: 220px"
                v-model="form.km"
                maxlength="10"
              ></el-input>
            </el-form-item>
            <el-form-item
              v-if="order.undefService"
              label="Total"
              prop="low_price"
            >
              <el-input
                style="width: 220px"
                v-model="order.services[0].items[0].low_price"
                @change="onPriceChange"
              ></el-input>
            </el-form-item>
            <div v-if="order.receiptMode">
              <el-form-item label="Tipo:">
                <el-radio-group v-model="service_type">
                  <el-radio :label="1" name="type">A/C</el-radio>
                  <el-radio :label="2" name="type">Mecánico</el-radio>
                  <el-radio :label="3" name="type">Eléctrico</el-radio>
                </el-radio-group>
              </el-form-item>
              <el-form-item label="MDP:">
                <el-radio-group v-model="method">
                  <el-radio :label="1" name="type" style="display: block"
                    >Efectivo</el-radio
                  >
                  <el-radio :label="2" name="type" style="display: block"
                    >Tarjeta de Crédito</el-radio
                  >
                  <el-radio :label="3" name="type" style="display: block"
                    >Tarjeta de Débito</el-radio
                  >
                  <el-radio :label="4" name="type" style="display: block"
                    >Cheque</el-radio
                  >
                  <el-radio :label="5" name="type" style="display: block"
                    >Transferencia</el-radio
                  >
                  <el-radio :label="6" name="type" style="display: block"
                    >Crédito</el-radio
                  >
                </el-radio-group>
              </el-form-item>
              <el-form-item label="IVA:">
                <el-checkbox v-model="tax"></el-checkbox>
              </el-form-item>
            </div>
            <br />
          </el-form>
        </el-col>

        <el-col :span="currentSale ? 18 : 16" :offset="currentSale ? 3 : 1">
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
                  <el-col :span="4">{{ getFolio(currentSale) }}</el-col>
                </el-row>
                <el-row class="row-item">
                  <el-col :span="6" :offset="4">Cliente:</el-col>
                  <el-col :span="4">{{ currentSale.client.name }}</el-col>
                </el-row>
                <el-row class="row-item">
                  <el-col :span="6" :offset="4">Teléfono:</el-col>
                  <el-col :span="4">{{
                    currentSale.phonenumber || "No especificado"
                  }}</el-col>
                </el-row>
                <el-row class="row-item">
                  <el-col :span="6" :offset="4">Carro:</el-col>
                  <el-col :span="4">{{
                    `${currentSale.maker} ${currentSale.brand} ${currentSale.year}`
                  }}</el-col>
                </el-row>
                <!-- <el-row class="row-item">
                  <el-col :span="6" :offset="4">Ultimo Servicio:</el-col>
                  <el-col :span="4">{{
                    currentSale.client.last_service || "No especificado"
                  }}</el-col>
                </el-row> -->
                <el-row class="row-item">
                  <el-col :span="6" :offset="4">Concepto:</el-col>
                  <el-col :span="4">{{
                    currentSale.concept || "No especificado"
                  }}</el-col>
                </el-row>
                <el-row class="row-item">
                  <el-col :span="6" :offset="4">Garantía:</el-col>
                  <el-col :span="4">{{
                    currentSale.guaranty || "No especificado"
                  }}</el-col>
                </el-row>
              </el-col>
            </el-row>
            <br />
            <el-row>
              <el-col :span="24">
                <el-row>
                  <el-col :span="6">
                    <h3>{{ form.brand }}</h3>
                  </el-col>
                  <el-col :span="4">
                    <h3>{{ form.year }}</h3>
                  </el-col>
                </el-row>
                <el-row
                  v-for="(service, index) in order.services"
                  v-bind:key="index"
                >
                  <el-col :span="22" :offset="2">
                    <h4>{{ service.label }}</h4>
                  </el-col>
                  <el-col :span="24">
                    <el-row
                      v-for="(item, index) in service.items"
                      v-bind:key="index"
                      class="row-item"
                    >
                      <el-col :span="6" :offset="4">{{
                        itemName(item)
                      }}</el-col>
                      <el-col :span="4">{{
                        hiddenFormatPrice(itemPrice(item, service))
                      }}</el-col>
                    </el-row>
                    <el-row class="row-item" v-if="total != sumServiceTotal()">
                      <el-col :span="6" :offset="4">Otro</el-col>
                      <el-col :span="4">{{
                        hiddenFormatPrice(total - sumServiceTotal())
                      }}</el-col>
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
                <h4>Total</h4>
              </el-col>
              <el-col :span="3" :offset="8">${{ formatPrice(total) }}</el-col>
              <el-col>
                <div style="float: right">
                  <el-button
                    @click="showPrices = !showPrices"
                    size="mini"
                    type="text"
                    >{{ textBtnInfo() }}</el-button
                  >
                </div>
              </el-col>
            </el-row>
          </el-card>
        </el-col>
      </el-row>
      <el-row type="flex" justify="end" v-if="!currentSale">
        <el-col :span="5" style="text-align: right">
          <br />
          <el-button type="secondary" @click="back()">Regresar</el-button>
          <el-button type="primary" @click="save()">Guardar</el-button>
        </el-col>
      </el-row>
      <el-row
        type="flex"
        justify="end"
        style="opacity: 0; overflow: hidden; height: 50px"
      >
        <el-col :span="8">
          <img
            ref="quotation"
            @load="image1Loaded = true"
            src="/img/receipt.jpg"
            width="1200px"
          />
        </el-col>
        <el-col :span="8">
          <img
            ref="receipt"
            @load="image2Loaded = true"
            src="/img/receipt2.jpg"
            width="1200px"
          />
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
      me: "",
      showPrices: false,
      image1Loaded: false,
      image2Loaded: false,
      method: 1,
      workshops: [],
      service_type: 1,
      tax: 0,
      order: {},
      currentSale: false,
      form: {
        workshop: "",
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
        reminder: "",
        km: "",
      },
      clients: [],
      users: [],
      rules: {
        workshop: [
          {
            required: true,
            message: "Taller es obligatorio",
            trigger: "change",
          },
        ],
        user: [
          {
            required: true,
            message: "Empleado es obligatorio",
            trigger: "change",
          },
        ],
      },
      context: "",
    };
  },
  mounted() {
    var $this = this;
    this.$root.$on("refreshReceipt", this.refreshReceipt);
    this.$root.$on("selectedFormat", this.selectedFormat);
    this.loadUser("/app");
    if (localStorage.getItem("order")) {
      try {
        this.loadMyWorkShop((wks) => {
          $this.order = JSON.parse(localStorage.getItem("order"));
          $this.form.maker = $this.order.car.maker;
          $this.form.brand = $this.order.car.brand;
          $this.form.year = $this.order.year;
          $this.form.workshop = wks;
          var services = $this.order.services.filter((s) => s.warranty);
          $this.form.guaranty = services.length > 0 ? services[0].warranty : "";
        });
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
              low_price: $this.currentSale.sale_services[x].price,
            });
          }
        }
        services.push({
          id: $this.currentSale.services[i].id,
          label: $this.currentSale.services[i].name,
          items: items,
        });
      }

      $this.order = {
        brand: $this.currentSale.car[0].brand,
        price: "low",
        services: services,
        year: $this.currentSale.sale_services[0].year,
      };
    }
  },
  methods: {
    loadUserAndClients(wks) {
      const $this = this;
      axios.get(`/api/clients?all=1&workshop=${wks}`).then(function (response) {
        $this.clients = response.data;
        if ($this.currentSale) {
          if ($this.currentSale.client) {
            $this.form.client = $this.currentSale.client.id;
          }
        }
      });

      axios
        .get(`/api/users?all=1&role=Empleado&workshop=${wks}`)
        .then(function (response) {
          $this.users = response.data;
          if ($this.currentSale) {
            $this.form.user = $this.currentSale.user.id;
          }
        });
    },
    selectedFormat(format) {
      // const COTIZACION = 0;
      // const PROCESO = 1;
      // const TERMINADO = 2;
      // const CANCELADO = 3;

      var canvas = this.$refs["my-canvas"];
      if (this.currentSale.status == 2) {
        this.createReceipt(
          this.currentSale,
          this.$refs["receipt"],
          canvas,
          format
        );
      } else {
        this.createQuotation(
          this.currentSale,
          this.$refs["quotation"],
          canvas,
          format
        );
      }
    },
    onPriceChange(value) {
      if (isNaN(value)) this.order.services[0].items[0].low_price = 0;
    },
    onChangeClient(value) {
      let res = this.clients.filter((c) => c.id == value);
      this.form.phonenumber = res.length > 0 ? res[0].phonenumber : ``;
    },
    loadUser(url) {
      const $this = this;
      $this.loading = true;
      axios.get(url).then(function (response) {
        $this.me = response.data[0];
        $this.loadUserAndClients($this.me.workshop_id);
        $this.loading = false;
      });
    },
    getFolio(sale) {
      if (sale.status == 2) return "REC" + this.pad(sale.folio, 5);
      else return "COT" + this.pad(sale.folio, 5);
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
      this.$refs.formatDialog.showDialog();
    },
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
      $this.$refs.form.validate((valid) => {
        if (valid) {
          $this.order.user = $this.form.user;
          if ($this.form.client) {
            $this.order.client = $this.form.client;
          }

          $this.order.workshop = $this.form.workshop;
          $this.order.phonenumber = $this.form.phonenumber;
          $this.order.concept = $this.form.concept;
          $this.order.color = $this.form.color;
          $this.order.next_service = $this.form.next_service;
          $this.order.reminder = $this.form.reminder;
          $this.order.km = $this.form.km;
          $this.order.total = $this.total;
          $this.order.maker = $this.form.maker;
          $this.order.brand = $this.form.brand;
          $this.order.year = $this.form.year;
          $this.order.details = $this.form.details;
          $this.order.guaranty = $this.form.guaranty;

          if (isNaN($this.order.total)) {
            $this.$alert("EL precio total no es válido", "Total no válido", {
              confirmButtonText: "OK",
              type: "warning",
            });
            return;
          }

          // create a receipt
          if ($this.order.receiptMode) {
            $this.order.status = 2;
            $this.order.method = $this.method;
            $this.order.service_type = $this.service_type;
            $this.order.tax = $this.tax;

            if (!$this.order.client) {
              $this.$alert(
                "Favor de seleccionar un cliente",
                "Cliente no válido",
                {
                  confirmButtonText: "OK",
                  type: "warning",
                }
              );
              return;
            }

            if (!$this.order.user) {
              $this.$alert(
                "Favor de seleccionar un empleado",
                "Empleado no válido",
                {
                  confirmButtonText: "OK",
                  type: "warning",
                }
              );
              return;
            }

            if ($this.service_type == 0) {
              $this.$alert(
                "Favor de seleccionar el tipo de servicio.",
                "Tipo de Servicio",
                {
                  confirmButtonText: "OK",
                  type: "warning",
                }
              );
              return;
            }
          }

          if ($this.form.next_service && !$this.form.reminder) {
            $this.$alert(
              `Ingrese el mensaje del recordatorio para la fecha del próximo servicio.`,
              "Rocordatorio no válido",
              { confirmButtonText: "OK", type: "warning" }
            );
            return;
          }

          axios
            .post("/api/sales", $this.order)
            .then(function (response) {
              $this.$notify({
                title: "¡Exito!",
                message: "La Orden de Servicio fue agregada correctamente",
                type: "success",
              });
              setTimeout(function () {
                localStorage.removeItem("order");
                window.location.href = "/sales";
              }, 1500);
            })
            .catch((error) => {
              if (error.response.data.errors) {
                var errors = error.response.data.errors;
                $this.$alert(errors[Object.keys(errors)[0]][0], "Error", {
                  confirmButtonText: "OK",
                  type: "error",
                });
              }
              $this.loading = false;
            });
        }
      });
    },
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
    },
  },
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
