<template>
  <el-container>
    <confirm-sales></confirm-sales>
    <el-main class="content">
      <el-row>
        <el-col :span="7">
          <br />
          <el-row>
            <el-col>
              <create-clients :onCreateClient="onCreateNewClient"></create-clients>
            </el-col>
          </el-row>
          <el-form
            :rules="rules"
            :model="currentSale"
            label-position="right"
            label-width="115px"
            class="query-form"
            ref="myForm"
          >
            <el-form-item label="Taller" prop="workshop">
              <el-select :disabled="true" v-model="currentSale.workshop_id">
                <el-option v-for="w in workshops" :key="w.id" :label="w.name" :value="w.id"></el-option>
              </el-select>
            </el-form-item>

            <el-form-item label="Empleado" prop="user">
              <el-select filterable placeholder="Selecciona un Empleado" v-model="currentSale.user">
                <el-option v-for="user in users" :key="user.id" :label="user.name" :value="user.id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Cliente" prop="client">
              <el-select
                filterable
                placeholder="Selecciona un Cliente"
                v-model="currentSale.client"
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
              <el-input style="width: 220px;" v-model="currentSale.phonenumber"></el-input>
            </el-form-item>
            <el-form-item label="Concepto" prop="concept">
              <el-input
                style="width: 220px;"
                type="textarea"
                v-model="currentSale.concept"
                maxlength="300"
                :rows="2"
              ></el-input>
            </el-form-item>
            <div>
              <el-form-item label="Garantía" prop="guaranty">
                <el-input
                  style="width: 220px;"
                  type="textarea"
                  v-model="currentSale.guaranty"
                  maxlength="165"
                  :rows="2"
                ></el-input>
              </el-form-item>
              <el-form-item label="Detalles" prop="details">
                <el-input
                  style="width: 220px;"
                  type="textarea"
                  v-model="currentSale.details"
                  maxlength="300"
                  :rows="2"
                ></el-input>
              </el-form-item>
              <el-form-item label="Marca" prop="maker">
                <el-input style="width: 220px;" v-model="currentSale.maker"></el-input>
              </el-form-item>
              <el-form-item label="Modelo" prop="brand">
                <el-input style="width: 220px;" v-model="currentSale.brand"></el-input>
              </el-form-item>
              <el-form-item label="Año" prop="year">
                <el-input style="width: 220px;" v-model="currentSale.year"></el-input>
              </el-form-item>
            </div>
            <el-form-item label="Color" prop="color">
              <el-input style="width: 220px;" v-model="currentSale.color"></el-input>
            </el-form-item>
            <!-- Cambido a proximo sevicio -->
            <el-form-item label="Próximo servicio" prop="next_service">
              <el-date-picker
                v-model="currentSale.next_service"
                type="date"
                format="dd-MM-yyyy"
                placeholder="Seleccionar Día"
              ></el-date-picker>
            </el-form-item>
            <el-form-item label="KM. de Ingreso" prop="km">
              <el-input style="width: 220px;" v-model="currentSale.km"></el-input>
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
            </div>
            <br />
          </el-form>
        </el-col>

        <el-col :span="16" :offset="1">
          <br />
          <el-card class="box-card">
            <el-row>
              <el-col :span="24">
                <el-row class="row-item">
                  <el-col :span="12">
                    <h3>Detalle General:</h3>
                  </el-col>
                </el-row>
                <el-row class="row-item">
                  <el-col :span="6" :offset="4">Cliente:</el-col>
                  <el-col :span="4">{{currentSale.client.name}}</el-col>
                </el-row>
                <el-row class="row-item">
                  <el-col :span="6" :offset="4">Teléfono:</el-col>
                  <el-col :span="4">{{ currentSale.phonenumber || "No especificado" }}</el-col>
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
                      <el-col :span="6" :offset="4">{{ item.name }}</el-col>
                      <el-col v-if="false" :span="4">{{ hiddenFormatPrice(item.low_price) }}</el-col>
                      <el-col :span="4">
                        <el-input
                          v-if="showPrices"
                          class="mycustom"
                          size="mini"
                          v-model="item.low_price"
                          @change="onChangePrice(item)"
                        ></el-input>
                      </el-col>
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
              <el-col :span="10">
                <h4>Total</h4>
              </el-col>
              <el-col :span="4" align="end">${{ formatPrice(total) }}</el-col>
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
      <el-row type="flex" justify="end">
        <el-col :span="5" style="text-align:right;">
          <br />
          <!-- <el-button type="secondary" @click="back()">Regresar</el-button> -->
          <el-button type="primary" @click="save()">Guardar</el-button>
        </el-col>
      </el-row>
    </el-main>
  </el-container>
