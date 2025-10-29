<?php
include("connect.php");
include("insert.php"); // your CRUD logic file

// =======================
// 🔍 FILTER (Search)
// =======================
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// =======================
// ↕️ SORTING
// =======================
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : "uname";
$sort_order = isset($_GET['order']) && $_GET['order'] == "desc" ? "DESC" : "ASC";
$next_order = $sort_order === "ASC" ? "desc" : "asc";

// =======================
// 📄 PAGINATION
// =======================
// Pagination setup
$limit = 5; // records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Count total records
$total_query = $conn->query("SELECT COUNT(*) AS total FROM users");
$total_result = $total_query->fetch_assoc();
$total_records = $total_result['total'] ?? 0;

// Calculate total pages
$total_pages = ceil($total_records / $limit);

// =======================
// 🟢 BUILD QUERY WITH SEARCH + SORT + PAGINATION
// =======================
$where = "";
if (!empty($search)) {
    $where = "WHERE uname LIKE ? OR uemail LIKE ? OR unum LIKE ?";
}

$sql = "SELECT * FROM users $where ORDER BY $sort_column $sort_order LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $param = "%$search%";
    $stmt->bind_param("sssii", $param, $param, $param, $limit, $offset);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

// =======================
// 🔢 PAGINATION COUNT
// =======================
$count_sql = "SELECT COUNT(*) as total FROM users " . ($where ? $where : "");
$count_stmt = $conn->prepare($count_sql);
if (!empty($search)) {
    $count_stmt->bind_param("sss", $param, $param, $param);
}
$count_stmt->execute();
$total_rows = $count_stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);
