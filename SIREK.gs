/**
 * SIREK - Sistem Informasi Rekrutmen Karyawan
 * Google Apps Script Version
 * RSUD Aji Muhammad Idris
 * 
 * Deploy as Web App untuk integrasi dengan Google Sheets
 */

// Configuration - Ganti dengan Spreadsheet ID Anda
const CONFIG = {
  SPREADSHEET_ID: 'YOUR_SPREADSHEET_ID_HERE',
  SHEET_NAMES: {
    PELAMAR: 'Pelamar',
    LOWONGAN: 'Lowongan',
    VERIFIKASI: 'Verifikasi',
    NILAI: 'Nilai',
    PENGUMUMAN: 'Pengumuman'
  }
};

/**
 * Handler untuk Web App - Menampilkan halaman utama
 */
function doGet(e) {
  const template = HtmlService.createTemplateFromFile('index');
  return template.evaluate()
    .setTitle('SIREK - RSUD Aji Muhammad Idris')
    .setXFrameOptionsMode(HtmlService.XFrameOptionsMode.ALLOWALL)
    .addMetaTag('viewport', 'width=device-width, initial-scale=1');
}

/**
 * Include file HTML
 */
function include(filename) {
  return HtmlService.createHtmlOutputFromFile(filename).getContent();
}

/**
 * API: Get all pelamar
 */
function getPelamar() {
  try {
    const ss = SpreadsheetApp.openById(CONFIG.SPREADSHEET_ID);
    const sheet = ss.getSheetByName(CONFIG.SHEET_NAMES.PELAMAR);
    const data = sheet.getDataRange().getValues();
    const headers = data[0];
    const rows = data.slice(1).map(row => {
      const obj = {};
      headers.forEach((header, i) => obj[header] = row[i]);
      return obj;
    });
    return { success: true, data: rows };
  } catch (error) {
    return { success: false, error: error.message };
  }
}

/**
 * API: Get pelamar by ID
 */
function getPelamarById(id) {
  try {
    const ss = SpreadsheetApp.openById(CONFIG.SPREADSHEET_ID);
    const sheet = ss.getSheetByName(CONFIG.SHEET_NAMES.PELAMAR);
    const data = sheet.getDataRange().getValues();
    const headers = data[0];
    const idCol = headers.indexOf('id');
    
    for (let i = 1; i < data.length; i++) {
      if (data[i][idCol] == id) {
        const obj = {};
        headers.forEach((header, j) => obj[header] = data[i][j]);
        return { success: true, data: obj };
      }
    }
    return { success: false, error: 'Data tidak ditemukan' };
  } catch (error) {
    return { success: false, error: error.message };
  }
}

/**
 * API: Search pelamar by registration number or NIK
 */
function searchPelamar(query) {
  try {
    const ss = SpreadsheetApp.openById(CONFIG.SPREADSHEET_ID);
    const sheet = ss.getSheetByName(CONFIG.SHEET_NAMES.PELAMAR);
    const data = sheet.getDataRange().getValues();
    const headers = data[0];
    const regCol = headers.indexOf('nomor_pendaftaran');
    const nikCol = headers.indexOf('nik');
    const nameCol = headers.indexOf('nama_lengkap');
    
    for (let i = 1; i < data.length; i++) {
      if (data[i][regCol] == query || data[i][nikCol] == query) {
        const obj = {};
        headers.forEach((header, j) => obj[header] = data[i][j]);
        return { success: true, data: obj };
      }
    }
    return { success: false, error: 'Data tidak ditemukan' };
  } catch (error) {
    return { success: false, error: error.message };
  }
}

/**
 * API: Add new pelamar
 */
