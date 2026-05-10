<?php
require_once(__DIR__ . "/../../database/config.php");
include "../../includes/header.php";

// Ensure admin access
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: /pages/login.php");
    exit();
}

// Fetch Activity Logs
$sql = "SELECT al.log_id, al.user_id, u.full_name, al.action, al.details, al.created_at 
        FROM activity_logs al
        JOIN users u ON al.user_id = u.user_id 
        ORDER BY al.created_at DESC";
$result = $conn->query($sql);

// Fetch Stats
$totalLogs = $conn->query("SELECT COUNT(*) AS total FROM activity_logs")->fetch_assoc()['total'];
$topUsers = $conn->query("SELECT u.full_name, COUNT(al.user_id) AS total 
                          FROM activity_logs al 
                          JOIN users u ON al.user_id = u.user_id 
                          GROUP BY al.user_id 
                          ORDER BY total DESC 
                          LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Reports</title>

    <link rel="stylesheet" href="/assets/css/includes.css">
    <link rel="stylesheet" href="/assets/css/table.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <?php include "../../includes/sidebar.php"; ?>
        </div>

        <div class="main-content">
            <div class="container mt-4">
                <div class="table-responsive">
                    <div class="table-wrapper">
                        <div class="table-title d-flex justify-content-between align-items-center">
                            <div>
                                <h1 style="color: #a83232;"><b>Activity Reports</b></h1>
                                <p>Monitor user and admin actions.</p>
                            </div>

                            <!-- Total Logs -->
                            <div class="col-md-3">
                                <div class="info-card shadow-sm border rounded-3 p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="text-muted mb-1">Total Logs</h6>
                                        <div class="icon-circle bg-primary text-white">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                    </div>
                                    <h2 class="fw-bold mb-1"><?= $totalLogs ?></h2>
                                    <span class="text-success small">
                                        <i class="fas fa-arrow-up"></i> 5% increase this month
                                    </span>
                                </div>
                            </div>

                        </div>

                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <!-- Bar Chart -->
                            <div class="wrapper-card col-md-6 col-lg-5">
                                <div class="canvas-container p-3 bg-white rounded shadow-sm" style="height:350px">
                                    <canvas id="logBarChart"></canvas>
                                </div>
                            </div>

                            <!-- Pie Chart -->
                            <div class="wrapper-card col-md-6 col-lg-5">
                                <div class="canvas-container p-3 bg-white rounded shadow-sm" style="height:350px; align-items: center; ">
                                    <canvas id="logPieChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class=" wrapper-card pagination-container  justify-content-between">
                            <div class=" d-flex gap-3 ">
                                <label for="rowsPerPageSelect" class="mt-2 ">Show:</label>
                                <select id="rowsPerPageSelect" class="form-select w-auto">
                                    <option value="5">5 entries</option>
                                    <option value="10" selected>10 entries</option>
                                    <option value="15">15 entries</option>
                                    <option value="20">20 entries</option>
                                </select>
                            </div>
                            <div class=" d-flex justify-content-between gap-3 my-3">
                                <input type="text" id="searchInput" class="form-control w-auto" placeholder="Search logs...">
                                <input type="date" id="filterDate" class="form-control w-auto">
                                <button class="btn btn-primary d-flex gap-2" id="downloadCSV"> <i class="fas fa-download mt-1"></i>Export CSV</button>
                            </div>
                        </div>
                        <!-- Activity Logs Table -->
                        <div class="wrapper-card mt-2">
                            <div class="table-container">
                                <table class="table table-striped table-hover" id="activitylogTable" style="max-height: 500px;">
                                    <thead>
                                        <colgroup>
                                            <col style="width: 10%;">
                                            <col style="width: 10%;">
                                            <col style="width: 15%;">
                                            <col style="width: 45%;">
                                            <col style="width: 20%;">


                                        </colgroup>
                                        <tr>
                                            <th>Log ID</th>
                                            <th>User</th>
                                            <th>Action</th>
                                            <th>Details</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= $row['log_id'] ?></td>
                                                <td><?= htmlspecialchars($row['full_name']) ?></td>
                                                <td><?= htmlspecialchars($row['action']) ?></td>
                                                <td><?= htmlspecialchars_decode($row['details']) ?></td>
                                                <td><?= $row['created_at'] ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="text-muted">Showing <b id="currentEntries">0</b> out of <b id="totalEntries">0</b> entries</span>
                                    <nav>
                                        <ul class="pagination pagination-sm mb-0" id="paginationControls"></ul>
                                    </nav>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- JavaScript for Charts & Filters -->
    <script>
        $(document).ready(function() {
            let rowsPerPage = parseInt($("#rowsPerPageSelect").val());
            let currentPage = 1;
            let rows = $("#activitylogTable tbody tr");
            let filteredRows = rows; // Holds search results

            function showPage(page, rowsPerPage) {
                rows.hide(); // Hide all rows
                filteredRows.slice((page - 1) * rowsPerPage, page * rowsPerPage).show(); // Show only the selected page rows
                $(".pagination button").removeClass("active");
                $(`.pagination button[data-page=${page}]`).addClass("active");
            }

            function generatePagination(rowsPerPage) {
                let totalRows = filteredRows.length;
                let totalPages = Math.ceil(totalRows / rowsPerPage);
                $("#paginationControls").html("");

                if (totalPages <= 1) return;

                for (let i = 1; i <= totalPages; i++) {
                    $("#paginationControls").append(
                        `<button class="${i === 1 ? 'active' : ''}" data-page="${i}">${i}</button>`
                    );
                }
            }

            // Initial Setup
            generatePagination(rowsPerPage);
            showPage(currentPage, rowsPerPage);

            // Change Page on Button Click
            $(document).on("click", ".pagination button", function() {
                currentPage = $(this).data("page");
                showPage(currentPage, rowsPerPage);
            });

            // Change Rows Per Page
            $("#rowsPerPageSelect").change(function() {
                rowsPerPage = parseInt($(this).val());
                currentPage = 1; // Reset to first page
                filteredRows = rows; // Reset filtered data when changing per page count
                generatePagination(rowsPerPage);
                showPage(currentPage, rowsPerPage);
            });

            // Live Search Filter with Fixed Rows per Page
            $("#searchInput").on("keyup", function() {
                let value = $(this).val().toLowerCase();

                if (value === "") {
                    filteredRows = rows; // Reset filtering when search bar is empty
                    $(".pagination").show();
                    generatePagination(rowsPerPage);
                    showPage(currentPage, rowsPerPage);
                } else {
                    filteredRows = rows.filter(function() {
                        return $(this).text().toLowerCase().indexOf(value) > -1;
                    });

                    // If the search results exceed rowsPerPage, keep the first page limited
                    if (filteredRows.length > rowsPerPage) {
                        filteredRows = filteredRows.slice(0, rowsPerPage);
                    }

                    rows.hide();
                    filteredRows.show();
                    $(".pagination").hide(); // Hide pagination during search
                }
            });

            function updateEntryCount() {
                let totalEntries = 0;
                let visibleEntries = 0;

                $("#activitylogTable tbody tr").each(function() {
                    let isVisible = $(this).is(":visible");
                    let hasContent = $(this).find("td").text().trim().length > 0;

                    if (hasContent) {
                        totalEntries++; // Count only non-empty rows
                        if (isVisible) {
                            visibleEntries++; // Count only visible rows
                        }
                    }
                });

                $("#currentEntries").text(visibleEntries);
                $("#totalEntries").text(totalEntries);
            }

            // Call this function whenever the table updates
            updateEntryCount();


            // Call function on page load
            $(document).ready(function() {
                updateEntryCount();
            });

            function showToast(message, type = "success") {
                let toastClass = (type === "success") ? "bg-success text-white" : "bg-danger text-white";
                let toastHTML = `
                    <div class="toast align-items-center ${toastClass} position-fixed bottom-0 end-0 p-2 m-3" role="alert" aria-live="polite" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                ${message}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                    `;

                $(".toast").remove(); // Prevent stacking multiple toasts
                $("body").append(toastHTML);
                let toast = new bootstrap.Toast($(".toast").last()[0]);
                toast.show();

                setTimeout(() => {
                    $(".toast").fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 4000);
            }

            let logsData = [];
            $("#activitylogTable tbody tr:visible").each(function() {
                let actionText = $(this).find("td:nth-child(3)").text().trim();
                if (actionText) {
                    logsData.push({
                        user: $(this).find("td:nth-child(2)").text(),
                        action: actionText,
                        date: $(this).find("td:nth-child(5)").text()
                    });
                }
            });


            // Hide charts if no data
            if (logsData.length === 0) {
                $("#logBarChart").parent().hide();
                $("#logPieChart").parent().hide();
                $("#noLogsMessage").show();
                return;
            } else {
                $("#logBarChart").parent().show();
                $("#logPieChart").parent().show();
                $("#noLogsMessage").hide();
            }

            // Search Filter
            $("#searchInput").on("keyup", function() {
                let value = $(this).val().toLowerCase();
                $("table tbody tr").each(function() {
                    let match = $(this).text().toLowerCase().indexOf(value) > -1;
                    $(this).toggle(match);
                });
            });

            // Date Filter
            $("#filterDate").on("change", function() {
                let filterDate = $(this).val();
                $("table tbody tr").each(function() {
                    let match = $(this).find("td:nth-child(5)").text().includes(filterDate);
                    $(this).toggle(match);
                });
            });

            // Export CSV
            $("#downloadCSV").on("click", function() {
                let csv = "Log ID,User,Action,Details,Date\n";
                $("table tbody tr:visible").each(function() {
                    let row = $(this).find("td").map(function() {
                        return $(this).text().replace(/,/g, ""); // Remove commas to avoid CSV format issues
                    }).get().join(",");
                    csv += row + "\n";
                });

                let blob = new Blob([csv], {
                    type: "text/csv"
                });
                let link = document.createElement("a");
                link.href = URL.createObjectURL(blob);
                link.download = "activity_logs.csv";
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });

            // Generate Charts
            let actions = logsData.map(log => log.action);
            let actionCounts = {};

            // Count occurrences of each action
            actions.forEach(action => {
                actionCounts[action] = (actionCounts[action] || 0) + 1;
            });

            let colors = ['#7A0019', '#F4B000', '#4B0010', '#FFC72C', '#A61B34', '#8F6B14', '#6E6257', '#A62828', '#5E0B1A', '#D89A00', '#8C2136'];

            if (Object.keys(actionCounts).length > 0) {
                let actionLabels = Object.keys(actionCounts);
                let actionData = Object.values(actionCounts);
                let colorSet = colors.slice(0, actionLabels.length); // Limit colors to action count

                // Bar Chart
                new Chart(document.getElementById("logBarChart"), {
                    type: 'bar',
                    data: {
                        labels: actionLabels,
                        datasets: [{
                            label: 'Actions Count',
                            data: actionData,
                            backgroundColor: colorSet,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });

                // Pie Chart
                new Chart(document.getElementById("logPieChart"), {
                    type: 'pie',
                    data: {
                        labels: actionLabels,
                        datasets: [{
                            label: 'Actions',
                            data: actionData,
                            backgroundColor: colorSet
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            } else {
                $("#logBarChart").parent().hide();
                $("#logPieChart").parent().hide();
                $("#noLogsMessage").show();
            }

        });
    </script>
</body>

</html>
