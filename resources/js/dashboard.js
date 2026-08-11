document.addEventListener('DOMContentLoaded', () => {
    const data = window.dashboardData;
    //settlement pie
    new Chart(document.getElementById('settlementPie'), {
        type: 'pie',
        data: {
            labels: data.settlement.labels,
            datasets: [{
                data: data.settlement.data,
                backgroundColor: [
                    '#28a745',
                    '#007bff',
                    '#ffc107'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    //cash flow chart
    new Chart(document.getElementById('cashFlowChart'), {
        type: 'bar',
        data: {
            labels: data.cashFlowChart.labels,
            datasets: [{
                label: data.lang.amount,
                data: data.cashFlowChart.data,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: function (value) {
                            return new Intl.NumberFormat('en', {
                                notation: 'compact',
                                maximumFractionDigits: 1
                            }).format(value);
                        }
                    }
                }
            }
        }
    });
    // Collection Pie
    new Chart(document.getElementById('collectionPie'), {
        type: 'pie',
        options: {
            responsive: true,
            maintainAspectRatio: false
        },
        data: {
            labels: data.collection.labels,
            datasets: [{
                data: data.collection.data,
                backgroundColor: [
                    '#17a2b8',
                    '#dc3545'
                ],
                borderWidth: 1
            }]
        },
    });
    //transaction pie
    new Chart(document.getElementById('transactionPie'), {
        type: 'pie',
        data: {
            labels: data.tbt.map(i => i.type),
            datasets: [{
                data: data.tbt.map(i => i.total),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    ///
    const revenueDaily = data.revenueDaily;
    const revenueMonthly = data.revenueMonthly;
    const profitDaily = data.profitDaily;
    const profitMonthly = data.profitMonthly;
    function chartData(data) {
        return {
            labels: data.map(x => x.label),
            values: data.map(x => x.value)
        };
    }
    // Revenue trend
    let revenueData = chartData(revenueDaily);
    let revenueChart = new Chart(
        document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: revenueData.labels,
            datasets: [{
                label: data.lang.revenue,
                data: revenueData.values,
                borderWidth: 2,
                tension: .3
            }]
        }
    });
    $('#revenueFilter').change(function () {
        let data = this.value === 'daily' ?
            revenueDaily :
            revenueMonthly;
        data = chartData(data);
        revenueChart.data.labels = data.labels;
        revenueChart.data.datasets[0].data = data.values;
        revenueChart.update();
    });
    // Profit trend
    let profitData = chartData(profitDaily);
    let profitChart = new Chart(
        document.getElementById('profitChart'), {
        type: 'line',
        data: {
            labels: profitData.labels,
            datasets: [{
                label: data.lang.profit,
                data: profitData.values,
                borderWidth: 2,
                tension: .3
            }]
        }
    });
    $('#profitFilter').change(function () {
        let data = this.value === 'daily' ?
            profitDaily :
            profitMonthly;
        data = chartData(data);
        profitChart.data.labels = data.labels;
        profitChart.data.datasets[0].data = data.values;
        profitChart.update();
    });
    ////
    const ordersDaily = data.ordersDaily;
    const ordersMonthly = data.ordersMonthly;
    const companyEarningsDaily = data.companyEarningsDaily;
    const companyEarningsMonthly = data.companyEarningsMonthly;
    function prepareChart(data) {
        return {
            labels: data.map(x => x.label),
            values: data.map(x => x.value)
        };
    }
    // Orders Chart
    let ordersData = prepareChart(ordersDaily);
    let ordersChart = new Chart(
        document.getElementById('ordersChart'), {
        type: 'line',
        data: {
            labels: ordersData.labels,
            datasets: [{
                label: data.lang.orders,
                data: ordersData.values,
                borderWidth: 2,
                tension: .3
            }]
        }
    });
    $('#ordersFilter').change(function () {
        let data = this.value === 'daily' ?
            ordersDaily :
            ordersMonthly;
        data = prepareChart(data);
        ordersChart.data.labels = data.labels;
        ordersChart.data.datasets[0].data = data.values;
        ordersChart.update();
    });
    // Company Earnings Chart
    let earningsData = prepareChart(companyEarningsDaily);
    let earningsChart = new Chart(
        document.getElementById('earningsChart'), {
        type: 'line',
        data: {
            labels: earningsData.labels,
            datasets: [{
                label: data.lang.companyEarnings,
                data: earningsData.values,
                borderWidth: 2,
                tension: .3
            }]
        }
    });
    $('#earningsFilter').change(function () {
        let data = this.value === 'daily' ?
            companyEarningsDaily :
            companyEarningsMonthly;
        data = prepareChart(data);
        earningsChart.data.labels = data.labels;
        earningsChart.data.datasets[0].data = data.values;
        earningsChart.update();
    });
});