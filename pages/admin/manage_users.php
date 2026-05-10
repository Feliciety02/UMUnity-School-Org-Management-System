<?php
require_once(__DIR__ . "/../../database/config.php");

include "../../includes/header.php";
// Check if user is an admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: /pages/login.php");
    exit();
}


//  Check if the request is AJAX and return only the user count
if (isset($_GET['ajax']) && $_GET['ajax'] === 'true') {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users");
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $total_users = $result->fetch_assoc()['count'] ?? 0;
        echo $total_users;
    } else {
        echo "Error";
    }
    exit(); //  Stop execution to prevent loading the rest of the page
}

//  Normal page load (not AJAX)
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM users");
if ($stmt->execute()) {
    $result = $stmt->get_result();
    $total_users = $result->fetch_assoc()['count'] ?? 0;
}

$sql = "SELECT 
    u.user_id, 
    u.full_name, 
    u.email, 
    u.status, 
    r.name AS role, 
    COALESCE(GROUP_CONCAT(DISTINCT o.name ORDER BY o.name SEPARATOR ', '), 'N/A') AS organization
FROM users u
LEFT JOIN roles r ON u.role_id = r.role_id
LEFT JOIN membership m ON u.user_id = m.user_id
LEFT JOIN organizations o ON m.org_id = o.org_id
GROUP BY u.user_id, u.full_name, u.email, u.status, r.name
ORDER BY u.user_id DESC
";


