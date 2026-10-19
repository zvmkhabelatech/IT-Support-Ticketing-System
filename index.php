<?php 
require 'config.php'; //[cite: 1]

// Handle New Ticket Submission directly on the dashboard
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_ticket') {
    $code = generateTicketCode(); //[cite: 1]
    $stmt = $pdo->prepare("INSERT INTO tickets (ticket_code, requester_name, department, category, priority, subject, description) VALUES (?,?,?,?,?,?,?)"); //[cite: 2]
    $stmt->execute([
        $code,
        $_POST['name'],
        $_POST['dept'],
        $_POST['category'],
        $_POST['priority'],
        $_POST['subject'],
        $_POST['desc']
    ]); //[cite: 2]
    $msg = "Ticket $code logged successfully!";
}

// Handle Status Updates directly from the table
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $stmt = $pdo->prepare("UPDATE tickets SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['ticket_id']]);
}

// Stats Queries
$stmt = $pdo->query("SELECT status, COUNT(*) as c FROM tickets GROUP BY status"); //[cite: 4]
$stats = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); //[cite: 4]
$total = array_sum($stats); //[cite: 4]

// Filtering
$statusFilter = $_GET['status'] ?? '';
$searchQuery = $_GET['search'] ?? '';


$sql = "SELECT * FROM tickets WHERE status != 'Closed'";
$params = [];

if ($statusFilter === 'Closed') {
    $sql = "SELECT * FROM tickets WHERE status = 'Closed'";
} elseif ($statusFilter) {
    $sql = "SELECT * FROM tickets WHERE status = ?";
    $params[] = $statusFilter;
}

if ($searchQuery) {
    $sql .= " AND (ticket_code LIKE ? OR requester_name LIKE ? OR subject LIKE ?)";
    $term = "%$searchQuery%";
    $params[] = $term;
    $params[] = $term;
    $params[] = $term;
}

