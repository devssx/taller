<template>
  <div>
    <el-badge
      v-show="clients.length>0"
      :value="clients.length"
      style="margin-top:0px;margin-right:0px;line-height:30px;"
    >
      <el-popover placement="bottom" title width="400" trigger="click">
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
    <el-popover placement="bottom" title width="400" trigger="click">
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
    this.loadReminders("/api/clients?reminders=1");
  },
  methods: {
    deleteNotification(index) {
      this.clients.splice(index, 1);
    },
    loadReminders(url) {
      var $this = this;
      axios.get(url).then(function(response) {
        //$this.clients = response.data;
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