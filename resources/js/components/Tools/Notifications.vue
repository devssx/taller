<template>
  <div>
    <el-badge
      v-if="clients.length>0"
      :value="clients.length"
      style="margin-top:0px;margin-right:0px;line-height:30px;"
    >
      <el-popover placement="bottom" title width="400" trigger="click" content="Sin Notificaciones">
        <a href="#" slot="reference" style="color:white;">Notificaciones</a>
      </el-popover>
    </el-badge>
    <!-- Si no hay -->
    <el-popover placement="bottom" title width="400" trigger="click" content="Sin Notificaciones">
      <a v-if="clients.length==0" href="#" slot="reference" style="color:white;">Notificaciones</a>
    </el-popover>
  </div>
</template>

<script>
export default {
  data() {
    return { clients: [] };
  },
  mounted: function() {
    this.loadReminders("/api/clients?reminders=1");
  },
  methods: {
    delete() {},
    loadReminders(url) {
      var $this = this;
      axios.get(url).then(function(response) {
        $this.clients = response.data;
        var n = 1;
        $this.clients.forEach(c => {
          n++;
          setTimeout(function() {
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