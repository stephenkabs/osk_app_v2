<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use App\Services\WirepickService;

class PaygateController extends Controller
{
public function create(Request $request, WirepickService $wirepick)
{
    $data = $request->validate([
        "msisdn"      => "required|min:9|max:12",
        "amount"      => "required|numeric|min:1",
        "gateway"     => "required|in:AIRTEL,MTN,ZAMTEL,CARD",
        "reference"   => "required|min:8|max:32|unique:transactions,reference",
        "description" => "required|min:4|max:64",
    ]);

    $txn = Transaction::create([
        "merchant_id" => 1,
        "msisdn"      => $data["msisdn"],
        "amount"      => $data["amount"],
        "gateway"     => $data["gateway"],
        "reference"   => $data["reference"],
        "description" => $data["description"],
        "status"      => "PENDING",
    ]);

    // Call Wirepick
    $wirepickRes = $wirepick->sendRequest($txn);

    // DEBUG (very important)
    Log::info("WIREPICK SAVE DEBUG", [
        "response" => $wirepickRes
    ]);

    // Update the transaction using REAL response
    $txn->update([
        "gw_id"          => $wirepickRes["gw_id"] ?? null,
        "mno_request_id" => $wirepickRes["mno_request_id"] ?? null,
        "status"         => $wirepickRes["status"] ?? "PENDING"
    ]);

    return response()->json([
        "status"     => $wirepickRes["status"] ?? "ACCEPTED",
        "reference"  => $txn->reference,
        "gw_id"      => $txn->gw_id,
        "mno_request_id" => $txn->mno_request_id,
        "next_check" => "/api/payments/status?reference={$txn->reference}"
    ]);
}




        public function status(Request $request, WirepickService $wirepick)
    {
        $request->validate([
            "reference" => "required|exists:transactions,reference"
        ]);

        $txn = Transaction::where("reference", $request->reference)->first();

        $wp = $wirepick->checkStatus($txn);

 $txn->update([
    "gw_id"          => $wirepickRes["gw_id"] ?? null,
    "mno_request_id" => $wirepickRes["mno_request_id"] ?? null,
    "status"         => $wirepickRes["status"] ?? "PENDING"
]);


        return response()->json([
            "reference" => $txn->reference,
            "status"    => $txn->status,
            "message"   => $txn->message,
            "amount"    => $txn->amount,
        ]);
    }
}
