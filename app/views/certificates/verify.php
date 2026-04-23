<?php
$companyName = 'Smatflix Technologies FZCO';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Certificate Verification — <?= htmlspecialchars($cert['candidate_name']) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg,#f5f3ec,#eae3d2); min-height:100vh; }
.verify-card { max-width: 720px; margin: 40px auto; background:#fff; border-radius:18px; box-shadow:0 20px 50px rgba(0,0,0,.08); overflow:hidden; }
.verify-head { background: linear-gradient(135deg,#1e3460,#2b4b87); color:#fff; padding:40px; text-align:center; }
.verify-head .check { width:70px; height:70px; border-radius:50%; background:#c5a054; color:#fff; font-size:32px; display:flex; align-items:center; justify-content:center; margin:0 auto 14px; }
.verify-head h1 { font-family:'Playfair Display',serif; font-size:26px; letter-spacing:2px; }
.verify-head p { opacity:.85; margin:0; }
.verify-body { padding:36px; }
.verify-row { display:flex; justify-content:space-between; padding:12px 0; border-bottom:1px solid #eee; }
.verify-row:last-child{border-bottom:0;}
.verify-row .k { color:#888; font-size:13px; }
.verify-row .v { font-weight:600; color:#1e3460; text-align:right; }
.badge-type { background:#c5a054; color:#fff; padding:4px 10px; border-radius:20px; font-size:12px; letter-spacing:1px; }
.footer-note { text-align:center; padding:18px; color:#888; font-size:12px; background:#fafaf7; }
</style>
</head>
<body>
<div class="verify-card">
    <div class="verify-head">
        <div class="check"><i class="fas fa-check"></i></div>
        <h1>CERTIFICATE VERIFIED</h1>
        <p>Issued by <?= htmlspecialchars($companyName) ?></p>
    </div>
    <div class="verify-body">
        <div class="verify-row"><span class="k">Certificate No.</span><span class="v"><?= htmlspecialchars($cert['certificate_no']) ?></span></div>
        <div class="verify-row"><span class="k">Type</span><span class="v"><span class="badge-type"><?= htmlspecialchars($cert['certificate_type']) ?></span></span></div>
        <div class="verify-row"><span class="k">Candidate Name</span><span class="v"><?= htmlspecialchars($cert['candidate_name']) ?></span></div>
        <?php if (!empty($cert['designation'])): ?>
        <div class="verify-row"><span class="k">Designation</span><span class="v"><?= htmlspecialchars($cert['designation']) ?></span></div>
        <?php endif; ?>
        <div class="verify-row"><span class="k">Subject / Program</span><span class="v"><?= htmlspecialchars($cert['subject']) ?></span></div>
        <?php if ($cert['duration_from'] && $cert['duration_to']): ?>
        <div class="verify-row"><span class="k">Duration</span><span class="v"><?= date('d M Y', strtotime($cert['duration_from'])) ?> &ndash; <?= date('d M Y', strtotime($cert['duration_to'])) ?></span></div>
        <?php endif; ?>
        <div class="verify-row"><span class="k">Issue Date</span><span class="v"><?= date('d M Y', strtotime($cert['issue_date'])) ?></span></div>
        <?php if (!empty($cert['issued_by'])): ?>
        <div class="verify-row"><span class="k">Issued By</span><span class="v"><?= htmlspecialchars($cert['issued_by']) ?><?php if (!empty($cert['issued_by_title'])): ?> <small class="text-muted d-block"><?= htmlspecialchars($cert['issued_by_title']) ?></small><?php endif; ?></span></div>
        <?php endif; ?>
    </div>
    <div class="footer-note">
        This verification page confirms the authenticity of the above certificate. For further inquiries contact <?= htmlspecialchars($companyName) ?>.
    </div>
</div>
</body>
</html>
