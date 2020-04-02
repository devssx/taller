<template>
  <el-container>
    <el-main class="content">
      <!-- Top bar -->
      <el-row>
        <el-col :span="24">
          <el-form inline label-position="right" label-width="80px" class="query-form">
            <el-form-item label="Año">
              <el-select v-model="year" filterable class="year" :disabled="years.length == 0">
                <el-option v-for="year in years" :key="year" :label="year" :value="year"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Marca">
              <el-select
                v-model="maker"
                filterable
                class="year"
                :disabled="makers.length == 0"
                @change="onMakerChange"
              >
                <el-option
                  v-for="maker in makers"
                  :key="maker.maker"
                  :label="maker.maker"
                  :value="maker.maker"
                ></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Modelo">
              <el-select v-model="brand" filterable class="year" :disabled="brands.length == 0">
                <el-option
                  v-for="brand in brands"
                  :key="brand.brand"
                  :label="brand.brand"
                  :value="brand.brand"
                ></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Motor">
              <el-select filterable v-model="motor">
                <el-option v-for="m in motors" :key="m" :label="m" :value="m"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item>
              <el-button
                type="primary"
                icon="el-icon-search"
                @click="search"
                :disabled="year == ''"
              >Buscar</el-button>
            </el-form-item>
          </el-form>
          <br />
        </el-col>
      </el-row>

      <el-row>
        <el-col :span="6">
          <h3>
            Servicios
            <div style="float:right;">
              <create-pop-sales></create-pop-sales>
            </div>
          </h3>
          <br />
          <el-collapse v-model="activeName" @change="serviceChanged" accordion>
            <el-collapse-item
              v-for="service in services"
              :key="service.id"
              :title="service.name"
              :name="service.id"
            >
              <table style="width:100%">
                <tr>
                  <th class="price-min">Precio Min</th>
                  <th class="price-med">Precio Med</th>
                  <th class="price-max">Precio Max</th>
                </tr>
                <tr>
                  <td>20%</td>
                  <td>25%</td>
                  <td>30%</td>
                </tr>
                <tr>
                  <td>$5,730.00</td>
                  <td>$6,015.00</td>
                  <td>$6,346.50</td>
                </tr>
              </table>
              <div class="edit-buttons">
                <el-button icon="el-icon-edit" size="mini"></el-button>
                <el-button type="danger" icon="el-icon-delete" size="mini"></el-button>
              </div>
            </el-collapse-item>
          </el-collapse>

          <br />
          <el-form inline label-position="right" label-width="80px" class="query-form">
            <el-form-item label="Articulo">
              <el-select
                @change="handleChange"
                filterable
                placeholder="Agregar un Articulo"
                v-model="item"
                :disabled="listItems.length == 0"
              >
                <el-option
                  v-for="(item, index) in listItems"
                  :key="index"
                  :label="item.name"
                  :value="index"
                ></el-option>
              </el-select>
            </el-form-item>
          </el-form>
        </el-col>

        <el-col :span="18" style="padding-left: 20px;">
          <el-row type="flex" align="middle" style="background-color:#f2f2f2;padding:4px">
            <el-col :span="8">
              <h3>{{currentServiceName}}</h3>
            </el-col>
            <el-col :span="8">
              <div v-if="Object.keys(carService).length === 0" style="float:right;">
                <!-- <el-checkbox v-model="updatePrices">Aplicar el porcentaje a todo la fila</el-checkbox> -->
              </div>
            </el-col>
            <el-col :span="8">
              <div style="float:right;">
                <el-button type="primary" icon="el-icon-tickets">Cotización</el-button>
                <el-button type="primary" icon="el-icon-tickets">Recibo</el-button>
              </div>
            </el-col>
          </el-row>

          <br />
          <el-card class="box-card">
            <!-- Creando -->
            <el-row class="row-header" style="margin-bottom:5px;" type="flex" align="middle">
              <el-col :span="12">SERVICIO</el-col>
              <el-col :span="12">FOTO DEL CARRO</el-col>
            </el-row>
            <el-row class="center" type="flex" align="middle">
              <el-col :span="12">{{currentServiceName}}</el-col>
              <el-col :span="12" class="bl">
                <img
                  width="50%"
                  src="https://s.aolcdn.com/dims-global/dims3/GLOB/legacy_thumbnail/788x525/quality/85/https://s.aolcdn.com/commerce/autodata/images/USC60HOC022A121001.jpg"
                />
              </el-col>
            </el-row>
            <el-row class="row-header">
              <el-col :span="4"></el-col>
              <el-col :span="4" :offset="4">DOLAR</el-col>
              <el-col :span="4">MARCA</el-col>
              <el-col :span="4">MODELO</el-col>
              <el-col :span="4">DEL AÑO</el-col>
              <el-col :span="4">AL AÑO</el-col>
            </el-row>
            <el-row class="center" type="flex" align="middle">
              <el-col :span="4" class="row-header">TIPO DE CAMBIO:</el-col>
              <el-col :span="4">
                <el-input-number
                  v-model="tdc"
                  @change="onTDCChange"
                  controls-position="right"
                  size="mini"
                  :precision="2"
                  :step="0.1"
                  :min="0"
                  style="width:100%;border-radius:0"
                ></el-input-number>
              </el-col>
              <el-col :span="4">HONDA</el-col>
              <el-col :span="4">CIVIC</el-col>
              <el-col :span="4">2015</el-col>
              <el-col :span="4">2020</el-col>
            </el-row>
            <el-row type="flex" align="middle">
              <el-col :span="12" class="row-header">PORCENTAJE DE GANANCIA EN LAS PIEZAS:</el-col>
              <el-col :span="2" class="price-min">20%</el-col>
              <el-col :span="2"></el-col>
              <el-col :span="2" class="price-med">25%</el-col>
              <el-col :span="2"></el-col>
              <el-col :span="2" class="price-max">30%</el-col>
              <el-col :span="2" class="cellborder" style="height:28px">
                <el-checkbox v-model="updatePrices">Aplicar</el-checkbox>
              </el-col>
            </el-row>
            <el-row class="row-header">
              <el-col :span="7">Refacción</el-col>
              <el-col :span="3">Precio DLLS</el-col>
              <el-col :span="2">Base</el-col>
              <el-col :span="2">%</el-col>
              <el-col :span="2">Mínimo</el-col>
              <el-col :span="2">%</el-col>
              <el-col :span="2">Medio</el-col>
              <el-col :span="2">%</el-col>
              <el-col :span="2">Máximo</el-col>
            </el-row>
            <!-- Items del servicio -->
            <row-item ref="selectItem" :items="items" :updatePrices="updatePrices" :tdc="tdc"></row-item>
            <!-- Totales -->
            <el-row class="cellborder" style="height:28px" type="flex" align="middle">
              <el-col :span="7">TOTAL</el-col>
              <el-col :span="5" class="bl price">
                <b>${{ formatPrice(sumItemPrice("price")) }}</b>
              </el-col>
              <el-col :span="4" class="bl price">
                <b>${{ formatPrice(sumItemPrice("low_price")) }}</b>
              </el-col>
              <el-col :span="4" class="bl price">
                <b>${{ formatPrice(sumItemPrice("mid_price")) }}</b>
              </el-col>
              <el-col :span="4" class="bl price">
                <b>${{ formatPrice(sumItemPrice("high_price")) }}</b>
              </el-col>
            </el-row>
            <!-- descripcion del servicio -->
            <el-row>
              <el-col class="cellborder">Descripción del servicio</el-col>
              <el-col
                class="cellborder"
                :span="20"
              >Este servicio consiste en desmontar el compresor dar servicio y revisar los componentes, después se instala el compresor nuevo y se hace el servicio y reemplazo de válvulas de servicio y filtro de cabina.</el-col>
            </el-row>
            <!-- descripcion del servicio -->
            <el-row>
              <el-col :span="4" class="cellborder">Garantía</el-col>
              <el-col :span="20" class="cellborder">2 meses de garantía en piezas y mano de obra.</el-col>
            </el-row>
          </el-card>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="4" :offset="20" style="text-align:right;">
          <br />
          <el-button
            type="primary"
            :disabled="car == '' || service == '' || items.length == 0 || save"
            @click="next()"
          >Guardar</el-button>
        </el-col>
      </el-row>
    </el-main>
  </el-container>
