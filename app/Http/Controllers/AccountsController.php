<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Accounts;

class AccountsController extends Controller
{
    //
    public function list()
    {
        $accounts = Accounts::all();
        return response()->json($accounts);
    }
    //create a new account

    public function create(Request $request)
    {
        $account = new Accounts;
        $account->name = $request->name;
        $account->type = $request->type;
        $account->save();
        return respose() ->json
        (
            [
            "message"=>"Account added"
            ],
            201
        );

    }
    //show an account based on its id
    public function find($id)
    {
        $account = Accounts::find($id);
        if(!empty($account))
        {
            return response()->json($account);
        }
        else
        {
            return respone()-json
            (
                [
                "message"=>"Account not found"
                ],
                404
            );
        }
    }
    //update based on id
    public function update(Request $request, $id)
    {
        if(Accounts::where('id', $id)->exists()){
            $account = Accounts::find($id);
            $account->name = is_null($request->name) ? $account->name :$request->name;
            $account->type = is_null($request->type) ? $account->type :$request->type;
            $account->save();
            return response()->json([
                "message"=>"Account updated successfuly"
            ], 201);


        }else{
            return response()->json([
                "message"=>"Account not found"
            ], 404);

        }

    }

    //delete based on it
    public function delete($id)
    {
        if(Accounts::where('id')->exists()){
            $account = Account::find($id);
            $account->delete();
            return response()->json([
                "message" =>"Account deleted"
            ], 202);
        }else{
            return response() ->json([
                "message"=>"Account not found"
            ], 404);
        }
    }


    public function login(Request $request)
    {
        // Your authentication logic here

        if (auth()->attempt($credentials)) {
            // Authentication passed
            return redirect()->intended('/');
        } else {
            // Authentication failed
            throw new AuthenticationException('Invalid Auth Token');
        }
    }

}
