<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;

class ContactController extends Controller
{
    public function show()
    {
        $contact = ContactUs::current();

        return view('pages.contact', compact('contact'));
    }
}
