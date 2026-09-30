<style>
    .pl-shell {
        padding: 0 1.5rem 1.5rem;
    }

    .pl-hero {
        background: linear-gradient(135deg, #172554 0%, #1d4ed8 52%, #22c55e 100%);
        color: #ffffff;
        border-radius: 20px;
        padding: 24px 28px;
        box-shadow: 0 24px 50px rgba(15, 23, 42, 0.16);
        margin-bottom: 24px;
    }

    .pl-hero-kicker {
        font-size: 12px;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.72);
        margin-bottom: 10px;
        display: block;
    }

    .pl-hero-title {
        font-size: 32px;
        line-height: 1.1;
        font-weight: 700;
        margin: 0;
        color: #ffffff !important;
    }

    .pl-hero-copy {
        color: rgba(255, 255, 255, 0.8);
        max-width: 760px;
        margin-top: 10px;
        font-size: 14px;
        line-height: 1.7;
    }

    .pl-toolbar {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        justify-content: flex-end;
        margin-top: 20px;
    }

    .pl-btn {
        border: 0;
        border-radius: 999px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
        cursor: pointer;
        text-decoration: none;
    }

    .pl-btn:hover {
        transform: translateY(-1px);
    }

    .pl-btn-primary {
        background: #0f172a;
        color: #ffffff !important;
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.18);
    }

    .pl-btn-primary:hover {
        background: #1e293b;
    }

    .pl-btn-secondary {
        background: rgba(4, 190, 35, 0.842);
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.24);
    }

    .pl-btn-neutral {
        background: #e2e8f0;
        color: #0f172a !important;
    }

    .pl-btn-neutral:hover {
        background: #cbd5e1;
    }

    .pl-card {
        border: 1px solid #dbe5f0;
        border-radius: 20px !important;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        overflow: hidden;
        margin-bottom: 24px;
        background: #fff;
    }

    .pl-card-head {
        padding: 20px 24px;
        border-bottom: 1px solid #e5edf5;
        background: #fff;
        border-radius: 20px 20px 0 0 !important;
    }

    .pl-card-title {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
    }

    .pl-card-copy {
        margin-top: 6px;
        color: #64748b;
        font-size: 14px;
    }

    .pl-card-body {
        padding: 24px;
        background: #fff;
    }

    .pl-table-shell {
        border: 1px solid #dbe5f0;
        border-radius: 18px;
        overflow: hidden;
    }

    .pl-table-shell table {
        margin: 0 !important;
        border: none !important;
    }
    
    .pl-table-shell thead th {
        background: #eaf2ff !important;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
        color: #0f172a !important;
        border-bottom: 1px solid #dbe5f0;
        padding: 14px 18px !important;
    }

    .pl-table-shell tbody td {
        padding: 14px 18px !important;
        font-size: 14px;
        color: #334155;
    }

    /* Modal styles from safetyboard details modal */
    .pl-modal-overlay {
        position: fixed;
        inset: 0;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 23, 42, 0.66);
        backdrop-filter: blur(4px);
        padding: 20px;
        display: none; /* Controlled via JS */
        align-items: center;
        justify-content: center;
        z-index: 99999 !important;
    }

    .pl-modal-overlay.active,
    .pl-modal-overlay.show,
    .pl-modal-overlay[style*="display: flex"],
    .pl-modal-overlay[style*="display: block"] {
        display: flex !important;
        opacity: 1 !important;
        visibility: visible !important;
    }

    .pl-modal-panel {
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 28px 60px rgba(15, 23, 42, 0.28);
        border: 1px solid rgba(148, 163, 184, 0.2);
        background: #ffffff;
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        animation: modalFadeIn 0.3s ease-out;
    }

    .pl-modal-panel-lg {
        max-width: 800px;
    }

    .pl-modal-panel form {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        min-height: 0;
        overflow: hidden;
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .pl-modal-header {
        padding: 20px 24px;
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 46%, #0f766e 100%);
        color: #ffffff;
        flex-shrink: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pl-modal-title {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        line-height: 1.1;
        color: #ffffff !important;
    }

    .pl-modal-close {
        width: 36px;
        height: 36px;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.22);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        background: rgba(255, 255, 255, 0.08);
        font-size: 20px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s ease;
        line-height: 1;
    }

    .pl-modal-close:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.05);
    }

    .pl-modal-body {
        padding: 24px;
        overflow-y: auto;
        flex-grow: 1;
    }

    .pl-modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #e5edf5;
        background: #f8fafc;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    .form-group-custom {
        margin-bottom: 16px;
    }

    .form-group-custom label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 6px;
    }

    .form-control-custom {
        width: 100%;
        background-color: #f8fafc;
        border: 1px solid #cbd5e1;
        color: #1e293b;
        font-size: 14px;
        border-radius: 12px;
        padding: 10px 14px;
        transition: all 0.2s ease;
    }

    .form-control-custom:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        outline: none;
        background-color: #fff;
    }

    .error-msg {
        color: #ef4444;
        font-size: 12px;
        margin-top: 4px;
        display: block;
    }
    
    .border-error {
        border-color: #ef4444 !important;
        background-color: #fef2f2 !important;
    }
</style>
