<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Models\User;

class Statistique extends Controller
{
    public function statistic() {
        $countUsers = User::all()->count();
       $countCategories = Category::count();
       $countDocuments = Document::count();

       $results = [
        'countUsers' => $countUsers ,
        'countCategories'=> $countCategories ,
        'countDocuments' => $countDocuments
       ];

       return response()->json($results);

    }
}
