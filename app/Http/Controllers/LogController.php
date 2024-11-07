<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Http\Requests\StoreLogRequest;
use App\Http\Requests\UpdateLogRequest;
use Illuminate\Http\Request;
class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = $request->get('length', 10);
            $page = ($request->get('start') / $perPage) + 1;
            $query = Log::query();
    
            // Apply search filter if provided
            if ($search = $request->get('search')['value']) {
                $query->where('action_type', 'like', "%{$search}%")
                      ->orWhere('transaction', 'like', "%{$search}%")
                      ->orWhere('author', 'like', "%{$search}%")
                      ->orWhere('created_at', 'like', "%{$search}%");
            }
    
            // Paginate the results
            $query->orderBy('created_at', 'desc');
            $logs = $query->paginate($perPage, ['*'], 'page', $page);
    
            // Prepare data array for DataTables
            $data = [];
            foreach ($logs as $log) {
                $data[] = [
                    'id'=>$log->id,
                    'date' => $log->created_at->format('F j, Y g:i A'), 
                    'action_type' => $log->action_type, 
                    'transaction' => $log->transaction, 
                    'author' => $log->author, 
                ];
            }
    
            // Return JSON response for DataTables
            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => $logs->total(),
                'recordsFiltered' => $logs->total(),
                'data' => $data,
            ]);
        }
            
        $logs=Log::paginate(10);
        
        return view('admin-pages.admin-logs',compact('logs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLogRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Log $log)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Log $log)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLogRequest $request, Log $log)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Log $log)
    {
        //
    }
}
