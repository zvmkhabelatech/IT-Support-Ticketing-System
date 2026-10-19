# 🛠️ IT Support Ticketing System

**Problem:** At Maminza Primary School, IT requests for 23 staff were tracked via WhatsApp — leading to lost tickets and no accountability.

**Solution:** Built a lightweight PHP ticketing portal as e-Cadre/ICT Assistant with ticket codes, priority tagging, status workflow, and email-ready notifications.

**Live Demo:** [View Interactive Demo](https://zvmkhabela.github.io/it-support-ticketing-system-demo/)

### 📸 Screenshots
- Dashboard with stats: Total, Open, In Progress, Resolved
- Create Ticket form with priority
- Filterable table with badges

### ✨ Features
- Unique Ticket Code: `TCK-YYYY-XXXX`
- Priority: Low / Medium / High / Critical (color-coded)
- Status Workflow: Open → In Progress → Resolved → Closed
- Department filter (Admin, Foundation Phase, Grade 4-7, ICT Lab)
- Stats dashboard
- Responsive, mobile-ready
- Ready for PHPMailer email extension

### 🛠️ Tech Stack
- HTML5 / CSS3 / JavaScript
- PHP 8 (PDO)
- MySQL

### 🚀 How to Run Locally
```bash
1. Import database.sql into MySQL (creates itsupport_db)
2. Update config.php with your DB credentials
3. Place in htdocs (XAMPP) or www (WAMP)
4. Visit http://localhost/it-support-ticketing-system/
