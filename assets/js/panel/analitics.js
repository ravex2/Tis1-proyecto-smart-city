

// codigo temporal para poder visualizar los graficos.
const interCtx = document.getElementById('interaccionChart').getContext('2d');
const gradient = interCtx.createLinearGradient(0, 0, 0, 300);
gradient.addColorStop(0, 'rgba(61, 113, 255, 0.2)');
gradient.addColorStop(1, 'rgba(61, 113, 255, 0)');

new Chart(interCtx, {
    type: 'line',
    data: {
        labels: ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4'],
        datasets: [{
            label: 'Reportes e Interacciones',
            data: [2100, 3400, 2800, 4500],
            borderColor: '#3d71ff',
            borderWidth: 3,
            fill: true,
            backgroundColor: gradient,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: '#fff',
            pointBorderColor: '#3d71ff'
        }]
    },
    options: {
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { border: { display: false }, grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8', font: { size: 10 } } },
            x: { border: { display: false }, grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 10 } } }
        }
    }
});

// Gráfico de Barras 
const sectorCtx = document.getElementById('sectorChart').getContext('2d');
new Chart(sectorCtx, {
    type: 'bar',
    data: {
        labels: ['Centro', 'Norte', 'Oriente', 'Sur', 'Rural'],
        datasets: [{
            data: [85, 42, 63, 31, 15],
            backgroundColor: (context) => {
                return context.dataIndex === 0 ? '#3d71ff' : '#e2e8f0';
            },
            borderRadius: 8,
            barThickness: 20
        }]
    },
    options: {
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { display: false },
            x: { grid: { display: false }, border: { display: false } }
        }
    }
});