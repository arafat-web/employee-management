<?php

namespace App\Http\Controllers;

use App\Models\EmployeeDocument;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EmployeeDocumentController extends Controller
{
    public function index($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $documents = $employee->documents()->with('uploadedBy')->latest()->get();

        return view('employees.documents.index', compact('employee', 'documents'));
    }

    public function create($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        return view('employees.documents.create', compact('employee'));
    }

    public function store(Request $request, $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:contract,certificate,id_document,other',
            'description' => 'nullable|string',
            'file' => 'required|file|max:10240', // 10MB max
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issue_date',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents/' . $employeeId, $fileName, 'public');

            $document = EmployeeDocument::create([
                'employee_id' => $employeeId,
                'title' => $validated['title'],
                'type' => $validated['type'],
                'description' => $validated['description'] ?? null,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'issue_date' => $validated['issue_date'] ?? null,
                'expiry_date' => $validated['expiry_date'] ?? null,
                'uploaded_by' => Auth::id(),
            ]);

            return redirect()->route('employees.documents.index', $employeeId)
                ->with('success', 'Document uploaded successfully!');
        }

        return back()->with('error', 'File upload failed.');
    }

    public function download($employeeId, $documentId)
    {
        $document = EmployeeDocument::where('employee_id', $employeeId)
            ->findOrFail($documentId);

        if (Storage::disk('public')->exists($document->file_path)) {
            return Storage::disk('public')->download($document->file_path, $document->file_name);
        }

        return back()->with('error', 'File not found.');
    }

    public function destroy($employeeId, $documentId)
    {
        $document = EmployeeDocument::where('employee_id', $employeeId)
            ->findOrFail($documentId);

        // Delete file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('employees.documents.index', $employeeId)
            ->with('success', 'Document deleted successfully!');
    }
}
