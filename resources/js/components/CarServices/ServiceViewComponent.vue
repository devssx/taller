<template>
  <el-container>
    <el-main class="content">
      <!-- Top bar -->
      <el-row type="flex" align="middle">
        <el-col :span="24">
          <el-form
            inline
            label-position="right"
            label-width="80px"
            class="query-form"
            style="text-align: end"
          >
            <el-form-item label="Servicio">
              <el-select
                placeholder="Filtrar carros por servicio"
                :size="controlSize"
                v-model="cService"
                filterable
                allow-create
                default-first-option
                @change="onCarServiceChange"
              >
                <el-option
                  v-for="service in carServices"
                  :key="service.name"
                  :label="service.name"
                  :value="service.name"
                ></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Año">
              <el-select
                :size="controlSize"
                default-first-option
                v-model="year"
                filterable
                class="select-year"
                :disabled="years.length == 0"
              >
                <el-option
                  v-for="year in years"
                  :key="year"
                  :label="year"
                  :value="year"
                ></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Marca">
              <el-select
                class="select-ipad"
                :size="controlSize"
                v-model="maker"
                filterable
                allow-create
                default-first-option
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
              <el-select
                class="select-modelo"
                :size="controlSize"
                v-model="brand"
                filterable
                allow-create
                default-first-option
                @change="onBrandChange"
              >
                <el-option
                  v-for="brand in brands"
                  :key="brand.brand"
                  :label="brand.brand"
                  :value="brand.brand"
                ></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Motor">
              <el-select
                class="select-motor"
                :size="controlSize"
                filterable
                allow-create
                default-first-option
                v-model="motor"
              >
                <el-option
                  v-for="m in motors"
                  :key="m"
                  :label="m"
                  :value="m"
                ></el-option>
              </el-select>
            </el-form-item>
            <el-form-item>
              <el-button
                :loading="isLoadingServices"
                :size="controlSize"
                type="primary"
                icon="el-icon-search"
                @click="search"
                :disabled="year == ''"
                >Buscar</el-button
              >
            </el-form-item>
          </el-form>
          <br />
          <!-- Formulario Crear Carro -->
          <create-cars ref="carsForm" :hidden="true"></create-cars>
        </el-col>
      </el-row>

      <el-row>
        <el-col :span="6">
          <el-row
            type="flex"
            align="middle"
            style="background-color: #f2f2f2; padding: 4px; height: 44px"
          >
            <el-col :span="8">
              <h4>
                Servicios
                <span v-if="getSelectedServices() > 0"
                  >({{ getSelectedServices() }})</span
                >
              </h4>
            </el-col>
            <el-col :span="16">
              <div style="float: right">
                <!-- Boton Agregar Servicio -->
                <create-pop-sales
                  :disabled="!isValidCarId || isLoadingServices"
                  :size="controlSize"
                ></create-pop-sales>
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
              :title="s.isSelected ? s.name + ' (Seleccionado)' : s.name"
              :name="index"
            >
              <!-- {{s.selectedPrice}} -->
              <table style="width: 100%">
                <tr>
                  <th class="price-min">Precio Min</th>
                  <th class="price-med">Precio Med</th>
                  <th class="price-max">Precio Max</th>
                </tr>
                <tr>
                  <td>${{ formatPrice(s.low_total) }}</td>
                  <td>${{ formatPrice(s.mid_total) }}</td>
                  <td>${{ formatPrice(s.high_total) }}</td>
                </tr>
                <tr>
                  <td>
                    <el-checkbox
                      v-model="s.isLowSelected"
                      @change="onServiceChecked(s, 'low')"
                    ></el-checkbox>
                  </td>
                  <td>
                    <el-checkbox
                      v-model="s.isMidSelected"
                      @change="onServiceChecked(s, 'mid')"
                    ></el-checkbox>
                  </td>
                  <td>
                    <el-checkbox
                      v-model="s.isMaxSelected"
                      @change="onServiceChecked(s, 'high')"
                    ></el-checkbox>
                  </td>
                </tr>
              </table>
              <div class="edit-buttons">
                <el-tooltip
                  effect="dark"
                  content="Eliminar Servicio"
                  placement="top"
                >
                  <el-button
                    @click="deleteService(s)"
                    type="danger"
                    icon="el-icon-delete"
                    :size="controlSize"
                  ></el-button>
                </el-tooltip>
              </div>
            </el-collapse-item>
          </el-collapse>
          <el-row v-if="!isValidCarId || !isValidService" style="padding: 10px"
            >Sin Servicios</el-row
          >
        </el-col>

        <el-col :span="18" style="padding-left: 20px">
          <el-row
            type="flex"
            align="middle"
            style="background-color: #f2f2f2; padding: 4px; height: 44px"
          >
            <el-col :span="8">
              <h3>{{ service.name }}</h3>
            </el-col>
            <el-col :span="8">
              <div style="float: right">
                <el-button
                  type="primary"
                  icon="el-icon-tickets"
                  :size="controlSize"
                  @click="next(0)"
                  >Cotización</el-button
                >
                <el-button
                  :size="controlSize"
                  type="primary"
                  icon="el-icon-tickets"
                  @click="next(1)"
                  >Recibo</el-button
                >
              </div>
            </el-col>
            <el-col :span="8">
              <div style="float: right">
                <el-tooltip
                  effect="dark"
                  :content="getServiceName()"
                  placement="top"
                >
                  <el-button
                    :size="controlSize"
                    :disabled="services.length == 0"
                    :loading="isSaving"
                    icon="el-icon-edit"
                    type="primary"
                    @click="saveService()"
                    >MODIFICAR</el-button
                  >
                </el-tooltip>
              </div>
            </el-col>
          </el-row>

          <br />
          <el-card class="box-card">
            <div v-if="!isValidCarId">Selecciona un carro válido</div>
            <div v-if="isValidCarId && !isValidService">Agrega un Servicio</div>
            <div v-if="isValidService">
              <!-- Creando -->
              <el-row
                class="row-header"
                style="margin-bottom: 5px"
                type="flex"
                align="middle"
              >
                <el-col :span="12">SERVICIO</el-col>
                <el-col :span="12">FOTO DEL CARRO</el-col>
              </el-row>
              <el-row class="center" type="flex" align="middle">
                <el-col :span="12">{{ service.name }}</el-col>
                <el-col :span="12" class="bl">
                  <el-image
                    style="width: 230px"
                    :src="selectedCar.image"
                  ></el-image>
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
                    :disabled="service.isReadOnly"
                    style="width: 100%; border-radius: 0"
                  ></el-input-number>
                </el-col>
                <el-col :span="4">{{ selectedCar.maker }}</el-col>
                <el-col :span="4">{{ selectedCar.brand }}</el-col>
                <el-col :span="4">{{ selectedCar.year }}</el-col>
                <el-col :span="4">{{ selectedCar.endYear }}</el-col>
              </el-row>
              <el-row type="flex" align="middle" class="br">
                <el-col :span="12" class="row-header"
                  >PORCENTAJE DE GANANCIA EN LAS PIEZAS:</el-col
                >
                <el-col :span="2" class="price-min">
                  <el-input
                    :readonly="service.isReadOnly"
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
                    :readonly="service.isReadOnly"
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
                    :readonly="service.isReadOnly"
                    size="mini"
                    @change="onGlobalHighPercentageChange"
                    maxlength="2"
                    v-model="service.high"
                    class="global"
                  ></el-input>
                </el-col>
                <el-col :span="2" style="height: 28px"></el-col>
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
                :items="service.items"
                :tdc="service.exchange_rate"
                :isReadOnly="service.isReadOnly"
                :fullMode="fullMode"
              ></row-item>

              <!-- ++ Items -->
              <el-row class="row-header-left" v-if="!service.isReadOnly">
                <el-col :span="24">
                  Agregar más artículos:
                  <el-select
                    size="mini"
                    @change="onItemSelected"
                    filterable
                    allow-create
                    default-first-option
                    placeholder="Agregar un Artículo"
                    v-model="item"
                    :disabled="
                      listItems.length == 0 ||
                      !isValidCarId ||
                      service.service_id == undefined ||
                      service.service_id == ''
                    "
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

              <!-- Totales -->
              <el-row
                class="bt bl br"
                style="height: 28px"
                type="flex"
                align="middle"
              >
                <el-col style="padding: 10px" :span="7">TOTAL</el-col>
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
                <el-col :span="4" style="padding: 10px"
                  >Descripción del servicio</el-col
                >
                <el-col :span="20">
                  <el-input
                    type="textarea"
                    :autosize="{ minRows: 2, maxRows: 4 }"
                    placeholder="Descripción"
                    v-model="service.comment"
                    :readonly="service.isReadOnly"
                    :disabled="service.items.length == 0"
                  ></el-input>
                </el-col>
              </el-row>
              <!-- descripcion del servicio -->
              <el-row class="bt bl br">
                <el-col :span="4" style="padding: 10px">Garantía</el-col>
                <el-col :span="20">
                  <el-input
                    type="textarea"
                    :autosize="{ minRows: 2, maxRows: 4 }"
                    placeholder="Garantía"
                    v-model="service.warranty"
                    :readonly="service.isReadOnly"
                    :disabled="service.items.length == 0"
                    maxlength="165"
                    show-word-limit
                  ></el-input>
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
  props: ["fullMode"],
  watch: {
    filterText(val) {
      this.$refs.services.filter(val);
    },
  },
  data() {
    return {
      controlSize: "mini",
      hasChanged: false,
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
        isReadOnly: false,
        exchange_rate: 0,
        low: 0,
        mid: 0,
        high: 0,
        name: "",
        comment: "",
        warranty: "",
      },

      selectedCar: {
        maker: "",
        brand: "",
        year: "",
        endYear: "",
        image: "",
      },

      maker: "",
      makers: [],
      cService: "",
      carServices: [],
      brand: "",
      brands: [],
      year: "",
      years: [],

      motor: "",
      motors: [],

      articulos: [],

      filterText: "",
      selectedServices: [],
      //selectedPrice: "low",
      car: "",
      cars: [],
      carsAux: [],
      services: [],
      item: "",
      listItems: [],
      save: false,
      defaultProps: {
        label: "label",
      },
    };
  },
  mounted: function () {
    const $this = this;

    const width = window.innerWidth > 0 ? window.innerWidth : screen.width;
    if (width > 1366) $this.controlSize = "medium";

    // Evento boton add service
    $this.$root.$on("addService", $this.addService);
    $this.$root.$on("onNewCarCreated", $this.onNewCarCreated);

    $this.year = new Date().getFullYear();
    $this.motors = [];
    $this.motor = "";

    $this.articulos = [];

    $this.years = [];
    for (var year = 1999; year <= new Date().getFullYear(); year++) {
      $this.years.push(year);
    }

    // 2021
    $this.getCarServices((services) => {
      $this.carServices = services;
    });

    axios.get("/api/cars?all").then(function (response) {
      $this.cars = response.data;
      $this.carsAux = response.data;
      // remover indefindo (solo usado en caso especial)
      for (var i = 0; i < $this.cars.length; i++) {
        if ($this.cars[i].maker === "indefinido") {
          $this.cars.splice(i, 1);
          i--;
        }
      }
    });

    axios.get("/api/items?all=1").then(function (response) {
      $this.listItems = response.data;
    });

    axios.get("/api/car/makers").then(function (response) {
      $this.makers = response.data;
      // remover indefindo (solo usado en caso especial)
      for (var i = 0; i < $this.makers.length; i++) {
        if ($this.makers[i].maker === "indefinido") {
          $this.makers.splice(i, 1);
          i--;
        }
      }
    });

    // GoBack
    if (localStorage.getItem("order") && $this.getParameter("back")) {
      var order = JSON.parse(localStorage.getItem("order"));
      setTimeout(function () {
        if (order.car) {
          $this.selectedCar = order.car;
          setTimeout(function () {
            $this.maker = $this.selectedCar.maker;
            $this.motor = $this.selectedCar.motor;
            $this.brand = order.brand;
            $this.year = order.year;
            $this.isValidCarId = true;
            $this.loadCarServices();
          }, 1000);
        }
      }, 0);
    }
  },
  methods: {
    onServiceChecked(item, type) {
      item.selectedPrice = "";

      switch (type) {
        case "low":
          if (item.isLowSelected) item.selectedPrice = "low";
          item.isSelected = item.isLowSelected;
          item.isMidSelected = false;
          item.isMaxSelected = false;
          break;
        case "mid":
          if (item.isMidSelected) item.selectedPrice = "mid";
          item.isSelected = item.isMidSelected;
          item.isLowSelected = false;
          item.isMaxSelected = false;
          break;
        case "high":
          if (item.isMaxSelected) item.selectedPrice = "high";
          item.isSelected = item.isMaxSelected;
          item.isLowSelected = false;
          item.isMidSelected = false;
          break;
      }
    },
    saveNewItem(item) {
      var $this = this;
      axios
        .post("/api/items", item)
        .then(function (response) {
          $this.adItemToService(response.data);
          $this.listItems.push(response.data);
        })
        .catch((error) => {
          if (error.response.data.errors) {
            var errors = error.response.data.errors;
            $this.$alert(errors[Object.keys(errors)[0]][0], "Error", {
              confirmButtonText: "OK",
              type: "error",
            });
          }
        });
    },
    getServiceName() {
      return this.service.name
        ? `Modificar ${this.service.name}`
        : "Modificar el servicio mostrado.";
    },
    onNewCarCreated(car) {
      this.cars.push(car);
      this.setSelectedCar(car);
    },
    setSelectedCar(car) {
      this.selectedCar.id = car.id;
      this.selectedCar.maker = car.maker;
      this.selectedCar.brand = car.brand;
      this.selectedCar.motor = car.motor;
      this.selectedCar.year = car.start_year;
      this.selectedCar.endYear = car.end_year;
      this.selectedCar.image = car.image;

      this.isValidCarId = true;
      this.loadCarServices();
    },
    next(mode) {
      let $this = this;
      var count = this.getSelectedServices();

      if (count > 0) {
        var services = this.services.filter((s) => s.isSelected);
        const order = {
          services: services,
          brand: this.brand,
          year: this.year,
          receiptMode: mode == 1,
          car: this.selectedCar,
        };

        const parsed = JSON.stringify(order);
        localStorage.setItem("order", parsed);
        window.location.href = "/sales/receipt";
      } else {
        let warn = this.isValidCarId ? "ningún servicio" : "carro ni servicio";
        $this
          .$confirm(
            `No se ha seleccionado ${warn}. ¿Desea continuar?`,
            "Advertencia",
            {
              confirmButtonText: "Si",
              cancelButtonText: "No",
              type: "warning",
            }
          )
          .then(() => {
            $this.searchUndefCar((car) => {
              $this.loadCarServiceByCarId(car.id, (serv) => {
                if (!$this.isValidCarId) {
                  $this.selectedCar.id = car.id;
                  $this.selectedCar.maker = "";
                  $this.selectedCar.brand = "";
                  $this.selectedCar.motor = "";
                  $this.selectedCar.year = car.start_year;
                  $this.selectedCar.endYear = car.end_year;
                  $this.selectedCar.image = car.image;
                }

                // default values
                serv[0].label = "Detalles del Servicio";
                serv[0].isLowSelected = true;
                serv[0].selectedPrice = "low";
                serv[0].items[0].low_price = 0;
                serv[0].items[0].item.name = "Costo total del servicio";

                const order = {
                  services: serv,
                  undefService: true,
                  year: $this.year,
                  brand: $this.selectedCar.brand,
                  car: $this.selectedCar,
                  receiptMode: mode == 1,
                };

                const parsed = JSON.stringify(order);
                localStorage.setItem("order", parsed);
                window.location.href = "/sales/receipt";
              });
            });
          });
      }
    },
    searchUndefCar(onFound) {
      axios
        .get("/api/car/search", {
          params: {
            maker: "indefinido",
            brand: "indefinido",
            motor: "indefinido",
            year: 2013,
            end_year: 2013,
          },
        })
        .then(function (response) {
          if (onFound && response.data.length > 0) onFound(response.data[0]);
        });
    },
    getSelectedServices() {
      if (this.services.length == 0) return 0;
      return this.services.filter((x) => x.isSelected).length;
    },
    deleteService(serviceItem) {
      var $this = this;
      $this
        .$confirm(
          "Esto eliminará permanentemente el servicio asociado a este carro. ¿Desea continuar?",
          "Advertencia",
          {
            confirmButtonText: "Si",
            cancelButtonText: "No",
            type: "warning",
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
            .then(function (response) {
              $this.$message({
                type: "success",
                message: "Servicio elminado",
              });
              //$this.$root.$emit("refreshTable");
              //$this.dialogVisible = false;
              //$this.loading = false;
              $this.loadCarServices();
            })
            .catch((error) => {
              //$this.loading = false;
              if (error.response.data.errors) {
                var errors = error.response.data.errors;
                $this.$alert(errors[Object.keys(errors)[0]][0], "Error", {
                  confirmButtonText: "OK",
                  type: "error",
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
        .then(function (response) {
          $this.services.splice(0, $this.services.length);

          for (var i = 0; i < response.data.length; i++) {
            $this.services.push({
              id: response.data[i].id, // clave (csid)
              isSelected: false,
              isLowSelected: false,
              isMidSelected: false,
              isMaxSelected: false,
              selectedPrice: "",
              isReadOnly: false,
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
              label: response.data[i].name,
              items: response.data[i].items,
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
    loadCarServiceByCarId(id, onLoaded) {
      var $this = this;
      axios.get(`/api/carservices?id=${id}`).then(function (response) {
        var services = [];

        for (var i = 0; i < response.data.length; i++) {
          services.push({
            id: response.data[i].id, // clave (csid)
            isSelected: false,
            isLowSelected: false,
            isMidSelected: false,
            isMaxSelected: false,
            selectedPrice: "",
            isReadOnly: false,
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
            label: response.data[i].name,
            items: response.data[i].items,
          });
        }

        if (onLoaded) onLoaded(services);
      });
    },
    addService: function (service, total) {
      if (this.selectedCar.id == undefined) {
        this.$notify({
          title: "Selecciona un carro válido",
          message: "El carro seleccionado no es válido",
          type: "error",
        });
        return;
      }

      // don't duplicate services
      var count = this.services.filter((x) => x.name == service.name).length;
      if (count > 0) {
        this.$notify({
          title: "Ya existe.",
          message: "El servicio seleccionado ya existe.",
          type: "error",
        });
        return;
      }

      var newService = {
        //id: null, // it has no id because it's new
        service_id: service.id,
        isNew: true,
        isSelected: false,
        isLowSelected: false,
        isMidSelected: false,
        isMaxSelected: false,
        selectedPrice: "",
        isReadOnly: false,
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
            high_price: total,
          },
        ],
      };

      // saves the new Service
      this.saveNewCarService(newService);
    },
    getParameter(name) {
      var result = null,
        tmp = [];
      location.search
        .substr(1)
        .split("&")
        .forEach(function (item) {
          tmp = item.split("=");
          if (tmp[0] === name) result = decodeURIComponent(tmp[1]);
        });
      return result;
    },
    onCarServiceChange(selectedValue) {
      var result = this.carServices.filter((s) => s.name == selectedValue);
      if (result.length > 0) {
        this.cars = result[0].cars;
      } else {
        // restore carlist
        this.cars = this.carsAux;
      }

      this.filterMakers(this.cars);
    },
    filterMakers(cars) {
      this.maker = "";
      this.makers.splice(0, this.makers.length);

      for (var i = 0; i < cars.length; i++) {
        var maker = this.cars[i].maker;
        if (maker === "indefinido") continue;

        var mkrs = this.makers.filter((m) => m.maker === maker);
        if (mkrs.length == 0) {
          this.makers.push({ maker: maker });
        }
      }

      //this.maker = this.makers[0].maker;
      //this.onMakerChange(this.maker);
    },
    onMakerChange(selectedValue) {
      this.brand = "";
      this.brands.splice(0, this.brands.length);

      this.motor = "";
      this.motors = [];

      // filtra los modelos de la marca seleccionada
      for (var i = 0; i < this.cars.length; i++) {
        var maker = this.cars[i].maker;
        if (maker == selectedValue) {
          if (
            this.brands.filter((m) => m.brand == this.cars[i].brand).length == 0
          ) {
            this.brands.push(this.cars[i]);
          }
        }
      }
    },
    onBrandChange(selectedValue) {
      this.motor = "";
      this.motors = [];

      // filtra los modelos de la marca seleccionada
      for (var i = 0; i < this.brands.length; i++) {
        var brand = this.brands[i].brand;
        if (brand == selectedValue) {
          if (
            this.motors.filter((m) => m == this.brands[i].motor).length == 0
          ) {
            this.motors.push(this.brands[i].motor);
          }
        }
      }

      if (this.motors.length == 1) {
        this.motor = this.motors[0];
      }
    },
    onTDCChange(currentValue, oldValue) {
      var count = this.service.items.filter(
        (x) => isNaN(x.usd_price) || x.usd_price <= 0
      ).length;
      if (count > 0) {
        this.$alert("Favor de asignar todos los precios en dólares.", "Error", {
          confirmButtonText: "OK",
          type: "error",
        });
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
      }
    },
    search() {
      var $this = this;
      $this.selectedServices = [];
      $this.services = [];

      axios
        .get("/api/car/search", {
          params: {
            maker: $this.maker.trim(),
            brand: $this.brand.trim(),
            motor: $this.motor,
            year: $this.year,
            end_year: $this.year,
          },
        })
        .then(function (response) {
          if (response.data.length > 0) {
            $this.setSelectedCar(response.data[0]);
          } else {
            $this.selectedCar.id = "";
            $this.isValidCarId = false;
            $this.isValidService = false;

            $this
              .$confirm(
                "No se encontró el carro. ¿Desea registrar el carro con esta información?",
                "Carro no encontrado",
                {
                  confirmButtonText: "Si",
                  cancelButtonText: "No",
                  type: "warning",
                }
              )
              .then((e) => {
                if (!$this.motor.trim()) 
                  $this.motor = '1.8';
                
                const car = {
                  maker: $this.maker.trim(),
                  brand: $this.brand.trim(),
                  motor: $this.motor.trim(),
                  year: $this.year,
                };
                $this.$refs.carsForm.showDialog(car);
              })
              .catch((e) => {});
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
    onItemSelected(value) {
      const $this = this;
      if (value && typeof value == "string") {
        // este item no existe lo busca si no esta se registra
        var index = -1;
        for (var i = 0; i < this.listItems.length; i++) {
          if (this.listItems[i].name.toUpperCase() == value.toUpperCase()) {
            index = i;
            value = i;
            break;
          }
        }

        // crea el item
        if (index < 0) {
          var newItem = { name: value, image: "", description: "" };
          $this.saveNewItem(newItem);

          // setTimeout(function() {
          //   $this
          //     .$confirm(
          //       `El Artículo '${value}' no existe ¿Desea registrarlo?`,
          //       "Artículo Nuevo",
          //       {
          //         confirmButtonText: "Si, Registrar",
          //         cancelButtonText: "Cancelar",
          //         type: "warning"
          //       }
          //     )
          //     .then(() => {
          //       $this.saveNewItem(newItem);
          //     })
          //     .catch(() => {});
          // }, 400);
          return;
        }
      }

      this.adItemToService(this.listItems[value]);
    },
    adItemToService(selectedItem) {
      // BUG: debes crear un nuevo item si no no actualiza
      var item = new Object();
      item.image = selectedItem.image;
      item.id = selectedItem.id;
      item.item_id = selectedItem.id;
      item.name = selectedItem.name;
      item.description = selectedItem.description;
      item.usd_price = 0;
      item.price = 0;
      item.low = this.service.low;
      item.low_price = 0;
      item.mid = this.service.mid;
      item.mid_price = 0;
      item.high = this.service.high;
      item.high_price = 0;
      this.service.items.push(item);
      this.$refs.selectItem.$forceUpdate();
    },
    formatPrice(value) {
      let val = (value / 1).toFixed(2);
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },
    sumItemPrice(value) {
      var total = 0;
      if (this.service.items) {
        for (var item in this.service.items) {
          if (this.service.items[item][value]) {
            total += parseFloat(this.service.items[item][value]);
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
    saveNewCarService(newService) {
      const $this = this;
      $this.isSaving = true;

      // create a new CarService
      const saveParams = {
        car: $this.selectedCar.id, // Save car.id
        service: newService.service_id, // service id
        items: newService.items, // itemlist

        comment: newService.comment,
        warranty: newService.warranty,
        exchange_rate: newService.exchange_rate,
        low: newService.low,
        mid: newService.mid,
        high: newService.high,
        high: newService.high,
      };

      axios
        .post("/api/carservices", saveParams)
        .then(function (response) {
          $this.service.isReadOnly = false;
          $this.isSaving = false;

          // agrega a la lista de servicios el nuevo servicio grabado
          if (response.data.createdService) {
            for (var i = 0; i < response.data.createdService.length; i++) {
              const dbService = response.data.createdService[i];
              $this.services.push({
                id: dbService.id,
                isSelected: false,
                isLowSelected: false,
                isMidSelected: false,
                isMaxSelected: false,
                selectedPrice: "",
                isReadOnly: false,
                low_total: 0,
                mid_total: 0,
                high_total: 0,
                low: dbService.low,
                mid: dbService.mid,
                high: dbService.high,
                comment: dbService.comment,
                warranty: dbService.warranty,
                exchange_rate: dbService.exchange_rate,
                service_id: dbService.service_id,
                name: dbService.name,
                label: dbService.name,
                items: dbService.items,
              });
            }

            // set isSelected lastItem
            $this.activeName = $this.services.length - 1;
            $this.serviceChanged($this.activeName);
          }
        })
        .catch((error) => {
          if (error.response.data.errors) {
            var errors = error.response.data.errors;
            $this.$alert(errors[Object.keys(errors)[0]][0], "Error", {
              confirmButtonText: "OK",
              type: "error",
            });
          }

          $this.isSaving = false;
        });
    },

    // Guardar
    saveService() {
      var $this = this;
      $this.isSaving = true;
      var saveParams = {
        car: $this.selectedCar.id, // save car.id
        service: $this.service.service_id, //
        items: $this.service.items, // itemlist

        comment: $this.service.comment,
        warranty: $this.service.warranty,
        exchange_rate: $this.service.exchange_rate,
        low: $this.service.low,
        mid: $this.service.mid,
        high: $this.service.high,
        csid: $this.service.id,
      };

      var isNew = $this.service.isNew;
      if ($this.service.isNew) {
        // create a new CarService
        saveParams = {
          car: $this.selectedCar.id, // Save car.id
          service: $this.service.service_id, // service id
          items: $this.service.items, // itemlist

          comment: $this.service.comment,
          warranty: $this.service.warranty,
          exchange_rate: $this.service.exchange_rate,
          low: $this.service.low,
          mid: $this.service.mid,
          high: $this.service.high,
          high: $this.service.high,
        };
      }

      // confirmar
      this.confirmar(
        `¿Esta seguro que desea modificar el servicio: ${this.service.name}?`,
        () => {
          axios
            .post("/api/carservices", saveParams)
            .then(function (response) {
              $this.$notify({
                title: "¡Exito!",
                message: isNew
                  ? "Servicio fue agregado correctamente"
                  : "Se ha editado el servicio correctamente",
                type: "success",
              });

              $this.service.isReadOnly = false;
              $this.isSaving = false;

              // reload
              $this.loadCarServices();
            })
            .catch((error) => {
              if (error.response.data.errors) {
                var errors = error.response.data.errors;
                $this.$alert(errors[Object.keys(errors)[0]][0], "Error", {
                  confirmButtonText: "OK",
                  type: "error",
                });
              }

              $this.isSaving = false;
            });
        }
      );
    },
  },
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

.row-header-left {
  background-color: #f2f2f2;
  font-weight: bold;
  text-align: left;
  padding: 4px;
  padding-left: 10px;
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

.select-year {
  width: 80px;
}

.select-motor {
  width: 130px;
}
.select-modelo {
  width: 130px;
}

.el-form-item {
  margin-bottom: 8px;
}
.query-form {
  margin-top: 0;
  padding-top: 8px;
  background-color: #f2f2f2;
}

/* Ipad Pro */
@media only screen and (max-width: 1366px) {
  .content.el-main {
    padding: 5px;
  }
  .select-ipad {
    width: 100px;
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
