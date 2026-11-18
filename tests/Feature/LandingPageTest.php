<?php

namespace Tests\Feature;

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    public function test_home_page_returns_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    public function test_home_named_route_exists_and_points_to_home_controller(): void
    {
        $this->assertTrue(Route::has('home'));
        $route = Route::getRoutes()->getByName('home');
        $this->assertNotNull($route);
        $this->assertEquals(HomeController::class . '@index', $route->getActionName());
        $this->assertEquals(url('/'), route('home'));
    }

    public function test_controller_passes_course_data_to_view(): void
    {
        $response = $this->get(route('home'));

        $response->assertViewHas('courses');
        $response->assertViewHas('siteName');
        $response->assertViewHas('tagline');

        $courses = $response->viewData('courses');
        $this->assertIsArray($courses);
        $this->assertCount(6, $courses);

        $courseNames = array_column($courses, 'nama');
        $this->assertContains('Web Development', $courseNames);
        $this->assertContains('Desain Grafis', $courseNames);
        $this->assertContains('Digital Marketing', $courseNames);
        $this->assertContains('Microsoft Office', $courseNames);
        $this->assertContains('Data Analysis', $courseNames);
        $this->assertContains('UI/UX Design', $courseNames);
    }

    public function test_view_renders_courses_using_foreach(): void
    {
        $response = $this->get(route('home'));

        $response->assertSee('Web Development');
        $response->assertSee('Desain Grafis');
        $response->assertSee('Digital Marketing');
        $response->assertSee('Microsoft Office');
        $response->assertSee('Data Analysis');
        $response->assertSee('UI/UX Design');

        // Check prices formatted
        $response->assertSee('Rp 750.000');
        $response->assertSee('Rp 650.000');
    }

    public function test_css_and_image_assets_are_linked(): void
    {
        $response = $this->get(route('home'));

        $response->assertSee('css/style.css');
        $response->assertSee('images/hero-kursus.jpg');
    }

    public function test_view_dynamically_renders_modified_or_new_course_data(): void
    {
        $customCourses = [
            [
                'nama' => 'Cyber Security Fundamental',
                'kategori' => 'Keamanan',
                'harga' => 950000,
                'peserta' => 5,
                'kapasitas' => 20,
                'tanggal' => '2026-11-01',
                'deskripsi' => 'Belajar etika peretasan dan proteksi sistem jaringan modern.'
            ],
            [
                'nama' => 'Mobile App Development Flutter',
                'kategori' => 'Teknologi',
                'harga' => 880000,
                'peserta' => 20,
                'kapasitas' => 20,
                'tanggal' => '2026-11-05',
                'deskripsi' => 'Bangun aplikasi Android dan iOS dari satu basis kode.'
            ]
        ];

        $view = $this->view('home', [
            'siteName' => 'KursusKu',
            'tagline' => 'Belajar, daftar, dan kelola kursus dalam satu tempat.',
            'year' => 2026,
            'keunggulan' => [],
            'courses' => $customCourses,
            'daftarKursus' => $customCourses
        ]);

        $view->assertSee('Cyber Security Fundamental');
        $view->assertSee('Mobile App Development Flutter');
        $view->assertSee('Rp 950.000');
        $view->assertSee('Rp 880.000');
        $view->assertSee('Keamanan');
        $view->assertSee('Tersedia');
        $view->assertSee('Penuh');
    }

    public function test_unknown_url_returns_404(): void
    {
        $response = $this->get('/abc');
        $response->assertStatus(404);

        $responseSecond = $this->get('/halaman-tidak-ada');
        $responseSecond->assertStatus(404);
    }
}
