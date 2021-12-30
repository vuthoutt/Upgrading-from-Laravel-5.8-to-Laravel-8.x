<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectComment;
use App\Models\Project;
use App\Models\PropertyComment;
use App\Models\Property;
use App\Models\ItemComment;
use App\Models\Item;
use App\Models\ItemInfo;
use App\Models\LocationComment;
use App\Models\Location;
use App\Models\LocationInfo;
use App\Models\Area;
use App\Models\DecommissionComment;

class CommentHistoryController extends Controller
{
    public function property(Request $request) {
        try {
            $record_id = $request->record_id;
            $comment_id = $request->has('comment_id') ? $request->comment_id : 0;

            $comment = PropertyComment::find($comment_id);
            if (!is_null($comment)) {
                Property::where('id', $record_id)->update(['comments' => $comment->comment]);
            }
            return redirect()->back()->with('msg', 'Property Comment Updated Successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('err', 'Property Comment Updated Fail!');
        }
    }

    public function project(Request $request) {
        try {
            $record_id = $request->record_id;
            $comment_id = $request->has('comment_id') ? $request->comment_id : 0;

            $comment = ProjectComment::find($comment_id);
            if (!is_null($comment)) {
                Project::where('id', $record_id)->update(['comments' => $comment->comment]);
            }
            return redirect()->back()->with('msg', 'Project Comment Updated Successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('err', 'Project Comment Updated Fail!');
        }
    }

    public function location(Request $request) {
        try {
            $record_id = $request->record_id;
            $comment_id = $request->has('comment_id') ? $request->comment_id : 0;

            $comment = LocationComment::find($comment_id);

            if (!is_null($comment)) {
                LocationInfo::where('location_id', $record_id)->update(['comments' => $comment->comment]);
            }
            return redirect()->back()->with('msg', 'Location Comment Updated Successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('err', 'Location Comment Updated Fail!');
        }
    }

    public function item(Request $request) {
        try {
            $record_id = $request->record_id;
            $comment_id = $request->has('comment_id') ? $request->comment_id : 0;

            $comment = ItemComment::find($comment_id);
            if (!is_null($comment)) {
                ItemInfo::where('item_id', $record_id)->update(['comment' => $comment->comment]);
            }
            return redirect()->back()->with('msg', 'Item Comment Updated Successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('err', 'Item Comment Updated Fail!');
        }
    }

    public function decommission($category, Request $request) {
        $record_id = $request->record_id;
        $comment_id = $request->has('comment_id') ? $request->comment_id : 0;

        try {
            $record_id = $request->record_id;
            $comment_id = $request->has('comment_id') ? $request->comment_id : 0;

            $comment = DecommissionComment::find($comment_id);
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

    public static function storeCommentHistory($type, $record_id, $comment, $parent_reference = null) {

        $data = [
            'record_id' => $record_id,
            'comment' => $comment,
            'parent_reference' => $parent_reference
        ];

        switch ($type) {
            case 'property':
                //check duplicate
                $comment = PropertyComment::where('comment', $comment)->where('record_id', $record_id)->first();
                if (is_null($comment)) {
                    PropertyComment::create($data);
                }
                break;

            case 'project':
                //check duplicate
                $comment = ProjectComment::where('comment', $comment)->where('record_id', $record_id)->first();
                if (is_null($comment)) {
                    ProjectComment::create($data);
                }
                break;

            case 'location':
                //check duplicate
                $comment = LocationComment::where('comment', $comment)->where('record_id', $record_id)->first();
                if (is_null($comment)) {
                    LocationComment::create($data);
                }
                break;

            case 'item':
                $comment = ItemComment::where('comment', $comment)->where('record_id', $record_id)->first();
                if (is_null($comment)) {
                    ItemComment::create($data);
                }

                break;

            default:
                # code...
                break;
        }
    }

    public static function storeDeccomissionHistory($type, $category, $record_id, $comment_id, $parent_reference = null) {
        DecommissionComment::create([
            'record_id' => $record_id,
            'type' => $type,
            'category' => $category,
            'comment' => $comment_id,
            'parent_reference' => $parent_reference,
            'user_id' => \Auth::user()->id ?? 0
        ]);
    }

}
