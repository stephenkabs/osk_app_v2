<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WirepickService;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsController extends Controller
{
    /**
     * Show reports dashboard page
     */
    public function index()
    {
        return view('reports.wirepick.index');
    }

    /**
     * Fetch report rows (JSON) for table + charts
     */
    public function fetch(Request $request, WirepickService $wirepick)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'gateway'    => 'nullable|string'
        ]);

        // Pull from Wirepick API
        $data = $wirepick->getReport([
            'start_date'     => $request->start_date,
            'end_date'       => $request->end_date,
            'report_format'  => 'JSON'
        ]);

        // Filter by gateway only if specified
        if ($request->gateway && $request->gateway !== "ALL") {
            $data = collect($data)->where("gateway", $request->gateway)->values();
        }

        return response()->json([
            'success' => true,
            'data'    => $data
        ]);
    }

    /**
     * Export CSV
     */
    public function exportCsv(Request $request, WirepickService $wirepick)
    {
        $request->validate([
            "start_date" => "required|date",
            "end_date"   => "required|date|after_or_equal:start_date",
        ]);

        $data = $wirepick->getReport([
            "start_date"     => $request->start_date,
            "end_date"       => $request->end_date,
            "report_format"  => "JSON",
        ]);

        // CSV filename
        $filename = "wirepick_report_" . now()->format("Ymd_His") . ".csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($data) {
            $file = fopen("php://output", "w");

            fputcsv($file, [
                "Timestamp", "MSISDN", "Amount", "Reference", "Gateway", "Status"
            ]);

            foreach ($data as $row) {
                fputcsv($file, [
                    $row["timestamp"],
                    $row["msisdn"],
                    $row["amount"],
                    $row["reference"],
                    $row["gateway"],
                    $row["status"],
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export PDF
     */
public function exportPdf(Request $request, WirepickService $wirepick)
{
    $request->validate([
        "start_date" => "required|date",
        "end_date"   => "required|date|after_or_equal:start_date",
    ]);

    $data = $wirepick->getReport([
        "start_date"     => $request->start_date,
        "end_date"       => $request->end_date,
        "report_format"  => "JSON",
    ]);

    // ⚠ Ensure data is array
    $rows = collect($data)->values()->toArray();

    $pdf = Pdf::loadView("reports.wirepick.pdf", compact("rows"))
        ->setPaper("a4", "portrait");

    return $pdf->download("wirepick_report_" . now()->format("Ymd_His") . ".pdf");
}

}
