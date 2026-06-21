<?php
session_start();
include "db.php";
include "includes/functions.php";
require_admin_login();

/* ---- Quick counters for the stat cards ---- */
$totalStudents = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'];
$pendingMaintenance = $conn->query("SELECT COUNT(*) AS c FROM maintenance WHERE status = 'Pending'")->fetch_assoc()['c'];
$pendingGatepass = $conn->query("SELECT COUNT(*) AS c FROM gatepass_leave WHERE status = 'Pending'")->fetch_assoc()['c'];
$pendingRefunds = $conn->query("
    SELECT COUNT(*) AS c FROM refund_requests
    WHERE 'Pending' IN (director_status, rector_status, librarian_status, accountant_status)
")->fetch_assoc()['c'];

/* ---- Maintenance requests by category (bar chart) ---- */
$categoryResult = $conn->query("
    SELECT category, COUNT(*) AS total
    FROM maintenance
    GROUP BY category
    ORDER BY total DESC
");
$categoryLabels = [];
$categoryCounts = [];
while ($row = $categoryResult->fetch_assoc()) {
    $categoryLabels[] = $row['category'];
    $categoryCounts[] = (int)$row['total'];
}

/* ---- Maintenance status breakdown (donut chart) ---- */
$statusResult = $conn->query("SELECT status, COUNT(*) AS total FROM maintenance GROUP BY status");
$statusLabels = [];
$statusCounts = [];
while ($row = $statusResult->fetch_assoc()) {
    $statusLabels[] = $row['status'];
    $statusCounts[] = (int)$row['total'];
}

/* ---- Gate pass vs leave (donut chart) ---- */
$typeResult = $conn->query("SELECT type, COUNT(*) AS total FROM gatepass_leave GROUP BY type");
$typeLabels = [];
$typeCounts = [];
while ($row = $typeResult->fetch_assoc()) {
    $typeLabels[] = $row['type'];
    $typeCounts[] = (int)$row['total'];
}

/* ---- Requests over the last 14 days (line chart) ---- */
$trendLabels = [];
$trendCounts = [];
for ($i = 13; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $trendLabels[] = date('d M', strtotime($date));

    $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM maintenance WHERE DATE(created_at) = ?");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $trendCounts[] = (int)$stmt->get_result()->fetch_assoc()['c'];
}

$activePage = 'admin_dashboard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="css/style.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<style>
    .chart-grid {
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }
    .chart-grid-3 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    .chart-card {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 18px 20px;
        box-shadow: var(--shadow);
    }
    .chart-card h3 { margin: 0 0 14px; font-size: 15px; color: var(--navy-800); }
    @media (max-width: 900px) {
        .chart-grid, .chart-grid-3 { grid-template-columns: 1fr; }
    }
</style>
</head>
<body>

<div class="app-shell">
    <?php include "admin_sidebar.php"; ?>

    <div class="main">
        <h2>Dashboard Overview</h2>
        <p class="page-subtitle">A snapshot of everything happening across the hostel right now.</p>

        <div class="cards">
            <div class="stat-card">
                <div class="stat-number"><?= (int)$totalStudents ?></div>
                <div class="stat-label">Total Students</div>
            </div>
            <div class="stat-card accent-amber">
                <div class="stat-number"><?= (int)$pendingMaintenance ?></div>
                <div class="stat-label">Pending Maintenance</div>
            </div>
            <div class="stat-card accent-amber">
                <div class="stat-number"><?= (int)$pendingGatepass ?></div>
                <div class="stat-label">Pending Gate Pass / Leave</div>
            </div>
            <div class="stat-card accent-red">
                <div class="stat-number"><?= (int)$pendingRefunds ?></div>
                <div class="stat-label">Refunds Awaiting Approval</div>
            </div>
        </div>

        <div class="chart-grid">
            <div class="chart-card">
                <h3>Maintenance Requests — Last 14 Days</h3>
                <canvas id="trendChart" height="90"></canvas>
            </div>
            <div class="chart-card">
                <h3>Maintenance Status</h3>
                <canvas id="statusChart" height="90"></canvas>
            </div>
        </div>

        <div class="chart-grid-3">
            <div class="chart-card">
                <h3>Maintenance by Category</h3>
                <canvas id="categoryChart" height="100"></canvas>
            </div>
            <div class="chart-card">
                <h3>Gate Pass vs Leave</h3>
                <canvas id="typeChart" height="100"></canvas>
            </div>
        </div>

        <h3 style="margin-top:28px;">Quick Links</h3>
        <div class="cards">
            <div class="card">
                <h3>Maintenance</h3>
                <p>View & resolve student maintenance issues.</p>
                <a href="admin_maintenance.php" class="btn btn-primary">View</a>
            </div>
            <div class="card">
                <h3>Gate Pass & Leave</h3>
                <p>Approve or reject gate pass & leave requests.</p>
                <a href="admin_gatepass_leave.php" class="btn btn-primary">View</a>
            </div>
            <div class="card">
                <h3>Refunds</h3>
                <p>Handle hostel fee refund requests.</p>
                <a href="admin_refund.php" class="btn btn-primary">View</a>
            </div>
            <div class="card">
                <h3>Change Info</h3>
                <p>Approve student information change requests.</p>
                <a href="admin_changeInformation.php" class="btn btn-primary">View</a>
            </div>
            <div class="card">
                <h3>I-Card</h3>
                <p>View & approve student I-Card requests.</p>
                <a href="admin_icard.php" class="btn btn-primary">View</a>
            </div>
            <div class="card">
                <h3>Hostel Fees</h3>
                <p>Add fee records and manage payment status.</p>
                <a href="admin_fees.php" class="btn btn-primary">View</a>
            </div>
            <div class="card">
                <h3>Students</h3>
                <p>Add new students or remove existing student accounts.</p>
                <a href="admin_add_student.php" class="btn btn-primary">View</a>
            </div>
        </div>
    </div>
</div>

<script>
const navy = '#16243b';
const amber = '#d97a34';
const green = '#2f9e63';
const red = '#d6483f';
const muted = '#94a3b8';

Chart.defaults.font.family = "-apple-system, 'Segoe UI', Roboto, sans-serif";
Chart.defaults.color = '#64748b';

new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($trendLabels) ?>,
        datasets: [{
            label: 'New requests',
            data: <?= json_encode($trendCounts) ?>,
            borderColor: amber,
            backgroundColor: 'rgba(217,122,52,0.12)',
            fill: true,
            tension: 0.3,
            pointRadius: 3
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});

new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($statusLabels) ?>,
        datasets: [{
            data: <?= json_encode($statusCounts) ?>,
            backgroundColor: [amber, green, red, navy],
            borderWidth: 0
        }]
    },
    options: { plugins: { legend: { position: 'bottom' } } }
});

new Chart(document.getElementById('categoryChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($categoryLabels) ?>,
        datasets: [{
            label: 'Requests',
            data: <?= json_encode($categoryCounts) ?>,
            backgroundColor: navy,
            borderRadius: 4
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});

new Chart(document.getElementById('typeChart'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($typeLabels) ?>,
        datasets: [{
            data: <?= json_encode($typeCounts) ?>,
            backgroundColor: [amber, navy],
            borderWidth: 0
        }]
    },
    options: { plugins: { legend: { position: 'bottom' } } }
});
</script>

</body>
</html>
