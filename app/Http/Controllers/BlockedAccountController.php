<?php

namespace App\Http\Controllers;

use App\Models\BlockedAccountModel;
use Illuminate\Http\Request;

class BlockedAccountController extends Controller
{
    public function add_blocked_account(Request $request) {
        // Validate the incoming request
        $request->validate([
            'userID' => 'required|integer',
        ]);
    
        // Retrieve userID from the request
        $userID = $request->input('userID');
    
        // Prepare data for insertion
        $insertData = [
            'userID' => $userID
        ];
        BlockedAccountModel::create($insertData);
        return response()->json(['message' => 'Account successfully blocked'], 201);
    }
    public function delete_blocked_account($id) {
        // Find the account by ID
        $blockedAccount = BlockedAccountModel::where('userID', $id)->first();

        // Check if the account exists
        if (!$blockedAccount) {
            return response()->json(['errors' => 'Account not found'], 404);
        }
 
        // Delete the account
        $blockedAccount->delete();
 
        // Return a response
        return response()->json(['message' => 'Account successfully unblocked'], 200);
    }
}
