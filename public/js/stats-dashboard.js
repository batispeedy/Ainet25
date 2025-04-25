// public/js/stats-dashboard.js


(function() {
  
    const chartScript = document.createElement('script');
    chartScript.src = 'https://cdn.jsdelivr.net/npm/chart.js';
    chartScript.onload = initCharts;
    document.head.appendChild(chartScript);
  
 
    function initCharts() {
      
      const dataEl = document.getElementById('stats-data');
      if (!dataEl) {
        console.error('stats-dashboard.js: elemento #stats-data não encontrado.');
        return;
      }
      let statsData;
      try {
        statsData = JSON.parse(dataEl.textContent);
      } catch (err) {
        console.error('stats-dashboard.js: erro a parsear JSON:', err);
        return;
      }
  
      const { salesByMonth, salesByCategory, salesByProduct, salesByMember } = statsData;
  
      createBarChart('salesByMonthChart',   Object.keys(salesByMonth),   Object.values(salesByMonth),   'Total (€)');
      createBarChart('salesByCategoryChart',Object.keys(salesByCategory),Object.values(salesByCategory),'Total (€)');
      createBarChart('salesByProductChart', Object.keys(salesByProduct), Object.values(salesByProduct), 'Total (€)');
      createBarChart('salesByMemberChart',  Object.keys(salesByMember),  Object.values(salesByMember),  'Total (€)');
    }
  
    /**
     * Cria um gráfico de barras Chart.js no canvas dado
     * @param {string} ctxId
     * @param {Array<string>} labels
     * @param {Array<number>} data
     * @param {string} label
     */
    function createBarChart(ctxId, labels, data, label) {
      const canvas = document.getElementById(ctxId);
      if (!canvas) return;
      const ctx = canvas.getContext('2d');
      new Chart(ctx, {
        type: 'bar',
        data: { labels, datasets: [{ label, data, borderWidth: 1 }] },
        options: { scales: { y: { beginAtZero: true } }, responsive: true, maintainAspectRatio: false }
      });
    }
  })();
  