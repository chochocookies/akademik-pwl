<?php
// Print-only rapor template — no layout wrapper
$student = $data['student'];
$grades  = $data['grades'];
$abs     = $data['absStats'];

// Ensure $note is always a safe array (never null)
$note = is_array($data['note']) ? $data['note'] : [];
$note = array_merge([
    'catatan_wali'          => null,
    'catatan_kepala'        => null,
    'predikat_sikap'        => 'B',
    'predikat_keterampilan' => 'B',
    'ranking'               => null,
], $note);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Rapor — <?= e($student['name']) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  @page { size: A4; margin: 15mm 15mm 15mm 20mm; }
  body { font-family: 'DM Sans', sans-serif; font-size: 11pt; color: #1e293b; background: white; }
  h1,h2,h3,h4 { font-family: 'Bricolage Grotesque', sans-serif; }
  .page { max-width: 210mm; margin: 0 auto; padding: 0; }

  /* Header */
  .report-header { display: flex; align-items: center; gap: 16px; padding-bottom: 12px; border-bottom: 3px double #1e3a8a; margin-bottom: 16px; }
  .school-logo { width: 60px; height: 60px; border-radius: 50%; background: #1e3a8a; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
  .school-logo svg { width: 32px; height: 32px; fill: white; }
  .school-info h1 { font-size: 16pt; font-weight: 800; color: #1e3a8a; }
  .school-info p { font-size: 10pt; color: #64748b; }

  /* Identity grid */
  .identity-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 24px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 16px; }
  .identity-row { display: flex; gap: 8px; font-size: 10.5pt; }
  .identity-label { color: #64748b; width: 100px; flex-shrink: 0; }
  .identity-value { font-weight: 600; }

  /* Tables */
  table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
  table th { background: #1e3a8a; color: white; padding: 7px 10px; font-size: 10pt; font-weight: 600; text-align: left; }
  table th.center, table td.center { text-align: center; }
  table td { padding: 6px 10px; border-bottom: 1px solid #e2e8f0; font-size: 10.5pt; }
  table tr:nth-child(even) td { background: #f8fafc; }
  table tr.total-row td { background: #1e3a8a !important; color: white; font-weight: 700; font-size: 11pt; }

  /* Section title */
  .section-title { font-size: 11pt; font-weight: 700; color: #1e3a8a; margin: 14px 0 8px; display: flex; align-items: center; gap: 8px; }
  .section-title::before { content: ''; display: inline-block; width: 4px; height: 16px; background: #1e3a8a; border-radius: 2px; }

  /* Grade badge */
  .grade { display: inline-block; padding: 2px 8px; border-radius: 100px; font-size: 9pt; font-weight: 700; color: white; }
  .grade-A { background: #059669; }
  .grade-B { background: #2563eb; }
  .grade-C { background: #d97706; }
  .grade-D { background: #dc2626; }

  /* Attendance box */
  .att-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
  .att-box { text-align: center; padding: 10px 6px; border-radius: 8px; }
  .att-box .num { font-family: 'Bricolage Grotesque', sans-serif; font-size: 20pt; font-weight: 800; }
  .att-box .lbl { font-size: 9pt; margin-top: 2px; }
  .att-hadir { background: #ecfdf5; color: #065f46; }
  .att-sakit { background: #eff6ff; color: #1e40af; }
  .att-izin  { background: #fffbeb; color: #92400e; }
  .att-alpha { background: #fef2f2; color: #991b1b; }

  /* Notes */
  .note-box { border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; min-height: 60px; font-size: 10.5pt; color: #475569; line-height: 1.6; margin-bottom: 12px; }

  /* Signature */
  .sig-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-top: 24px; }
  .sig-box { text-align: center; }
  .sig-box .sig-title { font-size: 10pt; margin-bottom: 60px; }
  .sig-box .sig-line { border-top: 1px solid #1e293b; padding-top: 4px; font-size: 10pt; font-weight: 600; }
  .sig-box .sig-nip { font-size: 9pt; color: #64748b; }

  /* Print */
  @media print {
    body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
    .no-print { display: none !important; }
  }
  @media screen {
    body { background: #e2e8f0; padding: 20px; }
    .page { background: white; padding: 20mm; box-shadow: 0 10px 40px rgba(0,0,0,0.15); }
  }
</style>
</head>
<body>
<!-- Print button (screen only) -->
<div class="no-print" style="position:fixed;top:16px;right:16px;z-index:100;display:flex;gap:8px;">
  <button onclick="window.print()" style="background:#2563eb;color:white;border:none;padding:10px 20px;border-radius:10px;font-family:inherit;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:8px;">
    🖨️ Cetak / Simpan PDF
  </button>
  <button onclick="window.close()" style="background:#f1f5f9;color:#475569;border:none;padding:10px 16px;border-radius:10px;font-family:inherit;font-size:13px;font-weight:600;cursor:pointer;">
    ✕ Tutup
  </button>
</div>

<div class="page">
  <!-- Header -->
  <div class="report-header">
    <div class="school-logo">
      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22 10v12H2V10L12 3l10 7zM12 5.3L4 10.8V20h16v-9.2L12 5.3zM10 14h4v6h-4z"/></svg>
    </div>
    <div class="school-info">
      <h1>LAPORAN HASIL BELAJAR SISWA</h1>
      <p><?= APP_NAME ?> · Tahun Pelajaran <?= TAHUN_AJARAN ?> · Semester <?= $semester ?></p>
    </div>
  </div>

  <!-- Identitas -->
  <div class="identity-grid">
    <?php $fields = [['Nama Siswa',$student['name']??'—'],['Jenis Kelamin',$student['gender']==='L'?'Laki-laki':'Perempuan'],['NIS',$student['nis']??'—'],['Tanggal Lahir',$student['birth_date']?formatDate($student['birth_date']):'—'],['Kelas',$student['nama_kelas']??'—'],['Semester','Semester '.$semester],['Wali Kelas',$student['wali_kelas_name']??'—'],['Tahun Pelajaran',TAHUN_AJARAN]]; ?>
    <?php foreach ($fields as [$lbl,$val]): ?>
    <div class="identity-row"><span class="identity-label"><?= $lbl ?></span><span>: </span><span class="identity-value"><?= e($val) ?></span></div>
    <?php endforeach; ?>
  </div>

  <!-- Nilai -->
  <div class="section-title">Nilai Akademik</div>
  <table>
    <thead>
      <tr>
        <th style="width:32px">#</th>
        <th>Mata Pelajaran</th>
        <th class="center" style="width:80px">Harian</th>
        <th class="center" style="width:60px">UTS</th>
        <th class="center" style="width:60px">UAS</th>
        <th class="center" style="width:80px">Nilai Akhir</th>
        <th class="center" style="width:60px">Predikat</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($grades as $i => $g):
      $na = (float)$g['nilai_akhir'];
      $gl = $na>=90?'A':($na>=80?'B':($na>=70?'C':'D'));
    ?>
    <tr>
      <td class="center"><?= $i+1 ?></td>
      <td><?= e($g['nama_mapel']) ?></td>
      <td class="center"><?= number_format((float)$g['nilai_harian'],1) ?></td>
      <td class="center"><?= number_format((float)$g['nilai_uts'],1) ?></td>
      <td class="center"><?= number_format((float)$g['nilai_uas'],1) ?></td>
      <td class="center" style="font-weight:700;font-size:12pt"><?= number_format($na,2) ?></td>
      <td class="center"><span class="grade grade-<?= $gl ?>"><?= $gl ?></span></td>
    </tr>
    <?php endforeach; ?>
    <?php if (!empty($grades)): ?>
    <tr class="total-row">
      <td colspan="5" style="text-align:right;padding-right:16px">Rata-rata Nilai Akhir</td>
      <td class="center"><?= number_format($avgNilai,2) ?></td>
      <td class="center"><span class="grade grade-<?= $predikat ?>" style="background:rgba(255,255,255,0.25)"><?= $predikat ?></span></td>
    </tr>
    <?php endif; ?>
    </tbody>
  </table>

  <!-- Kehadiran + Penilaian -->
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
    <div>
      <div class="section-title">Rekap Kehadiran</div>
      <div class="att-grid">
        <div class="att-box att-hadir"><div class="num"><?= $abs['hadir']??0 ?></div><div class="lbl">Hadir</div></div>
        <div class="att-box att-sakit"><div class="num"><?= $abs['sakit']??0 ?></div><div class="lbl">Sakit</div></div>
        <div class="att-box att-izin"><div class="num"><?= $abs['izin']??0 ?></div><div class="lbl">Izin</div></div>
        <div class="att-box att-alpha"><div class="num"><?= $abs['alpha']??0 ?></div><div class="lbl">Alpha</div></div>
      </div>
    </div>
    <div>
      <div class="section-title">Penilaian Karakter & Keterampilan</div>
      <table>
        <tr><td>Sikap / Karakter</td><td class="center"><span class="grade grade-<?= $note['predikat_sikap']??'B' ?>"><?= $note['predikat_sikap']??'B' ?></span></td></tr>
        <tr><td>Keterampilan</td><td class="center"><span class="grade grade-<?= $note['predikat_keterampilan']??'B' ?>"><?= $note['predikat_keterampilan']??'B' ?></span></td></tr>
        <?php if ($note['ranking']): ?><tr><td>Peringkat Kelas</td><td class="center"><strong>#<?= $note['ranking'] ?></strong></td></tr><?php endif; ?>
      </table>
    </div>
  </div>

  <!-- Catatan -->
  <div class="section-title">Catatan Wali Kelas</div>
  <div class="note-box"><?= $note['catatan_wali'] ? nl2br(e($note['catatan_wali'])) : '—' ?></div>

  <?php if ($note['catatan_kepala']): ?>
  <div class="section-title">Catatan Kepala Sekolah</div>
  <div class="note-box"><?= nl2br(e($note['catatan_kepala'])) ?></div>
  <?php endif; ?>

  <!-- Tanda tangan -->
  <div class="sig-grid">
    <div class="sig-box">
      <div class="sig-title">Mengetahui,<br>Orang Tua / Wali</div>
      <div class="sig-line">(....................................)</div>
    </div>
    <div class="sig-box">
      <div class="sig-title">Wali Kelas,</div>
      <div class="sig-line"><?= e($student['wali_kelas_name']??'—') ?></div>
      <?php if (!empty($student['wali_kelas_nip'])): ?><div class="sig-nip">NIP: <?= e($student['wali_kelas_nip']) ?></div><?php endif; ?>
    </div>
    <div class="sig-box">
      <div class="sig-title">Kepala Sekolah,</div>
      <div class="sig-line">(.....................................)</div>
    </div>
  </div>

  <p style="text-align:center;font-size:9pt;color:#94a3b8;margin-top:16px;padding-top:10px;border-top:1px solid #e2e8f0">
    Dicetak oleh: <?= e(Auth::user()['name']) ?> · <?= date('d M Y H:i') ?> · <?= APP_NAME ?>
  </p>
</div>
</body>
</html>
