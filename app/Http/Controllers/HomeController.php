<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Trajet;

class HomeController extends Controller
{
    public function index()
    {
        $usersCount = User::count();
        $trajetsCount = Trajet::count(); 

        return view('accueil', compact('usersCount', 'trajetsCount'));
    }
}