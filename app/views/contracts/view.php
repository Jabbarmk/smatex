<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<style>
@media print {
    .no-print { display: none !important; }
    body, .main-content { background: white !important; padding: 0 !important; margin: 0 !important; }
    .sidebar, nav { display: none !important; }
    .contract-wrapper { box-shadow: none !important; border: none !important; max-width: 100% !important; }
}
@media screen {
    .contract-wrapper { max-width: 900px; margin: 0 auto; }
}
.contract-wrapper { font-family: 'Inter', sans-serif; font-size: .92rem; line-height: 1.7; }
.ct-accent       { color: #1e3a5f; }
.ct-accent-line  { border-top: 3px solid #1e3a5f; }
.ct-party-box    { border: 1px solid #dde3ec; border-radius: 8px; padding: 1.2rem; background: #f8fafc; }
.ct-section-title { font-size: .7rem; letter-spacing: 1.5px; text-transform: uppercase; color: #8a9bb5; font-weight: 700; margin-bottom: .5rem; }
.ct-sig-line     { border-top: 1px solid #555; width: 200px; margin-top: 56px; padding-top: 5px; text-align: center; font-size: .78rem; color: #555; }
.ct-footer       { border-top: 1px solid #ddd; padding-top: .75rem; text-align: center; color: #aaa; font-size: .73rem; }
.ct-contents     { background: #fff; border: 1px solid #eaeef5; border-radius: 6px; padding: 1.5rem; color: #333; }
.status-active     { background:#d1fae5; color:#065f46; }
.status-draft      { background:#fef9c3; color:#713f12; }
.status-expired    { background:#fee2e2; color:#991b1b; }
.status-terminated { background:#e5e7eb; color:#374151; }
</style>

<!-- Action Bar -->
<div class="d-flex justify-content-center gap-2 mb-4 no-print flex-wrap">
    <button onclick="window.print()" class="btn btn-outline-danger">
        <i class="fas fa-file-pdf me-1"></i> Print / Save PDF
    </button>
    <button onclick="downloadPDF()" class="btn btn-primary">
        <i class="fas fa-download me-1"></i> Download PDF
    </button>
    <a href="<?= BASE_URL ?>contracts/edit/<?= $contract['id'] ?>" class="btn btn-warning">
        <i class="fas fa-edit me-1"></i> Edit
    </a>
    <a href="<?= BASE_URL ?>contracts" class="btn btn-light">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<?php
$companyName    = $settings['company_name']    ?? 'Company';
$companyAddress = $settings['company_address'] ?? '';
$companyEmail   = $settings['company_email']   ?? '';
$companyPhone   = $settings['company_phone']   ?? '';
$companyWebsite = $settings['company_website'] ?? '';
$companyTrn     = $settings['company_trn']     ?? '';
$currency       = $settings['currency_symbol'] ?? 'AED';

$statusClass = match($contract['status']) {
    'Active'     => 'status-active',
    'Expired'    => 'status-expired',
    'Terminated' => 'status-terminated',
    default      => 'status-draft',
};
?>

<!-- Contract Document -->
<div class="contract-wrapper bg-white p-4 p-md-5 rounded shadow-sm" id="contract-content">

    <!-- Header -->
    <div class="row align-items-center mb-3">
        <div class="col-md-4 d-flex align-items-center gap-3">
            <?php if (!empty($settings['company_logo'])): ?>
                <img src="<?= BASE_URL ?>public/uploads/<?= $settings['company_logo'] ?>" alt="Logo" style="max-height:65px;">
            <?php endif; ?>
            <div>
                <h4 class="fw-bold mb-0 ct-accent"><?= htmlspecialchars($companyName) ?></h4>
                <?php if ($companyTrn): ?>
                    <small class="text-muted fw-semibold">TRN: <?= htmlspecialchars($companyTrn) ?></small><br>
                <?php endif; ?>
                <small class="text-muted"><?= nl2br(htmlspecialchars($companyAddress)) ?></small>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <img src="<?= BASE_URL ?>public/dso2.png" alt="DSO" style="height:70px;width:auto;display:block;margin:0 auto;">
        </div>
        <div class="col-md-4 text-end">
            <h2 class="fw-bold text-uppercase mb-1 ct-accent">CONTRACT</h2>
            <h5 class="fw-bold mb-1"><?= htmlspecialchars($contract['contract_no']) ?></h5>
            <span class="badge <?= $statusClass ?> px-3 py-1 fs-6"><?= $contract['status'] ?></span>
        </div>
    </div>

    <div class="ct-accent-line mb-4"></div>

    <!-- Contract Meta -->
    <div class="row mb-4 g-3">
        <div class="col-sm-6 col-md-3">
            <div class="ct-section-title">Title</div>
            <div class="fw-semibold"><?= htmlspecialchars($contract['title']) ?></div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="ct-section-title">Type</div>
            <div><?= htmlspecialchars($contract['contract_type']) ?></div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="ct-section-title">Start Date</div>
            <div><?= $contract['start_date'] ? date('d M Y', strtotime($contract['start_date'])) : 'â€”' ?></div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="ct-section-title">End Date</div>
            <div><?= $contract['end_date'] ? date('d M Y', strtotime($contract['end_date'])) : 'â€”' ?></div>
        </div>
        <?php if ($contract['value'] > 0): ?>
        <div class="col-sm-6 col-md-3">
            <div class="ct-section-title">Contract Value</div>
            <div class="fw-bold"><?= $currency ?> <?= formatMoney($contract['value']) ?></div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Parties -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="ct-section-title">First Party</div>
            <div class="ct-party-box">
                <h6 class="fw-bold mb-1"><?= htmlspecialchars($contract['first_party_name']) ?: 'â€”' ?></h6>
                <?php if ($contract['first_party_representative']): ?>
                    <div class="text-muted small">Rep: <strong><?= htmlspecialchars($contract['first_party_representative']) ?></strong>
                    <?php if ($contract['first_party_designation']): ?>
                        &mdash; <?= htmlspecialchars($contract['first_party_designation']) ?>
                    <?php endif; ?></div>
                <?php endif; ?>
                <?php if ($contract['first_party_address']): ?>
                    <div class="text-muted small mt-1"><i class="fas fa-map-marker-alt me-1"></i><?= nl2br(htmlspecialchars($contract['first_party_address'])) ?></div>
                <?php endif; ?>
                <?php if ($contract['first_party_phone']): ?>
                    <div class="text-muted small"><i class="fas fa-phone me-1"></i><?= htmlspecialchars($contract['first_party_phone']) ?></div>
                <?php endif; ?>
                <?php if ($contract['first_party_email']): ?>
                    <div class="text-muted small"><i class="fas fa-envelope me-1"></i><?= htmlspecialchars($contract['first_party_email']) ?></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="ct-section-title">Second Party</div>
            <div class="ct-party-box">
                <h6 class="fw-bold mb-1"><?= htmlspecialchars($contract['second_party_name']) ?: 'â€”' ?></h6>
                <?php if ($contract['second_party_representative']): ?>
                    <div class="text-muted small">Rep: <strong><?= htmlspecialchars($contract['second_party_representative']) ?></strong>
                    <?php if ($contract['second_party_designation']): ?>
                        &mdash; <?= htmlspecialchars($contract['second_party_designation']) ?>
                    <?php endif; ?></div>
                <?php endif; ?>
                <?php if ($contract['second_party_address']): ?>
                    <div class="text-muted small mt-1"><i class="fas fa-map-marker-alt me-1"></i><?= nl2br(htmlspecialchars($contract['second_party_address'])) ?></div>
                <?php endif; ?>
                <?php if ($contract['second_party_phone']): ?>
                    <div class="text-muted small"><i class="fas fa-phone me-1"></i><?= htmlspecialchars($contract['second_party_phone']) ?></div>
                <?php endif; ?>
                <?php if ($contract['second_party_email']): ?>
                    <div class="text-muted small"><i class="fas fa-envelope me-1"></i><?= htmlspecialchars($contract['second_party_email']) ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Contract Contents -->
    <?php if ($contract['contents']): ?>
    <div class="mb-4">
        <div class="ct-section-title">Contract Contents</div>
        <div class="ct-contents"><?= $contract['contents'] ?></div>
    </div>
    <?php endif; ?>

    <!-- Terms & Conditions -->
    <?php if ($contract['terms_conditions']): ?>
    <div class="mb-4">
        <div class="ct-section-title">Terms &amp; Conditions</div>
        <div class="ct-contents" style="font-size:.85rem;"><?= $contract['terms_conditions'] ?></div>
    </div>
    <?php endif; ?>

    <!-- Signatures -->
    <div class="row mt-5 mb-4">
        <div class="col-4 text-center">
            <div class="ct-sig-line d-inline-block">
                First Party<br>
                <small class="text-muted"><?= htmlspecialchars($contract['first_party_name']) ?></small>
            </div>
        </div>
        <div class="col-4 text-center">
            <div class="ct-sig-line d-inline-block">Witness / Notary</div>
        </div>
        <div class="col-4 text-center">
            <div class="ct-sig-line d-inline-block">
                Second Party<br>
                <small class="text-muted"><?= htmlspecialchars($contract['second_party_name']) ?></small>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="ct-footer mt-4">
        <?= htmlspecialchars($companyName) ?>
        <?php if ($companyAddress): ?> | <?= htmlspecialchars(str_replace("\n", ", ", $companyAddress)) ?><?php endif; ?>
        <br>
        <?php if ($companyTrn): ?>TRN: <?= htmlspecialchars($companyTrn) ?> | <?php endif; ?>
        <?= htmlspecialchars($companyEmail) ?>
        <?php if ($companyPhone): ?> | <?= htmlspecialchars($companyPhone) ?><?php endif; ?>
        <?php if ($companyWebsite): ?> | <?= htmlspecialchars($companyWebsite) ?><?php endif; ?>
    </div>

</div>

<script>
window.jsPDF = window.jspdf.jsPDF;
function downloadPDF() {
    const el   = document.getElementById('contract-content');
    const btns = document.querySelectorAll('.no-print');
    btns.forEach(b => b.style.display = 'none');
    html2canvas(el, { scale: 1.5, useCORS: true, backgroundColor: '#ffffff' }).then(canvas => {
        const imgData = canvas.toDataURL('image/jpeg', 0.85);
        const pdf     = new jsPDF('p', 'mm', 'a4');
        const w       = pdf.internal.pageSize.getWidth();
        const h       = (canvas.height * w) / canvas.width;
        const ph      = pdf.internal.pageSize.getHeight();
        if (h <= ph) {
            pdf.addImage(imgData, 'JPEG', 0, 0, w, h);
        } else {
            let pos = 0, rem = h;
            while (rem > 0) {
                pdf.addImage(imgData, 'JPEG', 0, pos, w, h);
                rem -= ph; pos -= ph;
                if (rem > 0) pdf.addPage();
            }
        }
        pdf.save('Contract_<?= $contract['contract_no'] ?>.pdf');
        btns.forEach(b => b.style.display = '');
    });
}
</script>

