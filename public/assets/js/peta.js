// Peta interaktif (MapLibre GL JS + OpenFreeMap) untuk seluruh halaman
(function () {
    const data = window.DATA_PETA || {};
    const base = data.baseUrl || '/';
    const gaya = data.gaya || {};
    const elemen = document.getElementById('peta');
    const muat = document.getElementById('peta-muat');
    if (!elemen) return;

    const gagal = () => {
        if (!muat) return;
        muat.classList.add('gagal');
        muat.querySelector('.peta-muat-teks').textContent = 'Peta gagal dimuat. Periksa koneksi internet lalu muat ulang halaman.';
    };
    if (typeof maplibregl === 'undefined') { gagal(); return; }

    const akar = document.documentElement;
    const tema = () => (akar.dataset.tema === 'gelap' ? 'gelap' : 'terang');
    const warna = (nama) => getComputedStyle(akar).getPropertyValue(nama).trim();

    const peta = new maplibregl.Map({
        container: elemen,
        style: gaya[tema()],
        center: [110.42, -7.005],
        zoom: 11.5,
        attributionControl: { compact: true },
    });
    peta.addControl(new maplibregl.NavigationControl({ showCompass: false }), 'bottom-right');

    let sudahMuat = false;
    peta.on('load', () => { sudahMuat = true; if (muat) muat.classList.add('selesai'); });
    peta.on('error', () => { if (!sudahMuat) gagal(); });

    const escape = (t) => String(t ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const urlArah = (c) => `https://www.google.com/maps/dir/?api=1&destination=${c.lat},${c.lng}`;
    const isiPopup = (c, judul) =>
        `<strong>${judul}</strong>${escape(c.harga)}` +
        (c.rating ? ` | &#9733; ${escape(c.rating)}` : '') +
        (c.skor ? ` | ${escape(c.skor)} cocok` : '') +
        `<div class="popup-aksi"><a href="${base}cafe/${c.id}">Lihat detail</a>` +
        `<a href="${urlArah(c)}" target="_blank" rel="noopener">Petunjuk arah</a></div>`;

    const opsiPopup = { maxWidth: '300px' };
    const daftarCafe = data.cafe || [];
    const indeks = {};
    daftarCafe.forEach((c) => { indeks[c.id] = c; });

    // titik yang disorot: hasil filter di halaman Jelajahi (null berarti semua normal)
    const sorot = Array.isArray(data.sorot) ? new Set(data.sorot) : null;
    const redup = data.mode === 'rekomendasi' || data.mode === 'detail';
    const geojson = {
        type: 'FeatureCollection',
        features: daftarCafe.map((c) => ({
            type: 'Feature',
            geometry: { type: 'Point', coordinates: [c.lng, c.lat] },
            properties: { id: c.id, sorot: sorot ? sorot.has(c.id) : true },
        })),
    };

    // lapisan titik dipasang ulang setiap gaya peta berganti (mode terang/gelap)
    const pasangLapisan = () => {
        if (peta.getSource('cafe')) return;
        peta.addSource('cafe', { type: 'geojson', data: geojson });
        const kejelasan = redup ? 0.35 : ['case', ['get', 'sorot'], 0.85, 0.12];
        peta.addLayer({
            id: 'titik-cafe',
            type: 'circle',
            source: 'cafe',
            layout: { 'circle-sort-key': ['case', ['get', 'sorot'], 1, 0] },
            paint: {
                'circle-radius': ['interpolate', ['linear'], ['zoom'],
                    10, ['case', ['get', 'sorot'], redup ? 2.5 : 3.5, 2.5],
                    15, ['case', ['get', 'sorot'], redup ? 5 : 8, 5]],
                'circle-color': warna('--peta-titik'),
                'circle-opacity': kejelasan,
                'circle-stroke-color': warna('--peta-tepi'),
                'circle-stroke-width': 1,
                'circle-stroke-opacity': kejelasan,
            },
        });
    };
    peta.on('style.load', pasangLapisan);
    document.addEventListener('temaberubah', () => peta.setStyle(gaya[tema()]));

    peta.on('click', 'titik-cafe', (e) => {
        const c = indeks[e.features[0].properties.id];
        new maplibregl.Popup({ ...opsiPopup, offset: 8 }).setLngLat([c.lng, c.lat]).setHTML(isiPopup(c, escape(c.nama))).addTo(peta);
    });
    peta.on('mouseenter', 'titik-cafe', () => { peta.getCanvas().style.cursor = 'pointer'; });
    peta.on('mouseleave', 'titik-cafe', () => { peta.getCanvas().style.cursor = ''; });

    const buatPenanda = (isi) => {
        const el = document.createElement('div');
        el.className = 'penanda-ubin';
        el.innerHTML = `<span>${isi}</span>`;
        return el;
    };

    // halaman Jelajahi: arahkan peta ke hasil filter
    if (data.mode === 'daftar' && sorot && sorot.size > 0 && sorot.size < daftarCafe.length) {
        const batas = new maplibregl.LngLatBounds();
        sorot.forEach((id) => { if (indeks[id]) batas.extend([indeks[id].lng, indeks[id].lat]); });
        peta.fitBounds(batas, { padding: 60, maxZoom: 15, duration: 0 });
    }

    // 10 rekomendasi sebagai ubin bernomor (ditambahkan terbalik agar nomor 1 berada paling atas)
    if (data.mode === 'rekomendasi' && data.rekomendasi && data.rekomendasi.length) {
        const penanda = {};
        const batas = new maplibregl.LngLatBounds();
        [...data.rekomendasi].reverse().forEach((c) => {
            const popup = new maplibregl.Popup({ ...opsiPopup, offset: 20 }).setHTML(isiPopup(c, `${c.peringkat}. ${escape(c.nama)}`));
            penanda[c.id] = new maplibregl.Marker({ element: buatPenanda(c.peringkat) })
                .setLngLat([c.lng, c.lat]).setPopup(popup).addTo(peta);
            batas.extend([c.lng, c.lat]);
        });
        peta.fitBounds(batas, { padding: 70, maxZoom: 15, duration: 0 });

        // sorot penanda saat kartu hasil disentuh, fokuskan peta saat nomor diklik
        document.querySelectorAll('.hasil').forEach((baris) => {
            const m = penanda[baris.dataset.id];
            if (!m) return;
            baris.addEventListener('mouseenter', () => m.getElement().classList.add('aktif'));
            baris.addEventListener('mouseleave', () => m.getElement().classList.remove('aktif'));
        });
        document.querySelectorAll('[data-fokus]').forEach((tombol) => {
            tombol.addEventListener('click', () => {
                const m = penanda[tombol.dataset.fokus];
                if (!m) return;
                peta.flyTo({ center: m.getLngLat(), zoom: 16, duration: 700 });
                if (!m.getPopup().isOpen()) m.togglePopup();
                if (window.matchMedia('(max-width: 900px)').matches) elemen.scrollIntoView({ behavior: 'smooth' });
            });
        });
    }

    // halaman detail: satu coffee shop
    if (data.mode === 'detail' && data.fokus) {
        const c = { ...(indeks[data.fokus.id] || {}), ...data.fokus };
        new maplibregl.Marker({ element: buatPenanda('&#9679;') })
            .setLngLat([c.lng, c.lat])
            .setPopup(new maplibregl.Popup({ ...opsiPopup, offset: 20 }).setHTML(isiPopup(c, escape(c.nama))))
            .addTo(peta);
        peta.jumpTo({ center: [c.lng, c.lat], zoom: 16 });
    }
})();