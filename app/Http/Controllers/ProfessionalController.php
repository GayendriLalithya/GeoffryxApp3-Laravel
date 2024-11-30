<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Verify;
use App\Models\Certificate;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfessionalController extends Controller
{
    public function requestVerification(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8',
            'nic_no' => 'required|string',
            'nic_front' => 'required|image|mimes:jpeg,png,jpg,gif',
            'nic_back' => 'required|image|mimes:jpeg,png,jpg,gif',
            'professional_type' => 'required|in:charted_architect,structural_engineer,contractor',
            'certificates.*' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'certificate_name.*' => 'nullable|string', // Added validation for certificate names
        ]);
    
        // Get the logged-in user
        $user = User::where('email', $request->email)->first();
    
        // Handle NIC images
        $nicFrontPath = $request->file('nic_front')->store('public/images/nic');
        $nicBackPath = $request->file('nic_back')->store('public/images/nic');
    
        // Save the verify record
        $verify = new Verify();
        $verify->user_id = $user->user_id;
        $verify->nic_no = $request->nic_no;
        $verify->nic_front = $nicFrontPath;
        $verify->nic_back = $nicBackPath;
        $verify->professional_type = $request->professional_type;
        $verify->save();
    
        // Handle certificates
        if ($request->hasFile('certificates')) {
            $certificateNames = $request->input('certificate_name'); // Get the certificate names array
        
            foreach ($request->file('certificates') as $index => $file) {
                // Ensure that there is a corresponding certificate name for each file
                $certificateName = isset($certificateNames[$index]) ? $certificateNames[$index] : null;
                
                // Store the certificate file
                $certificatePath = $file->store('public/images/certificate');
                
                // Save the certificate record
                $certificate = new Certificate();
                $certificate->user_id = $user->user_id;
                $certificate->certificate_name = $certificateName; // Assign corresponding certificate name
                $certificate->certificate = $certificatePath;
                $certificate->save();
            }
        }
    
        return redirect()->back()->with('success', 'Verification request submitted successfully!');
    }
}
