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
            'professional_type' => 'required|in:Charted Architect,Structural Engineer,Contractor',
            'certificates.*' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'certificate_name.*' => 'nullable|string',
        ]);
        
        // Get the logged-in user
        $user = User::where('email', $request->email)->first();
        
        // Handle NIC images (nic_front and nic_back)
        $nicFrontPath = $this->storeImage($request->file('nic_front'), 'nic');
        $nicBackPath = $this->storeImage($request->file('nic_back'), 'nic');
        
        // Save the verify record
        $verify = new Verify();
        $verify->user_id = $user->user_id;
        $verify->nic_no = $request->nic_no;
        $verify->nic_front = $nicFrontPath;
        $verify->nic_back = $nicBackPath;
        $verify->professional_type = $request->professional_type;
        $verify->status = 'pending';
        $verify->save();
        
        // Handle certificates
        if ($request->hasFile('certificates')) {
            $certificateNames = $request->input('certificate_name'); // Get the certificate names array
            
            foreach ($request->file('certificates') as $index => $file) {
                // Ensure that there is a corresponding certificate name for each file
                $certificateName = isset($certificateNames[$index]) ? $certificateNames[$index] : null;
                
                // Store the certificate file
                $certificatePath = $this->storeImage($file, 'certificate');
                
                // Save the certificate record with verify_id instead of user_id
                $certificate = new Certificate();
                $certificate->verify_id = $verify->verify_id; // Use verify_id to link certificate to the verify request
                $certificate->certificate_name = $certificateName; // Assign corresponding certificate name
                $certificate->certificate = $certificatePath;
                $certificate->save();
            }
        }
        
        return redirect()->back()->with('success', 'Verification request submitted successfully!');
    }

    /**
     * Helper function to store an image with a unique timestamp-based name.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @return string
     */
    protected function storeImage($file, $folder)
    {
        // Generate a unique filename
        $filename = time() . '_' . $file->getClientOriginalName();
    
        // Store the file in the public directory (using 'public' disk)
        $file->storeAs('images/' . $folder, $filename, 'public');
    
        // Return the relative path to be stored in the database
        return 'images/' . $folder . '/' . $filename;
    }

}
