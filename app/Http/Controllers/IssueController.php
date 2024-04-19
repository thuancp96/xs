<?php namespace App\Http\Controllers;

use App\Helpers\NotifyHelpers;
use App\Http\Controllers\Controller;
use App\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class IssueController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	public function create()
    {
        return view('frontend.create_issue');
    }

    public function store(Request $request)
    {
		$dataReq = $request->all();
		$user = Auth::user();
		//your site secret key
        // $secret = '6Lf1KikpAAAAAP7RgUEtilMQJ8_GTjDBQhKwD6cc';
        //get verify response data
        // $verifyResponse = file_get_contents( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response'] );
        // $responseData   = json_decode( $verifyResponse );
		// $responseData->success 
        if ( true){
			$message = '<b>User:</b> '.$user->name .PHP_EOL;
			$message.= '<b>Mô tả:</b> '.$dataReq["description"].PHP_EOL;
			if($request->hasFile('image') && $request->file('image')->isValid()){
				$file = $request->file('image');
				$file_name = str_random(30) . '.' . $file->getClientOriginalExtension();
				$file->move(base_path() . '/public/assets/issue_image', $file_name);
				Issue::create(['image' => $file_name,"description" => $dataReq["description"],"user_id" => $user->id, "user_name" => $user->name]);
				$message.= '<b>Ảnh đính kèm:</b> '.'https://99luckey.com/assets/issue_image/'.$file_name;
			}else{
				echo "no image";
				Issue::create(["description" => $dataReq["description"],"user_id" => $user->id, "user_name" => $user->name]);
			}
			$bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
			$channelid = "-1002038570631";
			NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, $message);
			return redirect('/issues/create')->with(['message' => 'success'] );
		}else
			return redirect('/issues/create')->with(['message' => 'failed'] );
    }
	
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