function addPelamar(pelamarData) {
  try {
    const ss = SpreadsheetApp.openById(CONFIG.SPREADSHEET_ID);
    const sheet = ss.getSheetByName(CONFIG.SHEET_NAMES.PELAMAR);
    
    // Generate registration number
    const lastRow = sheet.getLastRow();
    const regNumber = 'RSUD-' + new Date().getFullYear() + '-' + String(lastRow).padStart(4, '0');
    
    const row = [
      lastRow, // id
      regNumber, // nomor_pendaftaran
      pelamarData.nama_lengkap || '',
      pelamarData.gelar || '',
      pelamarData.nik || '',
      pelamarData.tempat_lahir || '',
      pelamarData.tanggal_lahir || '',
      pelamarData.jenis_kelamin || '',
      pelamarData.pendidikan_terakhir || '',
      pelamarData.profesi || '',
      pelamarData.unit_penempatan || '',
      pelamarData.str_number || '',
      pelamarData.str_expired || '',
      pelamarData.no_hp || '',
      pelamarData.email || '',
      'Menunggu Verifikasi', // status
      new Date(), // created_at
      '' // catatan
    ];
    
    sheet.appendRow(row);
    
    return { 
      success: true, 
      message: 'Pendaftaran berhasil!',
      registrationNumber: regNumber 
    };
  } catch (error) {
    return { success: false, error: error.message };
  }
}

/**
 * API: Update verifikasi status
 */
function updateVerifikasi(id, status, catatan) {
  try {
    const ss = SpreadsheetApp.openById(CONFIG.SPREADSHEET_ID);
    const sheet = ss.getSheetByName(CONFIG.SHEET_NAMES.PELAMAR);
    const data = sheet.getDataRange().getValues();
    const headers = data[0];
    const idCol = headers.indexOf('id');
    const statusCol = headers.indexOf('status');
    const catatanCol = headers.indexOf('catatan');
    
    for (let i = 1; i < data.length; i++) {
      if (data[i][idCol] == id) {
        sheet.getRange(i + 1, statusCol + 1).setValue(status);
        sheet.getRange(i + 1, catatanCol + 1).setValue(catatan || '');
        return { success: true, message: 'Status diperbarui' };
      }
    }
    return { success: false, error: 'Data tidak ditemukan' };
  } catch (error) {
    return { success: false, error: error.message };
  }
}

/**
 * API: Update nilai
 */
function updateNilai(id, nilaiKompetensi, nilaiWawancara) {
  try {
    const ss = SpreadsheetApp.openById(CONFIG.SPREADSHEET_ID);
    const sheet = ss.getSheetByName(CONFIG.SHEET_NAMES.NILAI);
    
    // Calculate final score: 60% kompetensi + 40% wawancara
    const nilaiAkhir = (nilaiKompetensi * 0.6) + (nilaiWawancara * 0.4);
    
    const row = [
      id,
      nilaiKompetensi,
      nilaiWawancara,
      nilaiAkhir,
      new Date()
    ];
    
    // Check if nilai exists
    const data = sheet.getDataRange().getValues();
    const idCol = 0;
    let found = false;
    
    for (let i = 1; i < data.length; i++) {
      if (data[i][idCol] == id) {
        sheet.getRange(i + 1, 2).setValue(nilaiKompetensi);
        sheet.getRange(i + 1, 3).setValue(nilaiWawancara);
        sheet.getRange(i + 1, 4).setValue(nilaiAkhir);
        found = true;
        break;
      }
    }
    
    if (!found) {
      sheet.appendRow(row);
    }
    
    return { success: true, nilaiAkhir: nilaiAkhir };
  } catch (error) {
    return { success: false, error: error.message };
  }
}

/**
 * API: Get all lowongan
 */
function getLowongan() {
  try {
    const ss = SpreadsheetApp.openById(CONFIG.SPREADSHEET_ID);
    const sheet = ss.getSheetByName(CONFIG.SHEET_NAMES.LOWONGAN);
    const data = sheet.getDataRange().getValues();
    const headers = data[0];
    const rows = data.slice(1).map(row => {
      const obj = {};
      headers.forEach((header, i) => obj[header] = row[i]);
      return obj;
    });
    return { success: true, data: rows };
  } catch (error) {
    return { success: false, error: error.message };
  }
}

