<?php
require_once(__DIR__ . "/../../database/config.php");
include "../../includes/header.php";

// Check if user is an admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: /pages/login.php");
    exit();
}

$stmt = $conn->prepare("SELECT COUNT(*) as count FROM organizations WHERE status = 'active'");
if ($stmt->execute()) {
    $result = $stmt->get_result();
    $total_organizations = $result->fetch_assoc()['count'] ?? 0;
}

$sql = "SELECT 
            o.org_id, 
            o.name, 
            COALESCE(o.logo, 'assets/images/default-org.png') AS logo, 
            o.description, 
            COALESCE(u.full_name, 'N/A') AS leader_name, 
            COALESCE(c.category_name, 'N/A') AS category_name, 
            o.status 
        FROM organizations o
        LEFT JOIN membership m ON o.org_id = m.org_id AND m.role = 'officer'
        LEFT JOIN users u ON m.user_id = u.user_id
        LEFT JOIN org_categories c ON o.category_id = c.category_id
        ORDER BY o.org_id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Management</title>

    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="/assets/css/includes.css">
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
            <div class="container ">
                <div class="table-responsive">
                    <div class="table-wrapper">
                        <div class="table-title d-flex justify-content-between align-items-center">
                            <div>
                                <h1 style="color:  #a83232 !important;"><b> Organization Management</b></h1>
                                <p>Oversee and coordinate organizations effectively.</p>
                            </div>

                            <div class="col-md-3">
                                <div class="info-card shadow-sm border rounded-3 p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="text-muted mb-1">Total Organizations</h6>
                                        <div class="icon-circle bg-success text-white">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                    </div>
                                    <h2 class="fw-bold mb-1"><?= $total_organizations ?></h2>
                                    <span class="text-success small">
                                        <i class="fas fa-arrow-up"></i> 2 new this month
                                    </span>
                                </div>
                            </div>

                        </div>
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
                            <div class="d-flex gap-3"><input type="text" id="searchInput" class="form-control w-auto" placeholder="Search organizations...">
                                <button class="btn  d-flex align-items-center px-3" data-bs-toggle="modal" data-bs-target="#addOrgModal" id="addOrgBtn">
                                    <span class="material-icons-outlined" style="font-size: 20px; line-height: 1;">add</span>
                                    <span class="ms-1">Add Organization</span>
                                </button>
                            </div>

                        </div>
                        <div class="wrapper-card mt-4">
                            <div class="table-container">
                                <table class="table table-striped table-hover" id="orgTable" style="max-height: 500px;">
                                    <colgroup>
                                        <col style="width: 20%;">
                                        <col style="width: 20%;">
                                        <col style="width: 15%;">
                                        <col style="width: 20%;">
                                        <col style="width: 5%;">
                                        <col style="width: 20%;">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th> Organization Name</th>
                                            <th>Description</th>
                                            <th>Leader</th>
                                            <th>Category</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td class="d-flex align-items-center">
                                                    <?php
                                                    // Ensure correct path handling
                                                    $logo = !empty($row['logo']) && file_exists(__DIR__ . "/../.." . $row['logo'])
                                                        ? $row['logo']
                                                        : '/assets/images/orgs/default-org.png';
                                                    ?>
                                                    <img src="<?= htmlspecialchars($logo) ?>" alt="Org Logo" class="img-fluid rounded me-2" style="width: 56px; height: 56px;">
                                                    <span><?= htmlspecialchars($row['name']) ?></span>
                                                </td>

                                                <td><?= htmlspecialchars($row['description']) ?></td>
                                                <td><?= htmlspecialchars($row['leader_name']) ?></td>
                                                <td><?= htmlspecialchars($row['category_name']) ?></td>
                                                <td class="text-center">
                                                    <span class="badge bg-<?= ($row['status'] == 'active') ? 'success' : 'secondary' ?>">
                                                        <?= ucfirst($row['status']) ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-m p-2 text-primary editOrg" data-id="<?= $row['org_id'] ?>" data-bs-toggle="modal" data-bs-target="#editOrgModal">
                                                        <i class="fas fa-pen fa-lg"></i>
                                                    </button>
                                                    <button class="btn btn-m p-2 text-secondary disbandOrg" data-id="<?= $row['org_id'] ?>" data-status="<?= $row['status'] ?>" data-bs-toggle="modal"
                                                        data-bs-target="<?= ($row['status'] == 'active') ? '#disbandOrgModal' : '#reactivateOrgModal' ?>">
                                                        <i class="fas <?= ($row['status'] == 'active') ? 'fa-times-circle' : 'fa-check-circle' ?> fa-lg"></i>
                                                    </button>
                                                    <button class="btn btn-m p-2 text-danger deleteOrg" data-id="<?= $row['org_id'] ?>">
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
    <?php include "../../includes/footer.php"; ?>


    <!-- Add Organization Modal -->
    <div id="addOrgModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h4 class="modal-title">Add Organization</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fas fa-users text-success fa-3x mb-3"></i>
                        <p>Fill in the details below to <b>add a new organization</b>.</p>
                    </div>

                    <form id="addOrgForm">
                        <div class="mb-3">
                            <label for="org_name" class="fw-bold">Organization Name:</label>
                            <input type="text" class="form-control" name="org_name" id="org_name" required>
                        </div>

                        <div class="mb-3">
                            <label for="org_description" class="fw-bold">Description:</label>
                            <textarea class="form-control" name="org_description" id="org_description" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="fw-bold">Category:</label>
                            <select class="form-select" name="category_id" id="category_id" required>
                                <option value=""> Select Category </option>
                                <?php
                                $categoryQuery = "SELECT category_id, category_name FROM org_categories";
                                $categoryResult = $conn->query($categoryQuery);
                                while ($catRow = $categoryResult->fetch_assoc()) {
                                    echo '<option value="' . $catRow['category_id'] . '">' . $catRow['category_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="leader_id" class="fw-bold">Assign Leader (Optional):</label>
                            <select class="form-select" name="leader_id" id="leader_id">
                                <option value=""> Select Leader </option>
                                <?php
                                $leaderQuery = "SELECT user_id, full_name FROM users WHERE role_id = 2"; // Role 2 = officer
                                $leaderResult = $conn->query($leaderQuery);
                                while ($leaderRow = $leaderResult->fetch_assoc()) {
                                    echo '<option value="' . $leaderRow['user_id'] . '">' . $leaderRow['full_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-plus-circle"></i> Add Organization
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Organization Modal -->
    <div id="editOrgModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h4 class="modal-title">Edit Organization</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fas fa-edit text-primary fa-3x mb-3"></i>
                        <p>Fill in the details below to <b>edit organization</b>.</p>
                    </div>

                    <form id="editOrgForm" enctype="multipart/form-data">
                        <input type="hidden" name="org_id" id="edit_org_id">

                        <!-- Organization Name -->
                        <div class="mb-3">
                            <label for="edit_name" class="fw-bold">Name:</label>
                            <input type="text" class="form-control" name="name" id="edit_name" required>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="edit_description" class="fw-bold">Description:</label>
                            <textarea class="form-control" name="description" id="edit_description" rows="3" required></textarea>
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="edit_category" class="fw-bold">Category:</label>
                            <select class="form-select" name="category_id" id="edit_category" required>
                                <option value=""> Select Category </option>
                                <?php
                                $categoryQuery = "SELECT category_id, category_name FROM org_categories";
                                $categoryResult = $conn->query($categoryQuery);
                                while ($catRow = $categoryResult->fetch_assoc()) {
                                    echo '<option value="' . $catRow['category_id'] . '">' . $catRow['category_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Leader -->
                        <div class="mb-3">
                            <label for="edit_leader" class="fw-bold">Assign Leader:</label>
                            <select class="form-select" name="leader_id" id="edit_leader">
                                <option value=""> Select Leader </option>
                                <?php
                                $leaderQuery = "SELECT user_id, full_name FROM users WHERE role_id = 2"; // Role 2 = officer
                                $leaderResult = $conn->query($leaderQuery);
                                while ($leaderRow = $leaderResult->fetch_assoc()) {
                                    echo '<option value="' . $leaderRow['user_id'] . '">' . $leaderRow['full_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Organization Logo Preview -->
                        <div class="mb-3 text-center">
                            <label class="fw-bold d-block">Organization Logo:</label>
                            <img id="edit_logo_preview" class="rounded border" style="width: 150px; height: 150px; object-fit: cover;">
                            <input type="file" class="form-control mt-2" name="logo" id="edit_logo">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Disband Organization Modal -->
    <div id="disbandOrgModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h4 class="modal-title"><i class="fas fa-times-circle"></i> Disband Organization</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="organizations/disband_organization.php" method="POST">

                    <div class="modal-body text-center">
                        <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                        <p><b>Warning:</b> Are you sure you want to <b>disband</b> this organization? This action <b>cannot be undone</b>.</p>
                        <input type="hidden" id="disband_org_id">
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDisbandOrg">
                            <i class="fas fa-times-circle"></i> Disband
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Reactivate Organization Modal -->
    <div id="reactivateOrgModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h4 class="modal-title"><i class="fas fa-sync-alt"></i> Reactivate Organization</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-sync-alt text-success fa-3x mb-3"></i>
                    <p>Do you want to <b>reactivate</b> this organization?</p>
                    <input type="hidden" id="reactivate_org_id">
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="confirmReactivateOrg">
                        <i class="fas fa-sync"></i> Reactivate
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Organization Modal -->
    <div id="deleteOrgModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h4 class="modal-title"><i class="fas fa-trash-alt"></i> Confirm Deletion</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-trash-alt text-danger fa-3x mb-3"></i>
                    <p>Are you sure you want to <b>permanently delete</b> this organization? This action <b>cannot be undone</b>.</p>
                    <input type="hidden" id="delete_org_id">
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteOrg">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>



    <!-- jQuery & Bootstrap Bundle -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            let rowsPerPage = parseInt($("#rowsPerPageSelect").val());
            let currentPage = 1;
            let rows = $("#orgTable tbody tr");
            let filteredRows = rows; // Holds search results

            function showPage(page, rowsPerPage) {
                rows.hide(); // Hide all rows
                filteredRows.slice((page - 1) * rowsPerPage, page * rowsPerPage).show(); // Show selected page rows
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

            // Live Search Filter
            $("#searchInput").on("keyup", function() {
                let value = $(this).val().toLowerCase();

                if (value === "") {
                    filteredRows = rows; // Reset filtering when search bar is empty
                    $(".pagination").show();
                } else {
                    filteredRows = rows.filter(function() {
                        return $(this).text().toLowerCase().indexOf(value) > -1;
                    });

                    $(".pagination").hide(); // Hide pagination during search
                }

                generatePagination(rowsPerPage);
                showPage(currentPage, rowsPerPage);
            });

            function updateEntryCount() {
                let totalEntries = $("#orgTable tbody tr").length; // Count all table rows
                let visibleEntries = $("#orgTable tbody tr:visible").length; // Count only visible rows

                $("#currentEntries").text(visibleEntries);
                $("#totalEntries").text(totalEntries);
            }

            // Call function on page load
            $(document).ready(function() {
                updateEntryCount();
            });


            //  Toast Notification Function
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



            if (response.startsWith("success|")) {
                let orgData = response.split("|");
                let newOrgId = orgData[1];
                let name = orgData[2];
                let description = orgData[3];
                let leader = orgData[4] || "N/A";
                let category = orgData[5] || "N/A";
                let logo = orgData[6] || "/assets/images/orgs/default-org.png";
                let status = "active";

                // ✅ Append new row
                let newRow = `
            <tr id="org_${newOrgId}">
                <td class="d-flex align-items-center">
                    <img src="${logo}" alt="Org Logo" class="img-fluid rounded me-2" style="width: 58px; height: 58px;">
                    <span>${name}</span>
                </td>
                <td>${description}</td>
                <td>${leader}</td>
                <td>${category}</td>
                <td class="text-center">
                    <span class="badge bg-${status === 'active' ? 'success' : 'secondary'}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>
                </td>
                <td class="text-center">
                    <button class="btn btn-m p-2 text-primary editOrg" data-id="${newOrgId}" data-bs-toggle="modal" data-bs-target="#editOrgModal">
                        <i class="fas fa-pen fa-lg"></i>
                    </button>
                    <button class="btn btn-m p-2 text-secondary disbandOrg" data-id="${newOrgId}" data-status="${status}" data-bs-toggle="modal"
                        data-bs-target="${status === 'active' ? '#disbandOrgModal' : '#reactivateOrgModal'}">
                        <i class="fas ${status === 'active' ? 'fa-times-circle' : 'fa-check-circle'} fa-lg"></i>
                    </button>
                    <button class="btn btn-m p-2 text-danger deleteOrg" data-id="${newOrgId}">
                        <i class="fas fa-trash-alt fa-lg"></i>
                    </button>
                </td>
            </tr>`;

                $("#orgTable tbody").prepend(newRow); // Insert new row at the top
                $("#addOrgModal").modal("hide").on("hidden.bs.modal", function() {
                    $("#addOrgForm")[0].reset(); // Reset form when modal is fully hidden
                });
            }


            $(document).ready(function() {
                // Open Edit Organization Modal and Populate Fields
                $(document).on("click", ".editOrg", function() {
                    let orgId = $(this).data("id");

                    $.ajax({
                        url: "organizations/get_organization.php",
                        type: "POST",
                        data: {
                            org_id: orgId
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response && response.org_id) {
                                $("#edit_org_id").val(response.org_id);
                                $("#edit_name").val(response.name);
                                $("#edit_description").val(response.description.trim());
                                $("#edit_category").val(response.category_id);
                                $("#edit_leader").val(response.leader_id);

                                // ✅ Set Logo Preview
                                let logoPath = response.logo ? response.logo : "/assets/images/orgs/default-org.png";
                                $("#edit_logo_preview").attr("src", logoPath);

                                // Show the modal
                                $("#editOrgModal").modal("show");
                            } else {
                                alert("Error: Organization data not found.");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error: " + xhr.responseText);
                        }
                    });
                });

                // ✅ Live Preview for Logo Upload
                $("#edit_logo").change(function() {
                    let file = this.files[0];
                    if (file) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            $("#edit_logo_preview").attr("src", e.target.result);
                        };
                        reader.readAsDataURL(file);
                    }
                });

                // ✅ Submit Edit Organization Form
                $("#editOrgForm").submit(function(e) {
                    e.preventDefault();

                    let formData = new FormData(this);

                    $.ajax({
                        url: "organizations/edit_organization.php",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            let data = response.split("|");

                            if (data[0] === "success") {
                                let orgId = data[1];
                                let name = data[2];
                                let description = data[3].trim();
                                let logoPath = data[6] ? data[6] : "/assets/images/orgs/default-org.png";

                                let row = $("#orgTable tbody tr").filter(function() {
                                    return $(this).find(".editOrg").data("id") == orgId;
                                });

                                row.find("td:nth-child(1) img").attr("src", logoPath);
                                row.find("td:nth-child(1) span").text(name);
                                row.find("td:nth-child(2)").text(description);

                                $("#editOrgModal").modal("hide");
                                showToast("Organization updated successfully!", "success");
                            }
                        }
                    });
                });
            });


            // ✅ Open the correct modal before disbanding/reactivating
            $(document).on("click", ".disbandOrg, .reactivateOrg", function() {
                let orgId = $(this).data("id");
                let currentStatus = $(this).data("status");

                if (currentStatus === "active") {
                    $("#disband_org_id").val(orgId);
                    $("#disbandOrgModal").modal("show");
                } else {
                    $("#reactivate_org_id").val(orgId);
                    $("#reactivateOrgModal").modal("show");
                }
            });

            // ✅ Confirm Disband Organization
            $(document).on("click", "#confirmDisbandOrg", function() {
                let orgId = $("#disband_org_id").val();

                $.post("organizations/disband_organization.php", {
                    org_id: orgId,
                    status: "disbanded"
                }, function(response) {
                    if (response.trim() === "success") {
                        // Close modal first
                        $("#disbandOrgModal").modal("hide");

                        let row = $("#orgTable tbody tr").filter(function() {
                            return $(this).find(".disbandOrg, .reactivateOrg").data("id") == orgId;
                        });

                        let button = row.find(".disbandOrg, .reactivateOrg");
                        let badge = row.find(".badge");
                        let statusColumn = row.find(".status-column"); // Assuming there's a column for status

                        // Update the button, badge, and status column dynamically
                        button.removeClass("disbandOrg").addClass("reactivateOrg")
                            .html('<i class="fas fa-check-circle text-secondary fa-lg"></i>')
                            .attr("data-bs-target", "#reactivateOrgModal")
                            .data("status", "disbanded");

                        badge.text("Disbanded").removeClass("bg-success").addClass("bg-secondary");
                        statusColumn.text("Disbanded").removeClass("text-secondary").addClass("text-secondary"); // Update status column

                        showToast("Organization disbanded successfully!", "success");
                    } else {
                        showToast(`Error updating organization: ${response}`, "error");
                    }
                }).fail(function(xhr, status, error) {
                    showToast(`AJAX Error: ${xhr.status} - ${error}`, "error");
                });
            });

            // ✅ Confirm Reactivate Organization
            $(document).on("click", "#confirmReactivateOrg", function() {
                let orgId = $("#reactivate_org_id").val();

                $.post("organizations/disband_organization.php", {
                    org_id: orgId,
                    status: "active"
                }, function(response) {
                    if (response.trim() === "success") {
                        // Close modal first
                        $("#reactivateOrgModal").modal("hide");

                        let row = $("#orgTable tbody tr").filter(function() {
                            return $(this).find(".disbandOrg, .reactivateOrg").data("id") == orgId;
                        });

                        let button = row.find(".disbandOrg, .reactivateOrg");
                        let badge = row.find(".badge");
                        let statusColumn = row.find(".status-column"); // Assuming there's a column for status

                        // Update the button, badge, and status column dynamically
                        button.removeClass("reactivateOrg").addClass("disbandOrg")
                            .html('<i class="fas fa-times-circle text-secondary fa-lg"></i>')
                            .attr("data-bs-target", "#disbandOrgModal")
                            .data("status", "active");

                        badge.text("Active").removeClass("bg-secondary").addClass("bg-success");
                        statusColumn.text("Active").removeClass("text-secondary").addClass("text-secondary"); // Update status column

                        showToast("Organization reactivated successfully!", "success");
                    } else {
                        showToast(`Error updating organization: ${response}`, "error");
                    }
                }).fail(function(xhr, status, error) {
                    showToast(`AJAX Error: ${xhr.status} - ${error}`, "error");
                });
            });

            // ✅ Confirm Delete Organization
            $(document).on("click", ".deleteOrg", function() {
                let orgId = $(this).data("id");
                $("#delete_org_id").val(orgId);
                $("#deleteOrgModal").modal("show");
            });

            $(document).on("click", "#confirmDeleteOrg", function() {
                let orgId = $("#delete_org_id").val();

                $.post("organizations/delete_organization.php", {
                    org_id: orgId
                }, function(response) {
                    if (response.trim() === "success") {
                        // Close modal first
                        $("#deleteOrgModal").modal("hide");

                        // Remove row dynamically
                        let row = $("#orgTable tbody tr").filter(function() {
                            return $(this).find(".deleteOrg").data("id") == orgId;
                        });

                        row.fadeOut(300, function() {
                            $(this).remove();
                        });

                        showToast("Organization deleted successfully!", "success");
                    } else {
                        showToast(`Error deleting organization: ${response}`, "error");
                    }
                }).fail(function(xhr, status, error) {
                    showToast(`AJAX Error: ${xhr.status} - ${error}`, "error");
                });
            });
        });
    </script>
</body>

</html>