</template>

<script>
export default {
  props: {
    carService: {
      type: Object,
      default: function() {
        return {};
      }
    },
    items: {
      type: Array,
      default: function() {
        return [];
      }
    }
  },
  watch: {
    filterText(val) {
      this.$refs.services.filter(val);
    }
  },
  data() {
    return {
      //activeNames: ["1"], (No accordion)
      activeName: "1",
      currentServiceName: "Servicio",
      tdc: 20,

      maker: "",
      makers: [],
      brand: "",
      brands: [],
      year: "",
      years: [],

      motor: "",
      motors: [],

      articulos: [],

      filterText: "",
      selectedServices: [],
      selectedPrice: "low",
      car: "",
      cars: [],
      service: "",
      services: [],
      item: "",
      listItems: [],
      save: false,
      updatePrices: true,
      defaultProps: {
        label: "label"
      }
    };
  },
  mounted: function() {
    const $this = this;

    $this.year = new Date().getFullYear();
    $this.motors = [];
    $this.motors.push("1.8");
    $this.motors.push("2.4");
    $this.motor = "1.8";

    $this.articulos = [];

    $this.years = [];
    for (var year = 1999; year <= new Date().getFullYear(); year++) {
      $this.years.push(year);
    }

    axios.get("/api/cars?all=1").then(function(response) {
      $this.cars = response.data;
      if ($this.carService) {
        $this.car = $this.carService.car_id;
      }
    });

    axios.get("/api/services?all=1").then(function(response) {
      $this.services = response.data;
      if ($this.carService) {
        $this.service = $this.carService.service_id;
      }
    });

    axios.get("/api/items?all=1").then(function(response) {
      $this.listItems = response.data;

      // TEMPORAL
      for (var i = 0; i < 5; i++) {
        $this.handleChange(i + 1);
      }
      $this.changeAllPrices();
    });

    axios.get("/api/car/makers").then(function(response) {
      $this.makers = response.data;
    });

    axios.get("/api/car/brands").then(function(response) {
      $this.brands = response.data;
    });
  },
  methods: {
    onMakerChange(selectedValue) {
      var $this = this;
      $this.brands.splice(0, this.brands.length);
      axios
        .get("/api/car/brandsByMaker", {
          params: {
            maker: selectedValue
          }
        })
        .then(function(response) {
          $this.brands = response.data;
        });

      // for (var i = 0; i < 5; i++) {
      //   this.brands.push({ brand: i });
      // }

      // for (var i = 0; i < this.makers.length; i++) {
      //   var makerObj = this.makers[i];
      //   if (makerObj.maker == selectedValue) {
      //     this.brands.push(makerObj.brand);
      //   }
      // }
    },
    onTDCChange(currentValue, oldValue) {
      this.$refs.selectItem.refreshPrices(currentValue);
    },
    // TEMPORAL
    changeAllPrices() {
      this.item = "";
      for (var i = 0; i < this.listItems.length; i++) {
        var item = this.listItems[i];
        var base = item.priceUSD * this.tdc;
        item.price = base;
        item.low = 20;
        item.low_price = base + base * (item.low / 100);
        item.mid = 25;
        item.mid_price = base + base * (item.mid / 100);
        item.high = 30;
        item.high_price = base + base * (item.high / 100);
      }

      this.$refs.selectItem.$forceUpdate();
    },
    serviceChanged(value) {
      for (var i = 0; i < this.services.length; i++) {
        if (this.services[i].id == value) {
          this.currentServiceName = this.services[i].name;
          break;
        }
      }
    },
    search() {
      var $this = this;
      $this.selectedPrice = "low";
      $this.selectedServices = [];
      $this.services = [];
      axios
        .get("/api/servicesByCar", {
          params: {
            brand: $this.brand,
            year: $this.year
          }
        })
        .then(function(response) {
          for (var i = 0; i < response.data.length; i++) {
            $this.services.push({
              id: response.data[i].id,
              label: response.data[i].name,
              items: response.data[i].items
            });
          }
          if (localStorage.getItem("order") && $this.getParameter("back")) {
            var order = JSON.parse(localStorage.getItem("order"));
            setTimeout(function() {
              if ($this.services.length > 0) {
                $this.$refs.services.setCheckedNodes(order.services);
              } else {
                $this.services = order.services;
                setTimeout(function() {
                  $this.$refs.services.setCheckedNodes(order.services);
                }, 0);
              }
              $this.selectedPrice = order.price;
            }, 0);
          }
        });
    },
    handleChange(value) {
      // BUG: debes crear un nuevo item si no no actualiza
      var item = new Object();
      item.id = this.listItems[value].id;
      item.name = this.listItems[value].name;
      item.description = this.listItems[value].description;
      item.price = 20;
      item.priceUSD = 1;
      item.low = 10;
      item.low_price = 0;
      item.mid = 20;
      item.mid_price = 0;
      item.high = 30;
      item.high_price = 0;
      this.items.push(item);
      this.$refs.selectItem.$forceUpdate();
    },
    formatPrice(value) {
      let val = (value / 1).toFixed(2);
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },
    sumItemPrice(value) {
      var total = 0;
      if (this.items) {
        for (var item in this.items) {
          if (this.items[item][value]) {
            total += parseFloat(this.items[item][value]);
          }
        }
      }
      return total;
    },

    // Guardar
    next() {
      var $this = this;
      axios
        .post("/api/carservices", {
          car: this.car,
          service: this.service,
          items: this.items
        })
        .then(function(response) {
          $this.save = true;
          $this.$notify({
            title: "¡Exito!",
            message: "Servicio fue agregado correctamente",
            type: "success"
          });
          setTimeout(function() {
            window.location.href = "/carservices";
          }, 1000);
        })
        .catch(error => {
          if (error.response.data.errors) {
            var errors = error.response.data.errors;
            $this.$alert(errors[Object.keys(errors)[0]][0], "Error", {
              confirmButtonText: "OK",
              type: "error"
            });
          }
        });
    }
  }
};
</script>

