(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    if (typeof Chart === "undefined") return;
    const canvas = document.getElementById("myChart");
    if (!canvas) return;
 
    const chartDataScript = document.getElementById("chartData");
    if (!chartDataScript) return;

    let chartData;
    try {
      chartData = JSON.parse(chartDataScript.textContent);
    } catch (e) { 
      return;
    }

    const ctx = canvas.getContext("2d");

    const chart = new Chart(ctx, {
      type: "line",
      data: {
        labels: chartData.labels || [],
        datasets: [
          {
            label: chartData.labelSub || "Subscriptions",
            backgroundColor: "rgba(250, 180, 41, 0.1)",
            borderColor: "rgb(250, 180, 41)",
            pointBackgroundColor: "rgb(250, 180, 41)",
            pointRadius: 4,
            pointHoverRadius: 6,
            fill: true,
            data: chartData.subscription || []
          },
          {
            label: chartData.labelPoint || "Points",
            backgroundColor: "rgba(255, 99, 132, 0.1)",
            borderColor: "rgb(255, 99, 132)",
            pointBackgroundColor: "rgb(255, 99, 132)",
            pointRadius: 4,
            pointHoverRadius: 6,
            fill: true,
            data: chartData.pointEarnings || []
          },
          {
            label: chartData.labelProduct || "Products",
            backgroundColor: "rgba(93, 81, 246, 0.1)",
            borderColor: "rgb(93, 81, 246)",
            pointBackgroundColor: "rgb(93, 81, 246)",
            pointRadius: 4,
            pointHoverRadius: 6,
            fill: true,
            data: chartData.productEarnings || []
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        tooltips: {
          mode: "index",
          intersect: false
        },
        hover: {
          mode: "nearest",
          intersect: true
        },
        layout: {
          padding: {
            top: 20,
            left: 15,
            right: 15,
            bottom: 20
          }
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true,
              callback: function (value) {
                return (chartData.currency || "") + value;
              }
            }
          }]
        }
      }
    });

    // Resize observer for responsiveness
    if ('ResizeObserver' in window) {
      const resizeObserver = new ResizeObserver(() => {
        chart.resize();
      });
      resizeObserver.observe(canvas.parentElement);
    }
  });
})();