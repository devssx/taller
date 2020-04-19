<template>
  <div class="block" style="float:right;">
    <input
      type="file"
      ref="fileInput"
      @change="onFileSelected"
      accept=".jpg, .jpeg, .png"
      style="display:none"
    />
    <el-button @click="$refs.fileInput.click()" icon="el-icon-upload"></el-button>
  </div>
</template>

<script>
export default {
  props: ["action", "onSelected", "onPreview"],
  data() {
    return { selectedFile: null };
  },
  mounted: function() {},
  methods: {
    createPreview(file) {
      var $this = this;
      if (file) {
        var reader = new FileReader();

        reader.onload = function(e) {
          $this.onPreview(e.target.result);
        };

        // convert to base64 string
        reader.readAsDataURL(file);
      }
    },
    onFileSelected(event) {
      this.selectedFile = event.target.files[0];
      if (this.onSelected) {
        this.onSelected(this.selectedFile);
      }
      if (this.onPreview) {
        this.createPreview(this.selectedFile);
      }
    },
    submit(onSuccess, onFail) {
      var $this = this;
      if ($this.action) {
        const fd = new FormData();
        fd.append("image", $this.selectedFile, $this.selectedFile.name);
        axios
          .post($this.action, fd)
          .then(function(response) {
            if (onSuccess) onSuccess(response.data);
          })
          .catch(error => {
            if (onFail) onFail(error);
          });
      } else {
        $this.$alert("Metodo Action sin definir!", "Error", {
          confirmButtonText: "OK",
          type: "error"
        });
      }
    }
  }
};
</script>