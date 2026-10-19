<?php require 'config.php';
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $code=generateTicketCode();
 $stmt=$pdo->prepare("INSERT INTO tickets (ticket_code, requester_name, department, category, priority, subject, description) VALUES (?,?,?,?,?,?,?)");
 $stmt->execute([$code,$_POST['name'],$_POST['dept'],$_POST['category'],$_POST['priority'],$_POST['subject'],$_POST['desc']]);
 $msg="Ticket $code created! Email notification queued.";
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"><meta name="viewport" content="width=device-width">
        <title>New Ticket</title><link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container"><h2>Create Support Ticket</h2>
            <?php if($msg): ?><div class="alert success"><?= $msg ?></div><?php endif; ?>

            <form method="POST" class="form">
                <input name="name" placeholder="Your Name (e.g. Mrs Nkosi)" required>
                <input name="dept" placeholder="Department / Grade" required>

                <select name="category" required>
                    <option value="">Category</option>
                    <option>Hardware</option>
                    <option>Software</option>
                    <option>Network</option>
                    <option>Email</option>
                    <option>Printer</option>
                    <option>Other</option>
                </select>

                <select name="priority" required>
                    <option>Low</option>
                    <option selected>Medium</option>
                    <option>High</option>
                    <option>Critical</option>
                </select>

                <input name="subject" placeholder="Subject" required>
                <textarea name="desc" placeholder="Describe the issue..." rows="4" required>
                </textarea>
                <button class="btn primary">Submit Ticket</button>
                <a href="index.php" class="btn">Back</a>
            </form>
        </div>
    </body>
</html>