$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tickets = $stmt->fetchAll(); //[cite: 7]
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maminza Primary IT Support Dashboard</title>
    <link rel="stylesheet" href="style.css"> <!--[cite: 2, 4, 7] -->
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Top Navigation Header -->
        <header class="dashboard-header">
            <div class="header-brand">
                <div class="logo-box">M</div>
                <div>
                    <h1>Maminza Primary IT Support</h1>
                    <div class="badges">
                        <span class="badge-pill active"><span class="dot green"></span> Live portal • 23 staff active</span>
                        <span class="badge-pill blue">PHP / MySQL Stack</span>
                        <span class="badge-pill outline">e-Cadre support</span>
                    </div>
                </div>
            </div>
            <div class="header-stats">
                <div class="stat-card">
                    <span class="label">TOTAL</span>
                    <span class="value"><?= $total ?></span>
                    <span class="subtext">tickets</span>
                </div>
                <div class="stat-card">
                    <span class="label"><span class="dot blue"></span> OPEN</span>
                    <span class="value"><?= $stats['Open'] ?? 0 ?></span>
                    <span class="subtext">needs triage</span>
                </div>
                <div class="stat-card">
                    <span class="label"><span class="dot purple"></span> IN PROGRESS</span>
                    <span class="value"><?= $stats['In Progress'] ?? 0 ?></span>
                    <span class="subtext">being fixed</span>
                </div>
                <div class="stat-card">
                    <span class="label"><span class="dot green"></span> RESOLVED</span>
                    <span class="value"><?= $stats['Resolved'] ?? 0 ?></span>
                    <span class="subtext">this week</span>
                </div>
            </div>
        </header>

        <!-- Main Layout Grid -->
        <main class="dashboard-grid">
            <!-- Left Column: Form & Case Study -->
            <aside class="left-column">
                <section class="card-panel">
                    <div class="panel-header">
                        <div>
                            <h2>New Support Request</h2>
                            <p class="subtitle">Log issue for e-Cadre triage</p>
                        </div>
                    </div>

                    <?php if($msg): ?>
                        <div class="alert success"><?= htmlspecialchars($msg) ?></div>
                    <?php endif; ?>

                    <form method="POST" class="ticket-form">
                        <input type="hidden" name="action" value="create_ticket">

                        <div class="form-group">
                            <label>REQUESTER NAME</label>
                            <input type="text" name="name" placeholder="e.g. T. Mabaso" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>DEPARTMENT</label>
                                <select name="dept" required>
                                    <option value="Admin">Admin</option>
                                    <option value="Grade 1">Grade 1</option>
                                    <option value="Grade 2">Grade 2</option>
                                    <option value="Grade 3">Grade 3</option>
                                    <option value="Grade 4">Grade 4</option>
                                    <option value="Grade 5">Grade 5</option>
                                    <option value="Grade 6">Grade 6</option>
                                    <option value="Grade 7">Grade 7</option>
                                    <option value="ICT Lab">ICT Lab</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>CATEGORY</label>
                                <select name="category" required>
                                    <option value="Hardware">Hardware</option>
                                    <option value="Software">Software</option>
                                    <option value="Network">Network</option>
                                    <option value="Email">Email</option>
                                    <option value="Printer">Printer</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>PRIORITY</label>
                            <div class="priority-selector">
                                <label><input type="radio" name="priority" value="Low"> <span>Low</span></label>
                                <label><input type="radio" name="priority" value="Medium" checked> <span>Medium</span></label>
                                <label><input type="radio" name="priority" value="High"> <span>High</span></label>
                                <label><input type="radio" name="priority" value="Critical"> <span>Critical</span></label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>SUBJECT</label>
                            <input type="text" name="subject" placeholder="Brief summary of the issue" required>
                        </div>

                        <div class="form-group">
                            <label>DESCRIPTION</label>
                            <textarea name="desc" rows="3" placeholder="What happened? Steps taken? How many learners affected?" required></textarea>
                        </div>

                        <button type="submit" class="btn primary-btn">+ Submit Ticket</button>
                    </form>
                </section>

                <!-- Case Study Card -->
                <section class="card-panel case-study">
                    <div class="panel-header">
                        <h3><span class="icon">c</span> Case Study • Maminza Primary</h3>
                        <span class="badge-pill outline">23 staff</span>
                    </div>

                    <div class="timeline-item problem">
                        <strong class="title red">PROBLEM</strong>
                        <p>IT requests tracked via WhatsApp groups → lost tickets, no accountability, duplicate reports, e-Cadre can't prioritize. ICT Lab downtime affects 23 learners per session.</p>
                    </div>

                    <div class="timeline-item solution">
                        <strong class="title blue">SOLUTION</strong>
                        <p>Lightweight portal: status tracking (Open → In Progress → Resolved), priority tagging, auto TCK codes, e-Cadre email hook, department filtering for Admin & 6 grade blocks.</p>
                    </div>

                    <div class="tech-stack-section">
                        <strong class="title gray">TECH STACK</strong>
                        <div class="chips">
                            <span>HTML</span><span>CSS</span><span>JavaScript</span><span>PHP 8.2</span><span>MySQL</span><span>PHPMailer</span>
                        </div>
                    </div>

                    <div class="callout-box">
                        <strong>Built for real-world constraints</strong>
                        <p>No complex dependencies. Works on school WiFi, low-spec admin PCs, mobile for teachers.</p>
                    </div>
                </section>
            </aside>

            <!-- Right Column: Tickets Table & Filtering -->
            <section class="right-column card-panel">
                <div class="table-header">
                    <div class="filter-chips">
                        <a href="index.php" class="chip <?= !$statusFilter ? 'active' : '' ?>">All</a>
                        <a href="?status=Open" class="chip <?= $statusFilter === 'Open' ? 'active' : '' ?>">Open <?= isset($stats['Open']) ? $stats['Open'] : 0 ?></a>
                        <a href="?status=In Progress" class="chip <?= $statusFilter === 'In Progress' ? 'active' : '' ?>">In Progress <?= isset($stats['In Progress']) ? $stats['In Progress'] : 0 ?></a>
                        <a href="?status=Resolved" class="chip <?= $statusFilter === 'Resolved' ? 'active' : '' ?>">Resolved <?= isset($stats['Resolved']) ? $stats['Resolved'] : 0 ?></a>
                    </div>

                    <form method="GET" class="search-form">
                        <?php if($statusFilter): ?>
                            <input type="hidden" name="status" value="<?= htmlspecialchars($statusFilter) ?>">
                        <?php endif; ?>
                        <input type="text" name="search" placeholder="Search code, staff, subject..." value="<?= htmlspecialchars($searchQuery) ?>">
                    </form>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>CODE</th>
                                <th>REQUESTER</th>
                                <th>SUBJECT</th>
                                <th>PRIORITY</th>
                                <th>STATUS</th>
                                <th>DATE</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($tickets)): ?>
                                <tr>
                                    <td colspan="7" class="text-center">No tickets found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($tickets as $t): ?>
                                    <tr>
                                        <td class="code-cell">
                                            <strong><?= htmlspecialchars($t['ticket_code']) ?></strong>
                                            <span class="time-ago"><?= date('M d', strtotime($t['created_at'])) ?></span>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($t['requester_name']) ?></strong>
                                            <div class="subtext"><?= htmlspecialchars($t['department']) ?> • <?= htmlspecialchars($t['category']) ?></div>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($t['subject']) ?></strong>
                                            <div class="subtext"><?= htmlspecialchars(substr($t['description'], 0, 40)) ?>...</div>
                                        </td>
                                        <td>
                                            <span class="priority-badge priority-<?= strtolower($t['priority']) ?>">
                                                • <?= htmlspecialchars($t['priority']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-<?= str_replace(' ', '-', strtolower($t['status'])) ?>">
                                                <?= htmlspecialchars($t['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('Y-m-d', strtotime($t['created_at'])) ?></td>
                                        <td>
                                            <form method="POST" style="display:inline-block;">
                                                <input type="hidden" name="action" value="update_status">
                                                <input type="hidden" name="ticket_id" value="<?= $t['id'] ?>">
                                                <select name="status" onchange="this.form.submit()" class="status-dropdown">
                                                    <option value="Open" <?= $t['status'] === 'Open' ? 'selected' : '' ?>>Open</option>
                                                    <option value="In Progress" <?= $t['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                                    <option value="Resolved" <?= $t['status'] === 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                                                    <option value="Closed" <?= $t['status'] === 'Closed' ? 'selected' : '' ?>>Closed</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>