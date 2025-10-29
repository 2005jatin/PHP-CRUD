<?php
include("insert.php");
include("SORT.PHP");
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>PHP CRUD Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar-brand {
            font-weight: bold;
        }

        table th,
        table td {
            vertical-align: middle !important;
        }
    </style>
</head>

<body>

    <!-- 🟢 Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">PHP CRUD</a>
        </div>
    </nav>

    <div class="container my-5">
        <!-- 🟢 Message -->
        <?php echo $message; ?>

        <!-- 🟢 Add / Update Form -->
        <div class="card shadow p-4">
            <h4 class="mb-3 text-primary">
                <?php echo $editData ? "✏️ Edit User" : "➕ Add User"; ?>
            </h4>

            <form method="POST" action="">
                <?php if (!empty($editData)): ?>
                    <input type="hidden" name="original_email" value="<?php echo htmlspecialchars($editData['uemail']); ?>">
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">User Name</label>
                        <input type="text" class="form-control" name="uname"
                            value="<?php echo htmlspecialchars($editData['uname'] ?? ''); ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="uemail"
                            value="<?php echo htmlspecialchars($editData['uemail'] ?? ''); ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mobile Number</label>
                        <input type="text" class="form-control" name="unum"
                            value="<?php echo htmlspecialchars($editData['unum'] ?? ''); ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="upsw"
                            value="<?php echo htmlspecialchars($editData['upsw'] ?? ''); ?>">
                    </div>
                </div>

                <form method="POST" action="">

                    <button type="submit" name="save" class="btn btn-primary">
                        <?php echo $editData ? "Update User" : "Add User"; ?>
                    </button>
                </form>
                <?php if (!empty($editData)): ?>
                    <a href="index.php" class="btn btn-secondary ms-2">Cancel</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- 🟡 Users Table -->
        <h3 class="mt-5 text-center">👥 User List</h3>
        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Password</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['uname']); ?></td>
                            <td><?php echo htmlspecialchars($row['uemail']); ?></td>
                            <td><?php echo htmlspecialchars($row['unum']); ?></td>
                            <td><?php echo htmlspecialchars($row['upsw']); ?></td>
                            <td>
                                <a href="?edit=<?php echo urlencode($row['uemail']); ?>"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <a href="?delete=<?php echo urlencode($row['uemail']); ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No records found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>


</html>
