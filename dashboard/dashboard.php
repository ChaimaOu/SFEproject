<?php
// Start session
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is properly authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true || !isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    // Not authenticated, redirect to login page
    header("Location: ../index.php");
    exit();
}

// Get user information
$email = $_SESSION['email'];
$user_id = $_SESSION['user_id'];

// Sample data for dashboard metrics
// In a real application, you would fetch this from your database
$metrics = [
    'etudiants' => [
        'count' => 1254,
        'change' => 12,
        'positive' => true,
        'icon' => 'fa-user-graduate'
    ],
    'courses' => [
        'count' => 87,
        'change' => 5,
        'positive' => true,
        'icon' => 'fa-book'
    ],
    'partenaires' => [
        'count' => 42,
        'change' => 8,
        'positive' => true,
        'icon' => 'fa-handshake'
    ],
    'contrats' => [
        'count' => 156,
        'change' => 15,
        'positive' => true,
        'icon' => 'fa-file-contract'
    ],
    'factures' => [
        'count' => 328,
        'change' => -3,
        'positive' => false,
        'icon' => 'fa-file-invoice-dollar'
    ]
];

// Sample data for charts
$monthlyStudents = [120, 150, 180, 210, 250, 300, 350, 400, 450, 500, 550, 600];
$monthlyRevenue = [15000, 18000, 22000, 25000, 30000, 35000, 40000, 42000, 45000, 48000, 52000, 58000];
$courseDistribution = [
    'Technology' => 35,
    'Business' => 25,
    'Science' => 15,
    'Arts' => 10,
    'Languages' => 15
];
$partnerTypes = [
    'Corporate' => 45,
    'Academic' => 30,
    'Government' => 15,
    'Non-profit' => 10
];

