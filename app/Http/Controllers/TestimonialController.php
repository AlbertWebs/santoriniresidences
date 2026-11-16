<?php

namespace App\Http\Controllers;

use App\Support\TestimonialContent;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function __invoke(): View
    {
        $testimonials = TestimonialContent::published();
        abort_if($testimonials === [], 404);

        return view('testimonials', ['testimonials' => $testimonials]);
    }
}
