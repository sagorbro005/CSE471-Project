<?php

namespace App\Http\Controllers;

use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $sections = Faq::getSections();
        $faqs = Faq::orderBy('order')->get()->groupBy('section');

        return view('faqs.index', compact('sections', 'faqs'));
    }
}
