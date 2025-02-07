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
                    $report = Report::findOrFail($report->id);

                    // Title Slide
                    $slide = $presentation->getActiveSlide();
                    $this->createTitleSlide($slide, $report);

                    // Report Metadata Slide
                    $slide = $presentation->createSlide();
                    $this->createMetadataSlide($slide, $report);

                    // Auditor Details Slide
                    $slide = $presentation->createSlide();
                    $this->createAuditorSlide($slide, $report);

                    // Dashboard Metrics Slide
                    $slide = $presentation->createSlide();
                    $this->createMetricsSlide($slide, $report);

                    // Table of Contents Slide
                    $slide = $presentation->createSlide();
                    $this->createTOCSlide($presentation, $slide);

                    // Report Content Slides
                    $this->createContentSlides($presentation, $report);

                    // Risk Heat Map Slide
                    if ($report->risk_heat_map === 'yes') {
                        $slide = $presentation->createSlide();
                        $this->createRiskHeatMapSlide($slide);
                    }

                    // Audit Checklist Slide
                    if ($report->audit_checklist === 'yes') {
                        $slide = $presentation->createSlide();
                        $this->createAuditChecklistSlide($slide, $report);
                    }

                    // Save the PowerPoint file
                    $pptPath = 'reports/' . $report->id . '_presentation.pptx';
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
                    
                    // Set compatibility mode for better file saving
                    $phpWord->getCompatibility()->setOoxmlVersion(15);
                    $phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language('EN-US'));
                    
                    // Set default font
                    $phpWord->setDefaultFontName('Calibri');
                    $phpWord->setDefaultFontSize(11);
                    
                    // Add section with margins
                    $sectionStyle = array(
                        'orientation' => 'portrait',
                        'marginLeft' => 1440,  // 1 inch in twips
                        'marginRight' => 1440,
                        'marginTop' => 1440,
                        'marginBottom' => 1440,
                        'colsNum' => 1,
                        'pageNumberingStart' => 1,
                    );
                    $section = $phpWord->addSection($sectionStyle);
                    
                    // Title
                    $titleStyle = array('bold' => true, 'size' => 24, 'name' => 'Calibri');
                    $titleParagraph = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 500);
                    $section->addText($report->title, $titleStyle, $titleParagraph);
                    $section->addTextBreak(2);
                    
                    // Report Metadata
                    $headingStyle = array('bold' => true, 'size' => 16, 'name' => 'Calibri');
                    $normalStyle = array('size' => 11, 'name' => 'Calibri');
                    $paragraphStyle = array('spaceAfter' => 120);
                    
                    $section->addText('Report Information', $headingStyle, $paragraphStyle);
                    $section->addText("Version: {$report->version}", $normalStyle, $paragraphStyle);
                    $section->addText("Date: {$report->date}", $normalStyle, $paragraphStyle);
                    $section->addText("Classification: {$report->classification}", $normalStyle, $paragraphStyle);
                    $section->addTextBreak();
                    
                    // Auditor Details
                    $section->addText('Auditor Details', $headingStyle, $paragraphStyle);
                    $section->addText("Name: {$report->auditor_name}", $normalStyle, $paragraphStyle);
                    $section->addText("Certification: {$report->auditor_certification}", $normalStyle, $paragraphStyle);
                    $section->addTextBreak();
                    
                    // Metrics
                    $section->addText('Audit Metrics', $headingStyle, $paragraphStyle);
                    $section->addText("Total Risks: {$report->total_risks}", $normalStyle, $paragraphStyle);
                    $section->addText("Critical Risks: {$report->critical_risks}", $normalStyle, $paragraphStyle);
                    $section->addText("Compliance Status: {$report->compliance_status}%", $normalStyle, $paragraphStyle);
                    $section->addTextBreak();
                    
                    // Content sections with consistent styling
                    $contentSections = [
                        'Executive Summary' => $report->executive_summary,
                        'Purpose & Use of the Report' => $report->purpose,
                        'Background' => $report->background,
                        'Audit Scope' => $report->audit_scope,
                        'Auditor Independence' => $report->auditor_independence,
                        'Assessment Timings & Activities' => $report->assessment_timings,
                        'Audit Exclusions' => $report->audit_exclusions,
                        'Sources of Information' => $report->sources_of_information,
                        'Limitations and Disclaimer' => $report->limitations,
                        'Key Findings' => $report->key_findings,
                        'Key Recommendations' => $report->key_recommendations
                    ];
                    
                    foreach ($contentSections as $title => $content) {
                        $section->addText($title, $headingStyle, $paragraphStyle);
                        $textLines = explode("\n", $content);
                        foreach ($textLines as $line) {
                            if (trim($line) !== '') {
                                $section->addText(trim($line), $normalStyle, $paragraphStyle);
                            }
                        }
                        $section->addTextBreak();
                    }
                    
                    // Save the Word document
                    $wordPath = 'reports/' . $report->id . '_report.docx';
                    $fullPath = storage_path('app/public/' . $wordPath);
                    
                    // Ensure directory exists
                    $directory = dirname($fullPath);
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }
                    
                    // Create writer and save
                    $objWriter = WordIOFactory::createWriter($phpWord, 'Word2007');
                    $objWriter->save($fullPath);
                    
                    // Verify file exists and is readable
                    if (!file_exists($fullPath) || !is_readable($fullPath)) {
                        throw new \Exception('Failed to create Word document or file is not readable');
                    }
                    
                    $downloads[] = url('download/word/' . $report->id);
                    
                } catch (\Exception $e) {
                    Log::error('Word Generation Error: ' . $e->getMessage());
                    Log::error('Stack trace: ' . $e->getTraceAsString());
                    throw $e;
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
     * Create title slide
     */
    private function createTitleSlide($slide, $report)
    {
        $shape = $slide->createRichTextShape()
            ->setHeight(300)
            ->setWidth(600)
            ->setOffsetX(100)
            ->setOffsetY(100);
        $shape->createTextRun($report->title)
            ->getFont()
            ->setBold(true)
            ->setSize(28);
        
        $shape->createBreak();
        $shape->createTextRun('Version: ' . $report->version)
            ->getFont()
            ->setSize(16);
        
        $shape->createBreak();
        $shape->createTextRun('Date: ' . $report->date)
            ->getFont()
            ->setSize(16);
    }

    /**
     * Create metadata slide
     */
    private function createMetadataSlide($slide, $report)
    {
        $shape = $slide->createRichTextShape()
            ->setHeight(400)
            ->setWidth(600)
            ->setOffsetX(100)
            ->setOffsetY(50);
        
        $shape->createTextRun('Report Metadata')
            ->getFont()
            ->setBold(true)
            ->setSize(24);
        
        $shape->createBreak();
        $shape->createBreak();
        
        $this->addMetadataItem($shape, 'Report Title', $report->title);
        $this->addMetadataItem($shape, 'Report Version', $report->version);
        $this->addMetadataItem($shape, 'Report Date', $report->date);
        $this->addMetadataItem($shape, 'Classification', $report->classification);
    }

    /**
     * Create auditor details slide
     */
    private function createAuditorSlide($slide, $report)
    {
        $shape = $slide->createRichTextShape()
            ->setHeight(400)
            ->setWidth(600)
            ->setOffsetX(100)
            ->setOffsetY(50);
        
        $shape->createTextRun('Auditor Details')
            ->getFont()
            ->setBold(true)
            ->setSize(24);
        
        $shape->createBreak();
        $shape->createBreak();
        
        $this->addMetadataItem($shape, 'Auditor Name', $report->auditor_name);
        $this->addMetadataItem($shape, 'Certification', $report->auditor_certification);
    }

    /**
     * Create metrics slide
     */
    private function createMetricsSlide($slide, $report)
    {
        $shape = $slide->createRichTextShape()
            ->setHeight(400)
            ->setWidth(600)
            ->setOffsetX(100)
            ->setOffsetY(50);
        
        $shape->createTextRun('Dashboard Metrics')
            ->getFont()
            ->setBold(true)
            ->setSize(24);
        
        $shape->createBreak();
        $shape->createBreak();
        
        $this->addMetadataItem($shape, 'Total Risks', $report->total_risks);
        $this->addMetadataItem($shape, 'Critical Risks', $report->critical_risks);
        $this->addMetadataItem($shape, 'Compliance Status', $report->compliance_status . '%');
    }

    /**
     * Create table of contents slide with hyperlinks
     */
    private function createTOCSlide($presentation, $slide)
    {
        // Title shape
        $titleShape = $slide->createRichTextShape()
            ->setHeight(50)
            ->setWidth(600)
            ->setOffsetX(100)
            ->setOffsetY(50);
        
        $titleShape->createTextRun('Table of Contents')
            ->getFont()
            ->setBold(true)
            ->setSize(24);

        $sections = [
            'Purpose & Use of the Report',
            'Background',
            'Audit Scope',
            'Auditor Independence',
            'Assessment Timings & Activities',
            'Audit Exclusions',
            'Sources of Information',
            'Limitations and Disclaimer',
            'Executive Summary',
            'Key Findings',
            'Key Recommendations'
        ];

        // Create a single shape for all links
        $linksShape = $slide->createRichTextShape()
            ->setHeight(400)
            ->setWidth(600)
            ->setOffsetX(100)
            ->setOffsetY(120);

        foreach ($sections as $index => $section) {
            // Calculate target slide index (TOC is slide 5, content starts after)
            $targetSlideIndex = $index + 6;
            
            // Create hyperlink text
            $textRun = $linksShape->createTextRun(($index + 1) . '. ' . $section);
            $textRun->getFont()
                ->setSize(14)
                ->setColor(new Color('0000FF'))
                ->setUnderline(true);
            
            // Add hyperlink
            $textRun->getHyperlink()
                ->setSlideNumber($targetSlideIndex);
            
            // Add line break after each section except the last one
            if ($index < count($sections) - 1) {
                $linksShape->createBreak();
                $linksShape->createBreak();
            }
        }
    }

    /**
     * Create content slides with back links
     */
    private function createContentSlides($presentation, $report)
    {
        $sections = [
            'Purpose & Use of the Report' => $report->purpose,
            'Background' => $report->background,
            'Audit Scope' => $report->audit_scope,
            'Auditor Independence' => $report->auditor_independence,
            'Assessment Timings & Activities' => $report->assessment_timings,
            'Audit Exclusions' => $report->audit_exclusions,
            'Sources of Information' => $report->sources_of_information,
            'Limitations and Disclaimer' => $report->limitations,
            'Executive Summary' => $report->executive_summary,
            'Key Findings' => $report->key_findings,
            'Key Recommendations' => $report->key_recommendations
        ];
        
        foreach ($sections as $title => $content) {
            $slide = $presentation->createSlide();
            
            // Title
            $titleShape = $slide->createRichTextShape()
                ->setHeight(50)
                ->setWidth(800)
                ->setOffsetX(50)
                ->setOffsetY(50);
            
            $titleShape->createTextRun($title)
                ->getFont()
                ->setBold(true)
                ->setSize(24);
            
            // Content
            $contentShape = $slide->createRichTextShape()
                ->setHeight(400)
                ->setWidth(800)
                ->setOffsetX(50)
                ->setOffsetY(120);
            
            $contentShape->createTextRun($content)
                ->getFont()
                ->setSize(14);
            
            // Back to TOC link
            $backShape = $slide->createRichTextShape()
                ->setHeight(30)
                ->setWidth(200)
                ->setOffsetX(50)
                ->setOffsetY(540);
            
            $backText = $backShape->createTextRun('« Back to Contents');
            $backText->getFont()
                ->setSize(12)
                ->setColor(new Color('0000FF'))
                ->setUnderline(true);
            
            // Add hyperlink back to TOC (slide 5)
            $backText->getHyperlink()
                ->setSlideNumber(5);
        }
    }

    /**
     * Create risk heat map slide
     */
    private function createRiskHeatMapSlide($slide)
    {
        $shape = $slide->createRichTextShape()
            ->setHeight(500)
            ->setWidth(800)
            ->setOffsetX(50)
            ->setOffsetY(50);
        
        $shape->createTextRun('Risk Heat Map')
            ->getFont()
            ->setBold(true)
            ->setSize(24);
        
        // Add a table for the heat map
        $table = $slide->createTableShape(3);
        $table->setHeight(300);
        $table->setWidth(600);
        $table->setOffsetX(100);
        $table->setOffsetY(150);
        
        $risks = [
            ['High Impact High Likelihood', 'High Impact Medium Likelihood', 'High Impact Low Likelihood'],
            ['Medium Impact High Likelihood', 'Medium Impact Medium Likelihood', 'Medium Impact Low Likelihood'],
            ['Low Impact High Likelihood', 'Low Impact Medium Likelihood', 'Low Impact Low Likelihood']
        ];
        
        $colors = [
            [Color::COLOR_RED, Color::COLOR_RED, Color::COLOR_ORANGE],
            [Color::COLOR_RED, Color::COLOR_ORANGE, Color::COLOR_GREEN],
            [Color::COLOR_ORANGE, Color::COLOR_GREEN, Color::COLOR_GREEN]
        ];
        
        for ($row = 0; $row < 3; $row++) {
            for ($col = 0; $col < 3; $col++) {
                $cell = $table->getRow($row)->getCell($col);
                $cell->createTextRun($risks[$row][$col])->getFont()->setSize(10)->setColor(new Color('FFFFFF'));
                $cell->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new Color($colors[$row][$col]));
            }
        }
    }

    /**
     * Create audit checklist slide
     */
    private function createAuditChecklistSlide($slide, $report)
    {
        $shape = $slide->createRichTextShape()
            ->setHeight(500)
            ->setWidth(800)
            ->setOffsetX(50)
            ->setOffsetY(50);
        
        $shape->createTextRun('Audit Checklist')
            ->getFont()
            ->setBold(true)
            ->setSize(24);
        
        $shape->createBreak();
        $shape->createBreak();
        
        if (isset($report->audit_checklist)) {
            $shape->createTextRun(strip_tags($report->audit_checklist))
                ->getFont()
                ->setSize(14);
        } else {
            $shape->createTextRun('No audit checklist available.')
                ->getFont()
                ->setSize(14);
        }
    }

    /**
     * Helper function to add metadata items
     */
    private function addMetadataItem($shape, $label, $value)
    {
        $shape->createTextRun($label . ': ')
            ->getFont()
            ->setBold(true)
            ->setSize(14);
        
        $shape->createTextRun($value)
            ->getFont()
            ->setSize(14);
        
        $shape->createBreak();
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

    /**
     * Preview the PowerPoint presentation
     */
    public function previewPPT($id)
    {
        try {
            $report = Report::findOrFail($id);
            return view('Reports.ppt', ['report' => $report]);
        } catch (\Exception $e) {
            Log::error('PowerPoint Preview Error: ' . $e->getMessage());
            return back()->with('error', 'Error previewing PowerPoint: ' . $e->getMessage());
        }
    }
}
