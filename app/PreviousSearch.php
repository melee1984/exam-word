<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreviousSearch extends Model
{
   	protected $fillable = array('word', 'response');
	public $timestamps = true;

	public function searchString($searchString) {

		$data = array();

		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL => "https://wordsapiv1.p.rapidapi.com/words/".$searchString,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => [
				"x-rapidapi-host: wordsapiv1.p.rapidapi.com",
				"x-rapidapi-key: 68daf9b423mshcdb744a3f182433p176929jsn46744a960f12"
			],
		]);

		$response = curl_exec($curl);
		$response_arr = json_decode($response);

		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
			// $this->response = json_encode($err);
			$data['status'] = 0;
		} else {
			// echo $response;
			if ($response_arr) {
				$this->response = json_encode($response_arr->results);
	    		$this->save();	
			}
		}
		return $data;

	}

}	
