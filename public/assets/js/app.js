// Pengalih tema terang/gelap dan kirim otomatis filter
(function () {
    const akar = document.documentElement;
    const tombol = document.querySelector('[data-ganti-tema]');

    const perbaruiTombol = () => {
        if (!tombol) return;
        const gelap = akar.dataset.tema === 'gelap';
        tombol.setAttribute('aria-label', gelap ? 'Ganti ke mode terang' : 'Ganti ke mode gelap');
        tombol.title = gelap ? 'Mode terang' : 'Mode gelap';
    };
    perbaruiTombol();

    if (tombol) {
        tombol.addEventListener('click', () => {
            const tema = akar.dataset.tema === 'gelap' ? 'terang' : 'gelap';
            akar.dataset.tema = tema;
            try { localStorage.setItem('tema', tema); } catch (e) { /* mode privat */ }
            perbaruiTombol();
            document.dispatchEvent(new CustomEvent('temaberubah', { detail: tema }));
        });
    }

    // select pada halaman Jelajahi langsung mengirim form saat diganti
    document.querySelectorAll('[data-kirim-otomatis]').forEach((el) => {
        el.addEventListener('change', () => el.form.requestSubmit());
    });
})();