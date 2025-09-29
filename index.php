<?php
session_start();

// ---- Data Store ----
if (!isset($_SESSION['tasks']) || !is_array($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [
        1 => ['id' => 1, 'title' => 'Sample Task', 'completed' => false]
    ];
    $_SESSION['nextId'] = 2;
}

// ---- Handle Form Submit ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Create
    if ($action === 'create') {
        $title = trim($_POST['title']);
        if ($title !== "") {
            $id = $_SESSION['nextId']++;
            $_SESSION['tasks'][$id] = ['id' => $id, 'title' => $title, 'completed' => false];
        }
    }

    // Update
    if ($action === 'update') {
        $id = intval($_POST['id']);
        if (isset($_SESSION['tasks'][$id])) {
            $title = trim($_POST['title']);
            $_SESSION['tasks'][$id]['title'] = $title;
        }
    }

    // Delete
    if ($action === 'delete') {
        $id = intval($_POST['id']);
        if (isset($_SESSION['tasks'][$id])) {
            unset($_SESSION['tasks'][$id]);
        }
    }
}

?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>PHP CRUD</title>
    <style>
        body {
            font-family: Arial;
            margin: 20px;
        }

        table {
            width: 70%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        input[type=text] {
            padding: 5px;
        }

        button {
            padding: 5px 10px;
            margin: 2px;
            cursor: pointer;
        }

        form {
            display: inline;
        }
    </style>
</head>

<body>

    <h1>CRUD Operation with PHP</h1>

    <!-- Create Form -->
    <form method="post">
        <input type="hidden" name="action" value="create">
        <input type="text" name="title" placeholder="Enter new task" required>
        <button type="submit">Add Task</button>
    </form>

    <!-- Task List -->
    <?php if (!empty($_SESSION['tasks'])): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach ($_SESSION['tasks'] as $task): ?>
                <?php if (is_array($task)): ?>
                    <tr>
                        <td><?= $task['id'] ?></td>
                        <td><?= htmlspecialchars($task['title']) ?></td>
                        <td>
                            <!-- Update Form -->
                            <form method="post">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?= $task['id'] ?>">
                                <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                        <td>
                            <!-- Delete Form -->
                            <form method="post">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $task['id'] ?>">
                                <button type="submit" style="background:red;color:white;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

</body>

</html>