<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\UserController;

use App\Models\User;

use App\Models\UploadItem;

use App\Models\ReportItem;

use App\Models\Feedback;

use App\Notifications\ClaimNotification;


class ItemController extends Controller
{
    //
    function uploaditem(Request $request)
{
    // if (auth()->check()) {
        $uploadItem = new UploadItem;
        $uploadItem->user_id = $request->getUser();
        $uploadItem->itemname = $request->input('itemname');
        $uploadItem->category = $request->input('category');
        $uploadItem->date = $request->input('date');
        $uploadItem->time = $request->input('time');
        $uploadItem->area = $request->input('area');
        $uploadItem->street = $request->input('street');
        $uploadItem->city = $request->input('city');
        if ($request->hasFile('file')) {
            $uploadItem->file = $request->file('file')->store('uploaditems');
        }
        $uploadItem->description = $request->input('description');
        $uploadItem->save();
        return response()->json(['message' => 'Upload successful']);

    // else {
    //     return response()->json(['error' => 'Unauthenticated user'], 401);
    // }
}

    function reportitem(Request $request)
    {
        $reportitem = new ReportItem;
        $reportitem->itemname = $request->input('itemname');
        $reportitem->category = $request->input('category');
        // $reportitem->date = date('Y-m-d', strtotime($request->input('date')));
        $reportitem->date = $request->input('date');
        // $reportitem->time = date('H:i:s', strtotime($request->input('time')));
        $reportitem->time = $request->input('time');
        $reportitem->area = $request->input('area');
        $reportitem->street = $request->input('street');
        $reportitem->city = $request->input('city');
        $reportitem->file = $request->input('file');
        $reportitem->description = $request->input('description');
        $reportitem->save();
    }

    function feedback(Request $request)
    {
        $feedback = new Feedback;
        $feedback->name = $request->input('name');
        $feedback->phone = $request->input('phone');
        $feedback->email = $request->input('email');
        $feedback->comment = $request->input('comment');
        $feedback->save();

        return "hello";
    }

    // function searchitem($key)
    // {
    //     // return $key;
    //     return UploadItem::where ('itemname','LIKE',"%$key%")->get();
    // }


    public function searchitem(Request $request)
    {
        $query = $request->input('q');

        // search for items matching the query in both tables
        $uploadItems = UploadItem::where('file', 'like', "%{$query}%")
            ->orWhere('area', 'like', "%{$query}%")
            ->orWhere('itemname', 'like', "%{$query}%")
            ->orWhere('category', 'like', "%{$query}%")
            ->orWhere('date', 'like', "%{$query}%")
            ->orWhere('time', 'like', "%{$query}%")
            ->get();

        $reportItems = ReportItem::where('file', 'like', "%{$query}%")
            ->orWhere('area', 'like', "%{$query}%")
            ->orWhere('itemname', 'like', "%{$query}%")
            ->orWhere('category', 'like', "%{$query}%")
            ->orWhere('date', 'like', "%{$query}%")
            ->orWhere('time', 'like', "%{$query}%")
            ->get();

        // merge the results from both tables into a single collection
        $item = $uploadItems->merge($reportItems);

        // return the items as JSON
        return response()->json($item);
    }

    public function claim(Request $request)
    {
        $item = UploadItem::find($request->input('item_id'));
        $user = $item->user;

        $claimerName = Auth::user()->name;

        // Send the notification to the user who uploaded the item
        $user->notify(new ClaimNotification($item, $claimerName));
        return response()->json([
            'message' => 'Notification sent',
        ]);
    }
    public function claimItem($itemId)
{
    // Find the item
    $item = Item::find($itemId);

    // Get the user who uploaded the item
    $uploadedBy = $item->user;

    // Get the authenticated user who is claiming the item
    $claimedBy = auth()->user();

    // Send the notification to the user who uploaded the item
    $uploadedBy->notify(new ClaimNotification($item, $claimedBy));

    // Update the item status
    $item->status = 'claimed';
    $item->save();

    return response()->json([
        'message' => 'Item claimed successfully.'
    ]);
}

}
