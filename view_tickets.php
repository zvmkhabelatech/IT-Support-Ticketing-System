<?php require 'config.php';
$filter=$_GET['status']??''; 
$sql="SELECT * FROM tickets"; 
if($filter) $sql.=" WHERE status=".$pdo->quote($filter); 
$sql.=" ORDER BY created_at DESC"; 
$tickets=$pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"><meta name="viewport" content="width=device-width">
        <title>All Tickets</title><link rel="stylesheet" href="style.css">
    </head>

    <body>
        <div class="container"> 
            <h2>All Tickets - <?= count($tickets) ?></h2>
            <div class="filters">
                <a href="view_tickets.php" class="chip">All</a>
                <a href="?status=Open" class="chip">Open</a>
                <a href="?status=In Progress" class="chip">In Progress</a>
                <a href="?status=Resolved" class="chip">Resolved</a>
            </div>

        <table>
            <tr>
                <th>Code</th>
                <th>Requester</th>
                <th>Subject</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        <?php foreach($tickets as $t): ?>
            <tr>
            <td><?= $t['ticket_code'] ?></td>
            <td><?= htmlspecialchars($t['requester_name']) ?>
            <br><small><?= $t['department'] ?></small></td>
        <td>
            <strong><?= htmlspecialchars($t['subject']) ?></strong>
            <br><small><?= substr($t['description'],0,50) ?>...</small>
        </td>
        <td>
            <span class="badge priority-<?= strtolower($t['priority']) ?>"><?= $t['priority'] ?></span>
        </td>
        <td>
            <span class="badge status-<?= str_replace(' ','-',strtolower($t['status'])) ?>"><?= $t['status'] ?></span>
        </td>
        <td>
            <?= date('M d', strtotime($t['created_at'])) ?>
        </td>
        </tr>
        <?php endforeach; ?>
        </table>
        <a href="index.php" class="btn">Back Home</a>
        </div>
    </body>
</html>
