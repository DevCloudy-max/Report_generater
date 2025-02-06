<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use PDF;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Fill;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'version' => 'required|string|max:50',
                'date' => 'required|date',
                'classification' => 'required|string|in:Private,Internal,Public',
                'auditor_name' => 'required|string|max:255',
                'auditor_certification' => 'required|string|max:255',
                'total_risks' => 'required|integer|min:0',
                'critical_risks' => 'required|integer|min:0',
                'compliance_status' => 'required|numeric|min:0|max:100',
                'toc_sections' => 'nullable|string',
                'purpose' => 'required|string',
                'background' => 'required|string',
                'audit_scope' => 'required|string',
                'auditor_independence' => 'required|string',
                'assessment_timings' => 'required|string',
                'audit_exclusions' => 'required|string',
                'sources_of_information' => 'required|string',
                'limitations' => 'required|string',
                'executive_summary' => 'required|string',
                'key_findings' => 'required|string',
                'key_recommendations' => 'required|string',
                'risk_heat_map' => 'required|string|in:yes,no',
                'audit_checklist' => 'required|string|in:yes,no',
                'export_pdf' => 'required|string|in:yes,no',
                'export_word' => 'nullable|string|in:yes,no',
                'export_ppt' => 'nullable|string|in:yes,no',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validatedData = $validator->validated();

            // Convert radio button values to boolean
            $validatedData['risk_heat_map'] = $request->input('risk_heat_map') === 'yes';
            $validatedData['audit_checklist'] = $request->input('audit_checklist') === 'yes';

            // Create the report
            $report = Report::create($validatedData);
            $downloads = [];

            // Generate PDF if requested
            if ($request->input('export_pdf') === 'yes') {
                try {
                    $pdf = PDF::loadView('Reports.pdf', ['report' => $report]);
                    $pdfPath = 'reports/' . $report->id . '_report.pdf';
                    Storage::disk('public')->put($pdfPath, $pdf->output());
                    $downloads[] = url('download/pdf/' . $report->id);
                } catch (\Exception $e) {
                    Log::error('PDF Generation Error: ' . $e->getMessage());
                }
            }

            // Generate PowerPoint if requested
            if ($request->input('export_ppt') === 'yes') {
                try {
                    $presentation = new PhpPresentation();
                    
                    // Title Slide
                    $currentSlide = $presentation->getActiveSlide();
                    $shape = $currentSlide->createRichTextShape()
                        ->setHeight(300)
                        ->setWidth(600)
                        ->setOffsetX(100)
                        ->setOffsetY(100);
                    $shape->createTextRun($report->title)
                        ->getFont()
                        ->setBold(true)
                        ->setSize(28);
                    
                    // Executive Summary Slide
                    $currentSlide = $presentation->createSlide();
                    $shape = $currentSlide->createRichTextShape()
                        ->setHeight(500)
                        ->setWidth(800)
                        ->setOffsetX(50)
                        ->setOffsetY(50);
                    $shape->createTextRun('Executive Summary')
                        ->getFont()
                        ->setBold(true)
                        ->setSize(24);
                    $shape->createBreak();
                    $shape->createTextRun($report->executive_summary)
                        ->getFont()
                        ->setSize(14);
                    
                    // Key Findings Slide
                    $currentSlide = $presentation->createSlide();
                    $shape = $currentSlide->createRichTextShape()
                        ->setHeight(500)
                        ->setWidth(800)
                        ->setOffsetX(50)
                        ->setOffsetY(50);
                    $shape->createTextRun('Key Findings')
                        ->getFont()
                        ->setBold(true)
                        ->setSize(24);
                    $shape->createBreak();
                    $shape->createTextRun($report->key_findings)
                        ->getFont()
                        ->setSize(14);
                    
                    // Recommendations Slide
                    $currentSlide = $presentation->createSlide();
                    $shape = $currentSlide->createRichTextShape()
                        ->setHeight(500)
                        ->setWidth(800)
                        ->setOffsetX(50)
                        ->setOffsetY(50);
                    $shape->createTextRun('Key Recommendations')
                        ->getFont()
                        ->setBold(true)
                        ->setSize(24);
                    $shape->createBreak();
                    $shape->createTextRun($report->key_recommendations)
                        ->getFont()
                        ->setSize(14);
                    
                    // Metrics Slide
                    $currentSlide = $presentation->createSlide();
                    $shape = $currentSlide->createRichTextShape()
                        ->setHeight(500)
                        ->setWidth(800)
                        ->setOffsetX(50)
                        ->setOffsetY(50);
                    $shape->createTextRun('Audit Metrics')
                        ->getFont()
                        ->setBold(true)
                        ->setSize(24);
                    $shape->createBreak();
                    $shape->createTextRun("Total Risks: {$report->total_risks}")
                        ->getFont()
                        ->setSize(14);
                    $shape->createBreak();
                    $shape->createTextRun("Critical Risks: {$report->critical_risks}")
                        ->getFont()
                        ->setSize(14);
                    $shape->createBreak();
                    $shape->createTextRun("Compliance Status: {$report->compliance_status}%")
                        ->getFont()
                        ->setSize(14);

                    // Save the PowerPoint file
                    $pptPath = 'reports/' . $report->id . '_presentation.pptx';
                    Storage::disk('public')->put($pptPath, '');
                    $writer = IOFactory::createWriter($presentation, 'PowerPoint2007');
                    $writer->save(storage_path('app/public/' . $pptPath));
                    $downloads[] = url('download/ppt/' . $report->id);
                } catch (\Exception $e) {
                    Log::error('PowerPoint Generation Error: ' . $e->getMessage());
                }
            }

            // Generate Word document if requested
            if ($request->input('export_word') === 'yes') {
                try {
                    $phpWord = new PhpWord();
                    $section = $phpWord->addSection();
                    
                    // Title
                    $section->addText($report->title, ['bold' => true, 'size' => 24]);
                    $section->addTextBreak(2);
                    
                    // Report Metadata
                    $section->addText('Report Information', ['bold' => true, 'size' => 16]);
                    $section->addText("Version: {$report->version}");
                    $section->addText("Date: {$report->date}");
                    $section->addText("Classification: {$report->classification}");
                    $section->addTextBreak();
                    
                    // Auditor Details
                    $section->addText('Auditor Details', ['bold' => true, 'size' => 16]);
                    $section->addText("Name: {$report->auditor_name}");
                    $section->addText("Certification: {$report->auditor_certification}");
                    $section->addTextBreak();
                    
                    // Metrics
                    $section->addText('Audit Metrics', ['bold' => true, 'size' => 16]);
                    $section->addText("Total Risks: {$report->total_risks}");
                    $section->addText("Critical Risks: {$report->critical_risks}");
                    $section->addText("Compliance Status: {$report->compliance_status}%");
                    $section->addTextBreak();
                    
                    // Executive Summary
                    $section->addText('Executive Summary', ['bold' => true, 'size' => 16]);
                    $section->addText($report->executive_summary);
                    $section->addTextBreak();
                    
                    // Purpose
                    $section->addText('Purpose & Use of the Report', ['bold' => true, 'size' => 16]);
                    $section->addText($report->purpose);
                    $section->addTextBreak();
                    
                    // Background
                    $section->addText('Background', ['bold' => true, 'size' => 16]);
                    $section->addText($report->background);
                    $section->addTextBreak();
                    
                    // Audit Scope
                    $section->addText('Audit Scope', ['bold' => true, 'size' => 16]);
                    $section->addText($report->audit_scope);
                    $section->addTextBreak();
                    
                    // Auditor Independence
                    $section->addText('Auditor Independence', ['bold' => true, 'size' => 16]);
                    $section->addText($report->auditor_independence);
                    $section->addTextBreak();
                    
                    // Assessment Timings
                    $section->addText('Assessment Timings & Activities', ['bold' => true, 'size' => 16]);
                    $section->addText($report->assessment_timings);
                    $section->addTextBreak();
                    
                    // Audit Exclusions
                    $section->addText('Audit Exclusions', ['bold' => true, 'size' => 16]);
                    $section->addText($report->audit_exclusions);
                    $section->addTextBreak();
                    
                    // Sources of Information
                    $section->addText('Sources of Information', ['bold' => true, 'size' => 16]);
                    $section->addText($report->sources_of_information);
                    $section->addTextBreak();
                    
                    // Limitations
                    $section->addText('Limitations and Disclaimer', ['bold' => true, 'size' => 16]);
                    $section->addText($report->limitations);
                    $section->addTextBreak();
                    
                    // Key Findings
                    $section->addText('Key Findings', ['bold' => true, 'size' => 16]);
                    $section->addText($report->key_findings);
                    $section->addTextBreak();
                    
                    // Key Recommendations
                    $section->addText('Key Recommendations', ['bold' => true, 'size' => 16]);
                    $section->addText($report->key_recommendations);
                    
                    // Save the Word document
                    $wordPath = 'reports/' . $report->id . '_report.docx';
                    Storage::disk('public')->put($wordPath, '');
                    $writer = WordIOFactory::createWriter($phpWord, 'Word2007');
                    $writer->save(storage_path('app/public/' . $wordPath));
                    $downloads[] = url('download/word/' . $report->id);
                } catch (\Exception $e) {
                    Log::error('Word Generation Error: ' . $e->getMessage());
                }
            }

            if (empty($downloads)) {
                throw new \Exception('Failed to generate any of the requested reports');
            }

            return response()->json([
                'message' => 'Report generated successfully!',
                'downloads' => $downloads
            ]);

        } catch (\Exception $e) {
            Log::error('Report Generation Error: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Error generating report: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download the PDF report
     */
    public function downloadPDF($id)
    {
        try {
            $report = Report::findOrFail($id);
            $path = storage_path('app/public/reports/' . $id . '_report.pdf');
            
            if (!file_exists($path)) {
                throw new \Exception('PDF file not found');
            }
            
            return response()->download($path, $report->title . '.pdf');
        } catch (\Exception $e) {
            Log::error('PDF Download Error: ' . $e->getMessage());
            return back()->with('error', 'Error downloading PDF: ' . $e->getMessage());
        }
    }

    /**
     * Download the PowerPoint presentation
     */
    public function downloadPPT($id)
    {
        try {
            $report = Report::findOrFail($id);
            $path = storage_path('app/public/reports/' . $id . '_presentation.pptx');
            
            if (!file_exists($path)) {
                throw new \Exception('PowerPoint file not found');
            }
            
            return response()->download($path, $report->title . '.pptx');
        } catch (\Exception $e) {
            Log::error('PowerPoint Download Error: ' . $e->getMessage());
            return back()->with('error', 'Error downloading PowerPoint: ' . $e->getMessage());
        }
    }

    /**
     * Download the Word document
     */
    public function downloadWord($id)
    {
        try {
            $report = Report::findOrFail($id);
            $path = storage_path('app/public/reports/' . $id . '_report.docx');
            
            if (!file_exists($path)) {
                throw new \Exception('Word document not found');
            }
            
            return response()->download($path, $report->title . '.docx');
        } catch (\Exception $e) {
            Log::error('Word Download Error: ' . $e->getMessage());
            return back()->with('error', 'Error downloading Word document: ' . $e->getMessage());
        }
    }
}
