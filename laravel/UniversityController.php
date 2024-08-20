<?php

/*
 * This handles all aspects of mutating a "University"... create,read,update,delete
 *
 * */

use Illuminate\Http\Request;
use App\Models\University;
use Illuminate\Support\Facades\Validator;
use App\Validators\UniversityValidator;
class UniversityController extends \App\Http\Controllers\Controller
{
    public function university_management()

    {
        $paginationSize = \App\Http\Controllers\config('app.pagination_size');
        $universities = University::orderBy('name', 'asc')->simplePaginate($paginationSize);
        return \App\Http\Controllers\view('universities.university_management', ['universities' => $universities]);
    }

    public function create_university()
    {
        $data = [
            'title' => 'Add University',
            'route_name' => \App\Http\Controllers\route('store_university'),
            'submit_text' => 'Add University',
            'method' => 'POST'
        ];
        return \App\Http\Controllers\view('universities.university_change', $data);
    }

    public function store_university(Request $request)
    {
        $university = new University([
            'name' => $request->name,
            'email_domain' => $request->email_domain,
        ]);
        $validator = new UniversityValidator();
        $errors = $validator->validateAll($university);

        if (count($errors) > 0) {
            return \App\Http\Controllers\redirect()->back()->withErrors($errors)->withInput();
        }
        $university->save();

        return \App\Http\Controllers\redirect()->route('university_management')->with('success', 'University added successfully.');
    }

    public function edit_university($id)
    {
        $university = University::findOrFail($id);
        $data = [
            'university' => $university,
            'title' => 'Edit University: ' . $university->name,
            'route_name' => \App\Http\Controllers\route('update_university', ['id' => $university->id]),
            'submit_text' => 'Update University',
            'method' => 'PUT'
        ];
        return \App\Http\Controllers\view('universities.university_change', $data);
    }

    public function update_university(Request $request, $id)
    {
        $university = University::findOrFail($id);
        $university->name = $request->name;
        $university->email_domain = $request->email_domain;
        $validator = new UniversityValidator();
        $errors = $validator->validateAll($university);

        if (count($errors) > 0) {
            return \App\Http\Controllers\redirect()->back()->withErrors($errors)->withInput();
        }
        $university->save();

        return \App\Http\Controllers\redirect()->route('university_management')->with('success', 'University updated successfully.');
    }

    public function delete_university($id)
    {
        $university = University::findOrFail($id);
        $university->delete();

        return \App\Http\Controllers\redirect()->route('university_management')->with('success', 'University deleted successfully.');
    }
}
