// 1. Panggil library Express.js yang sudah kita install tadi
const express = require('express');

// 2. Panggil library 'path' (bawaan Node.js) untuk mengatur alamat folder di komputer
const path = require('path');

// 3. Buat sebuah aplikasi express baru
const app = express();

// 4. Tentukan port/jalur untuk frontend kita, yaitu port 3000
const PORT = 3000;

// 5. Beritahu Express untuk membuka "pintu akses" ke sebuah folder bernama 'public'.
//    Folder 'public' ini nanti akan kita isi dengan file HTML/tampilan web kita.
app.use(express.static(path.join(__dirname, 'public')));

// 6. Jalankan server frontend agar stand-by menerima perintah
app.listen(PORT, () => {
    console.log(`Server Frontend berjalan di http://localhost:${PORT}`);
});