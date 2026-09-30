// Latihan 2: satu fungsi generik untuk Daftar Buku & Daftar Anggota.
// Sumber data dan kolom dibaca dari atribut <table>:
//   data-sumber="../data/buku.json"
//   data-kunci="judul,pengarang,tahun,stok,kategori"
const DELAY_SIMULASI = 600; // ubah ke 3000 untuk Latihan 5, lalu kembalikan

async function muatTabel() {
  const table = document.querySelector(".table-responsive table");
  const loading = document.getElementById("loading-indicator");
  const btnMuat = document.getElementById("btn-muat-ulang");
  const cari = document.getElementById("search-input");
  if (!table || !table.dataset.sumber) return;

  const tbody = table.querySelector("tbody");
  const kunci = table.dataset.kunci.split(",");
  const jumlahKolom = table.querySelectorAll("thead th").length;

  loading.style.display = "block";
  if (btnMuat) btnMuat.disabled = true;
  if (cari) cari.value = "";
  tbody.innerHTML = "";

  try {
    await new Promise((resolve) => setTimeout(resolve, DELAY_SIMULASI));

    const res = await fetch(table.dataset.sumber);
    if (!res.ok) {
      throw new Error("Gagal mengambil data (status " + res.status + ")");
    }
    const data = await res.json();

    data.forEach(function (item) {
      const tr = document.createElement("tr");

      kunci.forEach(function (k) {
        const td = document.createElement("td");
        td.textContent = item[k];
        tr.appendChild(td);
      });

      const tdAksi = document.createElement("td");
      tdAksi.innerHTML =
        "<button type=\"button\">Edit</button> " +
        "<button type=\"button\" class=\"btn-hapus\">Hapus</button>";
      tr.appendChild(tdAksi);

      tbody.appendChild(tr);
    });
  } catch (err) {
    tbody.innerHTML =
      "<tr><td colspan=\"" + jumlahKolom + "\">Gagal memuat data: " + err.message + "</td></tr>";
  } finally {
    loading.style.display = "none";
    if (btnMuat) btnMuat.disabled = false;
    updateJumlah();
  }
}

document.addEventListener("DOMContentLoaded", function () {
  muatTabel();
  const btn = document.getElementById("btn-muat-ulang");
  if (btn) btn.addEventListener("click", muatTabel);
});
