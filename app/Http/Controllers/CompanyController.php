<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Http\Requests\CompanyStore;
use App\Notifications\CompanyAdded;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CompanyController extends Controller
{
    protected $redirectTo = '/';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(Company::latest()->get())
                ->addColumn('action', function ($data) {
                    $button = '<center><button type="button" name="edit" id="' . $data->id . '" class="edit btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i>  Edit</button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<form action="' . route(
                        'company.destroy',
                        $data->id
                    ) . '" method="post">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="delete btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
                        </form>';
                    return $button;
                })
                ->addColumn('logo-display', function ($data) {
                    $logo = $data->logo ? asset('storage/' . $data->logo) : "https://via.placeholder.com/100";
                    return "<center> <img src='" . $logo . "' class='img-rounded' alt='" . $data->name . "'> </center>";
                })
                ->rawColumns(['logo-display', 'action'])
                ->make(true);
        }

        return Company::paginate(10);
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
    public function store(CompanyStore $request)
    {
        $logo = $request->file('logo') ? $request->file('logo') : null;
        if ($logo) {
            Storage::disk('public')->put(
                $request->logo->getClientOriginalName(),
                File::get($logo)
            );
            $logo = $request->logo->getClientOriginalName();
        }

        $company = new Company();
        $company->name = $request->name;
        $company->email = $request->email;
        $company->website = $request->website;
        $company->logo = $logo;
        $company->save();

        if ($request->email) {
            $company->notify(new CompanyAdded($company));
        }

        return redirect(route('app.company'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Company::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyStore $request, Company $company)
    {
        $company->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return redirect(route('app.company'));
    }

    /**
     * Show UI for companies index
     * @return View
     */
    public function appIndex()
    {
        return view('page.company');
    }
}
