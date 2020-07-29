<?php namespace App\Controllers;

use CodeIgniter\Controller;

class Task extends Controller
{
    public function index()
    {
        return view('task');
    }
}