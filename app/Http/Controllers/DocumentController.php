<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DocumentController extends Controller
{
    public function updateDocuments(Request $request, $workId)
    {
        // Debug information
        \Log::info('Request received for workId: ' . $workId);
        \Log::info('Files present: ' . $request->hasFile('newFiles'));
        
        // Handle removed documents
        if ($request->filled('removedDocumentIds')) {
            $removedIds = json_decode($request->removedDocumentIds);
            \Log::info('Removed document IDs: ' . print_r($removedIds, true));
            
            if (!empty($removedIds)) {
                $documentsToDelete = Document::whereIn('document_id', $removedIds)->get();
                foreach ($documentsToDelete as $doc) {
                    $this->deleteOldFile($doc->document);
                    $doc->delete();
                }
            }
        }

        // Check if files were uploaded
        if ($request->hasFile('newFiles')) {
            // Log the number of files
            \Log::info('Number of files: ' . count($request->file('newFiles')));
            
            try {
                foreach ($request->file('newFiles') as $file) {
                    // Log file information
                    \Log::info('Processing file: ' . $file->getClientOriginalName());
                    
                    // Generate filename
                    $filename = time() . '_' . $file->getClientOriginalName();
                    
                    // Store the file
                    $file->storeAs('images/document', $filename, 'public');
                    
                    // Create document record
                    Document::create([
                        'document' => $filename,
                        'work_id' => $workId
                    ]);
                    
                    \Log::info('File saved: ' . $filename);
                }
                
                return redirect()->back()->with('alert-success', 'Documents updated successfully.');
            } catch (\Exception $e) {
                \Log::error('Error uploading files: ' . $e->getMessage());
                return redirect()->back()->with('alert-error', 'Failed to upload documents: ' . $e->getMessage());
            }
        }

        // return redirect()->back()->with('alert-info', 'No new documents to update.');
        return redirect()->back()->with('alert-success', 'Documents updated successfully.');
    }

    protected function deleteOldFile($filename)
    {
        $oldFilePath = storage_path('app/public/images/document/' . $filename);
        if (File::exists($oldFilePath)) {
            File::delete($oldFilePath);
        }
    }
}