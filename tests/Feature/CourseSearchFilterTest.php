<?php

namespace Tests\Feature;

use App\Models\Course;
use Database\Seeders\CourseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseSearchFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        (new CourseSeeder())->run();
    }

    public function test_courses_index_page_loads_with_search_and_filter_form(): void
    {
        $response = $this->get(route('courses.index'));

        $response->assertStatus(200);
        $response->assertViewIs('courses.index');
        $response->assertSee('id="course-filter-form"', false);
        $response->assertSee('id="course-search-input"', false);
        $response->assertSee('id="course-status-filter"', false);
        $response->assertSee('id="courses-table-container"', false);
        $response->assertSee('Semua Status');
        $response->assertSee('Cari');
    }

    public function test_courses_search_by_code_partial_and_exact(): void
    {
        // Exact code search
        $responseExact = $this->get(route('courses.index', ['search' => 'WEB-101']));
        $responseExact->assertStatus(200);
        $responseExact->assertSee('Web Dasar');
        $responseExact->assertDontSee('PHP Fundamental');
        $responseExact->assertDontSee('Laravel Dasar');

        // Partial code search
        $responsePartial = $this->get(route('courses.index', ['search' => 'LRV']));
        $responsePartial->assertStatus(200);
        $responsePartial->assertSee('Laravel Dasar');
        $responsePartial->assertDontSee('Web Dasar');
    }

    public function test_courses_search_by_name_partial_and_exact(): void
    {
        // Exact / full name keyword
        $response = $this->get(route('courses.index', ['search' => 'Bootstrap UI']));
        $response->assertStatus(200);
        $response->assertSee('Bootstrap UI');
        $response->assertDontSee('PHP Fundamental');

        // Partial name keyword
        $responsePartial = $this->get(route('courses.index', ['search' => 'Fundamental']));
        $responsePartial->assertStatus(200);
        $responsePartial->assertSee('PHP Fundamental');
        $responsePartial->assertDontSee('Web Dasar');
    }

    public function test_courses_search_is_case_insensitive(): void
    {
        // Lowercase search for uppercase code
        $responseLower = $this->get(route('courses.index', ['search' => 'web-101']));
        $responseLower->assertStatus(200);
        $responseLower->assertSee('WEB-101');
        $responseLower->assertSee('Web Dasar');

        // Uppercase search for mixed-case name
        $responseUpper = $this->get(route('courses.index', ['search' => 'LARAVEL']));
        $responseUpper->assertStatus(200);
        $responseUpper->assertSee('LRV-101');
        $responseUpper->assertSee('Laravel Dasar');
    }

    public function test_courses_filter_by_status_open(): void
    {
        $response = $this->get(route('courses.index', ['status' => 'open']));

        $response->assertStatus(200);
        // Open courses should be present
        $response->assertSee('Web Dasar');
        $response->assertSee('PHP Fundamental');
        $response->assertSee('Laravel Dasar');
        $response->assertSee('MySQL Dasar');
        $response->assertSee('JavaScript Dasar');

        // Draft and closed courses should NOT be present
        $response->assertDontSee('Bootstrap UI'); // draft
        $response->assertDontSee('jQuery dan Ajax'); // draft
        $response->assertDontSee('REST API Dasar'); // closed
    }

    public function test_courses_filter_by_status_closed(): void
    {
        $response = $this->get(route('courses.index', ['status' => 'closed']));

        $response->assertStatus(200);
        $response->assertSee('REST API Dasar');
        $response->assertDontSee('Web Dasar');
        $response->assertDontSee('Bootstrap UI');
    }

    public function test_courses_filter_by_status_draft(): void
    {
        $response = $this->get(route('courses.index', ['status' => 'draft']));

        $response->assertStatus(200);
        $response->assertSee('Bootstrap UI');
        $response->assertSee('jQuery dan Ajax');
        $response->assertDontSee('Web Dasar');
        $response->assertDontSee('REST API Dasar');
    }

    public function test_courses_combined_search_and_filter(): void
    {
        // Search 'Dasar' + status 'draft' should return nothing (no draft course has 'Dasar')
        $responseEmpty = $this->get(route('courses.index', [
            'search' => 'Dasar',
            'status' => 'draft',
        ]));
        $responseEmpty->assertStatus(200);
        $responseEmpty->assertSee('Kursus tidak ditemukan');
        $responseEmpty->assertDontSee('Web Dasar');

        // Search 'Dasar' + status 'open' should return open 'Dasar' courses
        $responseMatch = $this->get(route('courses.index', [
            'search' => 'Dasar',
            'status' => 'open',
        ]));
        $responseMatch->assertStatus(200);
        $responseMatch->assertSee('Web Dasar');
        $responseMatch->assertSee('Laravel Dasar');
        $responseMatch->assertSee('MySQL Dasar');
        $responseMatch->assertSee('JavaScript Dasar');
        $responseMatch->assertDontSee('REST API Dasar'); // closed
    }

    public function test_courses_pagination_limits_to_10_per_page(): void
    {
        // Seed 5 more courses so total is 13 (CourseSeeder has 8)
        for ($i = 1; $i <= 5; $i++) {
            Course::create([
                'code' => 'EXTRA-' . $i,
                'name' => 'Extra Test Course ' . $i,
                'fee' => 200000,
                'quota' => 15,
                'start_date' => '2026-12-01',
                'status' => 'open',
            ]);
        }

        $this->assertEquals(13, Course::count());

        // Page 1
        $resPage1 = $this->get(route('courses.index'));
        $resPage1->assertStatus(200);
        $coursesPage1 = $resPage1->viewData('courses');
        $this->assertCount(10, $coursesPage1);
        $this->assertEquals(13, $coursesPage1->total());
        $this->assertEquals(2, $coursesPage1->lastPage());
        $resPage1->assertSee('page=2');

        // Page 2
        $resPage2 = $this->get(route('courses.index', ['page' => 2]));
        $resPage2->assertStatus(200);
        $coursesPage2 = $resPage2->viewData('courses');
        $this->assertCount(3, $coursesPage2);
    }

    public function test_courses_pagination_retains_search_and_filter_query_parameters(): void
    {
        // Seed 15 open courses matching search keyword 'PaginateTest'
        for ($i = 1; $i <= 15; $i++) {
            Course::create([
                'code' => 'PAG-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'name' => 'PaginateTest Course ' . $i,
                'fee' => 200000,
                'quota' => 15,
                'start_date' => '2026-12-01',
                'status' => 'open',
            ]);
        }

        $response = $this->get(route('courses.index', [
            'search' => 'PaginateTest',
            'status' => 'open',
        ]));

        $response->assertStatus(200);
        // Link to page 2 should preserve both search and status params
        $response->assertSee('search=PaginateTest');
        $response->assertSee('status=open');
    }

    public function test_courses_empty_state_when_search_yields_no_results(): void
    {
        $response = $this->get(route('courses.index', ['search' => 'KeywordTidakMungkinAda123']));

        $response->assertStatus(200);
        $response->assertSee('Kursus tidak ditemukan');
        $response->assertSee('KeywordTidakMungkinAda123');
        $response->assertSee('Reset Filter &amp; Pencarian', false);
    }

    public function test_courses_empty_state_when_filter_yields_no_results(): void
    {
        // All 8 courses exist, but let's delete closed ones or search closed + nonexistent
        Course::where('status', 'closed')->delete();

        $response = $this->get(route('courses.index', ['status' => 'closed']));

        $response->assertStatus(200);
        $response->assertSee('Kursus tidak ditemukan');
        $response->assertSee('Tidak ada kursus dengan status');
        $response->assertSee('closed');
        $response->assertSee('Reset Filter &amp; Pencarian', false);
    }

    public function test_courses_empty_state_when_database_is_empty(): void
    {
        Course::query()->delete();

        $response = $this->get(route('courses.index'));

        $response->assertStatus(200);
        $response->assertSee('Belum ada data kursus.');
        $response->assertSee('Tambah Kursus Pertama');
    }

    public function test_courses_ajax_request_returns_table_partial(): void
    {
        $response = $this->get(route('courses.index', ['search' => 'PHP']), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('courses.partials.table');
        $response->assertSee('PHP Fundamental');
        $response->assertDontSee('<!DOCTYPE html>', false);
        $response->assertDontSee('<header class="site-header">', false);
    }

    public function test_courses_fallback_non_js_request_works_seamlessly(): void
    {
        // Request without XMLHttpRequest header
        $response = $this->get(route('courses.index', ['search' => 'PHP']));

        $response->assertStatus(200);
        $response->assertViewIs('courses.index');
        $response->assertSee('<!DOCTYPE html>', false);
        $response->assertSee('PHP Fundamental');
        $response->assertSee('id="course-filter-form"', false);
    }

    public function test_courses_catalog_seeder_populates_twenty_four_courses_and_paginates_properly(): void
    {
        (new \Database\Seeders\CourseCatalogSeeder())->run();

        // 8 original + 16 new = 24 courses
        $this->assertEquals(24, Course::count());

        // Page 1
        $resPage1 = $this->get(route('courses.index'));
        $resPage1->assertStatus(200);
        $coursesPage1 = $resPage1->viewData('courses');
        $this->assertCount(10, $coursesPage1);
        $this->assertEquals(24, $coursesPage1->total());
        $this->assertEquals(3, $coursesPage1->lastPage());
        $resPage1->assertSee('page=2');

        // Page 2
        $resPage2 = $this->get(route('courses.index', ['page' => 2]));
        $resPage2->assertStatus(200);
        $coursesPage2 = $resPage2->viewData('courses');
        $this->assertCount(10, $coursesPage2);

        // Page 2 with status filter retains query param
        $resFilterPage2 = $this->get(route('courses.index', ['status' => 'open', 'page' => 2]));
        $resFilterPage2->assertStatus(200);
        $coursesFilterPage2 = $resFilterPage2->viewData('courses');
        $this->assertCount(3, $coursesFilterPage2); // 13 open total: 10 on page 1, 3 on page 2
        $resFilterPage2->assertSee('status=open');
    }
}
