<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use App\Services\ShineCompliance\CommentHistoryService;
use App\Services\ShineCompliance\ItemService;
use App\Services\ShineCompliance\PropertyCommentService;
use App\Services\ShineCompliance\LocationCommentService;
use App\Services\ShineCompliance\PropertyService;
use App\Services\ShineCompliance\LocationService;
use Illuminate\Http\Request;


class CommentHistoryController extends Controller
{
    private $propertyService;
    private $homeService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        CommentHistoryService $commentHistoryService,
        ItemService $itemService,
        PropertyCommentService $propertyCommentService,
        LocationCommentService $locationCommentService,
        PropertyService $propertyService,
        LocationService $locationService
    )
    {
        $this->commentHistoryService = $commentHistoryService;
        $this->itemService = $itemService;
        $this->propertyCommentService = $propertyCommentService;
        $this->propertyService = $propertyService;
        $this->locationCommentService = $locationCommentService;
        $this->locationService = $locationService;
    }

    /**
     * Show my organisation by id.
     *
     */
    public function decommission($category, Request $request) {

        $record_id = $request->record_id ?? '';
        $comment_id = $request->has('comment_id') ? $request->comment_id : 0;

        try {
            $comment = $this->commentHistoryService->findDecommissionComment($comment_id);
            if (!is_null($comment)) {
                switch ($comment->category) {
                    case 'area':
                        Area::where('id', $record_id)->update(['decommissioned_reason' => $comment->comment]);
                        return redirect()->back()->with('msg', 'Area Comment Updated Successfully!');
                        break;

                    case 'location':
                        Location::where('id', $record_id)->update(['decommissioned_reason' => $comment->comment]);
                        return redirect()->back()->with('msg', 'Location Comment Updated Successfully!');
                        break;

                    case 'item':
                        Item::where('id', $record_id)->update(['decommissioned_reason' => $comment->comment]);
                        return redirect()->back()->with('msg', 'Item Comment Updated Successfully!');
                        break;

                    default:
                        # code...
                        break;
                }
            }
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->with('err', 'Item Comment Updated Fail!');
        }
    }

    public function item(Request $request) {
        try {
            $record_id = $request->record_id;
            $comment_id = $request->has('comment_id') ? $request->comment_id : 0;
            $comment = $this->itemService->getItemCommentbyId($comment_id);
            if (!is_null($comment)) {
                $this->itemService->updateItemInfo($record_id,$comment->comment);
            }
            return redirect()->back()->with('msg', 'Item Comment Updated Successfully!');
        } catch (Exception $e) {
            \Log::debug($e);
            return redirect()->back()->with('err', 'Item Comment Updated Fail!');
        }
    }

    public function property(Request $request) {
        try {
            $record_id = $request->record_id;
            $comment_id = $request->has('comment_id') ? $request->comment_id : 0;
            $comment = $this->propertyCommentService->getFindPropertyComment($comment_id);
            if (!is_null($comment)) {
                $this->propertyService->updateProperty($record_id,['comments' => $comment->comment]);
            }
            return redirect()->back()->with('msg', 'Property Comment Updated Successfully!');
        } catch (Exception $e) {
            \Log::debug($e);
            return redirect()->back()->with('err', 'Property Comment Updated Fail!');
        }
    }

    public function location(Request $request) {
        try {
            $record_id = $request->record_id;
            $comment_id = $request->has('comment_id') ? $request->comment_id : 0;

            $comment = $this->locationCommentService->getFindLocationComment($comment_id);

            if (!is_null($comment)) {
                $this->locationService->updateLocationInfo($record_id, ['comments' => $comment->comment]);
            }
            return redirect()->back()->with('msg', 'Location Comment Updated Successfully!');
        } catch (Exception $e) {
            \Log::debug($e);
            return redirect()->back()->with('err', 'Location Comment Updated Fail!');
        }
    }
}
