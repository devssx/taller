<template>
  <div>
    <el-badge
      v-show="clients.length>0"
      :value="clients.length"
      style="margin-top:0px;margin-right:0px;line-height:30px;"
    >
      <el-popover placement="bottom" width="400" trigger="click">
        <el-row v-for="(c, index) in clients" :key="c.id" style="padding:1px">
          <el-alert
            :title="c.name"
            :description="c.reminder"
            type="info"
            @close="deleteNotification(index)"
          ></el-alert>
        </el-row>
        <span v-show="clients.length==0" style="padding-left: 140px;">Sin Notificaciones</span>
        <a href="#" slot="reference" style="color:white;">Notificaciones</a>
      </el-popover>
    </el-badge>
    <!-- Si no hay -->
    <el-popover placement="bottom" width="400" trigger="click">
      <span v-show="clients.length==0" style="padding-left: 140px;">Sin Notificaciones</span>
      <a v-show="clients.length==0" href="#" slot="reference" style="color:white;">Notificaciones</a>
    </el-popover>
  </div>
</template>

<script>
export default {
  data() {
    return {
      clients: []
    };
  },
  mounted: function() {
    const $this = this;
    var today = $this.toFixedDateFormat(new Date(), `yyyy-MM-dd`);
    var reminders = $this.loadObject(`reminders`);
    if (!reminders || reminders.day != today) {
      // carga si no hay nada el dia de hoy
      $this.loadReminders("/api/clients?reminders=1", today);
    } else {
      reminders.data.forEach(c => {
        $this.clients.push(c);
      });
    }
  },
  methods: {
    deleteNotification(index) {
      this.clients.splice(index, 1);
      var today = this.toFixedDateFormat(new Date(), `yyyy-MM-dd`);

      // guarda lista de recordatorios
      this.deleteObject(`reminders`);
      this.saveObject({ day: today, data: this.clients }, `reminders`);
    },
    loadReminders(url, today) {
      var $this = this;

      axios.get(url).then(function(response) {
        // guarda lista de recordatorios
        $this.deleteObject(`reminders`);
        $this.saveObject({ day: today, data: response.data }, `reminders`);

        var n = 1;
        response.data.forEach(c => {
          n++;
          setTimeout(function() {
            // inserta reminder en la lista
            $this.clients.push(c);
            // muestra reminder
            $this.show(c.name, c.reminder);
          }, 1000 * n);
        });
      });
    },
    show(title, message) {
      this.$notify.info({ title: title, message: message });
    }
  }
};
</script>