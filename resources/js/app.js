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
});
