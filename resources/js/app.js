import 'bootstrap';
import $ from 'jquery';

window.$ = window.jQuery = $;

$(function () {
    const $courseSelect = $('#course_id');
    const $availabilityArea = $('#course-availability');

    if ($courseSelect.length && $availabilityArea.length) {
        $courseSelect.on('change', function () {
            const courseId = $(this).val();

            if (!courseId) {
                $availabilityArea
                    .removeClass('alert-success alert-danger alert-warning')
                    .addClass('alert alert-secondary')
                    .text('Pilih kursus untuk melihat kuota dan status ketersediaan.');
                return;
            }

            $availabilityArea
                .removeClass('alert-success alert-danger alert-warning')
                .addClass('alert alert-secondary')
                .text('Memuat ketersediaan...');

            $.ajax({
                url: '/courses/' + encodeURIComponent(courseId) + '/availability',
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    const courseName = data.name || data.course || 'Kursus';
                    const courseCode = data.code ? ' (' + data.code + ')' : '';
                    const quotaCount = data.quota !== undefined ? data.quota : data.remaining;
                    const statusVal = data.status || 'open';

                    let statusClass = 'alert-success';
                    if (statusVal === 'draft') {
                        statusClass = 'alert-warning';
                    } else if (statusVal === 'closed') {
                        statusClass = 'alert-danger';
                    }

                    $availabilityArea
                        .removeClass('alert-secondary alert-success alert-warning alert-danger')
                        .addClass('alert ' + statusClass)
                        .text(courseName + courseCode + ' — Kuota: ' + quotaCount + ' peserta | Status: ' + statusVal + '.');
                },
                error: function () {
                    $availabilityArea
                        .removeClass('alert-secondary alert-success alert-warning')
                        .addClass('alert alert-danger')
                        .text('Gagal memuat ketersediaan kursus.');
                }
            });
        });
    }

    const $filterForm = $('#course-filter-form');
    const $tableContainer = $('#courses-table-container');

    if ($filterForm.length && $tableContainer.length) {
        const $searchInput = $('#course-search-input');
        const $statusFilter = $('#course-status-filter');
        let debounceTimer = null;
        let currentRequest = null;

        function fetchCourses(targetUrl) {
            let url = targetUrl;
            if (!url) {
                const formData = $filterForm.serialize();
                url = $filterForm.attr('action') + (formData ? '?' + formData : '');
            }

            if (currentRequest && currentRequest.readyState !== 4) {
                currentRequest.abort();
            }

            $tableContainer.addClass('is-loading');

            currentRequest = $.ajax({
                url: url,
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (html) {
                    $tableContainer.html(html);
                    $tableContainer.removeClass('is-loading');

                    if (window.history && window.history.pushState) {
                        window.history.pushState({ path: url }, '', url);
                    }

                    const searchVal = String($searchInput.val() ?? '').trim();
                    const statusVal = String($statusFilter.val() ?? '').trim();
                    const hasFilter = searchVal.length > 0 || statusVal.length > 0;
                    let $resetBtn = $('#course-reset-btn');

                    if (hasFilter) {
                        if (!$resetBtn.length) {
                            const resetHtml = '<a href="' + $filterForm.attr('action') + '" id="course-reset-btn" class="btn btn-outline-secondary" title="Reset Pencarian & Filter">Reset</a>';
                            $filterForm.find('button[type="submit"]').parent().append(resetHtml);
                        }
                    } else {
                        $resetBtn.remove();
                    }
                },
                error: function (xhr, status) {
                    $tableContainer.removeClass('is-loading');
                    if (status !== 'abort') {
                        $tableContainer.prepend(
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                            'Gagal memuat data kursus. Silakan coba lagi.' +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                            '</div>'
                        );
                    }
                }
            });
        }

        $searchInput.on('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () {
                fetchCourses();
            }, 300);
        });

        $statusFilter.on('change', function () {
            fetchCourses();
        });

        $filterForm.on('submit', function (e) {
            e.preventDefault();
            fetchCourses();
        });

        $tableContainer.on('click', '.pagination a', function (e) {
            e.preventDefault();
            const pageUrl = $(this).attr('href');
            if (pageUrl && pageUrl !== '#') {
                fetchCourses(pageUrl);
            }
        });

        window.addEventListener('popstate', function () {
            location.reload();
        });
    }
});
