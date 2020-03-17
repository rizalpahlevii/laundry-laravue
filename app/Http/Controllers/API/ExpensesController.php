<?php

namespace App\Http\Controllers\API;

use App\Expense;
use App\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseCollection;
use App\Notifications\ExpensesNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;


class ExpensesController extends Controller
{
    public function index()
    {
       $user = request()->user();
       $expenses = Expense::with(['user'])->orderBy('created_at','DESC');
       if(request()->q !=''){
           $expenses = $expenses->where('description', 'LIKE', '%' . request()->q . '%');
       }
       if(in_array($user->role , [1,3])){
           $expenses = $expenses->where('user_id',$user->id);
       }
       return (new ExpenseCollection($expenses->paginate(10)));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required|string|max:150',
            'price' => 'required|integer',
            'note' => 'nullable|string'
        ]);
        $user = $request->user();
        $status = $user->role == 0 || $user->role == 2 ? 1 : 0;
        $request->request->add([
            'user_id' => $user->id,
            'status' => $status
        ]);
        $expenses = Expense::create($request->all());
        $users = User::whereIn('role', [0, 2])->get();
        Notification::send($users, new ExpensesNotification($expenses, $user));

        return response()->json(['status' => 'success']);
    }
    public function accept(Request $request)
    {
        $this->validate($request, ['id' => 'required|exists:expenses,id']); //VALIDASI DATA
        $expenses = Expense::with(['user'])->find($request->id); //AMBIL SINGLE DATA EXPENSES
        $expenses->update(['status' => 1]); //UBAH STATUSNYA JADI APPROVE
        Notification::send($expenses->user, new ExpensesNotification($expenses, $expenses->user)); //KIRIM NOTIFIKASI KE PEMBUAT REQUEST EXPENSES
        return response()->json(['status' => 'success']);
    }

    public function cancelRequest(Request $request)
    {
        $this->validate($request, ['id' => 'required|exists:expenses,id', 'reason' => 'required|string']); //VALIDASI
        $expenses = Expense::with(['user'])->find($request->id); //AMBIL SINGLE DATA EXPENSES
        $expenses->update(['status' => 2, 'reason' => $request->reason]); //UBAH STATUS DAN TAMBAHKAN REASON
        Notification::send($expenses->user, new ExpensesNotification($expenses, $expenses->user)); //KIRIM NOTIFIKASI
        return response()->json(['status' => 'success']);
    }
    public function edit($id)
    {
        $expenses = Expense::with(['user'])->find($id); //AMBIL DATA BERDASARKAN ID
        return response()->json(['status' => 'success', 'data' => $expenses]);
    }

    public function update(Request $request, $id)
    {
        //VALIDASI
        $this->validate($request, [
            'description' => 'required|string|max:150',
            'price' => 'required|integer',
            'note' => 'nullable|string'
        ]);
        $expenses = Expense::find($id); //AMBIL DATA BERDASARKAN ID
        $expenses->update($request->except('id')); //UPDATE DATA TERSEBUT
        return response()->json(['status' => 'success']);
    }
    public function destroy($id)
    {
        $expenses = Expense::find($id);
        $expenses->delete();
        return response()->json(['status' => 'success']);
    }
}
