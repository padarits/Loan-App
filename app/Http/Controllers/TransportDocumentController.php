<?php

namespace App\Http\Controllers;

use App\Models\TransportDocument;
use App\Models\TransportDocumentLine;
use Illuminate\Http\Request;
use App\Utils\MainUtils;
use App\Models\Warehouse;
use Carbon\Carbon;

class TransportDocumentController extends Controller
{
    // Return all users

    public function index(Request $request)
    {
        $columns = [
            0 => 'created_at',
            1 => 'status',
            2 => 'document_number',
            3 => 'document_date',
            4 => 'receiver_name',
            5 => 'vehicle_registration_number',
            6 => 'total_sum',
            // Add more columns as per your users table
        ];
    
        $totalData = TransportDocument::count();
        $totalFiltered = $totalData;
    
        $limit = intval($request->input('length'));
        $start = intval($request->input('start'));
        $order = $columns[intval($request->input('order.0.column'))];
        $dir = ($request->input('order.0.dir') === 'desc') ? 'desc' : 'asc';
    
        if(empty($request->input('search.value')))
        {
            $documents = TransportDocument::with('lines')
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get();
        }
        else {
            $search = $request->input('search.value');
            $search2 = str_replace([',', ' '], ['.', ''], $search);

            $documents = TransportDocument::with('lines')
                         ->where('document_number','LIKE',"%" . $search . "%")
                         ->orWhere('receiver_name', 'LIKE',"%". $search . "%")
                         ->orWhere('vehicle_registration_number', 'LIKE',"%". $search . "%")
                         ->orWhere('total_sum', 'LIKE',"%". $search2 . "%")
                         ->orWhere('document_date_str', 'LIKE',"%". $search2 . "%")
                         ->offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
    
            $totalFiltered = TransportDocument::where('document_number','LIKE',"%" . $search . "%")
                                 ->orWhere('receiver_name', 'LIKE',"%" . $search . "%")
                                 ->orWhere('vehicle_registration_number', 'LIKE',"%". $search . "%")
                                 ->orWhere('total_sum', 'LIKE',"%". $search2 . "%")
                                 ->orWhere('document_date_str', 'LIKE',"%". $search2 . "%")
                                 ->count();
        }
    
        $data = [];
        if(!empty($documents))
        {
            foreach ($documents as $document)
            {
                $nestedData['id'] = $document->id;
                $nestedData['status'] = $document->status;
                $nestedData['document_number'] = $document->document_number;
                $nestedData['document_date'] = \Carbon\Carbon::parse($document->document_date)->format('d.m.Y'); // Format as needed
                $nestedData['receiver_name'] = $document->receiver_name; // created_at->format('Y-m-d H:i:s'); // Format as needed
                $nestedData['supplier_name'] = $document->supplier_name;
                $nestedData['fn_total_sum'] = MainUtils::formatNumber($document->fn_total_sum, 2);
                $nestedData['vehicle_registration_number'] = $document->vehicle_registration_number;
                // Add more fields as necessary
    
                $data[] = $nestedData;
            }
        }
    
        $json_data = [
            "draw"            => intval($request->input('draw')),  // For security, use intval
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        ];
    
        return response()->json($json_data);
    }

    // Store a new post
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $document = TransportDocument::create($validated);

