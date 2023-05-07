<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Requirement;
use App\Models\Service;
use App\Models\ServiceAllocation;
use App\Models\Session;
use App\Models\Transaction;
use App\Models\Year;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;

class TransactionController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{

    public function addActivity(Request $request)
    {
        $is_admin = auth()->user()->hasRole('admin');
        $dataType_trans = Voyager::model('DataType')->where('slug', '=', "transactions")->first();
        $dataType_session = Voyager::model('DataType')->where('slug', '=', "sessions")->first();

        $session_id = $request->session_id;

        $dataTypeContent_trans = (strlen($dataType_trans->model_name) != 0)
            ? new $dataType_trans->model_name()
            : false;

        $dataTypeContent_session = (strlen($dataType_session->model_name) != 0)
            ? new $dataType_session->model_name()
            : false;

        $years = Year::all();
        $services = Service::parentAll();
        $allocator = auth()->user()->person ? auth()->user()->person->services->pluck('name', 'pivot.year_id')->toArray() : [];
        $activities = Activity::all();

        $session = $activity = $members = $trans = $requirements = null;

        if ($session_id != null) {
            $session = Session::query()->find($session_id);
            $activity = $activities->where('id', $session->activity_id)->first();
            $members = Service::query()->find($session->service_id)->members()->where('service_allocations.year_id', $session->year_id)
                ->where('service_allocations.is_servant', $activity->is_servant)->get() ?? null;
            $requirements = Requirement::query()->where('activity_id', $session->activity_id)->get();
            $trans = Transaction::query()->where('session_id', $session_id)->get();
        }

        return view('custom.addActivity', compact('dataType_session', 'dataType_trans',
            'dataTypeContent_session', 'dataTypeContent_trans', 'years', 'services', 'activities', 'session_id',
            'members', 'requirements', 'session', 'activity', 'trans', 'allocator', 'is_admin'));
    }

    public function addActivitySingle(Request $request)
    {
        $is_admin = auth()->user()->hasRole('admin');
        $session_id = $request->session_id;
        $years = Year::all();
        $services = Service::parentAll();
        $allocator = auth()->user()->person ? auth()->user()->person->services->pluck('name', 'pivot.year_id')->toArray() : [];
        $activities = Activity::all();

        $session = $activity = $members = $trans = $requirements = null;

        if ($session_id != null) {
            $session = Session::query()->find($session_id);
            $activity = $activities->where('id', $session->activity_id)->first();
            $members = Service::query()->find($session->service_id)->members()->where('service_allocations.year_id', $session->year_id)
                ->where('service_allocations.is_servant', $activity->is_servant)->get() ?? null;
            $requirements = Requirement::query()->where('activity_id', $session->activity_id)->get();
            $trans = Transaction::query()->where('session_id', $session_id)->get();
        }

        return view('custom.addActivitySingle', compact( 'years', 'services', 'activities', 'session_id',
            'members', 'requirements', 'session', 'activity', 'trans', 'allocator', 'is_admin'));
    }

    public function storeSession(Request $request)
    {
        if (isset($request->session_id)) {
            $session = Session::query()->find($request->session_id);
            $session->update(['date' => $request->date, 'activity_id' => $request->activity_id, 'year_id' => $request->year_id, 'service_id' => $request->service_id]);
        } else {
            $session = Session::query()->where('date', $request->date)->where( 'activity_id' , $request->activity_id)->where( 'year_id' ,$request->year_id)
                ->where( 'service_id' , $request->service_id)->first();
            if (!$session)
                $session = Session::query()->create(['date' => $request->date, 'activity_id' => $request->activity_id, 'year_id' => $request->year_id, 'service_id' => $request->service_id]);
        }
        return redirect(url()->previous() . '?session_id=' . $session->id);
    }

    public function storeTransaction($session, Request $request)
    {

        $session_id = $session;
        $req = explode('_', $request->person);
        $req_id = $req[0];
        $member_id = $req[1];

        if ($request->att == 1)
            return Transaction::query()->create(['requirement_id' => $req_id, 'session_id' => $session_id, 'person_id' => $member_id]);
        else
            return Transaction::query()->where('requirement_id', $req_id)->where('session_id', $session_id)->where('person_id', $member_id)->first()->delete();

    }

}
