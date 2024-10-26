<?php

namespace App\Http\Controllers;

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    private $s3;

    public function __construct()
    {
        // Initialize the S3 client with MinIO configuration
        $this->s3 = new S3Client([
            'version'     => 'latest',
            'region'      => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'endpoint'    => env('AWS_ENDPOINT'), // MinIO endpoint
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', true),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    // Upload Document
    public function upload(Request $request)


    {

        $request->validate([
            'file' => 'required|mimes:pdf,docx,doc,pptx,ppt,xls,xlsx,jpg',
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $file = $request->file('file');
        $keyname = 'uploads/' . $file->getClientOriginalName();

        try {
            // Upload to S3/MinIO
            $result = $this->s3->putObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => $keyname,
                'Body'   => fopen($file, 'r'),
                'ACL'    => 'public-read',
            ]);

            // Store document in the database
            $document = Document::create([
                'title' => $request->title,
                'file_path' => $keyname,
                'file_type' => $file->getClientOriginalExtension(),
                'user_id' => Auth::id(),
                'category_id' => $request->category_id,
            ]);

            return response()->json([
                'message' => 'File uploaded successfully',
                'document' => $document,
                'file_link' => $result['ObjectURL'],
            ]);
        } catch (S3Exception $e) {
            return response()->json([
                'error' => 'Upload Failed: ' . $e->getMessage(),
            ], 500);
        }
    }





    // Get Documents by Category and By user
    public function getByCategory($categoryId)
    {
        $userId = Auth::id();
        $documents = Document::where('category_id', $categoryId)->where('user_id', $userId)->with('user')->get();

        return response()->json($documents);
    }

    public function download($id)
{
    $document = Document::findOrFail($id);

    try {
        // Get the file from S3/MinIO
        $fileStream = $this->s3->getObject([
            'Bucket' => env('AWS_BUCKET'),
            'Key'    => $document->file_path,
        ]);

        return response($fileStream['Body'], 200)
            ->header('Content-Type', $fileStream['ContentType'])
            ->header('Content-Disposition', 'attachment; filename="' . basename($document->file_path) . '"');
    } catch (S3Exception $e) {
        return response()->json([
            'error' => 'Download Failed: ' . $e->getMessage(),
        ], 500);
    }
}


    // Delete Document
    public function delete($id)
    {
        $document = Document::findOrFail($id);

        try {
            // Delete from S3/MinIO
            $this->s3->deleteObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => $document->file_path,
            ]);

            // Delete from the database
            $document->delete();

            return response()->json([
                'message' => 'Document deleted successfully',
            ]);
        } catch (S3Exception $e) {
            return response()->json([
                'error' => 'Delete Failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
