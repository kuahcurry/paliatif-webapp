{{-- Shared admin panel styles - include this in all admin views --}}
<style>
    :root {
        --surface: #ffffff;
        --surface-muted: #f7faf5;
        --border: #e2e8f0;
        --text: #0f172a;
        --muted: #64748b;
        --accent: #4f9b4f;
        --shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
    }

    /* ── Base ── */
    .admin-page {
        background: #f4f6fb;
        font-family: 'Outfit', ui-sans-serif, system-ui, -apple-system, sans-serif;
    }

    .admin-layout {
        display: grid;
        grid-template-columns: 260px 1fr;
        min-height: 100vh;
    }

    .admin-sidebar {
        background: var(--surface);
        padding: 28px 20px;
        border-right: 1px solid #edf2f7;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* ── Sidebar ── */
    .sidebar-brand { display: flex; gap: 12px; align-items: center; font-weight: 700; }
    .brand-icon { width: 42px; height: 42px; border-radius: 16px; background: #dff3df; display: grid; place-items: center; }
    .brand-icon span { width: 22px; height: 22px; border-radius: 999px; background: #63b96b; display: block; }
    .sidebar-brand h1 { font-size: 0.95rem; color: var(--text); }
    .sidebar-brand p { font-size: 0.8rem; color: var(--muted); }
    .sidebar-brand small { font-size: 0.7rem; color: #94a3b8; }
    .sidebar-nav { display: flex; flex-direction: column; gap: 10px; }
    .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 12px; color: var(--muted); text-decoration: none; font-size: 0.9rem; transition: all 0.15s ease; }
    .nav-icon { width: 20px; height: 20px; display: grid; place-items: center; color: currentColor; }
    .nav-icon svg { width: 18px; height: 18px; }
    .nav-item.is-active, .nav-item:hover { background: #e1f1e1; color: #256c32; }
    .sidebar-footer { margin-top: auto; }
    .footer-card { background: #f8fafc; border-radius: 16px; padding: 16px; font-size: 0.75rem; color: var(--muted); }
    .footer-title { font-weight: 700; color: var(--text); }
    .footer-subtitle { margin-bottom: 6px; }

    /* ── Main content ── */
    .admin-main {
        padding: 26px 32px 48px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    /* ── Topbar ── */
    .admin-topbar { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; }
    .admin-topbar .topbar-title-wrapper { grid-column: 2; text-align: center; }
    .admin-topbar .ghost-button { grid-column: 1; justify-self: start; }
    .admin-topbar .topbar-actions { grid-column: 3; justify-self: end; }
    .admin-topbar h2 { font-size: 1.4rem; font-weight: 700; }
    .admin-topbar p { color: var(--muted); font-size: 0.85rem; }
    .topbar-actions { display: flex; align-items: center; gap: 10px; }
    .icon-button { position: relative; border: none; background: #fff; box-shadow: var(--shadow); border-radius: 12px; width: 38px; height: 38px; cursor: pointer; display: grid; place-items: center; color: #475569; text-decoration: none; font-size: 1rem; font-weight: 600; }
    .icon-button svg { width: 20px; height: 20px; }
    .badge { position: absolute; top: -4px; right: -4px; background: #22c55e; color: #fff; font-size: 0.65rem; width: 18px; height: 18px; border-radius: 999px; display: grid; place-items: center; }
    .user-chip { display: flex; align-items: center; gap: 10px; background: #fff; border-radius: 16px; padding: 6px 12px; box-shadow: var(--shadow); border: none; cursor: pointer; font-family: inherit; }
    .avatar { width: 34px; height: 34px; border-radius: 999px; background: #e0f2fe; color: #1d4ed8; font-weight: 700; display: grid; place-items: center; font-size: 0.85rem; }
    .user-name { font-size: 0.85rem; font-weight: 600; color: var(--text); text-align: left; }
    .user-role { font-size: 0.75rem; color: var(--muted); text-align: left; }
    .chevron { color: var(--muted); }

    /* ── Cards ── */
    .admin-content { display: grid; gap: 12px; }
    .card {
        background: var(--surface);
        border-radius: 20px;
        padding: 24px;
        box-shadow: var(--shadow);
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .card-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .section-header h4 { font-weight: 700; font-size: 1rem; }
    .section-header p { font-size: 0.78rem; color: var(--muted); }
    .header-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

    /* ── Stats ── */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 12px; }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 14px;
        background: #f8fafc;
        border-radius: 16px;
        padding: 16px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: grid;
        place-items: center;
        font-weight: 700;
        font-size: 1rem;
        color: #fff;
        flex-shrink: 0;
    }

    .stat-icon.bg-purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .stat-icon.bg-blue { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .stat-icon.bg-green { background: linear-gradient(135deg, #22c55e, #16a34a); }
    .stat-icon.bg-amber { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .stat-icon.bg-slate { background: linear-gradient(135deg, #64748b, #475569); }
    .stat-label { font-size: 0.72rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.03em; }
    .stat-value { font-size: 1.5rem; font-weight: 800; color: var(--text); line-height: 1.2; }

    /* ── User list ── */
    .user-list { display: grid; gap: 8px; }
    .user-row {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #f8fafc;
        border-radius: 12px;
        padding: 10px 14px;
        transition: background 0.15s ease;
    }
    .user-row:hover { background: #f1f5f9; }
    .user-avatar { width: 36px; height: 36px; border-radius: 999px; background: #e2f4e2; display: grid; place-items: center; font-weight: 700; font-size: 0.8rem; color: #2f855a; flex-shrink: 0; }
    .user-email { font-size: 0.72rem; color: var(--muted); }
    .empty-state { font-size: 0.85rem; color: var(--muted); text-align: center; padding: 16px; }

    /* ── Filters ── */
    .filter-inline { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
    .filter-inline select,
    .filter-inline input[type="text"] {
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 8px 12px;
        font-size: 0.78rem;
        font-family: inherit;
        background: #f8fafc;
        color: var(--text);
        outline: none;
        transition: border-color 0.2s ease;
    }
    .filter-inline select:focus,
    .filter-inline input:focus {
        border-color: var(--accent);
        background: #fff;
    }

    /* ── Buttons ── */
    .ghost-button {
        border: 1.5px solid #e2e8f0;
        background: #fff;
        color: #475569;
        border-radius: 12px;
        padding: 8px 14px;
        font-size: 0.78rem;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: inherit;
        font-weight: 500;
        transition: all 0.15s ease;
    }
    .ghost-button:hover { background: #f1f5f9; border-color: #cbd5e1; }

    .primary-button {
        background: linear-gradient(135deg, #4f9b4f, #3d8b3d);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 8px 18px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: inherit;
        transition: all 0.15s ease;
        box-shadow: 0 2px 8px rgba(79, 155, 79, 0.25);
    }
    .primary-button:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(79, 155, 79, 0.35); }

    .danger-button {
        background: none;
        border: 1.5px solid #fca5a5;
        color: #dc2626;
        border-radius: 10px;
        padding: 5px 14px;
        font-size: 0.75rem;
        cursor: pointer;
        font-family: inherit;
        font-weight: 500;
        transition: all 0.15s ease;
    }
    .danger-button:hover { background: #fef2f2; border-color: #f87171; }

    /* ── Alerts ── */
    .alert {
        padding: 12px 18px;
        border-radius: 14px;
        font-size: 0.82rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .alert.success { background: #ecfdf3; color: #15803d; border: 1px solid #bbf7d0; }
    .alert.warn { background: #fff7ed; color: #92400e; border: 1px solid #fed7aa; }

    /* ── Tables ── */
    .table-wrap { overflow-x: auto; border-radius: 14px; border: 1px solid #f1f5f9; }
    table { width: 100%; font-size: 0.82rem; border-collapse: collapse; }
    thead { background: #f8fafc; }
    th {
        text-align: left;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--muted);
        padding: 10px 14px;
        font-weight: 600;
        white-space: nowrap;
    }
    td {
        padding: 12px 14px;
        border-top: 1px solid #f1f5f9;
        color: var(--text);
        vertical-align: top;
        white-space: nowrap;
    }
    tr:hover td { background: #fafbfd; }
    td.nowrap { white-space: nowrap; }
    td.truncate { max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

    /* ── Pills & Tags ── */
    .pill {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .pill.green { background: #dcfce7; color: #15803d; }
    .pill.purple { background: #f3e8ff; color: #7c3aed; }
    .pill.slate { background: #f1f5f9; color: #475569; }
    .pill.amber { background: #fef3c7; color: #92400e; }
    .pill.rose { background: #fce7f3; color: #be123c; }
    .tags { display: flex; flex-wrap: wrap; gap: 4px; }
    .tag { background: #f1f5f9; padding: 3px 10px; border-radius: 999px; font-size: 0.7rem; color: #475569; }

    /* ── Misc ── */
    .fw-600 { font-weight: 600; }
    .text-muted { font-size: 0.75rem; color: var(--muted); }
    .action-group { display: flex; gap: 6px; align-items: center; }
    .empty-row { text-align: center; color: var(--muted); padding: 32px; font-size: 0.85rem; }

    .resp-form { display: flex; flex-direction: column; gap: 6px; }
    .resp-form textarea {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 8px 10px;
        font-size: 0.78rem;
        font-family: inherit;
        width: 100%;
        resize: vertical;
        min-height: 60px;
        outline: none;
        transition: border-color 0.2s ease;
    }
    .resp-form textarea:focus { border-color: var(--accent); }

    /* ── Form fields (create/edit) ── */
    .field { display: flex; flex-direction: column; gap: 6px; }
    .field label { font-size: 0.82rem; font-weight: 600; color: #334155; }
    .field input[type="text"],
    .field input[type="url"],
    .field textarea,
    .field select {
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 0.85rem;
        font-family: inherit;
        color: var(--text);
        background: #f8fafc;
        outline: none;
        transition: all 0.2s ease;
    }
    .field input:focus,
    .field textarea:focus,
    .field select:focus {
        border-color: var(--accent);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(79, 155, 79, 0.1);
    }

    .form-grid { display: grid; gap: 16px; }
    .form-actions {
        display: flex;
        gap: 10px;
        padding-top: 8px;
        border-top: 1px solid #f1f5f9;
    }

    /* ── Pagination ── */
    .admin-main nav[role="navigation"] { margin-top: 4px; }
    .admin-main nav[role="navigation"] .flex { display: flex; gap: 4px; align-items: center; flex-wrap: wrap; }
    .admin-main nav[role="navigation"] a,
    .admin-main nav[role="navigation"] span {
        padding: 6px 12px;
        border-radius: 10px;
        font-size: 0.75rem;
        border: 1px solid #e2e8f0;
        color: var(--muted);
        text-decoration: none;
        display: inline-flex;
    }
    .admin-main nav[role="navigation"] span[aria-current="page"] span {
        background: var(--accent);
        color: #fff;
        border-color: var(--accent);
    }
    .admin-main nav[role="navigation"] .relative:first-child,
    .admin-main nav[role="navigation"] .relative:last-child { display: none; }

    /* ── Responsive ── */
    @media (max-width: 900px) {
        .admin-layout { grid-template-columns: 1fr; }
        .admin-main { padding: 16px; }
        .admin-topbar { display: flex; flex-direction: column; align-items: flex-start; gap: 12px; }
        .admin-topbar .topbar-title-wrapper { text-align: left; }
        .admin-topbar .topbar-actions { justify-self: auto; align-self: flex-start; }
        .card-grid-2 { grid-template-columns: 1fr; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); }

        /* Mobile Card Tables */
        .table-wrap {
            border: none;
            background: transparent;
            overflow: visible;
            border-radius: 0;
            padding: 0;
        }

        table, thead, tbody, th, td, tr {
            display: block;
        }

        thead tr {
            display: none;
        }

        tr {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            margin-bottom: 16px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }

        tr:hover td {
            background: transparent;
        }

        td {
            border: none;
            border-bottom: 1px solid #f1f5f9;
            position: relative;
            padding: 12px 0 12px 105px !important;
            min-height: 46px;
            text-align: left;
            white-space: normal !important;
            display: flex;
            align-items: center;
        }

        td.nowrap {
            white-space: normal !important;
        }

        td:last-child {
            border-bottom: 0;
        }

        td::before {
            content: attr(data-label);
            position: absolute;
            left: 0;
            top: 12px;
            width: 95px;
            padding-right: 10px;
            font-weight: 700;
            font-size: 0.72rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .action-group {
            flex-wrap: wrap;
        }
    }
</style>
