<footer class="app-footer-modern">
    <div class="footer-shell">
        <div class="footer-main">
            <span class="footer-title">Sistem PLP - FKIP Universitas Siliwangi</span>
        </div>
        <div class="footer-meta">
            <span>Copyright &copy; 2022-{{ date('Y') }} SaReDep (Satya-Redi-DepiArdian)</span>
        </div>
    </div>
</footer>

<style>
    footer.app-footer-modern {
        background: transparent;
        border-top: 0;
        box-shadow: none;
        margin-top: 18px;
        padding-top: 0;
        padding-bottom: 18px;
    }

    footer.app-footer-modern .footer-shell {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 11px 14px;
        border: 1px solid rgba(214, 226, 242, 0.95);
        border-radius: 12px;
        background: linear-gradient(115deg, rgba(255, 255, 255, 0.96) 0%, rgba(239, 246, 255, 0.95) 55%, rgba(234, 248, 241, 0.95) 100%);
        box-shadow: 0 8px 22px rgba(28, 50, 84, 0.08);
        color: #33496b;
        position: relative;
        overflow: hidden;
    }

    footer.app-footer-modern .footer-shell::after {
        content: "";
        position: absolute;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        right: -64px;
        top: -80px;
        background: radial-gradient(circle at center, rgba(15, 106, 214, 0.18) 0%, rgba(15, 106, 214, 0) 70%);
        pointer-events: none;
    }

    footer.app-footer-modern .footer-main {
        display: flex;
        align-items: baseline;
        gap: 8px;
        min-width: 0;
    }

    footer.app-footer-modern .footer-title {
        font-weight: 800;
        letter-spacing: 0.2px;
        color: #1f3555;
        white-space: normal;
    }

    footer.app-footer-modern .footer-subtitle {
        font-size: 0.82rem;
        font-weight: 600;
        color: #5d7394;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    footer.app-footer-modern .footer-meta {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.84rem;
        font-weight: 700;
        color: #5a7090;
        white-space: normal;
    }

    body.dark footer.app-footer-modern {
        border-top: 0;
        box-shadow: none;
    }

    body.dark footer.app-footer-modern .footer-shell {
        border-color: rgba(146, 176, 223, 0.3);
        background: linear-gradient(115deg, rgba(30, 50, 79, 0.96) 0%, rgba(20, 37, 61, 0.96) 55%, rgba(18, 32, 56, 0.96) 100%);
        box-shadow: 0 12px 26px rgba(0, 0, 0, 0.28);
        color: #bfd3f1;
    }

    body.dark footer.app-footer-modern .footer-shell::after {
        background: radial-gradient(circle at center, rgba(90, 165, 255, 0.24) 0%, rgba(90, 165, 255, 0) 70%);
    }

    body.dark footer.app-footer-modern .footer-title {
        color: #eaf2ff;
    }

    body.dark footer.app-footer-modern .footer-subtitle,
    body.dark footer.app-footer-modern .footer-meta {
        color: #b9ccec;
    }

    @media (max-width: 900px) {
        footer.app-footer-modern {
            padding-bottom: 14px;
        }

        footer.app-footer-modern .footer-shell {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
            padding: 10px 12px;
        }

        footer.app-footer-modern .footer-main {
            flex-wrap: wrap;
            row-gap: 2px;
        }

        footer.app-footer-modern .footer-subtitle,
        footer.app-footer-modern .footer-meta {
            white-space: normal;
        }

        footer.app-footer-modern .footer-meta {
            font-size: 0.8rem;
        }
    }
</style>
