<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmployeeStore;
use App\Employee;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(Employee::latest()->get())
                ->addColumn('action', function ($data) {
                    $button = '<center><button type="button" name="edit" id="' . $data->id . '" class="edit btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i>  Edit</button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></center>';
                    return $button;
                })
                ->addColumn('company-display', function ($data) {
                    return $data->company->name;
                })
                ->rawColumns(['logo-display', 'action'])
                ->make(true);
        }

        return Employee::paginate(10);
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
     * @param  \App\Http\Requests\EmployeeStore $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeStore $request)
    {
        Employee::create($request->all());

        return redirect(route('app.employee'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Employee::find($id);
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
     * @param  Employee $employee
     * @param  EmployeeStore $request
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeStore $request, Employee $employee)
    {
        $employee->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Employee $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
    }

    /**
     * Show UI for companies index
     * @return View
     */
    public function appIndex()
    {
        $companies = \App\Company::all();

        return view('page.employee', compact('companies'));
    }
}
