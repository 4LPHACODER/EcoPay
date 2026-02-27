import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    const payload = window.ecoAnalytics || {};
    if (!payload) return;

    // Bar chart
    const barCtx = document.getElementById('barChart');
    if (barCtx && payload.barData) {
        new Chart(barCtx.getContext('2d'), {
            type: 'bar',
            data: payload.barData,
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }

    // Doughnut
    const doughnutCtx = document.getElementById('doughnutChart');
    if (doughnutCtx && payload.doughnutData) {
        new Chart(doughnutCtx.getContext('2d'), {
            type: 'doughnut',
            data: payload.doughnutData,
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    }

    // Line
    const lineCtx = document.getElementById('coinsLine');
    if (lineCtx && payload.lineData) {
        new Chart(lineCtx.getContext('2d'), {
            type: 'line',
            data: payload.lineData,
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    x: { display: true },
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }
});