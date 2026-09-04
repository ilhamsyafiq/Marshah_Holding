<?php
/**
 * Marshah Holding – Admin Dashboard
 *
 * Session-based single-password login. Two tabs:
 *   - Promosi : manage promo packages (add / edit / delete)
 *   - Leads   : catalog-download leads (status change / delete / filter / CSV)
 */

require_once __DIR__ . '/config.php';

session_start();

// ---- Login ----
if (isset($_POST['admin_login'])) {
    if (($_POST['password'] ?? '') === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $loginError = 'Kata laluan tidak sah.';
    }
}

// ---- Logout ----
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_logged_in']);
    header('Location: admin.php');
    exit;
}

// ---- Auth gate ----
if (empty($_SESSION['admin_logged_in'])) {
    ?>
    <!doctype html>
    <html lang="ms">
    <head>
        <meta charset="utf-8" />
        <title>Log Masuk Admin - Marshah Holding</title>
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <style>
            * { box-sizing:border-box; }
            body { margin:0; background:#f4f2fb; font-family:system-ui,Segoe UI,sans-serif; display:flex; align-items:center; justify-content:center; min-height:100vh; }
            .card { background:#fff; border-radius:14px; box-shadow:0 24px 48px -8px rgba(60,40,120,.15); padding:2.5rem; max-width:380px; width:100%; }
            h1 { font-size:1.3rem; margin:0 0 .3rem; color:#2a1f4d; }
            p.sub { color:#6b7280; font-size:.85rem; margin:0 0 1.5rem; }
            .input { width:100%; padding:12px 14px; border-radius:10px; border:1px solid #e5e2ef; font-size:.9rem; outline:none; margin-bottom:12px; }
            .input:focus { border-color:#7213A3; box-shadow:0 0 0 4px rgba(114,19,163,.18); }
            .btn { width:100%; padding:12px; border:none; border-radius:999px; background:linear-gradient(90deg,#7213A3,#9d78ff); color:#fff; font-weight:600; font-size:.9rem; cursor:pointer; }
            .error { background:#fef2f2; color:#dc2626; padding:10px; border-radius:8px; font-size:.8rem; margin-bottom:12px; }
        </style>
    </head>
    <body>
        <div class="card">
            <h1>Log Masuk Admin</h1>
            <p class="sub">Marshah Holding — Panel Pengurusan</p>
            <?php if (!empty($loginError)): ?><div class="error"><?= htmlspecialchars($loginError) ?></div><?php endif; ?>
            <form method="POST">
                <input type="hidden" name="admin_login" value="1" />
                <input class="input" type="password" name="password" placeholder="Kata laluan admin" autofocus required />
                <button type="submit" class="btn">Log Masuk</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// ---- Load stores ----
$promosFile = STORAGE_DIR . '/promos.json';
$leadsFile  = STORAGE_DIR . '/leads.json';

$promos = load_json($promosFile);
uasort($promos, fn($a, $b) => ((int)($a['order'] ?? 999)) <=> ((int)($b['order'] ?? 999)));

$leads = load_json($leadsFile);
uasort($leads, fn($a, $b) => strtotime($b['created_at'] ?? '0') - strtotime($a['created_at'] ?? '0'));

// ---- Leads filter ----
$leadSearch = trim($_GET['q'] ?? '');
$filteredLeads = array_filter($leads, function ($l) use ($leadSearch) {
    if ($leadSearch === '') return true;
    $hay = strtolower(implode(' ', [
        $l['nama'] ?? '', $l['telefon'] ?? '', $l['email'] ?? '',
        $l['source'] ?? '', $l['status'] ?? '',
    ]));
    return strpos($hay, strtolower($leadSearch)) !== false;
});

// ---- Stats ----
$totalLeads = count($leads);
$newLeads   = count(array_filter($leads, fn($l) => ($l['status'] ?? '') === 'baru'));

$activeTab = $_GET['tab'] ?? 'promosi';

$leadStatuses = [
    'baru'      => ['label' => 'Baru',      'color' => '#7213A3', 'bg' => '#f2edff'],
    'dihubungi' => ['label' => 'Dihubungi', 'color' => '#2563eb', 'bg' => '#eff6ff'],
    'menukar'   => ['label' => 'Menukar',   'color' => '#059669', 'bg' => '#ecfdf5'],
    'gagal'     => ['label' => 'Gagal',     'color' => '#dc2626', 'bg' => '#fef2f2'],
];
function leadBadge($status, $map) {
    $s = $map[$status] ?? ['label' => ucfirst($status ?: '-'), 'color' => '#6b7280', 'bg' => '#f3f4f6'];
    return '<span style="display:inline-block;padding:4px 10px;border-radius:999px;font-size:.7rem;font-weight:600;background:'.$s['bg'].';color:'.$s['color'].';">'.htmlspecialchars($s['label']).'</span>';
}
?>
<!doctype html>
<html lang="ms">
<head>
    <meta charset="utf-8" />
    <title>Dashboard Admin - Marshah Holding</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <style>
        :root { --bg:#f4f2fb; --card:#fff; --text:#241b3d; --muted:#6b7280; --border:#e8e5f2; --brand:#7213A3; --radius:10px; }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--bg); color:var(--text); font-family:system-ui,Segoe UI,sans-serif; }
        .topbar { background:#fff; border-bottom:1px solid var(--border); padding:12px 24px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:10; }
        .topbar h1 { font-size:1.05rem; margin:0; }
        .topbar a { color:var(--brand); text-decoration:none; font-size:.85rem; font-weight:500; }
        .container { max-width:1200px; margin:0 auto; padding:24px; }
        .tabs { display:flex; gap:6px; margin-bottom:22px; border-bottom:2px solid var(--border); }
        .tab { padding:10px 20px; text-decoration:none; color:var(--muted); font-weight:600; font-size:.9rem; border-bottom:2px solid transparent; margin-bottom:-2px; }
        .tab.active { color:var(--brand); border-bottom-color:var(--brand); }
        .stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px; margin-bottom:24px; }
        .stat-card { background:var(--card); border-radius:var(--radius); padding:16px 20px; box-shadow:0 2px 8px rgba(60,40,120,.05); }
        .stat-value { font-size:1.6rem; font-weight:700; }
        .stat-label { font-size:.72rem; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; margin-top:2px; }
        .bar { display:flex; gap:10px; flex-wrap:wrap; align-items:center; margin-bottom:18px; }
        .bar input { padding:8px 12px; border:1px solid var(--border); border-radius:8px; font-size:.82rem; outline:none; background:#fff; }
        .bar input:focus { border-color:var(--brand); }
        .btn { padding:9px 16px; border:none; border-radius:8px; background:var(--brand); color:#fff; font-size:.82rem; font-weight:600; cursor:pointer; text-decoration:none; display:inline-block; }
        .btn-ghost { background:#fff; color:var(--brand); border:1px solid var(--brand); }
        table { width:100%; border-collapse:collapse; background:var(--card); border-radius:var(--radius); overflow:hidden; box-shadow:0 2px 8px rgba(60,40,120,.05); }
        th { background:#faf9fe; text-align:left; padding:10px 14px; font-size:.68rem; text-transform:uppercase; letter-spacing:.5px; color:var(--muted); border-bottom:1px solid var(--border); }
        td { padding:12px 14px; border-bottom:1px solid #f3f1fa; font-size:.82rem; vertical-align:top; }
        tr:hover td { background:#fafafe; }
        .row-actions { display:flex; gap:6px; flex-wrap:wrap; }
        .action-btn { padding:6px 12px; border:1px solid var(--border); border-radius:6px; background:#fff; font-size:.75rem; cursor:pointer; color:var(--text); }
        .action-btn:hover { border-color:var(--brand); color:var(--brand); }
        .action-btn.danger:hover { border-color:#dc2626; color:#dc2626; }
        .empty { text-align:center; padding:48px; color:var(--muted); }
        select.inline { padding:6px 8px; border:1px solid var(--border); border-radius:6px; font-size:.78rem; }
        .pill-yes { color:#059669; font-weight:600; }
        .pill-no { color:#9ca3af; }

        /* Modal */
        .modal-overlay { display:none; position:fixed; inset:0; background:rgba(30,20,60,.45); z-index:100; align-items:flex-start; justify-content:center; padding:40px 16px; overflow-y:auto; }
        .modal-overlay.active { display:flex; }
        .modal { background:#fff; border-radius:14px; padding:26px; max-width:520px; width:100%; box-shadow:0 24px 48px rgba(30,20,60,.25); }
        .modal h2 { font-size:1.1rem; margin:0 0 16px; }
        .modal label { display:block; font-size:.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:var(--muted); margin:12px 0 4px; }
        .modal input[type=text], .modal textarea { width:100%; padding:10px 12px; border:1px solid var(--border); border-radius:8px; font-size:.85rem; outline:none; }
        .modal textarea { resize:vertical; min-height:70px; font-family:inherit; }
        .modal input:focus, .modal textarea:focus { border-color:var(--brand); }
        .check-row { display:flex; gap:20px; margin-top:14px; }
        .check-row label { display:flex; align-items:center; gap:6px; text-transform:none; letter-spacing:0; font-size:.85rem; color:var(--text); margin:0; }
        .modal-actions { display:flex; gap:8px; margin-top:20px; justify-content:flex-end; }
        .modal-actions button { padding:9px 20px; border-radius:8px; font-size:.82rem; font-weight:600; cursor:pointer; }
        .btn-save { background:var(--brand); color:#fff; border:none; }
        .btn-cancel { background:#fff; border:1px solid var(--border); color:var(--text); }
        .hint { font-size:.72rem; color:var(--muted); margin-top:4px; }
    </style>
</head>
<body>
    <div class="topbar">
        <h1>Marshah Holding — Dashboard Admin</h1>
        <div>
            <a href="index.html" style="margin-right:16px;">Lihat Laman</a>
            <a href="admin.php?logout=1">Log Keluar</a>
        </div>
    </div>

    <div class="container">
        <div class="tabs">
            <a class="tab <?= $activeTab === 'promosi' ? 'active' : '' ?>" href="admin.php?tab=promosi">Promosi</a>
            <a class="tab <?= $activeTab === 'leads' ? 'active' : '' ?>" href="admin.php?tab=leads">Leads</a>
        </div>

        <?php if ($activeTab === 'promosi'): ?>
        <!-- ============ PROMOSI TAB ============ -->
        <div class="bar">
            <button class="btn" onclick="openPromoModal(null)">+ Tambah Pakej</button>
        </div>

        <?php if (empty($promos)): ?>
            <div class="empty">Tiada pakej lagi. Klik "Tambah Pakej".</div>
        <?php else: ?>
        <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Susun</th><th>Nama</th><th>Harga</th><th>Badge</th>
                    <th>Utama</th><th>Aktif</th><th>Ciri</th><th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($promos as $p): ?>
                <tr>
                    <td><?= (int)($p['order'] ?? 0) ?></td>
                    <td style="font-weight:600;"><?= htmlspecialchars($p['name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($p['price'] ?? '') ?></td>
                    <td><?= htmlspecialchars($p['badge'] ?? '') ?: '<span class="pill-no">—</span>' ?></td>
                    <td><?= !empty($p['featured']) ? '<span class="pill-yes">Ya</span>' : '<span class="pill-no">Tidak</span>' ?></td>
                    <td><?= !empty($p['active']) ? '<span class="pill-yes">Ya</span>' : '<span class="pill-no">Tidak</span>' ?></td>
                    <td><?= count($p['features'] ?? []) ?></td>
                    <td>
                        <div class="row-actions">
                            <button class="action-btn" onclick='openPromoModal(<?= htmlspecialchars(json_encode($p, JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES) ?>)'>Edit</button>
                            <form method="POST" action="admin-update.php" onsubmit="return confirm('Padam pakej ini?');" style="display:inline;">
                                <input type="hidden" name="action" value="promo_delete">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($p['id'] ?? '') ?>">
                                <button type="submit" class="action-btn danger">Padam</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>

        <!-- Promo Modal -->
        <div class="modal-overlay" id="promoModal">
            <div class="modal">
                <h2 id="promoModalTitle">Tambah Pakej</h2>
                <form method="POST" action="admin-update.php">
                    <input type="hidden" name="action" value="promo_save">
                    <input type="hidden" name="id" id="pm-id">

                    <label>Nama Pakej</label>
                    <input type="text" name="name" id="pm-name" required>

                    <label>Penerangan</label>
                    <textarea name="desc" id="pm-desc"></textarea>

                    <label>Harga (cth. RM155,000)</label>
                    <input type="text" name="price" id="pm-price">

                    <label>Badge (cth. Popular — biar kosong jika tiada)</label>
                    <input type="text" name="badge" id="pm-badge">

                    <label>Ciri-ciri (satu baris satu ciri)</label>
                    <textarea name="features" id="pm-features" style="min-height:130px;"></textarea>
                    <div class="hint">Setiap baris jadi satu ciri (semua ditanda "termasuk").</div>

                    <label>URL Butang (cta_url)</label>
                    <input type="text" name="cta_url" id="pm-cta">

                    <label>Susunan (order)</label>
                    <input type="text" name="order" id="pm-order" placeholder="cth. 1">

                    <div class="check-row">
                        <label><input type="checkbox" name="featured" id="pm-featured" value="1"> Pakej Utama (featured)</label>
                        <label><input type="checkbox" name="active" id="pm-active" value="1"> Aktif</label>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn-cancel" onclick="closePromoModal()">Batal</button>
                        <button type="submit" class="btn-save">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <?php else: ?>
        <!-- ============ LEADS TAB ============ -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-value"><?= $totalLeads ?></div>
                <div class="stat-label">Jumlah Leads</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" style="color:var(--brand);"><?= $newLeads ?></div>
                <div class="stat-label">Leads Baru</div>
            </div>
        </div>

        <form class="bar" method="GET">
            <input type="hidden" name="tab" value="leads">
            <input type="text" name="q" placeholder="Cari nama, telefon, emel..." value="<?= htmlspecialchars($leadSearch) ?>" style="min-width:240px;">
            <button type="submit" class="btn">Cari</button>
            <?php if ($leadSearch !== ''): ?><a class="tab" href="admin.php?tab=leads" style="border:0;">Kosongkan</a><?php endif; ?>
            <a class="btn btn-ghost" href="admin-update.php?action=leads_csv">Muat turun CSV</a>
        </form>

        <?php if (empty($filteredLeads)): ?>
            <div class="empty">Tiada lead ditemui.</div>
        <?php else: ?>
        <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Tarikh</th><th>Nama</th><th>Telefon</th><th>Emel</th>
                    <th>Sumber</th><th>Status</th><th>Tukar Status</th><th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filteredLeads as $l): ?>
                <tr>
                    <td style="white-space:nowrap;font-size:.78rem;"><?= htmlspecialchars($l['created_at'] ?? '') ?></td>
                    <td style="font-weight:600;"><?= htmlspecialchars($l['nama'] ?? '') ?></td>
                    <td><?= htmlspecialchars($l['telefon'] ?? '') ?></td>
                    <td class="email"><?= htmlspecialchars($l['email'] ?? '') ?: '<span class="pill-no">—</span>' ?></td>
                    <td><?= htmlspecialchars($l['source'] ?? '') ?></td>
                    <td><?= leadBadge($l['status'] ?? '', $leadStatuses) ?></td>
                    <td>
                        <form method="POST" action="admin-update.php">
                            <input type="hidden" name="action" value="lead_status">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($l['id'] ?? '') ?>">
                            <select class="inline" name="status" onchange="this.form.submit()">
                                <?php foreach ($leadStatuses as $key => $meta): ?>
                                    <option value="<?= $key ?>" <?= ($l['status'] ?? '') === $key ? 'selected' : '' ?>><?= $meta['label'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="admin-update.php" onsubmit="return confirm('Padam lead ini?');">
                            <input type="hidden" name="action" value="lead_delete">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($l['id'] ?? '') ?>">
                            <button type="submit" class="action-btn danger">Padam</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>

    <script>
    function openPromoModal(p) {
        var isEdit = p && p.id;
        document.getElementById('promoModalTitle').textContent = isEdit ? 'Edit Pakej' : 'Tambah Pakej';
        document.getElementById('pm-id').value = isEdit ? p.id : '';
        document.getElementById('pm-name').value = isEdit ? (p.name || '') : '';
        document.getElementById('pm-desc').value = isEdit ? (p.desc || '') : '';
        document.getElementById('pm-price').value = isEdit ? (p.price || '') : '';
        document.getElementById('pm-badge').value = isEdit ? (p.badge || '') : '';
        document.getElementById('pm-cta').value = isEdit ? (p.cta_url || 'hubungi-kami.html') : 'hubungi-kami.html';
        document.getElementById('pm-order').value = isEdit ? (p.order != null ? p.order : '') : '';
        document.getElementById('pm-featured').checked = isEdit ? !!p.featured : false;
        document.getElementById('pm-active').checked = isEdit ? !!p.active : true;
        var feats = '';
        if (isEdit && Array.isArray(p.features)) {
            feats = p.features.map(function (f) { return f.text || ''; }).join('\n');
        }
        document.getElementById('pm-features').value = feats;
        document.getElementById('promoModal').classList.add('active');
    }
    function closePromoModal() {
        document.getElementById('promoModal').classList.remove('active');
    }
    var pmOverlay = document.getElementById('promoModal');
    if (pmOverlay) {
        pmOverlay.addEventListener('click', function (e) { if (e.target === this) closePromoModal(); });
    }
    </script>
</body>
</html>
