<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfessionalListController extends Controller
{
    public function index()
    {
        // Fetching all professionals' details
        $professionals = DB::select('CALL LoadAllProfessionals()'); // Adjust the stored procedure name as necessary

        return view('pages.customer.professional', compact('professionals'));
    }

    // public function show($id)
    // {
    //     // Fetching individual professional details including work history
    //     $professional = DB::select('CALL LoadProfessionalDetails(?)', [$id]); // Make sure to handle multiple result sets if needed
    //     $workHistory = DB::select('CALL LoadProfessionalWorkHistory(?)', [$id]);

    //     return view('pages.customer.professionals.show', compact('professional', 'workHistory'));
    // }

    public function show($id)
    {
        $professional = DB::select('CALL LoadProfessionalDetails(?)', [$id])[0];
        dd($professional);
        $pdo = DB::connection()->getPdo();
        $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
        $stmt = $pdo->prepare('CALL LoadProfessionalDetails(?)');
        $stmt->bindParam(1, $id, \PDO::PARAM_INT);
        $stmt->execute();

        $professional = $stmt->fetchAll(\PDO::FETCH_OBJ);
        $stmt->nextRowset();  // Move to the next result set
        $workHistory = $stmt->fetchAll(\PDO::FETCH_OBJ);

        return view('pages.customer.professionals.show', compact('professional', 'workHistory'));
    }

    public function showProfessional($id) {
        $professional = DB::table('professional_details')
                          ->where('professional_id', $id)
                          ->first();
        if (!$professional) {
            abort(404, 'Professional not found.');
        }
    
        return view('pages.customer.professional_detail', [
            'professional' => $professional,
            'id' => $id,
        ]);
    }
    
    
}