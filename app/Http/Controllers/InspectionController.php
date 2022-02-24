<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Clients;
use App\Models\State;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use MattDaneshvar\Survey\Models\Survey;

class InspectionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:web');
    }

    // Get All Assignments as inspection
    public function get() {
        return view('inspection.list');
    }

    //Load the Assignments Via Ajax Data Tables
    public function getDatatable( Request $request ){

        //Log::info($request);
        $totalRecordsPerPage = $request->get('length');
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');

        // Datatable Parameters.
        $columnOrder = $request->get('order');
        $columnsData = $request->get('columns');
        $searchData = $request->get('search');

        if( (isset($start) && !empty($start)) && ( isset($length) && !empty($length)) ){

            $pageNo = ($start/$length)+1;

        } else {
            $pageNo = 1;
        }


        if( isset($totalRecordsPerPage) && !empty($totalRecordsPerPage) ){
            $recordsPerPage = $totalRecordsPerPage;
        } else {
            $recordsPerPage = 10;
        }

        $assignment = Assignment::with(['communicationsPrivate','communicationsPublic','client.clientInfo','items'])->where('is_inspection',1);


        if(!empty($columnOrder)) {
            foreach($columnOrder as $colOrder){
                //Lets Explode for the JOIN data.
                $columnName = explode('.',$columnsData[$colOrder['column']]['data']);
                if(count($columnName) == 1) {
                    $assignment->orderBy($columnsData[$colOrder['column']]['data'], $colOrder['dir']);
                } else {
                    //Log::info('No sorting needed');
                }
            }
        }

        /* Client Filter */
        if(isset($columnsData[6]['search']['value']) && !empty($columnsData[6]['search']['value'])){
            $searchClient = $columnsData[6]['search']['value'];
            $assignment->whereHas('client', function($query) use ($searchClient) {
                $query->where('client_id',$searchClient);
            });
        }

        /* Assigment is Open Status Default Open*/
        if($columnsData[8]['search']['value'] !== NULL){
            $status = $columnsData[8]['search']['value'];
            $assignment->where('isopen',$status);
        }else {
            //Log::info('OPEN');
            $assignment->where('isopen', '=', 1);
        }

        /* Assigment Status */
        if(isset($columnsData[9]['search']['value'])){
            $status = $columnsData[9]['search']['value'];
            $assignment->where('approved',$status);
        }

        if(!empty($searchData['value'])){
            //Log::info($searchData['value']);
            $searchVar = $searchData['value'];
            //Log::info($searchVar);
            $assignment->where( function($q) use ($searchVar) {

                $q->whereHas('items', function($query) use ($searchVar) {
                    if(is_numeric($searchVar)){
                        //$query->where('ITEM_ID', '=', $searchVar);
                        $query->where('ITEM_ID', 'LIKE', '%'.$searchVar.'%');
                        $query->orWhere('ITEM_SERIAL', 'LIKE', '%'.$searchVar.'%');
                    } else{
                        $query->where('ITEM_MAKE', 'LIKE', '%'.$searchVar.'%');
                        $query->orWhere('ITEM_MODEL', 'LIKE', '%'.$searchVar.'%');
                        $query->orWhere('ITEM_SERIAL', 'LIKE', '%'.$searchVar.'%');
                        $query->orWhere('ITEM_DESC', 'LIKE', '%'.$searchVar.'%');
                    }

                });

                if(!is_numeric($searchVar)){
                    $q->orWhere('lease_nmbr', 'LIKE', '%'.$searchVar.'%');
                    //$q->orWhere('ls_full_name', 'LIKE', '%'.$searchVar.'%');
                    $q->orWhere('ls_company', 'LIKE','%'.$searchVar.'%');
                    //$q->orWhere('ls_address1', 'LIKE', '%'.$searchVar.'%');
                }
                if(is_numeric($searchVar)){
                    $q->orWhere('assignment_id', 'LIKE', '%'.$searchVar.'%');
                }

            });
        }

        //Log::info($assignment->toSql());

        $assignment = $assignment->orderBy('dt_stmp','desc')->paginate($recordsPerPage, ['*'], 'page', $pageNo);

        //Log::info($assignment);

        return response( ['status' => true,
            'draw' => $draw ,
            'recordsTotal' => $assignment->total(),
            'recordsFiltered' => $assignment->total(),
            'data' => $assignment->items() ] , 200);
    }

    // Add new Assignment Form
    public function addForm(){

        $months = array(
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July ',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        );

        return view('inspection.add',[
            'months' => $months,
            'clients' => Clients::orderBy('FIRSTNAME', 'asc')->get(),
            'states'  => State::all()
        ]);

    }

    public function getInspectionReports(){

        $reports = Survey::all();
        return view('inspection.listReports',['reports' =>  $reports]);

    }

    /* Not used right now */
    public function getReport($id){

        $report = Survey::findorfail($id);

        return view('inspection.viewReport',['report' =>  $report]);

    }

    public function showReport($id) {

        $report = Survey::findorfail($id);

        $reportPdf = PDF::loadView('inspection.viewReportPdf', ['report' => $report]);
        return $reportPdf->download('IPA_equipment_inspection#'.$report->id.'.pdf');

    }
}