        return response()->json($document, 201);
    }

    // Show a single post
    public function show($id)
    {
        $document = TransportDocument::find($id);

        if ($document) {
            return response()->json($document, 200);
        }

        return response()->json(['message' => 'Post not found'], 404);
    }

    // Update a post
    public function update(Request $request, $id)
    {
        $document = TransportDocument::find($id);

        if (!$document) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
        ]);

        //$post->update($validated);

        return response()->json($document, 200);
    }

    // Delete a post
    public function destroy($id)
    {
        $document = TransportDocument::find($id);

        if (!$document) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $document->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }

    public function search_receiver_reg_number(Request $request)
    {
        // Atgriež līdz 10 rezultātiem ar receiver_reg_number un receiver_name
        return TransportDocument::where('receiver_reg_number', 'like', '%' . $request->query('query') . '%')
                        ->orWhere('receiver_name', 'like', '%' . $request->query('query') . '%')
                        ->distinct()
                        ->limit(10)
                        ->get(['receiver_reg_number', 'receiver_name'])->toArray();
    }
    
    public function search_supplier_reg_number(Request $request)
    {
        // Atgriež līdz 10 rezultātiem ar supplier_reg_number un receiver_name
        return TransportDocument::where('supplier_reg_number', 'like', '%' . $request->query('query') . '%')
                        ->orWhere('supplier_name', 'like', '%' . $request->query('query') . '%')
                        ->distinct()
                        ->limit(10)
                        ->get(['supplier_reg_number', 'supplier_name'])->toArray();
    }

    public function search_item_article(Request $request)
    {
        // Atgriež līdz 10 rezultātiem ar receiver_reg_number un receiver_name
        return TransportDocumentLine::where('product_code', 'like', '%' . $request->query('query') . '%')
                        ->orWhere('product_name', 'like', '%' . $request->query('query') . '%')
                        ->distinct()
                        ->limit(10)
                        ->get(['product_code', 'product_name'])->toArray();
    }

    public static function getLastReceiverAddressFor($receiverRegNum, $receiverName)
    {
        return TransportDocument::where('receiver_reg_number', '=', $receiverRegNum)
                                        ->where('receiver_name', '=', $receiverName)
                                        ->orderBy('id', 'desc') // Order by id in descending order
                                        ->limit(1) // Limit to 1 record
                                        ->first(['receiver_address', 'receiving_location']); // Get the first record as an object
    }
    
    public static function search_receiver_address(Request $request)
    {
        return TransportDocument::where('receiver_reg_number', '=', $request->query('reg_number'))
                                        ->where('receiver_name', '=', $request->query('name'))
                                        ->distinct()
                                        ->orderBy('id', 'desc') // Order by id in descending order
                                        ->limit(10) // Limit to 1 record
                                        ->get(['receiver_address', 'receiving_location']); // Get the first record as an object
    }

    public static function search_supplier_address(Request $request)
    {
        return TransportDocument::where('supplier_reg_number', '=', $request->query('reg_number'))
                                        ->where('supplier_name', '=', $request->query('name'))
                                        ->distinct()
                                        ->orderBy('id', 'desc') // Order by id in descending order
                                        ->limit(10) // Limit to 1 record
                                        ->get(['supplier_address']); // Get the first record as an object
    }

    public static function getLastSupplierAddressFor($supplierRegNum, $supplierName)
    {
        return TransportDocument::where('supplier_reg_number', '=', $supplierRegNum)
                                        ->where('supplier_name', '=', $supplierName)
                                        ->orderBy('id', 'desc') // Order by id in descending order
                                        ->limit(1) // Limit to 1 record
                                        ->first(['supplier_address']); // Get the first record as an object
    }

    public static function addWarhouseEntry($entry, $warehouse_code, $transitWarehouseCode){
        $documentHeader = self::findOrCreateFor($entry, $warehouse_code, $transitWarehouseCode); // Find or create a document header
        self::createLineFor($documentHeader->id, $entry); // Add a line to the document
    }
    
    public static function findOrCreateFor($entry, $warehouse_code, $transitWarehouseCode){
        $supplier_name = '-';
        $supplier_address = '-';
        $warehouse = Warehouse::where('warehouse_code', '=', $warehouse_code)->first();
        if ($warehouse) {
            $supplier_name = $warehouse->name;
            $supplier_address = $warehouse->location ? $warehouse->location : '-';
        }
        
        $receiver_name = '-'; 
        $receiver_address = '-';
        $warehouse = Warehouse::where('warehouse_code', '=', $transitWarehouseCode)->first();
        if ($warehouse) {
            $receiver_name = $warehouse->name;
            $receiver_address = $warehouse->location ? $warehouse->location : '-';
        }       

        $warehouse_date = Carbon::today()->format('d.m.Y');
        if($entry['warehouse_date']){
            $warehouse_date = \Carbon\Carbon::parse($entry['warehouse_date'])->format('d.m.Y');
        }

        $documentHeader = TransportDocument::firstOrNew(
           ['status' => trim(TransportDocument::STATUS_NEW),
            'document_date' => \Carbon\Carbon::parse($warehouse_date)->toDateString(),
            'supplier_reg_number' => trim($warehouse_code),
            'receiver_reg_number' => trim($transitWarehouseCode)], // Attributes to search for
           [
            'document_date' => $warehouse_date,
            'document_number' => TransportDocument::generateDocumentNumber(),
            'supplier_name' =>  $supplier_name,
            'receiver_name' => $receiver_name,
            'supplier_address' => $supplier_address,
            'receiver_address' => $receiver_address,
            'issuer_name' => '-',
            'receiver_person_name' => '-',
            'receiving_location' => '-',
           ]);
        $documentHeader->save();
        return $documentHeader;
    }

    public static function createLineFor($headerGuid, $entry){
        $documentLine = TransportDocumentLine::create(
            [
                'transport_document_id' => $headerGuid,
                'product_code' => $entry['code'],
                'product_name' => $entry['name'] . ' (' . $entry['unit'] . ')',
                'quantity' => $entry['quantity'],
                'price' => $entry['price_per_unit'],
                'total' => $entry['total_price'],
            ]
        );
    }
}

