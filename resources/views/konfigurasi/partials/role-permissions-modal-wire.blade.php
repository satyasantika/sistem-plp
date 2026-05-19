{{-- Aktivitas ringan untuk modal hak role (tersaring, buka tutup accordion, cepat +/- kategori). --}}
<script>
(function($) {
    'use strict';

    const NS = '.rolePermModalEnh';

    function rpPickModalShell() {
        return $('div.modal-content[data-perm-modal]').filter(function () {
            return ($(this).find('#formAction').length || 0) > 0;
        }).first();
    }

    function rpInstCollapses(show) {
        const modalShell = rpPickModalShell();

        if (! modalShell.length || !window.bootstrap || !window.bootstrap.Collapse) {
            return;
        }

        modalShell.find('.accordion-collapse').each(function handleRegion(_, region) {
            const inst = window.bootstrap.Collapse.getOrCreateInstance(region, { toggle: false });
            try {
                show ? inst.show() : inst.hide();
            } catch (e) {}
        });
    }

    function qpNormalize(value) {
        return String(value || '')
            .toLowerCase()
            .normalize('NFKD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/\s+/g, ' ')
            .trim();
    }

    function rpEligibleCheckbox($chk) {
        const header = $chk.closest('.accordion-item');
        if (! header.length) {
            return true;
        }

        if (header.hasClass('d-none')) {
            return false;
        }

        const $row = $chk.closest('.rp-res-item');

        return ! $row.length || ! $row.hasClass('d-none');
    }

    function rpSyncFooterCount(modalShell) {
        const badge = modalShell.find('[data-rp-checked-count]');
        const form = modalShell.find('#formAction').first();

        if (! badge.length || ! form.length) {
            return;
        }

        let n = 0;
        modalShell.find('input[name="permission[]"]:checked').each(function counting() {
            if (rpEligibleCheckbox($(this))) {
                n += 1;
            }
        });

        const max = parseInt(form.attr('data-rp-permission-total') || '0', 10);
        if (max && n > max) {
            n = max;
        }

        badge.text(String(n));
    }

    function rpSearchSnapshot(rawVal) {
        const trimmedNorm = qpNormalize(rawVal || '');
        const tokens = trimmedNorm.split(' ').filter(Boolean).join(' ').trim();
        const keywordList = tokens.length ? trimmedNorm.split(/\s+/).filter(Boolean) : [];

        return {
            raw: String(rawVal ?? ''),
            tokens,
            keywordList,
            trimmed: tokens.length > 0,
            forceShow: trimmedNorm.includes('+++'),
        };
    }

    /** @returns {boolean} */
    function rpHayMatchesEveryKeyword(normalizedHaystack, keywordList, forceShow) {
        if (forceShow || !keywordList.length) {
            return true;
        }

        let ok = true;
        keywordList.forEach(function iteratingKeyword(fragment) {
            if (!normalizedHaystack.includes(fragment)) {
                ok = false;
            }
        });

        return ok;
    }

    function rpToggleSearch(ui) {
        const modalShell = rpPickModalShell();
        const $notice = modalShell.find('#rolePermFilterNotice');
        const $bulkReset = modalShell.find('#rolePermUncheckFiltered');

        if (! modalShell.find('#rolePermQuickFilter').length) {
            return;
        }

        if (! ui.trimmed) {
            modalShell.find('.accordion-item').removeClass('d-none');
            modalShell.find('.rp-res-item').removeClass('d-none');
            if ($notice.length) {
                $notice.attr('hidden', true).hide();
            }

            if ($bulkReset.length) {
                $bulkReset.attr('hidden', true).hide();
            }

            rpSyncFooterCount(modalShell);

            return;
        }

        if ($notice.length) {
            $notice.removeAttr('hidden').show();
        }

        if ($bulkReset.length) {
            $bulkReset.removeAttr('hidden').show();
        }

        modalShell.find('.accordion-item').each(function iteratingCategory() {
            const $pane = $(this);
            const collapseData = $pane.find('[data-acc-search-normalized]').first();
            const hayCategory = qpNormalize(collapseData.attr('data-acc-search-normalized') || '');
            const categoryMatched =
                rpHayMatchesEveryKeyword(hayCategory, ui.keywordList || [], !! ui.forceShow);
            let anyVisible = !! categoryMatched;

            $pane.find('.rp-res-item').each(function iteratingRow() {
                const $card = $(this);
                const rowHaystack = qpNormalize($card.attr('data-row-search-normalized') || '');
                const rowMatched =
                    rpHayMatchesEveryKeyword(rowHaystack, ui.keywordList || [], !! ui.forceShow);
                const match = ui.forceShow || categoryMatched || rowMatched;
                const hide = ! match;

                $card.toggleClass('d-none', hide);
                if (! hide) {
                    anyVisible = true;
                }
            });

            $pane.toggleClass('d-none', ! anyVisible);
        });

        rpSyncFooterCount(modalShell);
    }

    function rpBindOnce() {
        if (window.__rolePermissionModalUxBound__) {
            return;
        }

        window.__rolePermissionModalUxBound__ = true;

        $(document).on('input'+NS+' keyup'+NS, '#rolePermQuickFilter', function handleTyping() {
            rpToggleSearch(rpSearchSnapshot($(this).val()));
        });

        $(document).on('change'+NS,
            '[data-perm-modal] #formAction input[type="checkbox"][name="permission[]"]',
            function pivot() {
            rpSyncFooterCount(rpPickModalShell());
        });

        $(document).on('click'+NS, '#rolePermExpandAll', function expanding(event) {
            event.preventDefault();
            rpInstCollapses(true);
        });

        $(document).on('click'+NS, '#rolePermCollapseAll', function collapsing(event) {
            event.preventDefault();
            rpInstCollapses(false);
        });

        $(document).on('click'+NS, '#rolePermUncheckFiltered', function resetting(event) {
            event.preventDefault();
            const shell = rpPickModalShell();

            shell.find('input[type="checkbox"][name="permission[]"]:checked').each(function uncheckEligible() {
                const $chk = $(this);

                if (rpEligibleCheckbox($chk)) {
                    $chk.prop('checked', false);
                }
            });

            rpSyncFooterCount(shell);
        });

        $(document).on('click'+NS, '.js-role-perm-category-toggle', function flipping(event) {
            event.preventDefault();
            event.stopPropagation();

            const $category = $(this).closest('.accordion-item');
            if (! $category.length || $category.hasClass('d-none')) {
                return;
            }

            const $inputs = $category.find('.accordion-collapse input[type="checkbox"][name="permission[]"]');
            const usable = $inputs.filter(function filterEnabled() {
                return rpEligibleCheckbox($(this));
            });

            if (! usable.length) {
                return;
            }

            const allSelected = usable.length === usable.filter(':checked').length;
            usable.prop('checked', ! allSelected);

            rpSyncFooterCount(rpPickModalShell());
        });
    }

    window.wireKonfigPermissionModal = function () {
        rpBindOnce();
        rpToggleSearch(rpSearchSnapshot($('#rolePermQuickFilter').val() || ''));
        rpSyncFooterCount(rpPickModalShell());
    };

    window.wireKonfigRolesPermissionModal = window.wireKonfigPermissionModal;
})(jQuery);
</script>
