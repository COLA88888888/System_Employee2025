<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index() {

    $sort = strtolower(\Request::get('sort') ?? 'desc');

    // validate direction
    if (!in_array($sort, ['asc', 'desc'])) {
        $sort = 'desc';
    }

    $perpage = \Request::get('perpage');
    $department_id = \Request::get('department_id');
    $position_id = \Request::get('position_id');
    $search = \Request::get('search');

    $employee = Employee::join('departments','employees.department_id','=', 'departments.id')
    ->join('positions','employees.position_id','=','positions.id')
    ->select('employees.*','departments.name as department_name','positions.name as position_name')
    ->orderBy('employees.id', $sort);

    if($perpage=='all'){
        return response()->json($employee->get(), 200);
    }

    if($department_id!== "all") {
        $employee = $employee->where('employees.department_id',$department_id);
    }

    if($position_id!== "all") {
        $employee = $employee->where('employees.position_id',$position_id);
    }
    
    if ($search) {
        $employee = $employee->where(function ($query) use ($search) {
            $query->where('employees.fname','like','%' .$search . '%')
                ->orWhere('employees.tel','like','%' .$search . '%')
                ->orWhere('departments.name','like','%' .$search . '%')
                ->orWhere('positions.name','like','%' .$search . '%');
        });
    }

    $employee = $employee->paginate(intval($perpage) ?: 10)->toArray();
    
    return response()->json($employee,200);
}

    public function add(Request $request) {
        try {
            
            // set path sor image
            $imagePath = 'assets/img/Uploads_Staffs/';

            // check if image is uploaded
            if($request->hasFile('image')) {
                // change image name to time() + extension
                $image = $request->file('image'); // ກວດສອບວ່າມີຟາຍສົ່ງມາ ຫຼື ບໍ່
                $imageName = time() . '.' . $image->getClientOriginalExtension(); // ດຶງເອົານາມສະກຸນທີ່ເຮົາອັບໂຫລດຮູບວ່າເປັນນາມສະກຸນຫຍັງ
                // move image to public path
                $image->move(public_path($imagePath), $imageName); // ຮູບທີ່ເຮົາອັບໂຫລດມາຈະຖືກຍ້າຍໄປບັນທຶກເຂົ້າໃນໂຟນເດີເກັບຮູບ              
            }
            else {
                $imageName = null;
            }

            $employee = new Employee();
            $employee->fname = $request->fname;
            $employee->lname = $request->lname;
            $employee->image = $imageName;
            $employee->gender = $request->gender;
            $employee->department_id = $request->department_id;
            $employee->position_id = $request->position_id;
            $employee->dob = $request->dob;
            $employee->tel = $request->tel;
            $employee->email = $request->email;
            $employee->address = $request->address;
            $employee->save();

            $success = true;
            $message = ' ບັນທຶກຂໍ້ມູນສຳເລັດ';
        }
        catch(\Illuminate\Database\QueryException $e) {
            $success = false;
            $message = $e->getMessage();
        }

         $response = [
            'success' => $success,
            'message' => $message,
        ]; 

        return response()->json($response, 201);
    }

    public function edit($id) {
        // edit employee
        try {
            $employee = Employee::find($id);
            
            $success = true;
            $message = 'ດຶງຂໍ້ມູນສຳເລັດ';
        }catch (\Illuminate\Database\QueryException $e) {

            $success = false;
            $message =  $e->getMessage();
        }
        $response = [
            'success' => $success,
            'message' => $message,
            'employee' => $employee
        ];
        return response()->json($response, 200);
    }

    public function update(Request $request, $id) {
        
        try {

             // set path sor image
            $imagePath = 'assets/img/Uploads_Staffs/';

            $employee = Employee::find($id);

            if($request->hasFile('image')){
                // check image is uploaded and check image from products
            if($employee->image && file_exists(public_path($imagePath . $employee->image))){
                unlink(public_path($imagePath . $employee->image));  // ຖ້າມີການປ່ຽນຮູບໃຫ້ລົບຮູບເກົ່າອອກ
            }

            // ອັບໂຫລດຮູບໃໝ່ຂຶ້ນມາແທນ
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path($imagePath),$imageName);
            $employee->image = $imageName;

            }
            else {
                if(($request->image)){
                    $imageName = $employee->image;
                }
                else {
                       if($employee->image && file_exists(public_path($imagePath . $employee->image))){
                        unlink(public_path($imagePath . $employee->image));  // ຖ້າມີການປ່ຽນຮູບໃຫ້ລົບຮູບເກົ່າອອກ
                        }
                        $imageName = null;
                }
            }

            $employee->fname = $request->fname;
            $employee->lname = $request->lname;
            $employee->image = $imageName;
            $employee->gender = $request->gender;
            $employee->department_id = $request->department_id;
            $employee->position_id = $request->position_id;
            $employee->dob = $request->dob;
            $employee->tel = $request->tel;
            $employee->email = $request->email;
            $employee->address = $request->address;
            $employee->save();

            $success = true;
            $message = 'ແກ້ໄຂຂໍ້ມູນສຳເລັດ';

        } catch (\Illuminate\Database\QueryException $e) {
            $success = false;
            $message = $e->getMessage();     
        }

        $response = [
            'success' => $success,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    public function delete($id) {

        try {

              // set path sor image
            $imagePath = 'assets/img/Uploads_Staffs/';

            $employee = Employee::find($id);

            if($employee->image && file_exists(public_path($imagePath . $employee->image))){
                unlink(public_path($imagePath . $employee->image));  // ຖ້າມີການປ່ຽນຮູບໃຫ້ລົບຮູບເກົ່າອອກ
            }
            
            $employee->delete();

            $success = true;
            $message = 'ລົບຂໍ້ມູນສຳເລັດ';

        } catch (\Illuminate\Database\QueryException $e) {
            $success = false;
            $message = $e->getMessage();     
        }

        $response = [
            'success' => $success,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

}
