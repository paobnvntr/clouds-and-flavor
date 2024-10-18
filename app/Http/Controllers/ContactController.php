<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'message' => $request->input('message'),
        ]);

        return back()->with('success', 'Message sent successfully!');
    }
}
