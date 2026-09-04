<?php
/**
 * Marshah Holding – Admin Update Handler
 *
 * Session-guarded mutations for promos and leads.
 * Actions: promo_save, promo_delete, lead_status, lead_delete, leads_csv.
 */

require_once __DIR__ . '/config.php';

session_start();

// Auth guard
if (empty($_SESSION['admin_logged_in'])) {
    header('Location: admin.php');
    exit;
}

$promosFile = STORAGE_DIR . '/promos.json';
$leadsFile  = STORAGE_DIR . '/leads.json';

// CSV export is a GET (link) — handle before the POST-only gate
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'leads_csv') {
    $leads = load_json($leadsFile);
    uasort($leads, fn($a, $b) => strtotime($b['created_at'] ?? '0') - strtotime($a['created_at'] ?? '0'));

    if (ob_get_level()) { ob_end_clean(); }
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="leads-marshah-' . date('Ymd-His') . '.csv"');

    $out = fopen('php://output', 'w');
    // UTF-8 BOM so Excel reads Malay characters correctly
    fwrite($out, "\xEF\xBB\xBF");
    fputcsv($out, ['Nama', 'Telefon', 'Emel', 'Sumber', 'Status', 'Tarikh']);
    foreach ($leads as $l) {
        fputcsv($out, [
            $l['nama'] ?? '',
            $l['telefon'] ?? '',
            $l['email'] ?? '',
            $l['source'] ?? '',
            $l['status'] ?? '',
            $l['created_at'] ?? '',
        ]);
    }
    fclose($out);
    exit;
}

// All remaining actions are POST-only mutations
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin.php');
    exit;
}

switch ($action) {

    // ---- Create or update a promo package ----
    case 'promo_save': {
        $promos = load_json($promosFile);

        $id = trim($_POST['id'] ?? '');
        if ($id === '') {
            $id = uniqid('promo_', false);
        }

        // Parse features textarea (one per line) → [{text, included:true}]
        $features = [];
        $rawFeatures = $_POST['features'] ?? '';
        foreach (preg_split('/\r\n|\r|\n/', $rawFeatures) as $line) {
            $line = trim($line);
            if ($line !== '') {
                $features[] = ['text' => $line, 'included' => true];
            }
        }

        $promos[$id] = [
            'id'       => $id,
            'name'     => trim($_POST['name'] ?? ''),
            'desc'     => trim($_POST['desc'] ?? ''),
            'price'    => trim($_POST['price'] ?? ''),
            'badge'    => trim($_POST['badge'] ?? ''),
            'featured' => !empty($_POST['featured']),
            'active'   => !empty($_POST['active']),
            'features' => $features,
            'cta_url'  => trim($_POST['cta_url'] ?? 'hubungi-kami.html'),
            'order'    => (int)($_POST['order'] ?? 0),
        ];

        save_json($promosFile, $promos);
        header('Location: admin.php?tab=promosi');
        exit;
    }

    // ---- Delete a promo package ----
    case 'promo_delete': {
        $promos = load_json($promosFile);
        $id = trim($_POST['id'] ?? '');
        if ($id !== '' && isset($promos[$id])) {
            unset($promos[$id]);
            save_json($promosFile, $promos);
        }
        header('Location: admin.php?tab=promosi');
        exit;
    }

    // ---- Change lead status ----
    case 'lead_status': {
        $leads = load_json($leadsFile);
        $id = trim($_POST['id'] ?? '');
        $status = trim($_POST['status'] ?? '');
        $valid = ['baru', 'dihubungi', 'menukar', 'gagal'];
        if ($id !== '' && isset($leads[$id]) && in_array($status, $valid, true)) {
            $leads[$id]['status'] = $status;
            save_json($leadsFile, $leads);
        }
        header('Location: admin.php?tab=leads');
        exit;
    }

    // ---- Delete a lead ----
    case 'lead_delete': {
        $leads = load_json($leadsFile);
        $id = trim($_POST['id'] ?? '');
        if ($id !== '' && isset($leads[$id])) {
            unset($leads[$id]);
            save_json($leadsFile, $leads);
        }
        header('Location: admin.php?tab=leads');
        exit;
    }

    default:
        header('Location: admin.php');
        exit;
}