<style lang="scss">
table,
th,
td {
  border: 1px solid #ebeef5;
  border-collapse: collapse;
  text-align: center;
}

.excell {
  border-radius: 0;
}

.row-header {
  background-color: #f2f2f2;
  font-weight: bold;
  text-align: center;
  border: 1px solid #ebeef5;
  padding: 4px;
}

.center {
  text-align: center;
  border: 1px solid #ebeef5;
}

.cellborder {
  border: 1px solid #ebeef5;
}

.bl {
  border-left: 1px solid #ebeef5;
}

.price {
  text-align: right;
}
.price-min {
  height: 28px;
  color: white;
  text-align: center;
  background-color: #67c23a;
}
.price-med {
  height: 28px;
  color: white;
  text-align: center;
  background-color: #409eff;
}
.price-max {
  height: 28px;
  color: white;
  text-align: center;
  background-color: #f56c6c;
}

.edit-buttons {
  float: right;
  margin-top: 5px;
  margin-bottom: 5px;
}

.box-card {
  min-height: 400px;
  max-height: 1000px;
  overflow-y: auto;
  .el-tree {
    height: 350px;
    overflow-y: scroll;
    border: 1px solid rgb(242, 242, 242);
  }
}
.el-input.percentage {
  width: 50px;
  margin: 0 auto;
  display: block;
}
.el-input.price {
  width: 100%;
  input {
    text-align: right;
  }
}
.el-radio__label {
  display: none;
}
.price-selected {
  background: rgb(242, 242, 242);
}
</style>
