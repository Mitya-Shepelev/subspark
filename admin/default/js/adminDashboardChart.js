(function ($) {
  "use strict";

  $(document).ready(function () {
    const chartDataEl = document.getElementById("chartData");
    if (!chartDataEl || typeof Chart === "undefined") return;

    const chartData = JSON.parse(chartDataEl.textContent);
    const ctx = document.getElementById("myChart");
    if (!ctx) return;

    const context = ctx.getContext("2d");

    const gradient = context.createLinearGradient(0, 0, 0, ctx.height || 300);
    gradient.addColorStop(0, "rgba(94, 53, 177, 0.5)");
    gradient.addColorStop(0.5, "rgba(94, 53, 177, 0.25)");
    gradient.addColorStop(1, "rgba(94, 53, 177, 0)");

    const gradientTwo = context.createLinearGradient(0, 0, 0, ctx.height || 300);
    gradientTwo.addColorStop(0, "rgba(246, 81, 105, 0.5)");
    gradientTwo.addColorStop(0.5, "rgba(246, 81, 105, 0.25)");
    gradientTwo.addColorStop(1, "rgba(246, 81, 105, 0)");

    new Chart(context, {
      type: "line",
      data: {
        labels: chartData.labels,
        datasets: [
          {
            label: chartData.labelSub,
            backgroundColor: gradient,
            borderColor: "rgba(94, 53, 177, 1)",
            pointBackgroundColor: "rgba(94, 53, 177, 1)",
            fill: true,
            data: chartData.subscription,
            tension: 0.3
          },
          {
            label: chartData.labelPoint,
            backgroundColor: gradientTwo,
            borderColor: "rgba(246, 81, 105, 1)",
            pointBackgroundColor: "rgba(246, 81, 105, 1)",
            fill: true,
            data: chartData.pointEarnings,
            tension: 0.3
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          tooltip: {
            mode: "index",
            intersect: false
          },
          legend: {
            labels: {
              color: "#444"
            }
          }
        },
        interaction: {
          mode: "nearest",
          axis: "x",
          intersect: false
        },
        scales: {
          x: {
            ticks: {
              color: "#666"
            },
            grid: {
              display: false
            }
          },
          y: {
            beginAtZero: true,
            ticks: {
              color: "#666",
              callback: function (value) {
                return chartData.currency + value;
              }
            },
            grid: {
              borderDash: [5, 5]
            }
          }
        }
      }
    });
  });
})(jQuery);