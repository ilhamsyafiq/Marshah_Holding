<?php
/**
 * Catalog Download Handler
 *
 * On POST (nama, telefon, email): validate, store a lead, notify admin,
 * then stream the catalog PDF if present, else redirect to the thank-you anchor.
 * On GET: redirect to the catalog section of the homepage.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/mailer.php';

$leadsFile = STORAGE_DIR . '/leads.json';

// GET → just send the user back to the catalog section
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html#katalog');
    exit;
}

// Collect + validate input
$nama    = trim($_POST['nama'] ?? '');
$telefon = trim($_POST['telefon'] ?? '');
$email   = trim($_POST['email'] ?? '');

// nama + telefon required
if ($nama === '' || $telefon === '') {
    header('Location: index.html?katalog=ralat#katalog');
    exit;
}

// Build lead record
$id = uniqid('lead_', true);
$lead = [
    'id'         => $id,
    'nama'       => $nama,
    'telefon'    => $telefon,
    'email'      => $email,
    'source'     => 'katalog',
    'status'     => 'baru',
    'created_at' => date('Y-m-d H:i:s'),
];

// Append to leads store (keyed by id)
$leads = load_json($leadsFile);
$leads[$id] = $lead;
save_json($leadsFile, $leads);

// Optional admin notification — must not fatal if mail is unavailable
try {
    sendLeadNotification($lead);
} catch (\Throwable $e) {
    error_log('sendLeadNotification error: ' . $e->getMessage());
}

// Stream the catalog PDF if it exists
$pdfPath = __DIR__ . '/assets/katalog-marshah.pdf';
if (is_file($pdfPath)) {
    // Clear any accidental output before streaming binary
    if (ob_get_level()) {
        ob_end_clean();
    }
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="katalog-marshah.pdf"');
    header('Content-Length: ' . filesize($pdfPath));
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    readfile($pdfPath);
    exit;
}

// No PDF yet → thank-you redirect
header('Location: index.html?katalog=terima#katalog');
exit;
