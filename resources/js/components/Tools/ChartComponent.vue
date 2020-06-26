<script>
import { Bar } from "vue-chartjs";

export default {
  props: ["chartData"],
  extends: Bar,
  data: () => ({
    data: {
      labels: [],
      datasets: []
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        display: false
      },
      scales: {
        yAxes: [
          {
            ticks: {
              beginAtZero: true,
              callback: function(value) {
                var ranges = [
                  { divider: 1e6, suffix: "M" }
                  //{ divider: 1e3, suffix: "K" }
                ];
                function formatNumber(n) {
                  for (var i = 0; i < ranges.length; i++) {
                    if (n >= ranges[i].divider) {
                      return (
                        (n / ranges[i].divider).toString() + ranges[i].suffix
                      );
                    }
                  }
                  return n;
                }
                return "$" + formatNumber(value);
              }
            }
          }
        ]
      },
      tooltips: {
        enabled: true,
        mode: "single",
        callbacks: {
          label: function(tooltipItems, data) {
            function formatPrice(value) {
              let val = (value / 1).toFixed(2);
              return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
            return "$" + formatPrice(tooltipItems.yLabel);
          }
        }
      }
    }
  }),
  mounted() {
    this.setData(this.chartData);
  },
  methods: {
    setData(newData) {
      this.data.labels = [];
      this.data.datasets = [
        {
          label: ``,
          backgroundColor: [],
          data: []
        }
      ];

      if (newData && newData.length > 0) {
        var i = 0;
        newData.forEach(column => {
          this.data.datasets[0].data[i++] = column.value;
          this.data.labels.push(column.name);
          this.data.datasets[0].backgroundColor.push(column.color);
        });
      }

      this.renderChart(this.data, this.options);
    }
  }
};
</script>