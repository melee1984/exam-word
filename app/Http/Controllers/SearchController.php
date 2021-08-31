<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PreviousSearch;
use Validator;

class SearchController extends Controller
{
    /**
     * Function search 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function search(Request $request) {

    	$rules = [
	      'search'     => 'required',
	    ];

	    $validator = Validator::make($request->all(), $rules);

	    if ($validator->fails()) {
	     
		    return response()->json([
		    	'message' => $validator->messages()->first(),
		    ]);
	      
	    } 
	    else {

	    	$searchString = $request->input('search');

	    	$previous_search = PreviousSearch::firstOrNew([
	    		'word' => $searchString
	    	]);

	    	if ($previous_search) {
	    		if ($previous_search->response!="") {
	    			$data['results'] = json_decode($previous_search->response);
	    		}
	    		else {
	    			$previous_search->searchString($searchString);
	    			$data['results'] = json_decode($previous_search->response);
	    		}
	    	}
	    	return response()->json($data, 200);
	    }


    }
}
