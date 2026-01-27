<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: adminsignin.php');
    exit();
}

$perPage = 20;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

$total = 0;
if ($res = $conn->query('SELECT COUNT(*) AS c FROM contact_submissions')) {
    if ($row = $res->fetch_assoc()) { $total = (int)$row['c']; }
}
$totalPages = max(1, (int)ceil($total / $perPage));

$stmt = $conn->prepare('SELECT id, name, email, subject, message, submission_time FROM contact_submissions ORDER BY submission_time DESC LIMIT ? OFFSET ?');
$stmt->bind_param('ii', $perPage, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contact Messages</title>
  <style>
    :root {
      --primary: #0a773d;
      --primary-dark: #085c30;
      --bg: #f6f8fb;
      --text: #1f2937;
      --muted: #6b7280;
      --border: #e5e7eb;
      --card: #ffffff;
    }
    * { box-sizing: border-box; }
    body { font-family: 'Segoe UI', Tahoma, Arial, sans-serif; margin: 0; background: var(--bg); color: var(--text); }

    .page-wrap { max-width: 1100px; margin: 32px auto; padding: 0 16px; }
    .top-actions { display:flex; gap:12px; align-items:center; margin-bottom:16px; }
    .top-actions a { color: var(--primary); text-decoration: none; font-weight: 600; }
    .top-actions a:hover { text-decoration: underline; }
    .top-actions button { background: var(--primary); color:#fff; border: none; padding: 10px 14px; border-radius: 8px; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,.08); }
    .top-actions button:hover { background: var(--primary-dark); }

    .card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,.06); overflow: hidden; }
    .card-header { display:flex; align-items:center; justify-content: space-between; padding: 16px 20px; border-bottom:1px solid var(--border); background: linear-gradient(0deg, #fff, #fafafa); }
    .title { margin: 0; font-size: 20px; font-weight: 700; }
    .count-badge { background: var(--primary); color:#fff; padding: 6px 10px; border-radius: 999px; font-size: 13px; font-weight: 600; }

    .table-wrap { width: 100%; overflow: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 800px; }
    th, td { border-bottom: 1px solid var(--border); padding: 12px 12px; vertical-align: top; }
    th { background: #fafafa; text-align: left; font-size: 13px; color: var(--muted); letter-spacing: .02em; }
    tbody tr:hover { background: #f9fafb; }
    tbody tr:nth-child(odd) { background: #fcfcfd; }

    .message { white-space: pre-wrap; line-height: 1.5; }
    .email { color: #2563eb; text-decoration: none; }
    .email:hover { text-decoration: underline; }

    .pagination { display:flex; gap:8px; padding: 14px 16px; border-top: 1px solid var(--border); background: #fff; }
    .pagination a, .pagination span { padding: 8px 12px; border:1px solid var(--border); border-radius: 8px; text-decoration:none; color: var(--text); font-size: 13px; }
    .pagination .active { background: var(--primary); color:#fff; border-color: var(--primary); }

    @media (max-width: 768px) {
      .card-header { flex-direction: column; align-items: flex-start; gap:8px; }
      .table-wrap { overflow-x: auto; }
      .title { font-size: 18px; }
    }

    @media print {
      body { background: #fff; }
      .no-print, .pagination { display:none !important; }
      .card { box-shadow: none; border: none; }
      th, td { border: 1px solid #ddd; }
    }
  </style>
</head>
<body>
  <div class="page-wrap">
    <div class="top-actions no-print">
      <a href="adminPage.php">← Back to Admin</a>
      <button onclick="window.print()">Print</button>
    </div>

    <div class="card">
      <div class="card-header">
        <h1 class="title">Contact Messages</h1>
        <span class="count-badge">Total: <?= htmlspecialchars($total); ?></span>
      </div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Subject</th>
              <th>Message</th>
              <th>Submitted</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= (int)$row['id']; ?></td>
                  <td><?= htmlspecialchars($row['name']); ?></td>
                  <td><a class="email" href="mailto:<?= htmlspecialchars($row['email']); ?>"><?= htmlspecialchars($row['email']); ?></a></td>
                  <td><?= htmlspecialchars($row['subject']); ?></td>
                  <td class="message"><?= nl2br(htmlspecialchars($row['message'])); ?></td>
                  <td><?= htmlspecialchars($row['submission_time']); ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="6">No messages found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <div class="pagination no-print" aria-label="Pagination">
        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
          <?php if ($p === $page): ?>
            <span class="active" aria-current="page">Page <?= $p; ?></span>
          <?php else: ?>
            <a href="?page=<?= $p; ?>">Page <?= $p; ?></a>
          <?php endif; ?>
        <?php endfor; ?>
      </div>
    </div>
  </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
