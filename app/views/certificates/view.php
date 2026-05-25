<?php
$verifyUrl = 'https://www.smartflix.ae/certificate/' . $cert['certificate_slug'];
$qrUrl     = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&margin=0&data=' . urlencode($verifyUrl);

$logoPath = !empty($settings['company_logo'])
    ? BASE_URL . 'public/uploads/' . $settings['company_logo']
    : '';

$signStampPath = BASE_URL . 'public/assets/sign_stamp.png';

$durLabel = '';
if (!empty($cert['duration_from']) && !empty($cert['duration_to'])) {
    $durLabel = date('d M Y', strtotime($cert['duration_from'])) . ' &mdash; ' . date('d M Y', strtotime($cert['duration_to']));
} elseif (!empty($cert['duration_from'])) {
    $durLabel = 'From ' . date('d M Y', strtotime($cert['duration_from']));
} elseif (!empty($cert['duration_to'])) {
    $durLabel = 'Until ' . date('d M Y', strtotime($cert['duration_to']));
}

$companyWeb = 'www.smartflix.ae';

$body = trim($cert['description']);
if ($body === '') {
    $type    = $cert['certificate_type'];
    $subject = $cert['subject'];
    $durText = $durLabel ? ' from ' . strip_tags(html_entity_decode($durLabel)) : '';
    if ($type === 'Internship') {
        $body = "has successfully completed an internship in <strong>{$subject}</strong>{$durText}. During this tenure, the candidate demonstrated commendable dedication, professionalism and a strong willingness to learn, contributing meaningfully to the team and assigned responsibilities.";
    } elseif ($type === 'Experience') {
        $body = "has served with <strong>Smatflix Technologies FZCO</strong> as <strong>" . ($cert['designation'] ?: 'Team Member') . "</strong>{$durText}. The candidate was an integral part of our {$subject} team and consistently displayed competence, integrity and a commitment to excellence.";
    } elseif ($type === 'Training' || $type === 'Completion') {
        $body = "has successfully completed the <strong>{$subject}</strong>{$durText}. The candidate has demonstrated the required knowledge, skills and professional conduct expected for the successful completion of this program.";
    } else {
        $body = "is hereby recognized for <strong>{$subject}</strong>{$durText}. This certificate is presented in appreciation of the candidate's outstanding contribution and dedication.";
    }
}
?>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">

<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <a href="<?= BASE_URL ?>certificates" class="btn btn-light"><i class="fas fa-arrow-left me-1"></i> Back</a>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>certificates/edit/<?= $cert['id'] ?>" class="btn btn-outline-secondary"><i class="fas fa-edit me-1"></i> Edit</a>
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print me-1"></i> Download / Print PDF</button>
        <a href="<?= BASE_URL ?>certificates/verify/<?= $cert['certificate_slug'] ?>" target="_blank" class="btn btn-outline-success"><i class="fas fa-link me-1"></i> Public Link</a>
    </div>
</div>

<div class="cert-page-wrap">
<div class="certificate">

    <div class="cert-outer-border"></div>
    <div class="cert-inner-border"></div>

    <div class="cert-corner tl"></div>
    <div class="cert-corner tr"></div>
    <div class="cert-corner bl"></div>
    <div class="cert-corner br"></div>

    <div class="cert-watermark">CERTIFIED</div>

    <div class="cert-content">

        <!-- HEADER -->
        <div class="cert-header">
            <?php if ($logoPath): ?>
                <img src="<?= $logoPath ?>" alt="logo" class="cert-logo">
            <?php endif; ?>
            <div class="cert-company-name">SMATFLIX TECHNOLOGIES FZCO</div>
            <div class="cert-type-label">CERTIFICATE OF <?= strtoupper($cert['certificate_type']) ?></div>
        </div>

        <!-- DIVIDER -->
        <div class="cert-divider">
            <span class="divider-line"></span>
            <span class="divider-icon">&#10070;</span>
            <span class="divider-line"></span>
        </div>

        <!-- PRESENTED TO -->
        <div class="cert-presented">This is to certify that</div>
        <h1 class="cert-name"><?= htmlspecialchars($cert['candidate_name']) ?></h1>
        <?php if (!empty($cert['designation'])): ?>
        <div class="cert-designation"><?= htmlspecialchars($cert['designation']) ?></div>
        <?php endif; ?>

        <!-- BODY TEXT -->
        <div class="cert-body"><?= $body ?></div>

        <!-- DURATION STRIP -->
        <?php if ($durLabel): ?>
        <div class="cert-duration-strip">
            <span class="dur-icon"><i class="fas fa-calendar-alt"></i></span>
            <span class="dur-label">DURATION</span>
            <span class="dur-sep">|</span>
            <span class="dur-value"><?= $durLabel ?></span>
        </div>
        <?php endif; ?>

        <!-- META ROW -->
        <div class="cert-meta-row">
            <div>
                <span class="meta-key">Certificate No.</span>
                <span class="meta-val"><?= htmlspecialchars($cert['certificate_no']) ?></span>
            </div>
            <div class="meta-dot">&#9679;</div>
            <div>
                <span class="meta-key">Issue Date</span>
                <span class="meta-val"><?= date('d M Y', strtotime($cert['issue_date'])) ?></span>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="cert-footer">

            <!-- Signature -->
            <div class="cert-sign-block">
                <img src="<?= $signStampPath ?>" alt="Signature & Stamp" class="sign-img" style="max-width:160px;max-height:60px;object-fit:contain;" onerror="this.style.display='none'">
                <div class="sign-line"></div>
                <div class="sign-name"><?= htmlspecialchars($cert['issued_by'] ?: 'Authorized Signatory') ?></div>
                <div class="sign-title"><?= htmlspecialchars($cert['issued_by_title'] ?: 'Authorized Signatory') ?></div>
            </div>

            <!-- Company info -->
            <div class="cert-company-block">
                <div class="co-name">Smatflix Technologies FZCO</div>
                <div class="co-addr">Dubai Digital Park, Silicon Oasis</div>
                <div class="co-addr">Dubai, UAE</div>
                <div class="co-web">&#127760; <?= $companyWeb ?></div>
            </div>

            <!-- QR -->
            <div class="cert-stamp-qr">
                <div class="qr-wrap">
                    <img src="<?= $qrUrl ?>" alt="QR" class="qr-img">
                    <div class="qr-label">Scan to verify</div>
                </div>
            </div>

        </div>

    </div>