</template>

<script>
import { setTimeout } from "timers";

export default {
  props: ["sale"],
  data() {
    return {
      showPrices: true,
      image1Loaded: false,
      image2Loaded: false,
      method: 0,
      workshops: [],
      service_type: 0,
      tax: 0,
      order: {},
      currentSale: {
        workshop_id: "",
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
        km: "",
      },
      clients: [],
      users: [],
      rules: {
        workshop_id: [
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
    this.loadUser("/app");
    this.loadWorkShops("/api/workshop");

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

    axios.get("/api/clients?all=1").then(function (response) {
      $this.clients = response.data;
      if ($this.currentSale) {
        if ($this.currentSale.client) {
          $this.currentSale.client = $this.currentSale.client.id;
        }
      }
    });

    axios.get("/api/users?all=1&role=Empleado").then(function (response) {
      $this.users = response.data;
      if ($this.currentSale) {
        $this.currentSale.user = $this.currentSale.user.id;
      }
    });
  },
  methods: {
    onChangePrice(item) {
      if (isNaN(item.low_price) || !item.low_price)
        item.low_price = 0;
      this.computeTotal(item);
    },
    loadUser(url) {
      const $this = this;
      $this.loading = true;
      axios.get(url).then(function (response) {
        $this.me = response.data[0];
        $this.loading = false;
      });
    },
    loadWorkShops(url) {
      const $this = this;
      $this.loading = true;
      axios.get(url).then(function (response) {
        $this.workshops = response.data;
        $this.loading = false;

        if ($this.me) $this.currentSale.workshop_id = $this.me.workshop_id;
        else $this.currentSale.workshop_id = $this.workshops[0].id;
      });
    },
    getFolio(sale) {
      if (sale.status == 2) return "REC" + this.pad(sale.id, 5);
      else return "COT" + this.pad(sale.id, 5);
    },
    onCreateNewClient(newClient) {
      if (newClient.id) {
        this.clients.push(newClient);
        this.currentSale.client = newClient.id;
        this.currentSale.phonenumber = newClient.phonenumber;
      }
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
    back() {},
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

        // igualar total
        this.currentSale.total = total;
      }

      return total;
    },
    save() {
      var $this = this;
      $this.$refs.myForm.validate((valid) => {
        console.log(valid);
        if (valid) {
          $this.order.user = $this.currentSale.user;
          if ($this.currentSale.client) {
            $this.order.client = $this.currentSale.client;
          }

          $this.order.workshop = $this.currentSale.workshop_id;
          $this.order.phonenumber = $this.currentSale.phonenumber;
          $this.order.concept = $this.currentSale.concept;
          $this.order.color = $this.currentSale.color;
          $this.order.next_service = $this.currentSale.next_service;
          $this.order.km = $this.currentSale.km;
          $this.order.total = $this.total;

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

      return parseFloat(this.currentSale.total);
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
.mycustom {
  border: 0;
}

.mycustom .el-input__inner {
  border: 0;
  border-radius: 0;
  background: #efcfff;
  border-bottom: 1px solid #f0f0f0;
  text-align: right;
}
</style>