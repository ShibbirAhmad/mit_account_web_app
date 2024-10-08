<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmployeeSalary;
use App\Models\Team;
use App\Models\SalaryPerMonth;


class TeamController extends Controller
{


    public function __construct()
    {
        $this->middleware('admin');
    }
    public  function index(Request $request)
    {
        $item = $request->item ?? 10;
        $members = Team::orderBy('id', 'DESC')->where('status', $request->status)->paginate($item);
        $total_taken_salary = EmployeeSalary::sum('amount');
        $total_paid_salary = SalaryPerMonth::sum('amount');
        $total_due_salary = intval($total_taken_salary) - intval($total_paid_salary);
        return response()->json([
            "success" => "OK",
            "members" => $members,
            "total_due_salary" => number_format($total_due_salary),

        ]);
    }



    public function addTeamMember(Request $request)
    {

        $data = $request->validate([
            'name'  => 'required',
            'joining_date'  => 'required',
            'designation'  => 'required',
            'email'  => 'required|unique:employees',
            'phone'  => 'required|unique:employees|digits:11',
            'joining_date' => 'required'


        ]);


        $team = new Team();
        $team->name = $request->name;
        $team->joining_date = $request->joining_date;
        $team->phone = $request->phone;
        $team->email = $request->email;
        $team->designation = $request->designation;
        $team->status = 1;
        if ($request->hasFile('image')) {
            $file_path = $request->file('image')->store('images/team', 'public');
            $team->avator = $file_path;
        }
        if ($request->hasFile('resume')) {
            $resume_path = $request->file('resume')->store('images/team_resume', 'public');
            $team->resume = $resume_path;
        }

        if ($team->save()) {
            return response()->json([
                'success' => 'OK',
                'message' => 'New  added successfully '
            ]);
        }
    }



    public function getEditTeamMember($id)
    {

        $member  = Team::find($id);
        if ($member) {
            return response()->json([
                "success"  => "OK",
                "member"  => $member,
            ]);
        } else {
            return  response()->json([
                "success" => "Fail",
                "message" => "something wrong "
            ]);
        }
    }


    public function updateTeamMember(Request $request, $id)
    {

        // return $request->all();
        $data = $request->validate([
            'name'  => 'required',
            'joining_date'  => 'required',
            'designation'  => 'required',
            'email'  => 'required|unique:employees,email,' . $id,
            'phone'  => 'required|digits:11,unique:employees,phone,' . $id,
        ]);


        $team = Team::findOrFail($id);
        $team->joining_date = $request->joining_date;
        $team->name = $request->name;
        $team->phone = $request->phone;
        $team->email = $request->email;
        $team->designation = $request->designation;
        $team->status = 1;
        if ($request->hasFile('image')) {

            if (file_exists($team->avator)) {
                unlink('storage/' . $team->avator);
            }

            $file_path = $request->file('image')->store('images/team', 'public');
            $team->avator = $file_path;
        }

        if ($request->hasFile('resume')) {
            $resume_path = $request->file('resume')->store('images/team_resume', 'public');
            $team->resume = $resume_path;
        }

        if ($team->save()) {
            return response()->json([
                'success' => 'OK',
                'message' => 'Information updated successfully '
            ]);
        }
    }



    //function for download resume
    public function downloadResume($id)
    {

        $member = Team::findOrFail($id);
        $resume = $member->resume;
        return Response()->download("public/storage/" . $resume);
    }


    public function deactiveTeamMember($id)
    {

        $member  = Team::find($id);
        $member->status = 0;
        if ($member->save()) {
            return response()->json([
                "success"  => "OK",
                "message"  => "This member de-activated",
            ]);
        } else {
            return  response()->json([
                "success" => "Fail",
                "message" => "something wrong "
            ]);
        }
    }




    public function activeTeamMember($id)
    {

        $member  = Team::find($id);
        $member->status = 1;
        if ($member->save()) {
            return response()->json([
                "success"  => "OK",
                "message"  => "This member activated",
            ]);
        } else {
            return  response()->json([
                "success" => "Fail",
                "message" => "something wrong "
            ]);
        }
    }





    public function destroyTeamMember($id)
    {

        $member  = Team::find($id);
        if ($member->delete()) {
            return response()->json([
                "success"  => "OK",
                "message"  => "This member has removed",
            ]);
        } else {
            return  response()->json([
                "success" => "Fail",
                "message" => "something wrong "
            ]);
        }
    }
    public function salary($id)
    {

        $teamMember = Team::find($id);
        $member_salaries = EmployeeSalary::orderBy('date', 'DESC')->where('employee_id', $id)->get();
        $total_taken_amount = EmployeeSalary::where('employee_id', $id)->sum('amount');
        $total_paid_amount = SalaryPerMonth::where('employee_id', $id)->sum('amount');
        $paid_salary = SalaryPerMonth::orderBy('date', 'DESC')->where('employee_id', $id)->get();

        return response()->json([
            'member' => $teamMember,
            'salary' => $member_salaries,
            'paid_salary' => $paid_salary,
            'total_taken_amount' => $total_taken_amount,
            'total_paid_amount' => $total_paid_amount
        ]);
    }

    public function salaryDetails($id, $month)
    {
        $teamMember = Team::find($id);
        $start_date = $month . '-' . '01';
        $end_date = $month . '-' . '31';
        $member_salaries = EmployeeSalary::orderBy('date', 'DESC')->where('employee_id', $teamMember->id)
            ->whereDate('date', '>=', $start_date)
            ->whereDate('date', '<=', $end_date)
            ->orderBy('date', 'DESC')
            ->get();
        return response()->json([
            'member' => $teamMember,
            'salary' => $member_salaries
        ]);
    }



    public function search_team_member($data)
    {

        $members = Team::where('name', 'like', '%' . $data . '%')
            ->orWhere('phone', 'like', '%' . $data . '%')
            ->orWhere('email', 'like', '%' . $data . '%')
            ->paginate(10);
        return response()->json(['members' => $members]);
    }

    public function paidSalary(Request $request)
    {

        // return $request->all();
        $salary = new SalaryPerMonth();
        $salary->date = $request->date;
        $salary->month = $request->month;
        $salary->amount = $request->amount;
        $salary->employee_id = $request->employee_id;
        $salary->comment = $request->comment ?? null;
        $salary->save();
        return \response()->json('Salary was updated');
    }
}