// Recent activities
$recentActivities = [
    [
        'type' => 'student',
        'text' => 'New student registered: Marie Dupont',
        'time' => '2 minutes ago',
        'icon' => 'fa-user-plus'
    ],
    [
        'type' => 'course',
        'text' => 'New course added: Advanced Web Development',
        'time' => '1 hour ago',
        'icon' => 'fa-book'
    ],
    [
        'type' => 'contract',
        'text' => 'Contract #1234 signed with Acme Corp',
        'time' => '3 hours ago',
        'icon' => 'fa-file-contract'
    ],
    [
        'type' => 'invoice',
        'text' => 'Invoice #5678 paid by TechStart Inc.',
        'time' => 'Yesterday',
        'icon' => 'fa-file-invoice-dollar'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YOOL - Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Add ApexCharts for professional diagrams -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../yoolLogo.png" alt="YOOL Logo" class="logo">
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li class="active">
                        <a href="./dashboard.php"><i class="fas fa-home"></i> <span>Dashboard</span></a>
                    </li>
                    <li>
                        <a href="./etudiants.php"><i class="fas fa-user-graduate"></i> <span>Étudiants</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-book"></i> <span>Courses</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-handshake"></i> <span>Partenaires</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-file-contract"></i> <span>Contrats</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-file-invoice-dollar"></i> <span>Factures</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-chart-line"></i> <span>Analytics</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-cog"></i> <span>Settings</span></a>
                    </li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <a href="../logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <div class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>
                <div class="header-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search...">
                </div>
                <div class="header-user">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </div>
                    <div class="user-info">
                        <span class="user-name"><?php echo htmlspecialchars($email); ?></span>
                        <div class="user-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="dashboard-header">
                    <div>
                        <h1>Dashboard</h1>
                        <p class="subtitle">Welcome back, <?php echo explode('@', htmlspecialchars($email))[0]; ?></p>
                    </div>
                    <div class="date-selector">
                        <button class="date-btn active">Today</button>
                        <button class="date-btn">Week</button>
                        <button class="date-btn">Month</button>
                        <button class="date-btn">Year</button>
                    </div>
                </div>

                <!-- Stats Overview -->
                <div class="stats-container">
                    <?php foreach ($metrics as $key => $metric): ?>
                    <div class="stat-card">
                        <div class="stat-header">
                            <h3><?php echo ucfirst($key); ?></h3>
                            <div class="stat-icon <?php echo $key === 'etudiants' ? 'orange' : 
                                                    ($key === 'courses' ? 'blue' : 
                                                    ($key === 'partenaires' ? 'purple' : 
                                                    ($key === 'contrats' ? 'green' : 'red'))); ?>">
                                <i class="fas <?php echo $metric['icon']; ?>"></i>
                            </div>
                        </div>
                        <div class="stat-content">
                            <p class="stat-number"><?php echo number_format($metric['count']); ?></p>
                            <div class="stat-change <?php echo $metric['positive'] ? 'positive' : 'negative'; ?>">
                                <i class="fas <?php echo $metric['positive'] ? 'fa-arrow-up' : 'fa-arrow-down'; ?>"></i>
                                <span><?php echo abs($metric['change']); ?>%</span>
                            </div>
                        </div>
                        <div class="stat-footer">
                            <span>vs. previous period</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Main Charts Section -->
                <div class="main-charts">
                    <div class="chart-card large">
                        <div class="chart-header">
                            <h3>Student Growth & Revenue</h3>
                            <div class="chart-actions">
                                <button class="chart-action-btn active" data-chart="students">Students</button>
                                <button class="chart-action-btn" data-chart="revenue">Revenue</button>
                            </div>
                        </div>
                        <div class="chart-body">
                            <div id="mainChart"></div>
                        </div>
                    </div>
                </div>

                <!-- Secondary Charts Section -->
                <div class="secondary-charts">
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3>Course Distribution</h3>
                        </div>
                        <div class="chart-body">
                            <div id="courseDistributionChart"></div>
                        </div>
                    </div>
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3>Partner Types</h3>
                        </div>
                        <div class="chart-body">
                            <div id="partnerTypesChart"></div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity & Quick Actions -->
                <div class="bottom-section">
                    <!-- Recent Activity -->
                    <div class="card recent-activity">
                        <div class="card-header">
                            <h3>Recent Activity</h3>
                            <a href="#" class="view-all">View All</a>
                        </div>
                        <div class="card-body">
                            <div class="activity-list">
                                <?php foreach ($recentActivities as $activity): ?>
                                <div class="activity-item">
                                    <div class="activity-icon <?php echo $activity['type']; ?>">
                                        <i class="fas <?php echo $activity['icon']; ?>"></i>
                                    </div>
                                    <div class="activity-details">
                                        <p class="activity-text"><?php echo $activity['text']; ?></p>
                                        <p class="activity-time"><?php echo $activity['time']; ?></p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card quick-actions">
                        <div class="card-header">
                            <h3>Quick Actions</h3>
                        </div>
                        <div class="card-body">
                            <div class="actions-container">
                                <a href="#" class="action-card">
                                    <i class="fas fa-user-plus"></i>
                                    <span>Add Student</span>
                                </a>
                                <a href="#" class="action-card">
                                    <i class="fas fa-book-medical"></i>
                                    <span>New Course</span>
                                </a>
                                <a href="#" class="action-card">
                                    <i class="fas fa-handshake"></i>
                                    <span>Add Partner</span>
                                </a>
                                <a href="#" class="action-card">
                                    <i class="fas fa-file-contract"></i>
                                    <span>New Contract</span>
                                </a>
                                <a href="#" class="action-card">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                    <span>Create Invoice</span>
                                </a>
                                <a href="#" class="action-card">
                                    <i class="fas fa-download"></i>
                                    <span>Export Reports</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="dashboard.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Main Chart - Student Growth
            const mainChartOptions = {
                series: [{
                    name: 'Students',
                    data: <?php echo json_encode($monthlyStudents); ?>
                }],
                chart: {
                    height: 350,
                    type: 'area',
                    fontFamily: 'Inter, sans-serif',
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                colors: ['#4361ee'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.2,
                        stops: [0, 90, 100]
                    }
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    labels: {
                        style: {
                            colors: '#8e8da4',
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#8e8da4',
                            fontSize: '12px'
                        },
                        formatter: function(val) {
                            return val.toFixed(0);
                        }
                    }
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function(val) {
                            return val + " students";
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f1f1',
                    strokeDashArray: 4,
                    xaxis: {
                        lines: {
                            show: true
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                markers: {
                    size: 5,
                    colors: ['#4361ee'],
                    strokeColors: '#fff',
                    strokeWidth: 2,
                    hover: {
                        size: 7
                    }
                }
            };

            const mainChart = new ApexCharts(document.querySelector("#mainChart"), mainChartOptions);
            mainChart.render();

            // Toggle between Students and Revenue charts
            const chartActionBtns = document.querySelectorAll('.chart-action-btn');
            chartActionBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    chartActionBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    if (this.dataset.chart === 'students') {
                        mainChart.updateSeries([{
                            name: 'Students',
                            data: <?php echo json_encode($monthlyStudents); ?>
                        }]);
                        mainChart.updateOptions({
                            colors: ['#4361ee'],
                            tooltip: {
                                y: {
                                    formatter: function(val) {
                                        return val + " students";
                                    }
                                }
                            }
                        });
                    } else {
                        mainChart.updateSeries([{
                            name: 'Revenue',
                            data: <?php echo json_encode($monthlyRevenue); ?>
                        }]);
                        mainChart.updateOptions({
                            colors: ['#2cb67d'],
                            tooltip: {
                                y: {
                                    formatter: function(val) {
                                        return '€' + val.toLocaleString();
                                    }
                                }
                            }
                        });
                    }
                });
            });

            // Course Distribution Chart
            const courseDistributionOptions = {
                series: <?php echo json_encode(array_values($courseDistribution)); ?>,
                chart: {
                    type: 'donut',
                    height: 300,
                    fontFamily: 'Inter, sans-serif'
                },
                labels: <?php echo json_encode(array_keys($courseDistribution)); ?>,
                colors: ['#4361ee', '#3f37c9', '#4895ef', '#4cc9f0', '#560bad'],
                legend: {
                    position: 'bottom',
                    fontSize: '14px'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '22px',
                                    fontWeight: 600
                                },
                                value: {
                                    show: true,
                                    fontSize: '16px',
                                    formatter: function(val) {
                                        return val + '%';
                                    }
                                },
                                total: {
                                    show: true,
                                    label: 'Total',
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0) + '%';
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 250
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            const courseDistributionChart = new ApexCharts(document.querySelector("#courseDistributionChart"), courseDistributionOptions);
            courseDistributionChart.render();

            // Partner Types Chart
            const partnerTypesOptions = {
                series: <?php echo json_encode(array_values($partnerTypes)); ?>,
                chart: {
                    type: 'polarArea',
                    height: 300,
                    fontFamily: 'Inter, sans-serif'
                },
                labels: <?php echo json_encode(array_keys($partnerTypes)); ?>,
                colors: ['#f72585', '#7209b7', '#3a0ca3', '#4361ee'],
                stroke: {
                    colors: ['#fff']
                },
                fill: {
                    opacity: 0.8
                },
                legend: {
                    position: 'bottom',
                    fontSize: '14px'
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 250
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            const partnerTypesChart = new ApexCharts(document.querySelector("#partnerTypesChart"), partnerTypesOptions);
            partnerTypesChart.render();

            // Date selector buttons
            const dateBtns = document.querySelectorAll('.date-btn');
            dateBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    dateBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
        
    </script>
</body>
</html>
