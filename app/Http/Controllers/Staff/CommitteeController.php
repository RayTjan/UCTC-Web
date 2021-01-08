<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Program $program)
    {

        $programs = Program::all()->except($program->id)->pluck('id');

        $committees = User::whereIn('id',function ($query) use ($programs){
            $query->select('uctc_user_id')->from('uctc_program_user')->where('is_approved','1')->whereNotIn('uctc_program_id',$programs);
        })->get();

//        $committeeList = User::whereNotIn('id',function ($query) use ($program){
//            $query->select('uctc_user_id')->from('uctc_program_user')->whereNotIn('uctc_program_id',$program);
//        })->where('role_id',3)->get();

        return view('2ndRoleBlades.listCommittee', compact('committees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        if ($request->selected_program != null){
//            dd($request->selected_program);
            $attend = $user->attends()->syncWithoutDetaching($request->selected_program, ['is_approved'=>'0']);
            return empty($attend)?redirect()->back()->with('Fail',"Failed to add new committee") : redirect()->back()->with('Success',"committee Added Successfully");
        }
        else{
            return redirect()->back()->with('WHAT',"Failed to add new committee");
        }
    }
    public function approve($id, Request $request){
        $user = User::findOrFail($id);
        $program = $user->attends->where('id','=',$request->selected_program)->first();
        $program->pivot->update([
            'is_approved' => '1',
        ]);

        return empty($program) ? redirect()->back()->with('Fail', "Failed to update status")
            : redirect()->back()->with('Success', 'Success guest: #('.$user->identity->name.') approved');

    }
    public  function reject($id, Request $request)
    {
        $user = User::findOrFail($id);
        $program = $user->attends->where('id', '=', $request->selected_program)->first();
        $program->pivot->update([
            'is_approved' => '2',
        ]);

        return empty($program) ? redirect()->back()->with('Fail', "Failed to update status")
            : redirect()->back()->with('Success', 'Success guest: #('.$user->identity->name.') approved');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
