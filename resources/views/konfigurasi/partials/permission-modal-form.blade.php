{{--
 Form modal permission (dipakai role & user).
 Variabel wajib:
 $pmAccordionId, $pmFormAction, $pmHiddenNameValue, $pmPermissionUi, $pmCheckedIds, $pmInputIdPrefix,
 $pmModalTitle, $pmSubhead
 Opsional: $pmBannerHtml (string HTML aman), $pmFilterInputId (default rolePermQuickFilter)
--}}
@php
    $cats = $pmPermissionUi['categories'] ?? [];
    $filterInputId = $pmFilterInputId ?? 'rolePermQuickFilter';
@endphp

<style>
    .rp-modal {
        border: 0;
        border-radius: 16px;
        overflow: hidden;
    }

    .rp-modal .modal-header {
        border-bottom: 1px solid rgba(45, 70, 102, 0.12);
        background: linear-gradient(135deg, #f9fbff 0%, #edf3ff 100%);
    }

    .rp-modal .rp-subhead {
        font-size: 0.8125rem;
        color: #5f7291;
        font-weight: 500;
        margin-top: 0.15rem;
    }

    .rp-toolbar {
        position: sticky;
        top: -1px;
        z-index: 3;
        background: linear-gradient(180deg, #ffffff 0%, #fafcff 95%);
        border: 1px solid #e6ecf7;
        border-radius: 12px;
        padding: 0.65rem 0.85rem;
        margin-bottom: 0.75rem;
        box-shadow: 0 6px 18px rgba(19, 38, 67, 0.05);
        gap: 0.75rem;
    }

    .rp-toolbar .rp-search-wrap {
        min-width: 220px;
    }

    body.dark .rp-toolbar {
        background: linear-gradient(180deg, #1b273a 0%, #172234 96%);
        border-color: rgba(160, 184, 217, 0.18);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.32);
    }

    .rp-accordion-item {
        margin-bottom: 0.65rem;
        border: 1px solid rgba(62, 90, 134, 0.15);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 24px rgba(19, 38, 67, 0.04);
    }

    body.dark .rp-accordion-item {
        border-color: rgba(160, 184, 217, 0.15);
        background: transparent;
        box-shadow: none;
    }

    .rp-accordion-item .accordion-button {
        padding: 0.7rem 0.95rem;
        font-weight: 600;
        color: #1f3049;
        background: linear-gradient(90deg, #f6faff 0%, #f1f7ff 100%);
        gap: 0.5rem;
    }

    .rp-accordion-item .accordion-button:not(.collapsed) {
        border-bottom: 1px solid rgba(62, 90, 134, 0.1);
        background: linear-gradient(90deg, #eef6ff 0%, #e7f2ff 100%);
    }

    body.dark .rp-accordion-item .accordion-button {
        color: #e7efff;
        background: linear-gradient(90deg, #1e2b42 0%, #172236 100%);
    }

    .rp-chip {
        font-size: 0.6875rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        border-radius: 999px;
        padding: 0.12rem 0.52rem;
    }

    .rp-acc-heading .accordion-button {
        border-radius: 0;
    }

    .rp-acc-heading.rp-acc-heading {
        flex-wrap: nowrap;
    }

    .rp-resource-grid {
        display: grid;
        gap: 0.62rem;
    }

    @media (min-width: 992px) {
        .rp-resource-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    .rp-resource {
        padding: 0.65rem 0.82rem;
        border-radius: 10px;
        border: 1px solid rgba(62, 90, 134, 0.12);
        background: #fbfdff;
    }

    body.dark .rp-resource {
        background: #1f2d45;
        border-color: rgba(160, 184, 217, 0.12);
        color: #dbe8ff;
    }

    .rp-resource-meta {
        font-size: 0.75rem;
        color: #6b798f;
        font-family:
            ui-monospace,
            SFMono-Regular,
            Menlo,
            Monaco,
            Consolas,
            "Liberation Mono",
            monospace;
        word-break: break-word;
        line-height: 1.35;
    }

    body.dark .rp-resource-meta {
        color: rgba(215, 230, 255, 0.68);
    }

    .rp-action-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 0.52rem;
    }

    .rp-check {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.36rem 0.62rem;
        border-radius: 9px;
        border: 1px solid rgba(62, 90, 134, 0.18);
        background: rgba(255, 255, 255, 0.85);
        font-size: 0.784rem;
        font-weight: 500;
        cursor: pointer;
        user-select: none;
    }

    .rp-check:focus-within {
        outline: 2px solid rgba(13, 110, 253, 0.35);
        outline-offset: 1px;
    }

    body.dark .rp-check {
        background: #1a273c;
        border-color: rgba(160, 184, 217, 0.2);
        color: #eaf1ff;
    }

    .rp-check .badge {
        font-weight: 800;
        min-width: 1.42rem;
    }

    .rp-extras-stack {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .rp-extras-stack .rp-check {
        width: fit-content;
        max-width: 100%;
    }

    body.dark .rp-modal {
        background: #1b263b;
        color: #e7efff;
    }

    body.dark .rp-modal .modal-footer {
        border-top-color: rgba(160, 184, 217, 0.15);
        background: #182235;
    }

    .rp-footer-meta strong {
        color: var(--bs-primary, #0d6efd);
    }

    body.dark .rp-footer-meta strong {
        color: #8ec9ff;
    }

    .rp-acc-quick {
        min-width: 7.65rem;
    }
</style>

<div class="modal-content rp-modal" data-perm-modal>
    <form id="formAction"
        action="{{ $pmFormAction }}"
        method="post"
        data-rp-permission-total="{{ (int) ($pmPermissionUi['total_checkboxes'] ?? 0) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="name" value="{{ $pmHiddenNameValue }}">
        <div class="modal-header">
            <div class="flex-grow-1">
                <h5 class="modal-title mb-0" id="largeModalLabel">{{ $pmModalTitle }}</h5>
                <div class="rp-subhead">
                    {{ $pmSubhead }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body pb-4">
            @if (! empty($pmBannerHtml))
                <div class="mb-3">{!! $pmBannerHtml !!}</div>
            @endif

            @if (($pmPermissionUi['total_checkboxes'] ?? 0) > 0)
                <div class="d-flex flex-wrap align-items-start justify-content-between rp-toolbar gap-3">
                    <div class="flex-grow-1 rp-search-wrap">
                        <label class="visually-hidden" for="{{ $filterInputId }}">Filter izin</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 ps-2">
                                <i class="ti-search text-muted" aria-hidden="true"></i>
                            </span>
                            <input type="search"
                                id="{{ $filterInputId }}"
                                class="form-control border-start-0"
                                autocomplete="off"
                                placeholder="Cari modul, path, atau tindakan..."
                                aria-describedby="{{ $filterInputId }}Help">
                        </div>
                        <small id="{{ $filterInputId }}Help" class="form-text text-muted">
                            Pisahkan beberapa kata untuk hasil lebih spesifik. Contoh: «aktivitas studentdiaries».</small>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="rolePermExpandAll">
                            Buka semua</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="rolePermCollapseAll">
                            Ringkas semua</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="rolePermUncheckFiltered" hidden>
                            Hapus centang hasil filter
                        </button>
                    </div>
                </div>
            @endif

            @if (($pmPermissionUi['total_checkboxes'] ?? 0) > 0 && ($cats ?? []) !== [])
                <div class="accordion" id="{{ $pmAccordionId }}">
                    @foreach ($cats as $index => $cat)
                    @php
                        $catLabel = $cat['label'];
                        $catSlugSafe = preg_replace('/[^a-zA-Z0-9_-]/', '_', (string) $cat['slug']);
                        $headingId =
                            $pmAccordionId.'_h_'.$loop->iteration.'_'.$catSlugSafe;
                        $collapseId =
                            $pmAccordionId.'_c_'.$loop->iteration.'_'.$catSlugSafe;
                        $resources = $cat['resources'] ?? [];
                        $orphans = $cat['orphans'] ?? [];
                        $permCount = collect($resources)->sum(fn ($row) => count($row['actions'] ?? [])) + count($orphans);

                        $accordionSearchBlob = strtolower(
                            $catLabel.' '.$catSlugSafe.' '.$cat['slug'].' '.
                                collect($resources)->pluck('base')->implode(' ').' '.
                                collect($resources)->pluck('search_blob')->implode(' ').' '.
                                collect($orphans)->pluck('name')->implode(' '),
                        );
                        foreach ($resources as $__r) {
                            $accordionSearchBlob .= ' '.collect($__r['actions'] ?? [])
                                ->pluck('label')
                                ->filter()
                                ->implode(' ');
                        }

                        $accordionSearchBlobNormalized =
                            strtolower(preg_replace('/\s+/u', ' ', trim((string) $accordionSearchBlob)) ?? '');
                    @endphp
                    <div class="accordion-item rp-accordion-item rp-acc-pane position-relative">
                        <h2 id="{{ $headingId }}"
                            class="accordion-header rp-acc-heading d-flex flex-nowrap align-items-stretch gap-2 px-3 py-2 border-0 m-0">
                            <button
                                class="accordion-button flex-grow-1 d-flex justify-content-between align-items-center {{ $index === 0 ? '' : 'collapsed' }}"
                                type="button"
                                aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-controls="{{ $collapseId }}"
                                data-bs-toggle="collapse"
                                data-bs-target="#{{ $collapseId }}">
                                <span class="me-3 text-break text-start">{{ $catLabel }}</span>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary flex-shrink-0 rp-chip">{{ $permCount }}</span>
                            </button>
                            <button type="button"
                                class="btn btn-outline-primary btn-sm align-self-center flex-shrink-0 rp-acc-quick js-role-perm-category-toggle"
                                title="Alih seluruh centang dalam kategori ini">
                                +/- kategori
                            </button>
                        </h2>
                        <div id="{{ $collapseId }}"
                            class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                            aria-labelledby="{{ $headingId }}"
                            role="region"
                            data-acc-search-normalized="{{ e($accordionSearchBlobNormalized) }}">
                            <div class="accordion-body">
                                @if ($resources !== [])
                                    <div class="rp-resource-grid">
                                        @foreach ($resources as $resource)
                                            @php
                                                $__rows = strtolower(
                                                    implode(
                                                        ' ',
                                                        array_filter([(string) $resource['title'], $resource['base']]),
                                                    ),
                                                ).' '.$resource['search_blob'];
                                                $rowNormalized =
                                                    strtolower(preg_replace('/\s+/u', ' ', trim((string) $__rows)) ?? '');
                                                $resAriaId =
                                                    $pmAccordionId.'_res_'.$catSlugSafe.'_'.
                                                    (($loop->parent->iteration ?? 0) ?: 1).'_'.$loop->iteration;
                                            @endphp
                                            <div class="rp-resource rp-res-item"
                                                role="region"
                                                aria-labelledby="{{ $resAriaId }}"
                                                data-row-search-normalized="{{ e($rowNormalized) }}">
                                                <div class="fw-semibold text-break" id="{{ $resAriaId }}">
                                                    {{ $resource['title'] }}
                                                </div>
                                                <div class="rp-resource-meta">{{ $resource['base'] }}</div>
                                                <div class="rp-action-row">
                                                    @foreach ($resource['actions'] as $act)
                                                        @php($permissionId = $act['id'])
                                                        @php($inputId = $pmInputIdPrefix.$permissionId)
                                                        <label class="rp-check" for="{{ $inputId }}">
                                                            <input type="checkbox"
                                                                name="permission[]"
                                                                value="{{ $permissionId }}"
                                                                id="{{ $inputId }}"
                                                                class="form-check-input m-0"
                                                                @checked(isset($pmCheckedIds[$permissionId]) || isset($pmCheckedIds[(string) $permissionId]))>
                                                            <span
                                                                class="badge bg-{{ $act['key'] === 'delete'
                                                                    ? 'danger'
                                                                    : ($act['key'] === 'update'
                                                                        ? 'warning text-dark'
                                                                        : ($act['key'] === 'create'
                                                                            ? 'success'
                                                                            : 'primary')) }}">{{ $act['short'] }}</span>
                                                            <span>{{ $act['label'] }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if ($orphans !== [])
                                    @if ($resources !== [])
                                        <hr class="my-4 text-muted opacity-25">
                                        <p class="small text-muted mb-2 fw-semibold mb-3">Tanpa pola crud klasik</p>
                                    @endif
                                    <div class="rp-extras-stack">
                                        @foreach ($orphans as $orphan)
                                            @php($oid = $orphan['id'])
                                            @php($__on = strtolower((string) $orphan['name']))
                                            @php($inputIdOrph = $pmInputIdPrefix.'orph_'.$oid)
                                            <label class="rp-check rp-extras-item rp-res-item align-items-start"
                                                data-row-search-normalized="{{ e($__on) }}">
                                                <input type="checkbox"
                                                    name="permission[]"
                                                    value="{{ $oid }}"
                                                    class="form-check-input m-0 flex-shrink-0 mt-1"
                                                    id="{{ $inputIdOrph }}"
                                                    @checked(isset($pmCheckedIds[$oid]) || isset($pmCheckedIds[(string) $oid]))>
                                                <code class="small text-break d-inline">{{ $orphan['name'] }}</code>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            @endif

            @if (($pmPermissionUi['total_checkboxes'] ?? 0) === 0 || ($cats ?? []) === [])
                @if ($pmAssignableEmptyNotice ?? false)
                    <div class="alert alert-light border mb-0">
                        Semua permission untuk pengguna ini sudah tercakup lewat role yang melekat. Tidak ada izin
                        tambahan yang perlu diatur di sini.
                    </div>
                @else
                    <div class="alert alert-info mb-0">Belum ada data permission yang dapat diatur pada konteks ini.</div>
                @endif
            @endif

            @if (($pmPermissionUi['total_checkboxes'] ?? 0) > 0 && ($cats ?? []) !== [])
                <p class="text-muted small mt-4 mb-0" id="rolePermFilterNotice" hidden>
                    Menampilkan kategori/item yang cocok dengan pencarian. Baris yang disembunyikan tidak berubah
                    centangnya.
                </p>
            @endif
        </div>

        <div class="modal-footer d-flex flex-column flex-lg-row gap-3 align-items-stretch align-items-lg-center">
            <div class="text-start flex-grow-1 rp-footer-meta small text-muted">
                Tercentang:
                <strong data-rp-checked-count>{{ (int) ($pmPermissionUi['selected_count'] ?? 0) }}</strong>
                /
                {{ (int) ($pmPermissionUi['total_checkboxes'] ?? 0) }}
                izin{{ ($pmPermissionUi['total_checkboxes'] ?? 0) > 0 ? ' yang dapat diatur di sini' : '' }}.
            </div>
            <div class="ms-lg-auto d-flex flex-wrap gap-2 justify-content-end">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">
                    Simpan perubahan
                </button>
            </div>
        </div>
    </form>
</div>
