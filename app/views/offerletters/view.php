<?php
$companyName    = $settings['company_name']    ?? 'Company';
$companyAddress = $settings['company_address'] ?? '';
$companyEmail   = $settings['company_email']   ?? '';
$companyPhone   = $settings['company_phone']   ?? '';
$companyWebsite = $settings['company_website'] ?? '';
$companyTrn     = $settings['company_trn']     ?? '';

$gross = $offer['basic_salary'] + $offer['housing_allowance'] + $offer['transport_allowance'] + $offer['other_allowance'];

$logoPath  = !empty($settings['company_logo']) ? BASE_URL . 'public/uploads/' . $settings['company_logo'] : '';
$signPath  = BASE_URL . 'assets/sign.png';
$stampPath = BASE_URL . 'assets/stamp.png';
?>
<?php if (isset($_GET['success'])): ?>
<div class="alert alert-success alert-dismissible fade show no-print" role="alert">
    Offer letter updated successfully.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<style>
@media print {
    .no-print { display: none !important; }
    body, .main-content { background: white !important; padding: 0 !important; margin: 0 !important; }
    .sidebar, nav { display: none !important; }
    .ol-wrapper { box-shadow: none !important; border: none !important; max-width: 100% !important; }
    @page { size: A4 portrait; margin: 15mm; }
}
@media screen {
    .ol-wrapper { max-width: 860px; margin: 0 auto; }
}
.ol-wrapper {
    font-family: 'Inter', sans-serif;
    font-size: .9rem;
    color: #222;
    line-height: 1.7;
}
.ol-divider { border-top: 3px solid #1e3460; margin: 0; }
.ol-divider-thin { border-top: 1px solid #ddd; }
.ol-table td, .ol-table th { padding: .5rem .9rem; font-size: .875rem; }
.ol-table thead { background: #1e3460; color: #fff; font-size: .75rem; text-transform: uppercase; letter-spacing: .5px; }
.ol-table tbody tr:last-child { border-top: 2px solid #1e3460; font-weight: 700; background: #f0f4ff; }
.ol-sig-line { border-top: 1px solid #555; width: 180px; margin-top: 50px; padding-top: 4px; font-size: .8rem; color: #555; text-align: center; }
.ol-terms li { margin-bottom: .35rem; }
</style>

<!-- Action Bar -->
<div class="d-flex justify-content-center gap-2 mb-4 no-print">
    <button onclick="window.print()" class="btn btn-outline-danger"><i class="fas fa-file-pdf me-1"></i> Print / Save PDF</button>
    <button onclick="downloadPDF()" class="btn btn-primary"><i class="fas fa-download me-1"></i> Download PDF</button>
    <a href="<?= BASE_URL ?>offerletters/edit/<?= $offer['id'] ?>" class="btn btn-outline-secondary"><i class="fas fa-edit me-1"></i> Edit</a>
    <a href="<?= BASE_URL ?>offerletters/create" class="btn btn-success"><i class="fas fa-plus me-1"></i> New Offer</a>
    <a href="<?= BASE_URL ?>offerletters" class="btn btn-light"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<!-- Offer Letter Document -->
<div class="ol-wrapper bg-white p-4 p-md-5 rounded shadow-sm" id="offer-content">

    <!-- LETTERHEAD -->
    <div class="row align-items-center mb-3">
        <div class="col-4 d-flex align-items-center gap-3">
            <?php if ($logoPath): ?>
                <img src="<?= $logoPath ?>" alt="Logo" style="max-height:65px;">
            <?php endif; ?>
            <div>
                <h4 class="fw-bold mb-0"><?= htmlspecialchars($companyName) ?></h4>
                <?php if ($companyTrn): ?><small class="text-muted">TRN: <?= htmlspecialchars($companyTrn) ?></small><br><?php endif; ?>
                <small class="text-muted"><?= nl2br(htmlspecialchars($companyAddress)) ?></small>
            </div>
        </div>
        <div class="col-4 text-center">
            <img src="<?= BASE_URL ?>public/dso2.png" alt="DSO" style="height:70px;width:auto;display:block;margin:0 auto;">
        </div>
        <div class="col-4 text-end">
            <h3 class="fw-bold text-uppercase mb-1" style="color:#1e3460;letter-spacing:1px;">OFFER LETTER</h3>
            <div class="fw-bold" style="color:#1e3460;"><?= htmlspecialchars($offer['offer_no']) ?></div>
            <small class="text-muted">Date: <?= date('d M Y', strtotime($offer['offer_date'])) ?></small>
        </div>
    </div>

    <div class="ol-divider mb-4"></div>

    <!-- ADDRESSED TO -->
    <p class="mb-1"><strong>To,</strong></p>
    <p class="mb-0 fw-bold fs-5"><?= htmlspecialchars($offer['candidate_name']) ?></p>
    <?php if ($offer['nationality']): ?>
    <p class="mb-3 text-muted">Nationality: <?= htmlspecialchars($offer['nationality']) ?></p>
    <?php else: ?><div class="mb-3"></div><?php endif; ?>

    <!-- SALUTATION -->
    <p>Dear <strong><?= htmlspecialchars($offer['candidate_name']) ?></strong>,</p>

    <p>
        We are pleased to offer you the position of <strong><?= htmlspecialchars($offer['designation'] ?: 'Team Member') ?></strong>
        <?php if ($offer['department']): ?> in the <strong><?= htmlspecialchars($offer['department']) ?></strong> department<?php endif; ?>
        at <strong><?= htmlspecialchars($companyName) ?></strong>.
        <?php if ($offer['joining_date']): ?>
        We would like you to join us on <strong><?= date('d M Y', strtotime($offer['joining_date'])) ?></strong>.
        <?php endif; ?>
    </p>
    <p>This letter outlines the terms and conditions of your employment. Please review the details below carefully.</p>

    <div class="ol-divider-thin my-4"></div>

    <!-- POSITION & SALARY DETAILS -->
    <div class="row mb-4">
        <div class="col-md-5 mb-3 mb-md-0">
            <div class="p-3 rounded h-100" style="background:#f8f9fc;border:1px solid #e0e4ef;">
                <p class="fw-bold text-uppercase small text-muted mb-2">Position Details</p>
                <table class="table table-sm table-borderless mb-0" style="font-size:.875rem;">
                    <tr><td class="text-muted ps-0 fw-semibold" style="width:50%">Designation:</td><td class="pe-0"><?= htmlspecialchars($offer['designation']) ?></td></tr>
                    <tr><td class="text-muted ps-0 fw-semibold">Department:</td><td class="pe-0"><?= htmlspecialchars($offer['department']) ?: 'â€”' ?></td></tr>
                    <?php if ($offer['joining_date']): ?>
                    <tr><td class="text-muted ps-0 fw-semibold">Joining Date:</td><td class="pe-0"><?= date('d M Y', strtotime($offer['joining_date'])) ?></td></tr>
                    <?php endif; ?>
                    <tr><td class="text-muted ps-0 fw-semibold">Probation:</td><td class="pe-0"><?= htmlspecialchars($offer['probation_period']) ?></td></tr>
                </table>
            </div>
        </div>
        <div class="col-md-7">
            <p class="fw-bold text-uppercase small text-muted mb-2">Compensation Package (Monthly)</p>
            <table class="table ol-table mb-0" style="border:1px solid #eee;">
                <thead>
                    <tr>
                        <th>Component</th>
                        <th class="text-end">Amount (AED)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Basic Salary</td><td class="text-end"><?= formatMoney($offer['basic_salary']) ?></td></tr>
                    <?php if ($offer['housing_allowance'] > 0): ?>
                    <tr><td>Housing Allowance</td><td class="text-end"><?= formatMoney($offer['housing_allowance']) ?></td></tr>
                    <?php endif; ?>
                    <?php if ($offer['transport_allowance'] > 0): ?>
                    <tr><td>Transport Allowance</td><td class="text-end"><?= formatMoney($offer['transport_allowance']) ?></td></tr>
                    <?php endif; ?>
                    <?php if ($offer['other_allowance'] > 0): ?>
                    <tr><td>Other Allowance</td><td class="text-end"><?= formatMoney($offer['other_allowance']) ?></td></tr>
                    <?php endif; ?>
                    <tr><td>Total Monthly Package</td><td class="text-end"><?= formatMoney($gross) ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TERMS & CONDITIONS -->
    <div class="mb-4">
        <p class="fw-bold mb-2" style="color:#1e3460;">Terms & Conditions</p>
        <ul class="ol-terms">
            <li><strong>Working Hours:</strong> <?= htmlspecialchars($offer['working_hours']) ?>.</li>
            <li><strong>Annual Leave:</strong> <?= htmlspecialchars($offer['annual_leave']) ?>.</li>
            <li><strong>Notice Period:</strong> Either party may terminate the employment by giving <?= htmlspecialchars($offer['notice_period']) ?> written notice.</li>
            <li><strong>Probation Period:</strong> Your employment will be subject to a probation period of <?= htmlspecialchars($offer['probation_period']) ?>, during which either party may terminate with shorter notice as per UAE Labour Law.</li>
            <li>This offer is subject to the successful completion of all required documentation and background verification.</li>
            <li>Your employment shall be governed by UAE Labour Law and the Company's internal policies and procedures.</li>
        </ul>
    </div>

    <?php if (!empty($offer['notes'])): ?>
    <div class="mb-4 p-3 rounded" style="background:#fffbf0;border:1px solid #ffe08a;">
        <strong>Note:</strong> <?= nl2br(htmlspecialchars($offer['notes'])) ?>
    </div>
    <?php endif; ?>

    <!-- ACCEPTANCE -->
    <p class="mb-4">
        We look forward to welcoming you to our team. Please sign and return a copy of this letter by
        <strong><?= date('d M Y', strtotime('+7 days', strtotime($offer['offer_date']))) ?></strong>
        to confirm your acceptance. If you have any questions, please feel free to contact us.
    </p>

    <p>Warm regards,</p>

    <div class="ol-divider-thin mb-5"></div>

    <!-- SIGNATURES -->
    <div class="row mt-2 mb-4 align-items-end">
        <div class="col-5 text-center">
            <div style="display:flex;align-items:center;justify-content:center;gap:10px;margin-bottom:8px;">
                <img src="<?= $signPath ?>" alt="Signature"
                     style="max-height:65px;max-width:110px;object-fit:contain;"
                     onerror="this.style.display='none'">
                <img src="<?= $stampPath ?>" alt="Stamp"
                     style="max-height:75px;max-width:75px;object-fit:contain;"
                     onerror="this.style.display='none'">
            </div>
            <div class="ol-sig-line d-inline-block">
                <?= htmlspecialchars($offer['issued_by'] ?: 'Authorized Signatory') ?><br>
                <span class="text-muted"><?= htmlspecialchars($offer['issued_by_title'] ?: 'Authorized Signatory') ?></span><br>
                <span class="text-muted"><?= htmlspecialchars($companyName) ?></span>
            </div>
        </div>
        <div class="col-2"></div>
        <div class="col-5 text-center">
            <div class="ol-sig-line d-inline-block">
                <?= htmlspecialchars($offer['candidate_name']) ?><br>
                <span class="text-muted">Candidate Acceptance</span><br>
                <span class="text-muted">Date: _______________</span>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="ol-divider-thin mt-4 pt-3 text-center text-muted" style="font-size:.78rem;">
        <?= htmlspecialchars($companyName) ?>
        <?php if ($companyAddress): ?> &bull; <?= htmlspecialchars(str_replace("\n", ", ", $companyAddress)) ?><?php endif; ?>
        <?php if ($companyEmail): ?> &bull; <?= htmlspecialchars($companyEmail) ?><?php endif; ?>
        <?php if ($companyPhone): ?> &bull; <?= htmlspecialchars($companyPhone) ?><?php endif; ?>
        <?php if ($companyWebsite): ?> &bull; <?= htmlspecialchars($companyWebsite) ?><?php endif; ?>
    </div>

</div>

<script>
window.jsPDF = window.jspdf.jsPDF;
function downloadPDF() {
    const el = document.getElementById('offer-content');
    const btns = document.querySelectorAll('.no-print');
    btns.forEach(b => b.style.display = 'none');
    html2canvas(el, { scale: 1.5, useCORS: true, backgroundColor: '#ffffff' }).then(canvas => {
        const imgData = canvas.toDataURL('image/jpeg', 0.9);
        const pdf = new jsPDF('p', 'mm', 'a4');
        const w = pdf.internal.pageSize.getWidth();
        const h = (canvas.height * w) / canvas.width;
        const ph = pdf.internal.pageSize.getHeight();
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
        pdf.save('OfferLetter_<?= $offer['offer_no'] ?>.pdf');
        btns.forEach(b => b.style.display = '');
    });
}
</script>