</div>
</div>

<style>
.cert-page-wrap {
    display: flex;
    justify-content: center;
    padding: 24px 0 56px;
}

/* Portrait A4 */
.certificate {
    position: relative;
    width: 210mm;
    height: 297mm;
    background: #fdfaf3;
    background-image:
        radial-gradient(circle at 20% 12%, rgba(197,160,84,.08) 0%, transparent 52%),
        radial-gradient(circle at 80% 88%, rgba(30,52,96,.08) 0%, transparent 52%);
    box-shadow: 0 28px 70px rgba(0,0,0,.22);
    overflow: hidden;
    font-family: 'Cormorant Garamond', Georgia, serif;
    color: #1e3460;
    box-sizing: border-box;
}

/* Borders */
.cert-outer-border {
    position: absolute;
    inset: 7mm;
    border: 2.5px solid #c5a054;
    pointer-events: none;
    z-index: 1;
}
.cert-inner-border {
    position: absolute;
    inset: 10.5mm;
    border: 1px solid rgba(197,160,84,.4);
    pointer-events: none;
    z-index: 1;
}

/* Corners */
.cert-corner {
    position: absolute;
    width: 20mm;
    height: 20mm;
    border: 2.5px solid #1e3460;
    z-index: 2;
}
.cert-corner.tl { top: 5.5mm;    left: 5.5mm;   border-right: 0; border-bottom: 0; }
.cert-corner.tr { top: 5.5mm;    right: 5.5mm;  border-left: 0;  border-bottom: 0; }
.cert-corner.bl { bottom: 5.5mm; left: 5.5mm;   border-right: 0; border-top: 0; }
.cert-corner.br { bottom: 5.5mm; right: 5.5mm;  border-left: 0;  border-top: 0; }

/* Watermark */
.cert-watermark {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%) rotate(-35deg);
    font-size: 100px;
    font-family: 'Playfair Display', serif;
    font-weight: 900;
    color: rgba(197,160,84,.045);
    letter-spacing: 12px;
    white-space: nowrap;
    pointer-events: none;
    z-index: 0;
}

/* Content */
.cert-content {
    position: relative;
    z-index: 3;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 13mm 15mm 0;
    box-sizing: border-box;
}

/* Header */
.cert-header { margin-bottom: 4mm; }
.cert-logo {
    max-height: 64px;
    margin: 0 auto 7px;
    display: block;
}
.cert-company-name {
    font-family: 'Playfair Display', serif;
    font-size: 22px;
    font-weight: 700;
    letter-spacing: 4px;
    color: #1e3460;
    margin-bottom: 4px;
}
.cert-type-label {
    font-family: 'Inter', sans-serif;
    font-size: 11px;
    letter-spacing: 8px;
    color: #c5a054;
    text-transform: uppercase;
}

