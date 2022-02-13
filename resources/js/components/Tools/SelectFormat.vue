<template>
  <el-col :span="5">
    <el-dialog
      title="Seleccionar Formato"
      :visible.sync="dialogVisible"
      width="920px"
      :before-close="handleClose"
    >
      <el-row>
        <el-col :span="24">
          <el-select style="width: 100%" v-model="value" placeholder="Formato">
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value"
              :disabled="item.disabled"
            >
            </el-option>
          </el-select>
        </el-col>
      </el-row>
      <el-row>
        <el-col>
          <h3 style="margin-top: 16px; margin-bottom: 16px">
            Piezas Reemplazadas:
          </h3>
        </el-col>
      </el-row>
      <el-row align="middle" style="text-align: center">
        <el-col :span="24">
          <table>
            <tbody>
              <tr style="vertical-align:top">
                <td v-for="piece in parts" :key="piece.key">
                  <el-checkbox v-model="piece.selected"></el-checkbox>
                  <el-image
                    style="width: 54px; display: block"
                    :src="piece.img"
                  ></el-image>
                  <div style="font-size: 8px">{{ piece.title }}</div>
                </td>
              </tr>
            </tbody>
          </table>
        </el-col>
      </el-row>
      <span slot="footer" class="dialog-footer">
        <el-button @click="cancel()">Cancelar</el-button>
        <el-button type="primary" @click="onSelect()">Aceptar</el-button>
      </span>
    </el-dialog>
  </el-col>
</template>
<script>
export default {
  props: ["name"],
  data() {
    return {
      parts: [
        {
          selected: false,
          img: "/img/piece_a.png",
          title: "Válvulas de Servicio",
          key: "A",
        },
        {
          selected: false,
          img: "/img/piece_b.png",
          title: "Filtro de Cabina",
          key: "B",
        },
        {
          selected: false,
          img: "/img/piece_c.png",
          title: "Empaques",
          key: "C",
        },
        {
          selected: false,
          img: "/img/piece_d.png",
          title: "Banda",
          key: "D",
        },
        {
          selected: false,
          img: "/img/piece_e.png",
          title: "Compresor",
          key: "E",
        },
        {
          selected: false,
          img: "/img/piece_f.png",
          title: "Filtro",
          key: "F",
        },
        {
          selected: false,
          img: "/img/piece_g.png",
          title: "Fan Clitch",
          key: "G",
        },
        {
          selected: false,
          img: "/img/piece_h.png",
          title: "Blower",
          key: "H",
        },
        {
          selected: false,
          img: "/img/piece_i.png",
          title: "Resistencia",
          key: "I",
        },
        {
          selected: false,
          img: "/img/piece_j.png",
          title: "Bulbo",
          key: "J",
        },
        {
          selected: false,
          img: "/img/piece_k.png",
          title: "Condensador",
          key: "K",
        },
        {
          selected: false,
          img: "/img/piece_l.png",
          title: "Manguera",
          key: "L",
        },
        {
          selected: false,
          img: "/img/piece_m.png",
          title: "Válvula",
          key: "M",
        },
        {
          selected: false,
          img: "/img/piece_n.png",
          title: "Evaporador",
          key: "N",
        },
        {
          selected: false,
          img: "/img/piece_o.png",
          title: "Electrico",
          key: "O",
        },
      ],
      options: [
        {
          value: "0",
          label: "Concepto",
        },
        {
          value: "1",
          label: "Lista de artículos sin precio",
        },
        {
          value: "2",
          label: "Lista de artículos con precio",
        },
      ],
      value: "0",
      dialogVisible: false,
      loading: false,
      labelPosition: "left",
    };
  },
  methods: {
    showDialog(values) {
      this.dialogVisible = true;

      // load checkboxes
      for (let part of this.parts) {
        part.selected = values.includes(part.key);
      }
    },
    handleClose(done) {
      this.dialogVisible = false;
    },
    cancel() {
      this.value = "0";
      this.dialogVisible = false;
    },
    onSelect() {
      let $this = this;
      this.dialogVisible = false;
      var optionValue = parseInt(this.value);
      this.value = "0";

      // selected parts
      let parts = ``;
      for (let part of $this.parts) {
        if (part.selected) {
          parts += part.key;
        }
      }

      setTimeout(function () {
        $this.$root.$emit("selectedFormat", optionValue, $this.name, parts);
      }, 500);
    },
  },
};
</script>
