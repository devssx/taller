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
                :loading="isLoadingServices"
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
          <el-row type="flex" align="middle" style="background-color:#f2f2f2;padding:4px">
            <el-col :span="8">
              <h4>
                Servicios
                <span v-if="services.length > 0">({{getSelectedServices()}})</span>
              </h4>
            </el-col>
            <el-col :span="16">
              <div style="float:right;">
                <create-pop-sales :disabled="!isValidCarId"></create-pop-sales>
              </div>
            </el-col>
          </el-row>
          <br />
          <el-collapse
            v-loading="isLoadingServices"
            v-model="activeName"
            @change="serviceChanged"
            accordion
          >
            <el-collapse-item
              v-for="(s, index) in services"
              :key="index"
              :title="s.name"
              :name="index"
            >
              <table style="width:100%">
                <tr>
                  <th class="price-min">Precio Min</th>
                  <th class="price-med">Precio Med</th>
                  <th class="price-max">Precio Max</th>
                </tr>
                <tr>
                  <td>${{formatPrice(s.low_total)}}</td>
                  <td>${{formatPrice(s.mid_total)}}</td>
                  <td>${{formatPrice(s.high_total)}}</td>
                </tr>
                <tr>
                  <td>
                    <el-radio v-model="s.selectedPrice" label="min"></el-radio>
                  </td>
                  <td>
                    <el-radio v-model="s.selectedPrice" label="mid"></el-radio>
                  </td>
                  <td>
                    <el-radio v-model="s.selectedPrice" label="high"></el-radio>
                  </td>
                </tr>
              </table>
              <el-checkbox v-model="s.selected">Seleccionar</el-checkbox>
              <div class="edit-buttons">
                ID: {{s.id}}
                <el-button @click="editService(s)" icon="el-icon-edit" size="mini"></el-button>
                <el-button
                  @click="deleteService(s)"
                  type="danger"
                  icon="el-icon-delete"
                  size="mini"
                ></el-button>
              </div>
            </el-collapse-item>
          </el-collapse>
          <el-row v-if="!isValidCarId || !isValidService" style="padding: 10px;">Sin Servicios</el-row>
        </el-col>

        <el-col :span="18" style="padding-left: 20px;">
          <el-row type="flex" align="middle" style="background-color:#f2f2f2;padding:4px">
            <el-col :span="8">
              <h3>{{service.name}}</h3>
            </el-col>
            <el-col :span="8"></el-col>
            <el-col :span="8">
              <div style="float:right;">
                <el-button type="primary" icon="el-icon-tickets">Cotización</el-button>
                <el-button type="primary" icon="el-icon-tickets" @click="next()">Recibo</el-button>
              </div>
            </el-col>
          </el-row>

          <br />
          <el-card class="box-card">
            <div v-if="!isValidCarId">Selecciona un carro válido</div>
            <div v-if="isValidCarId && !isValidService">Agrega un Servicio</div>
            <div v-if="isValidService">
              <!-- Creando -->
              <el-row class="row-header" style="margin-bottom:5px;" type="flex" align="middle">
                <el-col :span="12">SERVICIO</el-col>
                <el-col :span="12">FOTO DEL CARRO</el-col>
              </el-row>
              <el-row class="center" type="flex" align="middle">
                <el-col :span="12">{{service.name}}</el-col>
                <el-col :span="12" class="bl">
                  <el-image style="width:230px;" :src="selectedCar.image"></el-image>
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
                    v-model="service.exchange_rate"
                    @change="onTDCChange"
                    controls-position="right"
                    size="mini"
                    :precision="2"
                    :step="0.1"
                    :min="0"
                    :disabled="isLoadingServices"
                    style="width:100%;border-radius:0"
                  ></el-input-number>
                </el-col>
                <el-col :span="4">{{selectedCar.maker}}</el-col>
                <el-col :span="4">{{selectedCar.brand}}</el-col>
                <el-col :span="4">{{selectedCar.year}}</el-col>
                <el-col :span="4">{{selectedCar.endYear}}</el-col>
              </el-row>
              <el-row type="flex" align="middle" class="br">
                <el-col :span="12" class="row-header">PORCENTAJE DE GANANCIA EN LAS PIEZAS:</el-col>
                <el-col :span="2" class="price-min">
                  <el-input
                    size="mini"
                    @change="onGlobalLowPercentageChange"
                    maxlength="2"
                    v-model="service.low"
                    class="global"
                  ></el-input>
                </el-col>
                <el-col :span="2"></el-col>
                <el-col :span="2" class="price-med">
                  <el-input
                    size="mini"
                    @change="onGlobalMidPercentageChange"
                    maxlength="2"
                    v-model="service.mid"
                    class="global"
                  ></el-input>
                </el-col>
                <el-col :span="2"></el-col>
                <el-col :span="2" class="price-max">
                  <el-input
                    size="mini"
                    @change="onGlobalHighPercentageChange"
                    maxlength="2"
                    v-model="service.high"
                    class="global"
                  ></el-input>
                </el-col>
                <el-col :span="2" style="height:28px"></el-col>
              </el-row>
              <el-row class="row-header">
                <el-col class="price">
                  <el-select
                    size="mini"
                    @change="handleChange"
                    filterable
                    placeholder="Agregar un Artículo"
                    v-model="item"
                    :disabled="listItems.length == 0 || !isValidCarId || service.service_id == undefined || service.service_id == ''"
                  >
                    <el-option
                      v-for="(item, index) in listItems"
                      :key="index"
                      :label="item.name"
                      :value="index"
                    ></el-option>
                  </el-select>
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
              <row-item
                v-loading="isLoadingServices"
                ref="selectItem"
                :items="items"
                :tdc="service.exchange_rate"
              ></row-item>

              <!-- Totales -->
              <el-row class="bt bl br" style="height:28px" type="flex" align="middle">
                <el-col style="padding:10px" :span="7">TOTAL</el-col>
                <el-col :span="5" class="price">
                  <b>${{ formatPrice(sumItemPrice("price")) }}</b>
                </el-col>
                <el-col :span="4" class="price">
                  <b>${{ formatPrice(sumItemPrice("low_price")) }}</b>
                </el-col>
                <el-col :span="4" class="price">
                  <b>${{ formatPrice(sumItemPrice("mid_price")) }}</b>
                </el-col>
                <el-col :span="4" class="price">
                  <b>${{ formatPrice(sumItemPrice("high_price")) }}</b>
                </el-col>
              </el-row>
              <!-- descripcion del servicio -->
              <el-row class="bt bl br">
                <el-col :span="4" style="padding:10px">Descripción del servicio</el-col>
                <el-col :span="20">
                  <el-input
                    type="textarea"
                    :autosize="{ minRows: 2, maxRows: 4}"
                    placeholder="Descripción"
                    v-model="service.comment"
                    :disabled="isLoadingServices || items.length == 0"
                  ></el-input>
                </el-col>
              </el-row>
              <!-- descripcion del servicio -->
              <el-row class="bt bl br">
                <el-col :span="4" style="padding:10px">Garantía</el-col>
                <el-col :span="20">
                  <el-input
                    type="textarea"
                    :autosize="{ minRows: 2, maxRows: 4}"
                    placeholder="Garantía"
                    v-model="service.warranty"
                    :disabled="isLoadingServices || items.length == 0"
                  ></el-input>
                </el-col>
              </el-row>

              <el-row class="bt">
                <el-col :span="4" :offset="20" style="text-align:right;">
                  <br />
                  <el-button
                    type="primary"
                    :loading="isSaving"
                    :disabled="selectedCar.id == undefined || selectedCar.id == '' || service == '' || items.length == 0 || save"
                    @click="saveService()"
                  >Guardar</el-button>
                </el-col>
              </el-row>
            </div>
          </el-card>
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
      isLoadingServices: false,
      isSaving: false,
      isValidCarId: false,
      isValidService: false,
      activeName: "0",

      // selectedService
      service: {
        low_total: 0,
        mid_total: 0,
        high_total: 0,
        exchange_rate: 0,
        low: 0,
        mid: 0,
        high: 0,
        name: "",
        comment: "",
        warranty: ""
      },

      selectedCar: {
        maker: "",
        brand: "",
        year: "",
        endYear: "",
        image:
          "https://st.motortrend.ca/uploads/sites/10/2017/05/2017-ford-focus-titanium-sedan-angular-front.png"
      },

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
      services: [],
      item: "",
      listItems: [],
      save: false,
      defaultProps: {
        label: "label"
      }
    };
  },
  mounted: function() {
    const $this = this;

    // EVento boton add service
    $this.$root.$on("addService", $this.addService);

    $this.year = new Date().getFullYear();
    $this.motors = [];
    $this.motors.push("");
    $this.motors.push("1.8");
    $this.motors.push("2.4");
    $this.motor = "";

    $this.articulos = [];

    $this.years = [];
    for (var year = 1999; year <= new Date().getFullYear(); year++) {
      $this.years.push(year);
    }

    // axios.get("/api/cars?all=1").then(function(response) {
    //   $this.cars = response.data;
    //   // if ($this.carService) {
    //   //   $this.car = $this.carService.car_id;
    //   // }
    // });

    // axios.get("/api/services?all=1").then(function(response) {
    //   $this.services = response.data;
    //   if ($this.carService) {
    //     $this.service = $this.carService.service_id;
    //   }
    // });

    axios.get("/api/items?all=1").then(function(response) {
      $this.listItems = response.data;
    });

    axios.get("/api/car/makers").then(function(response) {
      $this.makers = response.data;
    });

    axios.get("/api/car/brands").then(function(response) {
      $this.brands = response.data;
    });
  },
  methods: {
    next() {
      this.$alert("Cear Orden de Servicio", "Info", {
        confirmButtonText: "OK",
        type: "error"
      });
      // const order = {
      //   services: this.selectedServices,
      //   price: this.selectedPrice,
      //   brand: this.brand,
      //   year: this.year
      // };

      // const parsed = JSON.stringify(order);
      // localStorage.setItem("order", parsed);
      window.location.href = "/sales/receipt";
    },
    getSelectedServices() {
      if (this.services.length == 0) return 0;
      return this.services.filter(x => x.selected).length;
    },
    editService(serviceItem) {
      this.$confirm("Editar servicio ¿Desea continuar?", "Advertencia", {
        confirmButtonText: "OK",
        cancelButtonText: "Cancel",
        type: "warning"
      }).then(() => {
        //
      });
    },
    deleteService(serviceItem) {
      var $this = this;
      $this
        .$confirm(
          "Esto eliminará permanentemente el registro. ¿Desea continuar?",
          "Advertencia",
          {
            confirmButtonText: "OK",
            cancelButtonText: "Cancel",
            type: "warning"
          }
        )
        .then(() => {
          if (serviceItem.isNew) {
            $this.loadCarServices();
            return;
          }

          //$this.loading = true;
          axios
            .delete("/api/carservices/" + serviceItem.id)
            .then(function(response) {
              $this.$message({
                type: "success",
                message: "Servicio elminado"
              });
              //$this.$root.$emit("refreshTable");
              //$this.dialogVisible = false;
              //$this.loading = false;
              $this.loadCarServices();
            })
            .catch(error => {
              //$this.loading = false;
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
    },
    loadCarServices() {
      var $this = this;
      $this.isLoadingServices = true;
      axios
        .get("/api/carservices?id=" + $this.selectedCar.id)
        .then(function(response) {
          $this.services.splice(0, $this.services.length);

          for (var i = 0; i < response.data.length; i++) {
            $this.services.push({
              id: response.data[i].id, // clave (csid)
              selected: false,
              selectedPrice: "min",
              low_total: 0,
              mid_total: 0,
              high_total: 0,
              low: response.data[i].low,
              mid: response.data[i].mid,
              high: response.data[i].high,
              comment: response.data[i].comment,
              warranty: response.data[i].warranty,
              exchange_rate: response.data[i].exchange_rate,
              service_id: response.data[i].service_id,
              name: response.data[i].name,
              items: response.data[i].items
            });
          }

          if ($this.services.length > 0) {
            $this.activeName = 0;
            $this.isValidService = true;
            $this.serviceChanged(0);
          } else {
            $this.isValidService = false;
          }
          $this.isLoadingServices = false;
        });
    },
    addService: function(service, total) {
      var $this = this;
      if ($this.selectedCar.id == undefined) {
        $this.$notify({
          title: "Selecciona Un carro válido",
          message: "El carro seleccionado no es válido",
          type: "error"
        });
        return;
      }

      var newService = {
        //id: null, // it has no id because it's neww
        service_id: service.id,
        isNew: true,
        selected: false,
        selectedPrice: "min",
        comment: "",
        warranty: "",
        exchange_rate: 0,
        name: service.name,
        low_total: total,
        mid_total: total,
        high_total: total,
        low: 0,
        mid: 0,
        high: 0,
        items: [
          {
            id: 1,
            name: "Mano de Obra",
            description: "",
            usd_price: 0,
            price: total,
            low: 0,
            low_price: total,
            mid: 0,
            mid_price: total,
            high: 0,
            high_price: total
          }
        ]
      };

      $this.services.push(newService);

      // set selected lastItem
      $this.activeName = $this.services.length - 1;
      $this.serviceChanged($this.activeName);
    },
    onMakerChange(selectedValue) {
      var $this = this;
      $this.brand = "";
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
    },
    onTDCChange(currentValue, oldValue) {
      var count = this.items.filter(x => isNaN(x.usd_price) || x.usd_price <= 0)
        .length;
      if (count > 0) {
        this.$alert(
          "No estan asignados todos los precios en dolares.",
          "Error",
          {
            confirmButtonText: "OK",
            type: "error"
          }
        );
        return;
      }

      this.$refs.selectItem.refreshPrices(currentValue);
    },

    // Global percentage
    onGlobalLowPercentageChange(currentValue, oldValue) {
      this.$refs.selectItem.changeGlobalPrecentage(currentValue, "low");
    },
    onGlobalMidPercentageChange(currentValue, oldValue) {
      this.$refs.selectItem.changeGlobalPrecentage(currentValue, "mid");
    },
    onGlobalHighPercentageChange(currentValue, oldValue) {
      this.$refs.selectItem.changeGlobalPrecentage(currentValue, "high");
    },

    serviceChanged(index) {
      if (isNaN(index) || index === "") return;

      if (index >= 0 && index < this.services.length) {
        this.service = this.services[index];
        this.isValidService = true;
        
        this.items.splice(0, this.items.length);
        if (this.service.items) {
          for (var j = 0; j < this.service.items.length; j++)
            this.items.push(this.service.items[j]);
        }
      }
    },
    search() {
      var $this = this;
      $this.selectedPrice = "low";
      $this.selectedServices = [];
      $this.services = [];
      $this.items.splice(0, $this.items.length);

      axios
        .get("/api/car/search", {
          params: {
            maker: $this.maker,
            brand: $this.brand,
            motor: $this.motor,
            year: $this.year,
            end_year: $this.year // TODO END Year
          }
        })
        .then(function(response) {
          if (response.data.length > 0) {
            $this.selectedCar.id = response.data[0].id;
            $this.selectedCar.maker = response.data[0].maker;
            $this.selectedCar.brand = response.data[0].brand;
            $this.selectedCar.year = response.data[0].start_year;
            $this.selectedCar.endYear = response.data[0].end_year;
            $this.selectedCar.image = response.data[0].image;

            $this.isValidCarId = true;
            $this.loadCarServices();
          } else {
            $this.selectedCar.id = "";
            $this.isValidCarId = false;
            $this.isValidService = false;

            $this.$notify({
              title: "No existe el carro",
              message: "No se encontró el carro seleccinado",
              type: "warning"
            });
          }

          /* TODO TEMPORALMENTE COMENTADO
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
          } */
        });
    },
    handleChange(value) {
      // BUG: debes crear un nuevo item si no no actualiza
      var item = new Object();
      item.id = this.listItems[value].id;
      item.name = this.listItems[value].name;
      item.description = this.listItems[value].description;
      item.usd_price = 1;
      item.price = 20;
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

      if (value == "low_price") {
        this.service.low_total = total;
      }
      if (value == "mid_price") {
        this.service.mid_total = total;
      }
      if (value == "high_price") {
        this.service.high_total = total;
      }

      return total;
    },

    // Guardar
    saveService() {
      var $this = this;
      // alert("car_id: " + $this.selectedCar.id);
      // alert("service_id: " + $this.service.service_id);
      // alert("csid: " + $this.service.id);
      // alert("warranty: " + $this.service.warranty);
      // alert("exchange_rate: " + $this.service.exchange_rate);
      // console.log($this.items);

      $this.isSaving = true;
      var saveParams = {
        car: $this.selectedCar.id, // Grabar car.id
        service: $this.service.service_id, //
        items: $this.items, // itemlist

        comment: $this.service.comment,
        warranty: $this.service.warranty,
        exchange_rate: $this.service.exchange_rate,
        low: $this.service.low,
        mid: $this.service.mid,
        high: $this.service.high,
        csid: $this.service.id
      };

      var reload = $this.service.isNew;

      if ($this.service.isNew) {
        // create a new CarService
        // alert("Es nuevo");
        saveParams = {
          car: $this.selectedCar.id, // Grabar car.id
          service: $this.service.service_id, // service id
          items: $this.items, // itemlist

          comment: $this.service.comment,
          warranty: $this.service.warranty,
          exchange_rate: $this.service.exchange_rate,
          low: $this.service.low,
          mid: $this.service.mid,
          high: $this.service.high,
          high: $this.service.high
        };
      }

      axios
        .post("/api/carservices", saveParams)
        .then(function(response) {
          // TODO save al editar cualquier campo $this.save = true;
          $this.$notify({
            title: "¡Exito!",
            message: "Servicio fue agregado correctamente",
            type: "success"
          });

          // Reload services
          if (reload) $this.loadCarServices();
          $this.isSaving = false;
          // setTimeout(function() {
          //   window.location.href = "/carservices";
          // }, 1000);
        })
        .catch(error => {
          if (error.response.data.errors) {
            var errors = error.response.data.errors;
            $this.$alert(errors[Object.keys(errors)[0]][0], "Error", {
              confirmButtonText: "OK",
              type: "error"
            });
          }

          $this.isSaving = false;
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
  padding: 4px;
}

.center {
  text-align: center;
  border: 1px solid #ebeef5;
}

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
.el-input.global {
  width: 60px;
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
.global > input {
  background: transparent;
  border-radius: 0;
  border: 0;
  color: white;
  font-size: small;
  text-align: center;
}
</style>
