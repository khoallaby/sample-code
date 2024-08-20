<?php
/*
 * A controller for importing/exporting Users
 *
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Models\University;
use App\Models\Company;
use App\Models\CareerPathway;
use App\Models\Internship;
use Maatwebsite\Excel\Facades\Excel;
use App\Validators\UserValidator;
use App\Validators\CompanyValidator;
use App\Validators\InternshipValidator;
use App\Http\Requests\ValidateUserRequest;


class CSVImportController extends \App\Http\Controllers\Controller
{
    public function upload_csv_user()
    {
        return \App\Http\Controllers\view('users.upload_csv_user');
    }

    public function export_csv_user()
    {
        $users = User::all();
        $csvFileName = sprintf("user-export-%s.csv", date('m-d-Y'));
        $csvHeaders = [
            'first_name',
            'last_name',
            'email',
            'role',
            'non_scale',
        ];

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );


        $callback = function() use ($users, $csvHeaders) {
            $file = fopen('php://output', 'w');

            # csv headers
            fputcsv($file, $csvHeaders);

            foreach ($users as $user) {
                fputcsv($file,
                    [
                        $user->first_name,
                        $user->last_name,
                        $user->email,
                        $user->role,
                        $user->non_scale,
                    ]
                );
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function import_csv(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $csvData = file_get_contents($file);
        $rows = array_map("str_getcsv", explode("\n", $csvData));
        $header = array_shift($rows);

        $users = [];

        foreach ($rows as $index => $row) {
            if (empty(array_filter($row))) {
                continue;
            }
            if(count($header) != count($row)) {
                $firstName = isset($row['first_name']) ? $row['first_name'] : 'N/A';
                $lastName = isset($row['last_name']) ? $row['last_name'] : 'N/A';
                return \App\Http\Controllers\redirect()->route('upload_csv_user')->with('error', "Mismatched columns at row " . ($index + 2) . " for user {$firstName} {$lastName}. Expected " . count($header) . " columns but got " . count($row) . " columns.");
            }
            $users[] = array_combine($header, $row);
        }

        return \App\Http\Controllers\view('users.preview_csv', compact('users', 'header'));
    }


    public function confirm_csv_import(Request $request)
    {
        $users = $request->input('users');
        $userValidator = new UserValidator();

        foreach ($users as $index => $user) {
            $userInstance = new User([
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email' => $user['email'],
            ]);

            $errors = $userValidator->validateAll($userInstance);

            if (count($errors)) {
                $errorIndex = $index + 1;
                return \App\Http\Controllers\redirect()->route('upload_csv_user')->with('error', "Invalid data found in CSV file at row {$errorIndex}.");
            }

            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'role' => (int)$user['role'],
                ]
            );
        }

        return \App\Http\Controllers\redirect()->route('user_management')->with('success', 'CSV file imported successfully.');
    }

}