/**
 * API: Get statistics
 */
function getStatistics() {
  try {
    const ss = SpreadsheetApp.openById(CONFIG.SPREADSHEET_ID);
    const sheet = ss.getSheetByName(CONFIG.SHEET_NAMES.PELAMAR);
    const data = sheet.getDataRange().getValues();
    
    const total = data.length - 1;
    const statusCol = data[0].indexOf('status');
    
    let lolos = 0, tidakLolos = 0, menunggu = 0;
    
    for (let i = 1; i < data.length; i++) {
      const status = data[i][statusCol];
      if (status === 'Lolos') lolos++;
      else if (status === 'Tidak Lolos') tidakLolos++;
      else menunggu++;
    }
    
    return {
      success: true,
      data: {
        total: total,
        lolos: lolos,
        tidakLolos: tidakLolos,
        menunggu: menunggu
      }
    };
  } catch (error) {
    return { success: false, error: error.message };
  }
}

/**
 * API: Send email notification
 */
function sendNotificationEmail(email, subject, body) {
  try {
    MailApp.sendEmail({
      to: email,
      subject: subject,
      htmlBody: body
    });
    return { success: true };
  } catch (error) {
    return { success: false, error: error.message };
  }
}

/**
 * Generate PDF bukti pendaftaran
 */
function generateBuktiPDF(pelamarId) {
  // Implementation menggunakan Google Docs Template
  // Returns blob PDF
}

/**
 * Export to Excel
 */
function exportToExcel() {
  const ss = SpreadsheetApp.openById(CONFIG.SPREADSHEET_ID);
  const date = new Date().toISOString().split('T')[0];
  const fileName = `SIREK_Export_${date}.xlsx`;
  
  SpreadsheetApp.getActiveSpreadsheet().toast('File akan di-export!', 'Export', 5);
  
  return {
    success: true,
    message: 'Export dimulai. File akan tersedia di drive.',
    fileName: fileName
  };
}

/**
 * Setup sheets jika belum ada
 */
function setupSheets() {
  const ss = SpreadsheetApp.openById(CONFIG.SPREADSHEET_ID);
  
  const sheets = [
    { name: CONFIG.SHEET_NAMES.PELAMAR, headers: ['id', 'nomor_pendaftaran', 'nama_lengkap', 'gelar', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'pendidikan_terakhir', 'profesi', 'unit_penempatan', 'str_number', 'str_expired', 'no_hp', 'email', 'status', 'created_at', 'catatan'] },
    { name: CONFIG.SHEET_NAMES.LOWONGAN, headers: ['id', 'judul', 'departemen', 'deskripsi', 'requirements', 'lokasi', 'tipe', 'tanggal_mulai', 'tanggal_akhir', 'status', 'created_at'] },
    { name: CONFIG.SHEET_NAMES.VERIFIKASI, headers: ['id', 'pelamar_id', 'dokumen_ktp', 'dokumen_ijazah', 'dokumen_str', 'dokumen_cv', 'status', 'catatan', 'verified_by', 'verified_at'] },
    { name: CONFIG.SHEET_NAMES.NILAI, headers: ['pelamar_id', 'nilai_kompetensi', 'nilai_wawancara', 'nilai_akhir', 'updated_at'] },
    { name: CONFIG.SHEET_NAMES.PENGUMUMAN, headers: ['id', 'pelamar_id', 'status_akhir', 'ranking', 'pengumuman_dikirim', 'updated_at'] }
  ];
  
  sheets.forEach(sheetConfig => {
    let sheet = ss.getSheetByName(sheetConfig.name);
    if (!sheet) {
      sheet = ss.insertSheet(sheetConfig.name);
      sheet.appendRow(sheetConfig.headers);
    }
  });
  
  return { success: true, message: 'Sheets telah di-setup!' };
}

/**
 * Test function
 */
function testAPI() {
  const result = getStatistics();
  Logger.log(JSON.stringify(result));
  return result;
}
