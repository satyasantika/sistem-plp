<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FKIP EDU - Eksplorasi Edukasi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #071c2f;
            --bg-soft: #0f2e4a;
            --surface: rgba(255, 255, 255, 0.1);
            --surface-strong: rgba(255, 255, 255, 0.16);
            --text: #f4f8ff;
            --muted: #b9c8dc;
            --primary: #12b4a6;
            --secondary: #f4b942;
            --shadow: 0 18px 48px rgba(2, 8, 20, 0.35);
            --radius: 20px;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: radial-gradient(1200px 600px at 5% 0%, #1a4c75 0%, transparent 60%),
                        radial-gradient(800px 500px at 100% 0%, #0d796f 0%, transparent 55%),
                        linear-gradient(160deg, var(--bg) 0%, #0a2135 55%, #072843 100%);
            color: var(--text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100%;
        }

        .grain {
            position: fixed;
            inset: 0;
            pointer-events: none;
            opacity: 0.14;
            background-image: radial-gradient(circle at 1px 1px, #ffffff 1px, transparent 0);
            background-size: 20px 20px;
            mix-blend-mode: soft-light;
            z-index: 0;
        }

        .container {
            width: min(1200px, 92%);
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 0;
            gap: 12px;
        }

        .brand {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            letter-spacing: 0.8px;
        }

        .brand .pill {
            display: inline-block;
            margin-left: 8px;
            font-size: 12px;
            padding: 6px 10px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 999px;
            color: #d8e8ff;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn {
            text-decoration: none;
            border: 0;
            border-radius: 12px;
            padding: 10px 16px;
            font-weight: 600;
            font-size: 14px;
            transition: transform .25s ease, box-shadow .25s ease, background .25s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--secondary), #ffd27d);
            color: #13283d;
            box-shadow: 0 12px 24px rgba(244, 185, 66, 0.3);
        }

        .btn-ghost {
            background: var(--surface);
            color: var(--text);
            border: 1px solid rgba(255, 255, 255, 0.28);
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .hero {
            display: grid;
            grid-template-columns: 1.3fr 1fr;
            gap: 24px;
            align-items: stretch;
            margin-top: 8px;
        }

        .card {
            background: linear-gradient(165deg, rgba(255, 255, 255, 0.13), rgba(255, 255, 255, 0.06));
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 28px;
        }

        .hero h1 {
            font-family: 'Sora', sans-serif;
            margin: 8px 0 10px;
            font-size: clamp(28px, 5vw, 52px);
            line-height: 1.1;
        }

        .eyebrow {
            color: #9dddf6;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 12px;
        }

        .tagline {
            color: #fff4d6;
            font-size: clamp(16px, 2.2vw, 24px);
            font-weight: 600;
            margin-bottom: 16px;
        }

        .lead {
            color: var(--muted);
            line-height: 1.7;
            font-size: 15px;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-top: 16px;
        }

        .stat {
            background: rgba(5, 12, 23, 0.28);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            padding: 14px;
        }

        .stat .n {
            font-family: 'Sora', sans-serif;
            font-size: 28px;
            color: #ffe4a8;
            font-weight: 800;
        }

        .stat .d {
            color: #c9d9ea;
            font-size: 13px;
            margin-top: 4px;
        }

        .section {
            margin-top: 26px;
        }

        .section h2 {
            font-family: 'Sora', sans-serif;
            margin: 0 0 12px;
            font-size: clamp(22px, 3vw, 34px);
        }

        .kicker {
            color: #8dd4ce;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .usp-grid,
        .partner-grid,
        .req-grid,
        .calendar-grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .panel {
            background: var(--surface);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 18px;
        }

        .panel h3 {
            margin: 0 0 8px;
            font-size: 17px;
            font-family: 'Sora', sans-serif;
        }

        .panel p,
        .panel li {
            margin: 0;
            color: var(--muted);
            line-height: 1.65;
            font-size: 14px;
        }

        .journey {
            display: grid;
            grid-template-columns: 1fr 54px 1fr;
            gap: 12px;
            align-items: stretch;
        }

        .cycle {
            padding: 20px;
            border-radius: 16px;
            background: linear-gradient(170deg, rgba(10, 40, 63, 0.8), rgba(7, 33, 53, 0.55));
            border: 1px solid rgba(255, 255, 255, 0.19);
        }

        .cycle h3 {
            margin: 0 0 10px;
            font-size: 20px;
            font-family: 'Sora', sans-serif;
            color: #f8fbff;
        }

        .cycle ul {
            margin: 0;
            padding-left: 20px;
            color: var(--muted);
            line-height: 1.8;
        }

        .connector {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffe2a8;
            font-size: 32px;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .footer {
            text-align: center;
            color: #d2ddec;
            padding: 24px 0 40px;
            font-size: 13px;
        }

        .reveal {
            opacity: 0;
            transform: translateY(16px);
            animation: reveal .8s ease forwards;
        }

        .delay-1 { animation-delay: .08s; }
        .delay-2 { animation-delay: .16s; }
        .delay-3 { animation-delay: .24s; }
        .delay-4 { animation-delay: .32s; }

        @keyframes reveal {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 980px) {
            .hero,
            .journey {
                grid-template-columns: 1fr;
            }

            .connector {
                min-height: 56px;
            }
        }

        @media (max-width: 700px) {
            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .usp-grid,
            .partner-grid,
            .req-grid,
            .calendar-grid,
            .stat-grid {
                grid-template-columns: 1fr;
            }

            .card {
                padding: 22px;
            }
        }
    </style>
</head>

<body>
    <div class="grain"></div>
    <main class="container">
        <header class="topbar reveal">
            <div class="brand">FKIP EDU <span class="pill">Eksplorasi Edukasi</span></div>
            <div class="actions">
                <a href="{{ route('login') }}" class="btn btn-primary">Masuk Sistem</a>
                <a href="https://drive.google.com/file/d/1vnImoQQPYmeJ4KJNtD66DaoHZmVS7fkF/view" class="btn btn-ghost">Pedoman Program</a>
            </div>
        </header>

        <section class="hero section">
            <article class="card reveal delay-1">
                <div class="eyebrow">Identitas Program</div>
                <h1>FKIP EDU</h1>
                <div class="tagline">Transformasi Pembelajaran Berdampak untuk Indonesia Emas 2045.</div>
                <p class="lead">
                    FKIP EDU adalah program pengalaman lapangan transformatif bagi mahasiswa Program Sarjana Pendidikan
                    di FKIP Universitas Siliwangi untuk menguatkan jati diri dan kompetensi calon tenaga pendidik
                    melalui pengalaman nyata di mitra satuan pendidikan atau institusi.
                </p>
            </article>

            <aside class="card reveal delay-2">
                <div class="kicker">Highlight Program</div>
                <div class="stat-grid">
                    <div class="stat">
                        <div class="n">20 SKS</div>
                        <div class="d">Terintegrasi selama satu semester penuh.</div>
                    </div>
                    <div class="stat">
                        <div class="n">TaRL</div>
                        <div class="d">Pendekatan Teaching at the Right Level yang adaptif.</div>
                    </div>
                    <div class="stat">
                        <div class="n">IKU + SDGs</div>
                        <div class="d">Mendukung target kinerja universitas dan pembangunan berkelanjutan.</div>
                    </div>
                    <div class="stat">
                        <div class="n">Deep Learning</div>
                        <div class="d">Pengalaman belajar bermakna dan berdampak nyata.</div>
                    </div>
                </div>
            </aside>
        </section>

        <section class="section reveal delay-2">
            <div class="kicker">Unique Selling Points</div>
            <h2>Keunggulan FKIP EDU</h2>
            <div class="usp-grid">
                <div class="panel">
                    <h3>Pembelajaran Transformatif</h3>
                    <p>Fokus pada pengalaman belajar mendalam yang berdaya ubah bagi siswa, bukan sekadar administrasi.</p>
                </div>
                <div class="panel">
                    <h3>Beban SKS Terintegrasi</h3>
                    <p>Program setara 20 SKS di luar program studi dalam satu semester pelaksanaan.</p>
                </div>
                <div class="panel">
                    <h3>Pendekatan Inovatif</h3>
                    <p>Menerapkan metode TaRL yang responsif terhadap tingkat kebutuhan dan capaian belajar siswa.</p>
                </div>
                <div class="panel">
                    <h3>Kontribusi Nyata</h3>
                    <p>Dirancang untuk mendukung IKU universitas dan akselerasi pencapaian SDGs.</p>
                </div>
            </div>
        </section>

        <section class="section reveal delay-3">
            <div class="kicker">Customer Journey</div>
            <h2>Struktur Pelaksanaan Dua Siklus</h2>
            <div class="journey">
                <div class="cycle">
                    <h3>Siklus I</h3>
                    <ul>
                        <li>Observasi kultur sekolah/institusi mitra.</li>
                        <li>Pengamatan struktur organisasi dan tata kelola.</li>
                        <li>Identifikasi kebutuhan pada lingkungan mitra.</li>
                    </ul>
                </div>
                <div class="connector">→</div>
                <div class="cycle">
                    <h3>Siklus II</h3>
                    <ul>
                        <li>Latihan praktik mengajar mandiri/terbimbing.</li>
                        <li>Partisipasi aktif pada kegiatan institusi mitra.</li>
                        <li>Pelaksanaan ujian praktik mengajar.</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="section reveal delay-3">
            <div class="kicker">Pendaftaran Peserta</div>
            <h2>Persyaratan Mengikuti FKIP EDU</h2>
            <div class="req-grid">
                <div class="panel"><p>Mahasiswa aktif Program Sarjana Pendidikan di semester berjalan.</p></div>
                <div class="panel"><p>Telah lulus minimal 90 SKS.</p></div>
                <div class="panel"><p>Telah lulus Microteaching dengan nilai minimal B.</p></div>
                <div class="panel"><p>Mengontrak paket mata kuliah FKIP EDU total 20 SKS pada KRS.</p></div>
            </div>
        </section>

        <section class="section reveal delay-4">
            <div class="kicker">Mitra Strategis</div>
            <h2>Bidang Layanan Kemitraan</h2>
            <div class="partner-grid">
                <div class="panel">
                    <h3>Pendidikan Masyarakat</h3>
                    <p>Bermitra dengan PKBM, SKB, LKP, dan instansi pemerintah terkait.</p>
                </div>
                <div class="panel">
                    <h3>Pendidikan Bidang Studi</h3>
                    <p>Bermitra dengan SMP/SMA untuk prodi Bahasa Indonesia, Bahasa Inggris, Jasmani, Matematika,
                        Biologi, Geografi, Ekonomi, Sejarah, dan Fisika.</p>
                </div>
            </div>
        </section>

        <section class="section reveal delay-4">
            <div class="kicker">Kalender Penting</div>
            <h2>Estimasi Waktu Pelaksanaan</h2>
            <div class="calendar-grid">
                <div class="panel">
                    <h3>Pembekalan / Perkuliahan Paket</h3>
                    <p>19 Januari - 11 Februari 2026.</p>
                </div>
                <div class="panel">
                    <h3>Penerjunan ke Mitra</h3>
                    <p>Dimulai pada Februari 2026, mengikuti jadwal pelaksanaan Siklus I.</p>
                </div>
            </div>
        </section>

        <section class="section reveal delay-4">
            <div class="card" style="text-align:center;">
                <h2 style="margin-top:0;">Siap Bergabung dengan FKIP EDU?</h2>
                <p class="lead" style="max-width:760px; margin: 0 auto 18px;">
                    Masuk ke sistem untuk melihat informasi program, alur pelaksanaan, dan aktivitas akademik Anda.
                </p>
                <a href="{{ route('login') }}" class="btn btn-primary">Masuk ke FKIP EDU</a>
            </div>
        </section>

        <footer class="footer">
            FKIP Universitas Siliwangi • FKIP EDU (Eksplorasi Edukasi)
        </footer>
    </main>
</body>
</html>
