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
$allowed_columns = ["id", "uname", "uemail", "unum", "upsw"];
$sort_column = isset($_GET['sort']) && in_array($_GET['sort'], $allowed_columns) ? $_GET['sort'] : "id";
$sort_order = (isset($_GET['order']) && $_GET['order'] == "desc") ? "DESC" : "ASC";
$next_order = $sort_order === "ASC" ? "desc" : "asc";

// =======================
// 📄 PAGINATION
// =======================
$limit = 5; // records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// =======================
// 🟢 BUILD QUERY WITH SEARCH + SORT + PAGINATION
// =======================
$where = "";
$params = [];
$types = "";

if (!empty($search)) {
    // ✅ Search by ID, Name, Email, or Mobile
    $where = "WHERE id LIKE ? OR uname LIKE ? OR uemail LIKE ? OR unum LIKE ?";
    $param = "%$search%";
    $params = [$param, $param, $param, $param];
    $types = "ssss";
}

$sql = "SELECT * FROM users $where ORDER BY $sort_column $sort_order LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $params[] = $limit;
    $params[] = $offset;
    $types .= "ii";
    $stmt->bind_param($types, ...$params);
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
    $count_stmt->bind_param("ssss", $param, $param, $param, $param);
}
$count_stmt->execute();
$total_rows = $count_stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);