$result = $conn->query($sql);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>

    <link rel="stylesheet" href="/assets/css/includes.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="/assets/css/table.css">
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <!-- Bootstrap 5 -->
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
            <div class="container">
                <div class="table-responsive">
                    <div class="dashboard-stack">
                        <section class="page-hero">
                            <span class="page-eyebrow"><i class="fas fa-users-cog"></i> User Administration</span>
                            <h1 class="page-hero-title">User Management</h1>
                            <p class="page-hero-copy">Review accounts, adjust roles, and keep access control consistent across the organization platform.</p>
                            <div class="page-hero-meta">
                                <span class="page-hero-chip"><i class="fas fa-users"></i> <?= $total_users ?> total users</span>
                                <span class="page-hero-chip"><i class="fas fa-sliders"></i> Search, filter, and edit records live</span>
                            </div>
                        </section>

                    <div class="table-wrapper">
                        <div class="table-title d-flex justify-content-between align-items-center">
                            <div>
                                <h1><b>User Management</b></h1>
                                <p>Efficiently oversee user accounts, roles, and statuses.</p>
                            </div>
                            <div class="col-md-3">
                                <div class="info-card shadow-sm border rounded-3 p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="text-muted mb-1">Total Users</h6>
                                        <div class="stats-card-icon bg-success">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                    <h2 class="fw-bold mb-1" id="totalUsersCount"><?= $total_users ?></h2>
                                    <span class="text-muted small">
                                        Live count from the current system database
                                    </span>
                                </div>
                            </div>

                        </div>
                        <div class="wrapper-card is-toolbar">
                            <div class="control-bar">
                            <div class="control-group">
                                <label for="rowsPerPageSelect" class="control-label">Rows</label>
                                <select id="rowsPerPageSelect" class="form-select w-auto">
                                    <option value="5">5 entries</option>
                                    <option value="10" selected>10 entries</option>
                                    <option value="15">15 entries</option>
                                    <option value="20">20 entries</option>
                                </select>
                            </div>
                            <div class="control-group">
                                <div class="search-shell">
                                    <input type="text" id="searchInput" class="form-control w-100" placeholder="Search users...">
                                </div>
                                <button class="btn  d-flex align-items-center px-3" data-bs-toggle="modal" data-bs-target="#addUserModal" id="addUserBtn">
                                    <span class="material-icons-outlined" style="font-size: 20px; line-height: 1;">add</span>
                                    <span class="ms-1">Add User</span>
                                </button>
                            </div>
                            </div>
                        </div>
                        <div class="wrapper-card mt-4">
                            <div class="table-container">
                                <table class="table table-striped table-hover" id="userTable" style="max-height: 500px;">
                                    <colgroup>
                                        <col style="width: 5%;">
                                        <col style="width: 15%;">
                                        <col style="width: 20%;">
                                        <col style="width: 10%;">
                                        <col style="width: 10%;">
                                        <col style="width: 25%;">
                                        <col style="width: 20%;">

                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center fw-bold ">Name</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Role</th>
                                            <th class="text-center">Organization</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td class="text-center"><?= $row['user_id'] ?></td>
                                                <td><?= htmlspecialchars($row['full_name']) ?></td>
                                                <td><?= htmlspecialchars($row['email']) ?></td>
                                                <td class="text-center">
                                                    <span class="badge bg-<?= ($row['status'] == 'active') ? 'success' : 'secondary' ?>">
                                                        <?= ucfirst($row['status']) ?>
                                                    </span>
                                                </td>
                                                <td class="text-center"><?= ucfirst($row['role']) ?></td>
                                                <td><?= nl2br(htmlspecialchars($row['organization'])) ?></td>
                                                <td class="text-center">
                                                    <button class="btn btn-m p-2 text-primary editUser" data-id="<?= $row['user_id'] ?>" data-bs-toggle="modal" data-bs-target="#editUserModal">
                                                        <i class="fas fa-pen fa-lg"></i>
                                                    </button>
                                                    <!-- Suspend/Activate Button -->
                                                    <button class="btn btn-m p-2 text-secondary suspendUser" data-id="<?= $row['user_id'] ?>" data-status="<?= $row['status'] ?>" data-bs-toggle="modal"
                                                        data-bs-target="<?= ($row['status'] == 'active') ? '#suspendUserModal' : '#activateUserModal' ?>">
                                                        <i class="fas <?= ($row['status'] == 'active') ? 'fa-user-slash' : 'fa-user-check' ?> fa-lg"></i>
                                                    </button>
                                                    <button class="btn btn-m p-2 text-danger deleteUser" data-id="<?= $row['user_id'] ?>">
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
    </div>


    <!<!-- Add User Modal -->
        <div id="addUserModal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h4 class="modal-title"><i class="fas fa-user-plus"></i> Add User</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="fas fa-user-plus text-success fa-3x mb-3"></i>
                            <p>Fill in the details below to <b>add a new user</b>.</p>
                        </div>

                        <form id="addUserForm">
                            <div class="mb-3">
                                <label for="full_name" class="fw-bold">Full Name:</label>
                                <input type="text" class="form-control" name="full_name" id="full_name" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="fw-bold">Email:</label>
                                <input type="email" class="form-control" name="email" id="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="fw-bold">Password:</label>
                                <input type="password" class="form-control" name="password" id="password" required>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="fw-bold">Role:</label>
                                <select class="form-select" name="role" id="role">
                                    <option value=""> Select Role </option>
                                    <option value="1">Admin</option>
                                    <option value="2">Officer</option>
                                    <option value="3" selected>Student</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="org_id" class="fw-bold">Assign Organization (For Officers & Students):</label>
                                <select class="form-select" name="org_id" id="org_id">
                                    <option value="">Select Organization</option>
                                    <?php
                                    $orgQuery = $conn->query("SELECT org_id, name FROM organizations WHERE status='active'");
                                    while ($org = $orgQuery->fetch_assoc()) {
                                        echo "<option value='" . htmlspecialchars($org['org_id']) . "'>" . htmlspecialchars($org['name']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-user-plus"></i> Add User
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suspend User Modal -->
        <div id="suspendUserModal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h4 class="modal-title">Confirm Suspension</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-user-slash text-warning fa-3x mb-3"></i>
                        <p>Are you sure you want to <b>suspend</b> this user?</p>
                        <input type="hidden" id="suspend_user_id">
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-warning" id="confirmSuspendUser">
                            <i class="fas fa-user-slash"></i> Suspend
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activate User Modal -->
        <div id="activateUserModal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h4 class="modal-title">Confirm Activation</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-user-check text-success fa-3x mb-3"></i>
                        <p>Do you want to <b>activate</b> this user?</p>
                        <input type="hidden" id="activate_user_id">
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="confirmActivateUser">
                            <i class="fas fa-user-check"></i> Activate
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="deleteUserModal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h4 class="modal-title">Confirm Deletion</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-trash-alt text-danger fa-3x mb-3"></i>
                        <p>Are you sure you want to permanently delete this user? This action cannot be undone.</p>
                        <input type="hidden" id="delete_user_id">
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteUser">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div id="editUserModal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h4 class="modal-title">Edit User</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="fas fa-user-edit text-primary fa-3x mb-3"></i>
                            <p><b>Modify</b> the user details below.</p>
                        </div>

                        <form id="editUserForm">
                            <input type="hidden" name="user_id" id="edit_user_id">

                            <div class="mb-3">
                                <label for="edit_full_name" class="fw-bold">Full Name:</label>
                                <input type="text" class="form-control" name="full_name" id="edit_full_name" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_email" class="fw-bold">Email:</label>
                                <input type="email" class="form-control" name="email" id="edit_email" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_role" class="fw-bold">Role:</label>
                                <select class="form-select" name="role_id" id="edit_role">
                                    <option value="1">Admin</option>
                                    <option value="2">Officer</option>
                                    <option value="3">Student</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_org_id" class="fw-bold">Assign Organization (For Officers & Students):</label>
                                <select class="form-select" name="org_id" id="edit_org_id">
                                    <option value="">Select Organization</option>
                                    <?php
                                    $orgQuery = $conn->query("SELECT org_id, name FROM organizations WHERE status='active'");
                                    while ($org = $orgQuery->fetch_assoc()) {
                                        echo "<option value='" . htmlspecialchars($org['org_id']) . "'>" . htmlspecialchars($org['name']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>


                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

</body>

</html>
<!-- jQuery & Bootstrap Bundle -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        let rowsPerPage = parseInt($("#rowsPerPageSelect").val());
        let currentPage = 1;
        let rows = $("#userTable tbody tr");
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
            let totalEntries = $("#userTable tbody tr").length; // Count all table rows
            let visibleEntries = $("#userTable tbody tr:visible").length; // Count only visible rows

            $("#currentEntries").text(visibleEntries);
            $("#totalEntries").text(totalEntries);
        }

        // Call function on page load
        $(document).ready(function() {
            updateEntryCount();
        });

        // Function to show toast notifications
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
            $("body").append(toastHTML);
            let toast = new bootstrap.Toast($(".toast").last()[0]);
            toast.show();
            setTimeout(() => {
                $(".toast").fadeOut(300, function() {
                    $(this).remove();
                });
            }, 4000);
        }



        $("#addUserForm").submit(function(e) {
            e.preventDefault();

            $.post("users/add_user.php", $("#addUserForm").serialize(), function(response) {
                if (response.trim().startsWith("success|")) {
                    let userData = response.split("|");
                    let newUserId = userData[1];
                    let fullName = userData[2];
                    let email = userData[3];
                    let roleId = userData[4];
                    let organizationId = userData[5] || "N/A";



                    // Convert role ID to role name
                    let roleName = "Student"; // Default
                    if (roleId == 1) roleName = "Admin";
                    if (roleId == 2) roleName = "Officer";

                    // Fetch organization name from dropdown
                    let organizationName = "N/A";
                    let selectedOrg = $("select[name='org_id'] option:selected").text();
                    if (organizationId !== "N/A") {
                        organizationName = selectedOrg;
                    }

                    // Default user status is Active
                    let userStatus = "active";
                    if (organizationName.includes("Select Organization")) {
                        organizationName = organizationName.replace("Select Organization", "").trim();
                    }
                    // Append new row to table with working buttons
                    let newRow = `
                            <tr id="user_${newUserId}">
                                <td class="text-center">${newUserId}</td>
                                <td>${fullName}</td>
                                <td>${email}</td>
                                <td class="text-center"><span class="badge bg-success">Active</span></td>
                                <td class="text-center">${roleName}</td>
                                <td>${organizationName}</td>    
                                <td class="text-center">
                                    <button class="btn btn-m p-2 text-primary editUser" data-id="${newUserId}" data-bs-toggle="modal" data-bs-target="#editUserModal">
                                        <i class="fas fa-pen fa-lg"></i>
                                    </button>
                                    <button class="btn btn-m p-2 text-secondary suspendUser" data-id="${newUserId}" data-status="active" data-bs-toggle="modal" data-bs-target="#suspendUserModal">
                                        <i class="fas fa-user-slash fa-lg"></i>
                                    </button>
                                    <button class="btn btn-m p-2 text-danger deleteUser" data-id="${newUserId}" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                                        <i class="fas fa-trash-alt fa-lg"></i>
                                    </button>
                                </td>
                            </tr>
                            `;

                    // Close modal properly
                    let modal = document.getElementById('addUserModal');
                    let modalInstance = bootstrap.Modal.getInstance(modal);
                    modalInstance.hide();

                    // Append the new row
                    $("#userTable tbody").prepend(newRow);

                    // Reset the form
                    $('#addUserForm')[0].reset();

                    // Show success message
                    showToast("User added successfully!");

                } else {
                    showToast(response, "error");
                }
            }).fail(function(xhr, status, error) {
                showToast("AJAX Error: " + xhr.status + " - " + error, "error");
            });
        });

        $(document).on("click", ".editUser", function() {
            let userId = $(this).data("id");

            $.ajax({
                url: "users/get_user.php",
                type: "POST",
                data: {
                    user_id: userId
                },
                dataType: "json",
                success: function(response) {
                    if (response && !response.error) {
                        $("#edit_user_id").val(response.user_id);
                        $("#edit_full_name").val(response.full_name);
                        $("#edit_email").val(response.email);
                        $("#edit_role").val(response.role_id);

                        // Set selected organization in dropdown
                        if (response.org_id) {
                            $("#edit_org_id").val(response.org_id);
                        } else {
                            $("#edit_org_id").val("");
                        }

                        $("#editUserModal").modal("show");
                    } else {
                        showToast("Error: " + response.error, "error");
                    }
                },
                error: function(xhr, status, error) {
                    showToast("AJAX Error: " + xhr.responseText, "error");
                }
            });
        });

        // Submit Edit User Form via AJAX
        $("#editUserForm").submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: "users/edit_user.php",
                type: "POST",
                data: $("#editUserForm").serialize(),
                success: function(response) {
                    console.log("Server response:", response); // Debugging

                    if (response.trim().startsWith("success|")) {
                        let userData = response.split("|");
                        let userId = userData[1];
                        let fullName = userData[2];
                        let email = userData[3];
                        let roleId = userData[4];
                        let organizationId = userData[5] || "N/A";

                        // Convert role ID to role name
                        let roleName = "Student";
                        if (roleId == 1) roleName = "Admin";
                        if (roleId == 2) roleName = "Officer";

                        // Get organization name from dropdown
                        let organizationName = "N/A";
                        let selectedOrg = $("#edit_org_id option:selected").text();
                        if (organizationId !== "N/A") {
                            organizationName = selectedOrg;
                        }

                        // Find the correct row and update details
                        let row = $("#userTable tbody tr").filter(function() {
                            return $(this).find(".editUser").data("id") == userId;
                        });

                        row.find("td:nth-child(2)").text(fullName); // Full Name
                        row.find("td:nth-child(3)").text(email); // Email
                        row.find("td:nth-child(5)").text(roleName); // Role
                        row.find("td:nth-child(6)").text(organizationName); // Organization

                        // Close modal after update
                        $("#editUserModal").modal("hide");

                        // Show success message
                        showToast("User updated successfully!", "success");
                    } else {
                        showToast("Error updating user: " + response, "error");
                    }
                },
                error: function(xhr, status, error) {
                    showToast("AJAX Error: " + xhr.status + " - " + error, "error");
                }
            });
        });



        let deleteUserId = null;
        let deleteButton = null;


        // Event delegation for the Edit button
        $(document).on("click", ".editUser", function() {
            let userId = $(this).data("id");

            // Fetch user data using AJAX
            $.ajax({
                url: "users/get_user.php", // Update with the correct URL
                type: "POST",
                data: {
                    user_id: userId
                },
                dataType: "json",
                success: function(response) {
                    if (response) {
                        $("#edit_user_id").val(response.user_id);
                        $("#edit_full_name").val(response.full_name);
                        $("#edit_email").val(response.email);
                        $("#edit_role").val(response.role_id);
                        $("#edit_status").val(response.status); // Populate the status field if necessary
                        $("#editUserModal").modal("show"); // Open modal
                    } else {
                        alert("Error: User not found.");
                    }
                },
                error: function(xhr, status, error) {
                    alert("AJAX Error: " + xhr.responseText);
                }
            });
        });


        $(document).on("click", ".deleteUser", function() {
            let userId = $(this).data("id");
            $("#delete_user_id").val(userId); // Set user ID in modal
            $("#deleteUserModal").modal("show"); // Show the delete modal
        });

        // Confirm Deletion via AJAX
        $(document).on("click", "#confirmDeleteUser", function() {
            let userId = $("#delete_user_id").val(); // Get user ID from modal

            $.ajax({
                url: "users/delete_user.php",
                type: "POST",
                data: {
                    user_id: userId
                },
                success: function(response) {
                    console.log("Server Response:", response);

                    if (response.trim() === "success") {
                        // Find the row and remove it
                        $("#userTable tbody tr").filter(function() {
                            return $(this).find(".deleteUser").data("id") == userId;
                        }).fadeOut(300, function() {
                            $(this).remove();
                        });

                        $("#deleteUserModal").modal("hide"); // Close modal
                        showToast("User deleted successfully!", "success");
                    } else {
                        showToast("Error deleting user: " + response, "error");
                    }
                },
                error: function(xhr, status, error) {
                    showToast("AJAX Error: " + xhr.status + " - " + error, "error");
                }
            });
        });




        // ✅ Open the correct modal before suspending/activating a user
        $(document).on("click", ".suspendUser, .activateUser", function() {
            let userId = $(this).data("id");
            let currentStatus = $(this).attr("data-status"); // Use attr instead of data()

            if (currentStatus === "active") {
                $("#suspend_user_id").val(userId);
                $("#suspendUserModal").modal("show");
            } else {
                $("#activate_user_id").val(userId);
                $("#activateUserModal").modal("show");
            }
        });

        // ✅ Confirm Suspend User
        $(document).on("click", "#confirmSuspendUser", function() {
            let userId = $("#suspend_user_id").val();

            $.post("users/change_status.php", {
                user_id: userId,
                status: "inactive"
            }, function(response) {
                let res = JSON.parse(response);
                console.log("Server Response (Suspend):", res);

                if (res.status === "success") {
                    $("#suspendUserModal").modal("hide");

                    // ✅ Update UI Immediately
                    let button = $(`.suspendUser[data-id='${userId}'], .activateUser[data-id='${userId}']`).closest("tr");
                    let btn = button.find(".suspendUser, .activateUser");
                    let badge = button.find(".badge");
                    btn.removeClass("suspendUser").addClass("activateUser")
                        .html('<i class="fas fa-user-check fa-lg"></i>')
                        .attr("data-bs-target", "#activateUserModal")
                        .attr("data-status", "inactive"); // Use attr instead of data()

                    badge.text("Inactive").removeClass("bg-success").addClass("bg-secondary");

                    showToast("User suspended successfully!", "success");
                } else {
                    showToast(`Error: ${res.message}`, "error");
                }
            }).fail(function(xhr, status, error) {
                showToast(`AJAX Error: ${xhr.status} - ${error}`, "error");
            });
        });

        // ✅ Confirm Activate User
        $(document).on("click", "#confirmActivateUser", function() {
            let userId = $("#activate_user_id").val();

            $.post("users/change_status.php", {
                user_id: userId,
                status: "active"
            }, function(response) {
                let res = JSON.parse(response);
                console.log("Server Response (Activate):", res);

                if (res.status === "success") {
                    $("#activateUserModal").modal("hide");

                    // ✅ Update UI Immediately
                    let button = $(`.suspendUser[data-id='${userId}'], .activateUser[data-id='${userId}']`).closest("tr");
                    let btn = button.find(".suspendUser, .activateUser");
                    let badge = button.find(".badge");
                    btn.removeClass("activateUser").addClass("suspendUser")
                        .html('<i class="fas fa-user-slash fa-lg"></i>')
                        .attr("data-bs-target", "#suspendUserModal")
                        .attr("data-status", "active"); // Use attr instead of data()

                    badge.text("Active").removeClass("bg-secondary").addClass("bg-success");

                    showToast("User activated successfully!", "success");
                } else {
                    showToast(`Error: ${res.message}`, "error");
                }
            }).fail(function(xhr, status, error) {
                showToast(`AJAX Error: ${xhr.status} - ${error}`, "error");
            });
        });

    });
</script>
