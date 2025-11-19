<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display the contact page with dummy data
     */
    public function index()
    {
        $contacts = [
            [
                'icon' => '📍',
                'title' => 'Alamat',
                'content' => 'Jl. Fitness Sehat No. 123, Jakarta Pusat 12860, Indonesia'
            ],
            [
                'icon' => '📞',
                'title' => 'Telepon',
                'content' => '+62 812-3456-7890'
            ],
            [
                'icon' => '📧',
                'title' => 'Email',
                'content' => 'support@flexora.com'
            ],
            [
                'icon' => '⏰',
                'title' => 'Jam Operasional',
                'content' => 'Senin - Minggu: 06:00 - 22:00 WIB'
            ]
        ];

        return view('contact', compact('contacts'));
    }
}
