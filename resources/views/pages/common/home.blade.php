<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<!-- Main Content -->
<div class="main-content">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stats-card">
                            <div class="card-body">
                                <h6>Active Projects</h6>
                                <h2>12</h2>
                                <div class="mt-2">
                                    <small><i class="fas fa-arrow-up"></i> 8% from last month</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card">
                            <div class="card-body">
                                <h6>Total Professionals</h6>
                                <h2>48</h2>
                                <div class="mt-2">
                                    <small><i class="fas fa-arrow-up"></i> 12% from last month</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card">
                            <div class="card-body">
                                <h6>Monthly Revenue</h6>
                                <h2>$24.5K</h2>
                                <div class="mt-2">
                                    <small><i class="fas fa-arrow-up"></i> 15% from last month</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card">
                            <div class="card-body">
                                <h6>Pending Tasks</h6>
                                <h2>34</h2>
                                <div class="mt-2">
                                    <small><i class="fas fa-arrow-down"></i> 5% from last month</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="chart-container">
                            <h5>Project Progress</h5>
                            <canvas id="projectChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="chart-container">
                            <h5>Revenue Distribution</h5>
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Project Progress Chart
        const projectCtx = document.getElementById('projectChart').getContext('2d');
        new Chart(projectCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Completed Projects',
                    data: [5, 8, 12, 15, 20, 25],
                    borderColor: '#008080',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Revenue Distribution Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'doughnut',
            data: {
                labels: ['Design', 'Development', 'Marketing', 'Other'],
                datasets: [{
                    data: [30, 40, 20, 10],
                    backgroundColor: [
                        '#008080',
                        '#00a0a0',
                        '#00c0c0',
                        '#00e0e0'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>