/* Divider */
.cert-divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 3mm 0;
    color: #c5a054;
}
.divider-line { display: block; width: 80px; height: 1px; background: #c5a054; }
.divider-icon { font-size: 15px; }

/* Presented */
.cert-presented {
    font-style: italic;
    font-size: 20px;
    color: #777;
    margin-bottom: 3mm;
    font-family: 'Cormorant Garamond', serif;
}

/* Name */
.cert-name {
    font-family: 'Playfair Display', serif;
    font-size: 54px;
    font-weight: 900;
    color: #1e3460;
    letter-spacing: 2px;
    line-height: 1.05;
    margin: 0 0 3mm;
    border-bottom: 1.5px solid #c5a054;
    padding-bottom: 4mm;
    width: 88%;
}

/* Designation */
.cert-designation {
    font-size: 17px;
    font-style: italic;
    color: #888;
    margin-bottom: 5mm;
    letter-spacing: 1px;
    font-family: 'Cormorant Garamond', serif;
}

/* Body */
.cert-body {
    font-size: 19px;
    line-height: 1.55;
    color: #3a3a3a;
    text-align: center;
    max-width: 160mm;
    margin-bottom: 4mm;
}
.cert-body strong { color: #1e3460; font-weight: 700; }

/* Duration strip */
.cert-duration-strip {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    background: linear-gradient(90deg, rgba(197,160,84,.1), rgba(197,160,84,.22), rgba(197,160,84,.1));
    border: 1px solid rgba(197,160,84,.55);
    border-radius: 4px;
    padding: 6px 22px;
    margin-bottom: 4mm;
    font-family: 'Inter', sans-serif;
}
.dur-icon { color: #c5a054; font-size: 12px; }
.dur-label {
    font-size: 10px;
    letter-spacing: 3px;
    color: #c5a054;
    font-weight: 700;
    text-transform: uppercase;
}
.dur-sep { color: rgba(197,160,84,.5); font-size: 14px; }
.dur-value {
    font-size: 16px;
    font-weight: 600;
    color: #1e3460;
    font-family: 'Cormorant Garamond', serif;
    letter-spacing: .5px;
}

/* Meta row */
.cert-meta-row {
    display: flex;
    align-items: center;
    gap: 20px;
    font-family: 'Inter', sans-serif;
    margin-bottom: 4mm;
}
.meta-key {
    display: block;
    font-size: 10px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #bbb;
    margin-bottom: 2px;
}
.meta-val {
    font-size: 15px;
    font-weight: 700;
    color: #1e3460;
    font-family: 'Cormorant Garamond', serif;
}
.meta-dot { color: #c5a054; font-size: 9px; }

/* ── Footer ── */
.cert-footer {
    margin-top: 4mm;
    width: 100%;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    border-top: 1px solid rgba(197,160,84,.35);
    padding: 5mm 2mm 12mm;
    gap: 4mm;
    box-sizing: border-box;
}

/* Signature */
.cert-sign-block {
    width: 40%;
    text-align: center;
}
.sign-img {
    max-width: 190px;
    max-height: 70px;
    margin: 0 auto 5px;
    display: block;
    object-fit: contain;
}
.sign-line {
    border-top: 1.5px solid #1e3460;
    width: 80%;
    margin: 0 auto 6px;
}
.sign-name {
    font-weight: 700;
    font-size: 16px;
    color: #1e3460;
    letter-spacing: .5px;
}
.sign-title {
    font-size: 13px;
    color: #888;
    font-family: 'Inter', sans-serif;
    letter-spacing: .5px;
    margin-top: 2px;
}

/* Company block */
.cert-company-block {
    width: 30%;
    text-align: center;
    font-family: 'Inter', sans-serif;
}
.co-name {
    font-size: 13px;
    font-weight: 700;
    color: #1e3460;
    letter-spacing: .4px;
    margin-bottom: 4px;
    line-height: 1.3;
}
.co-addr {
    font-size: 11.5px;
    color: #888;
    line-height: 1.6;
}
.co-web {
    font-size: 11.5px;
    color: #c5a054;
    margin-top: 5px;
    letter-spacing: .5px;
    font-weight: 600;
}

/* Stamp + QR */
.cert-stamp-qr {
    width: 33%;
    display: flex;
    align-items: flex-end;
    justify-content: flex-end;
    gap: 8px;
}
.stamp-img {
    width: 88px;
    height: 88px;
    object-fit: contain;
    opacity: .85;
}
.qr-wrap { text-align: center; }
.qr-img {
    width: 84px;
    height: 84px;
    border: 1px solid #e2d6b6;
    padding: 3px;
    background: #fff;
    display: block;
}
.qr-label {
    font-size: 9.5px;
    color: #aaa;
    margin-top: 4px;
    letter-spacing: 1px;
    font-family: 'Inter', sans-serif;
    text-transform: uppercase;
}

/* Print */
@media print {
    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    body, html {
        background: #fff !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: hidden !important;
    }
    .no-print, .sidebar, nav,
    .main-content > .d-flex,
    .sidebar-overlay { display: none !important; }
    .main-content { margin: 0 !important; padding: 0 !important; }
    .cert-page-wrap { padding: 0 !important; margin: 0 !important; }
    .certificate {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 210mm !important;
        height: 297mm !important;
        box-shadow: none !important;
        overflow: hidden !important;
        margin: 0 !important;
    }
    @page { size: A4 portrait; margin: 0; }
}
</style>
