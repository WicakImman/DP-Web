// ===== Hamburger menu =====
function initNavToggle() {
  const toggleBtn = document.getElementById("nav-toggle-btn");
  const nav = document.querySelector("header nav");
  if (!toggleBtn || !nav) return;

  toggleBtn.addEventListener("click", function () {
    nav.classList.toggle("nav-open");
  });
}

// ===== Counter jumlah baris (Latihan 4) =====
function updateJumlah() {
  const info = document.getElementById("info-jumlah");
  const table = document.querySelector(".table-responsive table");
  if (!info || !table) return;

  const rows = Array.from(table.querySelectorAll("tbody tr")).filter(function (row) {
    return !row.querySelector("td[colspan]");
  });

  const total = rows.length;
  const tampil = rows.filter(function (row) {
    return row.style.display !== "none";
  }).length;

  const satuan = info.dataset.satuan || "data";
  info.textContent = "Menampilkan " + tampil + " dari " + total + " " + satuan;
}

// ===== Konfirmasi hapus (event delegation) =====
function initHapusConfirm() {
  document.addEventListener("click", function (e) {
    const btn = e.target.closest(".btn-hapus");
    if (!btn) return;

    const row = btn.closest("tr");
    const nama = row ? row.querySelector("td")?.textContent : "data ini";
    const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");

    if (yakin && row) {
      row.remove();
      updateJumlah();
    }
  });
}

// ===== Filter tabel real-time, satu kolom saja (Latihan 3) =====
function initTableFilter() {
  const input = document.getElementById("search-input");
  const table = document.querySelector(".table-responsive table");
  if (!input || !table) return;

  const kolom = parseInt(input.dataset.kolom || "0", 10);

  input.addEventListener("keyup", function () {
    const keyword = input.value.toLowerCase();
    const rows = table.querySelectorAll("tbody tr");

    rows.forEach(function (row) {
      const sel = row.querySelectorAll("td")[kolom];
      const teks = sel ? sel.textContent.toLowerCase() : "";
      row.style.display = teks.includes(keyword) ? "" : "none";
    });

    updateJumlah();
  });
}

// ===== Validasi form =====
function tampilkanError(input, pesan) {
  hapusError(input);
  const span = document.createElement("span");
  span.className = "error";
  span.textContent = pesan;
  input.insertAdjacentElement("afterend", span);
}

function hapusError(input) {
  const next = input.nextElementSibling;
  if (next && next.classList.contains("error")) {
    next.remove();
  }
}

const ATURAN_TEKS = ["judul", "pengarang", "nama", "alamat", "telepon"];

const ATURAN_ANGKA = [
  { name: "tahun", min: 1900, max: 2026, pesan: "Tahun harus di antara 1900-2026." },
  { name: "stok", min: 0, max: Infinity, pesan: "Stok tidak boleh kosong atau negatif." }
];

const ATURAN_POLA = [
  { name: "isbn", pola: /^[0-9-]+$/, pesan: "ISBN hanya boleh berisi angka dan tanda hubung (-)." }
];

function initValidasiForm() {
  const form = document.getElementById("form-tambah");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    let valid = true;

    ATURAN_TEKS.forEach(function (name) {
      const field = form.querySelector("[name='" + name + "']");
      if (!field) return;

      if (field.value.trim() === "") {
        tampilkanError(field, "Field ini wajib diisi.");
        valid = false;
      } else {
        hapusError(field);
      }
    });

    ATURAN_ANGKA.forEach(function (aturan) {
      const field = form.querySelector("[name='" + aturan.name + "']");
      if (!field) return;

      const nilai = parseInt(field.value, 10);
      if (isNaN(nilai) || nilai < aturan.min || nilai > aturan.max) {
        tampilkanError(field, aturan.pesan);
        valid = false;
      } else {
        hapusError(field);
      }
    });

    ATURAN_POLA.forEach(function (aturan) {
      const field = form.querySelector("[name='" + aturan.name + "']");
      if (!field) return;

      const isi = field.value.trim();
      if (isi !== "" && !aturan.pola.test(isi)) {
        tampilkanError(field, aturan.pesan);
        valid = false;
      } else {
        hapusError(field);
      }
    });

    if (!valid) {
      e.preventDefault();
    }
  });
}

// ===== Titik masuk =====
document.addEventListener("DOMContentLoaded", function () {
  initNavToggle();
  initHapusConfirm();
  initTableFilter();
  initValidasiForm();
  updateJumlah();
});
