<?php
require_once(__DIR__ . "/../../database/config.php");
require_once(__DIR__ . "/../../database/functions.php");
include "../../includes/header.php";

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure the session is properly set and user is a leader
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'leader') {
    header("Location: /pages/login.php");
    exit();
}

// Validate Database Connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Get Leader ID
$leader_id = $_SESSION['user_id'];
$leader = get_user_by_id($leader_id, $conn);

if (!$leader) {
    die("Error: Leader user not found in the database.");
}

// Fetch organization details
$organization = get_leader_organization($leader_id, $conn);
$org_name = $organization ? $organization['name'] : "No Organization Assigned";

// Fetch event statistics
$total_events = get_total_org_events($leader_id, $conn);

// Fetch only the leader's organization events
$sql = "SELECT 
            e.event_id, 
            e.title, 
            e.date_time, 
            e.venue, 
            e.capacity, 
            e.status,
            COALESCE(o.name, 'N/A') AS organization
        FROM events e
        INNER JOIN organizations o ON e.org_id = o.org_id
        WHERE o.leader_id = ?
        ORDER BY e.date_time DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $leader_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="/assets/css/includes.css">
    <link rel="stylesheet" href="/assets/css/table.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <?php include "../../includes/sidebar.php"; ?>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="container mt-4">
                <div class="table-wrapper">
                    <div class="table-title d-flex justify-content-between align-items-center">
                        <div>
                            <h1 style="color: #a83232;"><b> Event Management</b></h1>
                            <p>Manage events for your organization.</p>
                        </div>
                        <div class="col-md-3">
                            <div class="info-card shadow-sm border rounded-3 p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="text-muted mb-1">Total Events</h6>
                                    <div class="icon-circle bg-success text-white">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </div>
                                <h2 class="fw-bold mb-1" id="totalEventsCount"><?= $total_events ?></h2>
                            </div>
                        </div>
                    </div>

                    <div class="wrapper-card pagination-container d-flex justify-content-between">
                        <div>
                            <label for="rowsPerPageSelect">Show:</label>
                            <select id="rowsPerPageSelect" class="form-select w-auto">
                                <option value="5">5 entries</option>
                                <option value="10" selected>10 entries</option>
                                <option value="15">15 entries</option>
                                <option value="20">20 entries</option>
                            </select>
                        </div>
                        <div class="d-flex gap-3">
                            <input type="text" id="searchInput" class="form-control w-auto" placeholder="Search events...">
                            <button class="btn btn-primary d-flex align-items-center px-3" data-bs-toggle="modal" data-bs-target="#addEventModal">
                                <i class="fas fa-plus"></i> <span class="ms-1">Add Event</span>
                            </button>
                        </div>
                    </div>

                    <div class="wrapper-card mt-4">
                        <div class="table-container">
                            <table class="table table-striped table-hover" id="eventTable">
                                <thead>
                                    <tr>
                                        <th class="text-center">Event</th>
                                        <th class="text-center">Date & Time</th>
                                        <th class="text-center">Venue</th>
                                        <th class="text-center">Organization</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr id="event_<?= $row['event_id'] ?>">
                                            <td class="text-center"><?= htmlspecialchars($row['title']) ?></td>
                                            <td class="text-center"><?= date("M d, Y h:i A", strtotime($row['date_time'])) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($row['venue']) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($row['organization']) ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-<?= ($row['status'] == 'approved') ? 'success' : 'secondary' ?>">
                                                    <?= ucfirst($row['status']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-m p-2 text-warning viewEvent" data-id="<?= $row['event_id'] ?>" data-bs-toggle="modal" data-bs-target="#viewEventModal">
                                                    <i class="fas fa-info-circle fa-lg"></i>
                                                </button>
                                                <button class="btn btn-sm text-primary editEvent" data-id="<?= $row['event_id'] ?>" data-bs-toggle="modal" data-bs-target="#editEventModal">
                                                    <i class="fas fa-edit fa-lg"></i>
                                                </button>
                                                <button class="btn btn-sm text-danger deleteEvent" data-id="<?= $row['event_id'] ?>">
                                                    <i class="fas fa-trash-alt fa-lg"></i>
                                                </button>
                                            </td>
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

    <!-- Add Event Modal -->
    <div id="addEventModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h4 class="modal-title">Add Event</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addEventForm">
                        <label class="mb-1">Title:</label>
                        <input type="text" class="form-control mb-3" name="title" required>

                        <label class="mb-1">Description:</label>
                        <textarea class="form-control mb-3" name="description" required></textarea>

                        <label class="mb-1">Date & Time:</label>
                        <input type="datetime-local" class="form-control mb-3" name="date_time" required>

                        <label class="mb-1">Venue:</label>
                        <input type="text" class="form-control mb-3" name="venue" required>

                        <label class="mb-1">Capacity:</label>
                        <input type="number" class="form-control mb-3" name="capacity" min="1" required>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-plus"></i> Add Event
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- View Event Modal -->
    <div id="viewEventModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h4 class="modal-title">Event Details</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center"><i class="fas fa-calendar-alt text-warning fa-3x mb-3"></i>
                        <h5 id="viewEventTitle" class="fw-bold mb-3"></h5>
                        <p id="viewEventDescription" class="text-muted mb-5"></p>
                    </div>
                    <p class="mb-3"><strong>Date & Time:</strong> <span id="viewEventDate"></span></p>
                    <p class="mb-3"><strong>Venue:</strong> <span id="viewEventVenue"></span></p>
                    <p class="mb-3"><strong>Capacity:</strong> <span id="viewEventCapacity"></span></p>
                    <p class="mb-3"><strong>Organization:</strong> <span id="viewEventOrganization"></span></p>
                    <p class="mb-3"><strong>Status:</strong> <span id="viewEventStatus" class="badge"></span></p>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Event Modal -->
    <div id="editEventModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h4 class="modal-title">Edit Event</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editEventForm">
                        <input type="hidden" name="event_id" id="edit_event_id">

                        <label class="mb-1">Title:</label>
                        <input type="text" class="form-control mb-3" name="title" id="edit_title" required>

                        <label class="mb-1">Description:</label>
                        <textarea class="form-control mb-3" name="description" id="edit_description" required></textarea>

                        <label class="mb-1">Date & Time:</label>
                        <input type="datetime-local" class="form-control mb-3" name="date_time" id="edit_date_time" required>

                        <label class="mb-1">Venue:</label>
                        <input type="text" class="form-control mb-3" name="venue" id="edit_venue" required>

                        <label class="mb-1">Capacity:</label>
                        <input type="number" class="form-control mb-3" name="capacity" id="edit_capacity" min="1" required>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            let rowsPerPage = parseInt($("#rowsPerPageSelect").val());
            let currentPage = 1;
            let rows = $("#eventTable tbody tr");
            let filteredRows = rows;

            function showPage(page, rowsPerPage) {
                rows.hide();
                filteredRows.slice((page - 1) * rowsPerPage, page * rowsPerPage).show();
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
                        `<button class="btn btn-sm btn-outline-primary ${i === 1 ? 'active' : ''}" data-page="${i}">${i}</button>`
                    );
                }
            }

            generatePagination(rowsPerPage);
            showPage(currentPage, rowsPerPage);

            $(document).on("click", ".pagination button", function() {
                currentPage = $(this).data("page");
                showPage(currentPage, rowsPerPage);
            });

            $("#rowsPerPageSelect").change(function() {
                rowsPerPage = parseInt($(this).val());
                currentPage = 1;
                filteredRows = rows;
                generatePagination(rowsPerPage);
                showPage(currentPage, rowsPerPage);
            });

            $("#searchInput").on("keyup", function() {
                let value = $(this).val().toLowerCase();
                if (value === "") {
                    filteredRows = rows;
                    $(".pagination").show();
                } else {
                    filteredRows = rows.filter(function() {
                        return $(this).text().toLowerCase().indexOf(value) > -1;
                    });
                    $(".pagination").hide();
                }
                generatePagination(rowsPerPage);
                showPage(currentPage, rowsPerPage);
            });

            function updateEntryCount() {
                let totalEntries = $("#eventTable tbody tr").length;
                let visibleEntries = $("#eventTable tbody tr:visible").length;
                $("#currentEntries").text(visibleEntries);
                $("#totalEntries").text(totalEntries);
            }

            updateEntryCount();

            function showToast(message, type = "success") {
                let toastClass = (type === "success") ? "bg-success text-white" : "bg-danger text-white";
                let toastHTML = `
        <div class="toast align-items-center ${toastClass} position-fixed bottom-0 end-0 p-2 m-3" role="alert">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        `;
                $("body").append(toastHTML);
                let toast = new bootstrap.Toast($(".toast").last()[0]);
                toast.show();
                setTimeout(() => {
                    $(".toast").fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 3000);
            }

            $("#eventImageInput").change(function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $("#eventImagePreview").attr("src", e.target.result).show();
                    };
                    reader.readAsDataURL(file);
                }
            });

            $("#addEventForm").submit(function(event) {
                event.preventDefault();

                let formData = new FormData(this);
                fetch('events/add_event.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            let newRow = `
                                <tr id="event_${data.event_id}">
                                    <td class="text-center">${data.title}</td>
                                    <td class="text-center">${formatDateTime(data.date_time)}</td>
                                    <td class="text-center">${data.venue}</td>
                                    <td class="text-center">${data.organization}</td>
                                    <td class="text-center"><span class="badge bg-secondary">Pending</span></td>
                                    <td class="text-center">
                                        <button class="btn btn-m p-2 text-warning viewEvent" data-id="${data.event_id}" data-bs-toggle="modal" data-bs-target="#viewEventModal"><i class="fas fa-info-circle fa-lg"></i></button>
                                        <button class="btn btn-sm text-primary editEvent" data-id="${data.event_id}" data-bs-toggle="modal" data-bs-target="#editEventModal"><i class="fas fa-edit fa-lg"></i></button>
                                        <button class="btn btn-sm text-danger deleteEvent" data-id="${data.event_id}"><i class="fas fa-trash-alt fa-lg"></i></button>
                                    </td>
                                </tr>`;
                            $("#eventTable tbody").prepend(newRow);
                            $("#addEventModal").modal("hide");
                            $("#addEventForm")[0].reset();
                            showToast("Event added successfully!");
                        } else {
                            showToast("Error: " + data.message, "error");
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast("Server error occurred!", "error");
                    });
            });

            $(document).on("click", ".deleteEvent", function() {
                let eventId = $(this).data("id");

                if (confirm("Are you sure you want to delete this event?")) {
                    $.post('events/process_event.php', {
                        event_id: eventId,
                        action: 'delete'
                    }, function(response) {
                        if (response.trim() === "Event Deleted") {
                            $("#event_" + eventId).fadeOut(300, function() {
                                $(this).remove();
                            });
                            showToast("Event deleted successfully!");
                        } else {
                            showToast("Error deleting event!", "error");
                        }
                    }).fail(function(xhr) {
                        showToast("AJAX Error: " + xhr.status + " - " + xhr.statusText, "error");
                    });
                }
            });

            $(document).on("click", ".editEvent", function() {
                let eventId = $(this).data("id");

                $.ajax({
                    url: "events/get_event.php",
                    type: "POST",
                    data: {
                        event_id: eventId
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data) {
                            $("#edit_event_id").val(data.event_id);
                            $("#edit_title").val(data.title);
                            $("#edit_description").val(data.description);
                            $("#edit_date_time").val(data.date_time);
                            $("#edit_venue").val(data.venue);
                            $("#edit_capacity").val(data.capacity);
                            $("#editEventModal").modal("show");
                        } else {
                            showToast("Error loading event details!", "error");
                        }
                    },
                    error: function(xhr, status, error) {
                        showToast("AJAX Error: " + xhr.status + " - " + error, "error");
                    }
                });
            });

            $("#editEventForm").submit(function(event) {
                event.preventDefault();

                $.post("events/edit_event.php", $("#editEventForm").serialize(), function(response) {
                    if (response.trim().startsWith("success|")) {
                        let eventData = response.split("|");
                        let eventId = eventData[1];
                        let title = eventData[2];
                        let dateTime = eventData[3];
                        let venue = eventData[4];

                        let row = $("#event_" + eventId);
                        row.find("td:nth-child(1)").text(title);
                        row.find("td:nth-child(2)").text(formatDateTime(dateTime));
                        row.find("td:nth-child(3)").text(venue);

                        $("#editEventModal").modal("hide");
                        showToast("Event updated successfully!");
                    } else {
                        showToast("Error updating event!", "error");
                    }
                }).fail(function(xhr) {
                    showToast("AJAX Error: " + xhr.status + " - " + xhr.statusText, "error");
                });
            });

            // View Event (Load details into modal)
            $(document).on("click", ".viewEvent", function() {
                let eventId = $(this).data("id");

                $.ajax({
                    url: "events/get_event.php",
                    type: "POST",
                    data: {
                        event_id: eventId
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response && response.event_id) {
                            // ✅ Populate modal fields with event data
                            $("#viewEventTitle").text(response.title);
                            $("#viewEventDescription").text(response.description);
                            $("#viewEventDate").text(formatDateTime(response.date_time));
                            $("#viewEventVenue").text(response.venue);
                            $("#viewEventCapacity").text(response.capacity ? response.capacity : "No limit");
                            $("#viewEventOrganization").text(response.organization ? response.organization : "N/A");

                            // ✅ Set Status Badge (Dynamically change color like Edit Modal)
                            let statusBadge = $("#viewEventStatus");
                            statusBadge.text(response.status);
                            statusBadge.removeClass().addClass("badge bg-" +
                                (response.status === "approved" ? "success" :
                                    response.status === "pending" ? "warning" :
                                    response.status === "rejected" ? "danger" : "secondary"));

                            // ✅ Open the modal
                            $("#viewEventModal").modal("show");
                        } else {
                            showToast("Error: Event data not found.", "error");
                        }
                    },
                    error: function(xhr, status, error) {
                        showToast("AJAX Error: " + xhr.responseText, "error");
                    }
                });
            });

            // ✅ Helper Function to Format Date & Time
            function formatDateTime(dateTimeStr) {
                let dateObj = new Date(dateTimeStr);
                if (isNaN(dateObj)) return dateTimeStr; // If invalid, return original

                let options = {
                    year: "numeric",
                    month: "short",
                    day: "numeric",
                    hour: "2-digit",
                    minute: "2-digit",
                    hour12: true
                };
                return dateObj.toLocaleString("en-US", options);
            }

        });
    </script>

</body>

</html>
