<?php

namespace App\Helpers;

use App\Commands\UpdateCustomerTypeByUserIdService;
use App\Commands\UpdateCustomerTypeGame;
use App\Commands\UpdateCustomerTypeGameABCMAXPOINTV2;
use App\Commands\UpdateMeFromParentEXService;
use App\CustomerType_Game;
use App\CustomerType_Game_Original;
use App\Facade\UserFacade;
use App\Game;
use App\Helpers\BoSoHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use JWTFactory;
use JWTAuth;
use App\User;
use App\Location;
use App\Helpers\LocationHelpers;
use App\Helpers\GameHelpers;
use App\Helpers\HistoryHelpers;
use App\Helpers\QuickbetHelpers;
use App\Helpers\UserHelpers;
use App\Helpers\XoSo;
use App\Helpers\XoSoRecordHelpers;
use App\History;
use App\QuickPlayRecord;
use App\XoSoResult;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Cache;
use luk79\CryptoJsAes\CryptoJsAes;
// require "CryptoJsAes.php";
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PrettyTable;
use \Queue;
use Telegram\Bot\Api;
use SevenEcks\Tableify\Tableify;
// use \Cache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Crypt;
use Google2FA;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use SebastianBergmann\Environment\Console;

class XosobotHelpers
{
    private $token = "";
    private $quickbet;
    private $bot_type;

    private $keyboardTrangchu =
    array(
        array(
            array('text' => 'Nhập tin cược', 'callback_data' => 'cuocnhanh'),
            array('text' => 'Nhập tay', 'callback_data' => 'datcuocmanual'),
        ),
        array(
            array('text' => 'Bảng cược', 'callback_data' => 'bangcuoc'),
            array('text' => 'Sao kê', 'callback_data' => 'saoke'),
        ),
        array(
            array('text' => 'Kết quả', 'callback_data' => 'ketqua'),
            array('text' => 'Thông số', 'callback_data' => 'thongso'),
        ),
        array(
            array('text' => 'Nhận thông báo kq xs Miền bắc', 'url' => 'https://t.me/thongbaoketquaxosomienbac'),
        ),
        array(
            array(
                'text' => 'Hướng dẫn', "url" => "https://t.me/+yGhoX1YDOG82OWZl"
            ),
        ),
        array(
            array(
                'text' => 'Xổ số miền bắc - Webapp', 'web_app' => array("url" => "https://99luckey.com")
            ),
        ),
        array(
            array(
                'text' => 'Bóng đá - Webapp', 'web_app' => array("url" => "https://99luckey.com")
            ),
        )
    );

    private $keyboardTrangchuAgent =
    array(
        array(
            array('text' => 'Tài khoản', 'callback_data' => 'taikhoan_agent'),
            array('text' => 'Thống kê', 'callback_data' => 'thongke_agent'),
        ),
        array(
            array('text' => 'Bảng biểu', 'callback_data' => 'bangbieu_agent'),
            array('text' => 'Bảng thao tác giá', 'callback_data' => 'bangthaotacgia_agent'),
        ),
        array(
            array('text' => 'Thao tác', 'callback_data' => 'thaotac'),
        ),
        array(
            array('text' => 'Nhận thông báo kq xs Miền bắc', 'url' => 'https://t.me/thongbaoketquaxosomienbac'),
        ),
        array(
            array(
                'text' => 'Hướng dẫn', "url" => "https://t.me/+MnvdZ6xgKb02MzA9"
            ),
        ),
        array(
            array(
                'text' => 'Web app', 'web_app' => array("url" => "https://ag.99luckey.com")
            ),
        )
    );

    private $keyboardTrangchuAdmin =
    array(
        // array(
        //     array('text' => 'Tài khoản', 'callback_data' => 'taikhoan_agent'),
        //     array('text' => 'Thống kê', 'callback_data' => 'thongke_agent'),
        // ),
        array(
            array('text' => 'Thao tác', 'callback_data' => 'thaotac'),
        ),
        array(
            array('text' => 'Nhận thông báo kq xs Miền bắc', 'url' => 'https://t.me/thongbaoketquaxosomienbac'),
        ),
        array(
            array(
                'text' => 'Hướng dẫn', "url" => "https://t.me/+MnvdZ6xgKb02MzA9"
            ),
        ),
        array(
            array(
                'text' => 'Web app', 'web_app' => array("url" => "https://ag.99luckey.com")
            ),
        ),
        // array(
        //     array(
        //         'text' => 'Web app 2 (Android)', "url" => "https://ag.99luckey.com"
        //     ),
        // ),
        // array(
        //     array(
        //         'text' => 'Web app 2 (Android)', 'web_app' => array("url" => "https://ag.99luckey.com")
        //     ),
        // )
    );

    private $keyboardQuanlymemberAgent =
    array(
        array(
            array('text' => 'Thêm mới', 'callback_data' => 'themmoitaikhoanmember_agent'),
            array('text' => 'Thao tác', 'callback_data' => 'thaotactaikhoanmember_agent'),
        ),
        array(
            array('text' => '< Back', 'callback_data' => 'back'),
        )
    );

    private $keyboardThongso =
    array(
        array(
            array('text' => 'Giá mua', 'callback_data' => 'giamua'),
            array('text' => 'Giới hạn cược', 'callback_data' => 'gioihancuoc'),
        ),
        array(
            array('text' => 'Giá thấp: Tắt', 'callback_data' => 'giathap'),
            array('text' => 'Thông số giá thấp', 'callback_data' => 'thongsogt'),
        ),
        array(
            array('text' => 'Chỉnh thông số giá thấp', 'callback_data' => 'chinhthongsogt'),
        ),
        array(
            array('text' => '< Back', 'callback_data' => 'back'),
        )
    );

    private $keyboardOnlyBack =
    array(
        array(
            array('text' => '< Back', 'callback_data' => 'back'),
        )
    );

    private $keyboardHuongdancuoc =
    array(
        array(
            array('text' => '< Back', 'callback_data' => 'back'),
            array(
                'text' => 'Hướng dẫn cược nhanh', 'web_app' => array("url" => 'https://99luckey.com/bridge-both-tele?url=https://99luckey.com/assets/huongdannhaptin.html')
            )
        )
    );

    private $keyboardBangcuocchitiet =
    array(
        array(
            array('text' => '< Back', 'callback_data' => 'back'),
            array('text' => 'Hủy cược', 'callback_data' => 'huycuoc'),
        )
    );

    private $keyboardBangcuocchitietPaging =
    array(
        array(
            array('text' => '<< Trước', 'callback_data' => 'previouspage_'),
            array('text' => 'Sau >>', 'callback_data' => 'nextpage_'),
        ),
        array(
            array('text' => '< Back', 'callback_data' => 'back'),
            array('text' => 'Hủy cược', 'callback_data' => 'huycuoc'),
        )
    );

    private $keyboardChitiettincuoc =
    array(
        array(
            array('text' => 'Vào cược', 'callback_data' => 'vaocuoc'),
            array('text' => 'Nhập lại tin', 'callback_data' => 'nhaplaitin'),
            array('text' => 'Xem lại giá', 'callback_data' => 'xemlaigia'),
        ),
        array(
            array('text' => 'Hủy', 'callback_data' => 'vaocuoc_huy'),
        )
    );

    private $keyboardThongsogt =
    array(
        array(
            array('text' => 'Chỉnh thông số giá thấp', 'callback_data' => 'chinhthongsogt'),
        ),
        array(
            array('text' => '< Back', 'callback_data' => 'back'),
        )
    );

    private $keyboardChinhthongsoxacnhan =
    array(
        array(
            array('text' => 'Đồng ý', 'callback_data' => 'chinhthongsogt_dongy'),
            array('text' => 'Nhập lại giá', 'callback_data' => 'chinhthongsogt_nhaplai')
        ),
        array(
            array('text' => '< Back', 'callback_data' => 'back'),
        )
    );

    private $keyboardHuycuocxacnhan =
    array(
        array(
            array('text' => 'Đồng ý', 'callback_data' => 'huycuoc_dongy'),
        ),
        array(
            array('text' => '< Back', 'callback_data' => 'back'),
        )
    );

    private $keyboardSaoketuannay =
    array(
        array(
            array('text' => 'Thứ 2', 'callback_data' => 'saoketuannay_thu2'),
            array('text' => 'Thứ 3', 'callback_data' => 'saoketuannay_thu3'),
        ),
        array(
            array('text' => 'Thứ 4', 'callback_data' => 'saoketuannay_thu4'),
            array('text' => 'Thứ 5', 'callback_data' => 'saoketuannay_thu5'),
        ),
        array(
            array('text' => 'Thứ 6', 'callback_data' => 'saoketuannay_thu6'),
            array('text' => 'Thứ 7', 'callback_data' => 'saoketuannay_thu7'),
        ),
        array(
            array('text' => '< Back', 'callback_data' => 'back'),
            array('text' => 'CN', 'callback_data' => 'saoketuannay_cn'),
        )
    );

    private $keyboardHoivienthangthuatuannay =
    array(
        array(
            array('text' => 'Thứ 2', 'callback_data' => 'hoivienthangthuatuannay_thu2'),
            array('text' => 'Thứ 3', 'callback_data' => 'hoivienthangthuatuannay_thu3'),
        ),
        array(
            array('text' => 'Thứ 4', 'callback_data' => 'hoivienthangthuatuannay_thu4'),
            array('text' => 'Thứ 5', 'callback_data' => 'hoivienthangthuatuannay_thu5'),
        ),
        array(
            array('text' => 'Thứ 6', 'callback_data' => 'hoivienthangthuatuannay_thu6'),
            array('text' => 'Thứ 7', 'callback_data' => 'hoivienthangthuatuannay_thu7'),
        ),
        array(
            array('text' => '< Back', 'callback_data' => 'hoivienthangthua_agent'),
            array('text' => 'CN', 'callback_data' => 'hoivienthangthuatuannay_cn'),
        )
    );

    private $keyboardSaoketuantruoc =
    array(
        array(
            array('text' => 'Thứ 2', 'callback_data' => 'saoketuantruoc_thu2'),
            array('text' => 'Thứ 3', 'callback_data' => 'saoketuantruoc_thu3'),
        ),
        array(
            array('text' => 'Thứ 4', 'callback_data' => 'saoketuantruoc_thu4'),
            array('text' => 'Thứ 5', 'callback_data' => 'saoketuantruoc_thu5'),
        ),
        array(
            array('text' => 'Thứ 6', 'callback_data' => 'saoketuantruoc_thu6'),
            array('text' => 'Thứ 7', 'callback_data' => 'saoketuantruoc_thu7'),
        ),
        array(
            array('text' => '< Back', 'callback_data' => 'back'),
            array('text' => 'CN', 'callback_data' => 'saoketuantruoc_cn'),
        )
    );


    private $keyboardVaocuocSochon =
    array(
        array(
            array('text' => 'Nhập lại số', 'callback_data' => 'vaocuocmanual_nhaplaiso'),
            array('text' => 'Chọn lại thể loại', 'callback_data' => 'vaocuocmanual_chonlaitheloai'),
        ),
        array(
            array('text' => 'Hủy', 'callback_data' => 'vaocuocmanual_huy'),
        )
    );

    private $keyboardVaocuocTheloai =
    array(
        array(
            array('text' => 'Chọn lại thể loại', 'callback_data' => 'vaocuocmanual_chonlaitheloai'),
        ),
        array(
            array('text' => 'Hủy', 'callback_data' => 'vaocuocmanual_huy'),
        )
    );

    private $keyboardKetqua =
    array(
        array(
            array('text' => '< Back', 'callback_data' => 'back'),
            array('text' => 'Tải lại', 'callback_data' => 'reload_ketqua'),
        )
    );

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($token, $bot_type)
    {
        $this->token = $token;
        $this->bot_type = $bot_type;
        // Log::info($token);
    }

    public function Cal_Ank($game_id, $bet_number)
    {
        $ank = 1;
        if (
            $game_id == 29 || $game_id == 9 || $game_id == 10 || $game_id == 11 || $game_id == 19
            || $game_id == 309 || $game_id == 310 || $game_id == 311
            || $game_id == 409 || $game_id == 410 || $game_id == 411
            || $game_id == 509 || $game_id == 510 || $game_id == 511
            || $game_id == 609 || $game_id == 610 || $game_id == 611
            || $game_id == 709 || $game_id == 710 || $game_id == 711
        ) {
            $arrbetnumber = explode(',', $bet_number);
            $countbetnumber = count($arrbetnumber);
            switch ($game_id) {
                case 29:
                    $factnumber = 2;
                    break;
                case 9:
                case 309:
                case 409:
                case 509:
                case 609:
                case 709:
                    $factnumber = 2;
                    break;
                case 10:
                case 310:
                case 410:
                case 510:
                case 610:
                case 710:
                    $factnumber = 3;
                    break;
                case 11:
                case 19:
                case 311:
                case 411:
                case 511:
                case 611:
                case 711:
                    $factnumber = 4;
                    break;
                default:
                    $factnumber = 1;
                    break;
            }
            $ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact($factnumber) / XoSoRecordHelpers::fact($countbetnumber - $factnumber);
        }
        return $ank;
    }

    private function callback($up)
    {
        return isset($up['callback_query']) ? true : false;
    }

    private function appendMessageID2Queue($messageid,$chatid){
        $queue = Cache::get('queue_messages' . $chatid,[]);
        if (!is_array($queue)) $queue = [];
        $isContrains = false;
        foreach ($queue as $key => $message) {
            if ($message[0] == $messageid) $isContrains = true;
        }
        if (!$isContrains)
        {
            $queue[] = [$messageid,$this->token];
            Cache::put('queue_messages' . $chatid, $queue, env('CACHE_TIME_BOT', 24 * 60));
        }
    }

    public function clearMessageQueue($chatid){
        $arrMQ = Cache::get('queue_messages' . $chatid,[]);
        // $arrMQ = explode(",",$queue);
        // var_dump($arrMQ);
        foreach($arrMQ as $message){
            $this->deleteMessage($chatid,$message[0],$message[1]);
            sleep(1);
        }
        Cache::put('queue_messages' . $chatid, [], env('CACHE_TIME_BOT', 24 * 60));
    }

    private function sendChatAction($id)
    {
        $telegram = new Api($this->token);
        $response = $telegram->getMe();
        $response = $telegram->sendChatAction([
            'chat_id' => $id,
            'action' => 'typing'
        ]);
        return 0;
    }

    private function sendMessage($id, $message)
    {
        $telegram = new Api($this->token);
        $response = $telegram->getMe();
        $response = $telegram->sendMessage([
            'chat_id' => $id,
            'text' => $message,
            'parse_mode' => 'HTML'
        ]);
        $this->appendMessageID2Queue($response->getMessageId(),$id);
        return $response->getMessageId();
    }

    public function deleteMessage($id, $messageId,$token="")
    {
        try {
            $telegram = new Api($token == "" ? $this->token : $token);
            $response = $telegram->getMe();
            $response = $telegram->deleteMessage([
                'chat_id' => $id,
                'message_id' => $messageId,
            ]);
            return $response->getMessageId();
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            echo $ex->getMessage();
        }
        return 0;
    }

    private function sendMessageReplyMarkup($id, $message, $keyboard)
    {
        $telegram = new Api($this->token);
        $response = $telegram->getMe();

        $reply_markup = $telegram->replyKeyboardMarkup($keyboard);

        $response = $telegram->sendMessage([
            'chat_id' => $id,
            // 'text' => 'Hello guy', 
            'reply_markup' => $reply_markup,
            'text' => $message,
            'parse_mode' => 'HTML'
        ]);
        $this->appendMessageID2Queue($response->getMessageId(),$id);
        return $response->getMessageId();
    }

    private function editMessageReplyMarkup($id, $message_id, $message, $keyboard)
    {
        try {
            $telegram = new Api($this->token);
            $response = $telegram->getMe();

            $reply_markup = $telegram->replyKeyboardMarkup($keyboard);

            $response = $telegram->editMessageText([
                'chat_id' => $id,
                'message_id' => $message_id,
                'reply_markup' => $reply_markup,
                'text' => $message,
                'parse_mode' => 'HTML'
            ]);
            return $response->getMessageId();
        } catch (Exception  $ex) {
            $this->sendMessage($id, $ex->getMessage());
        }
        return 0;
    }

    private function editMessage($id, $message_id, $message)
    {
        try {
            $telegram = new Api($this->token);
            $response = $telegram->getMe();

            $response = $telegram->editMessageText([
                'chat_id' => $id,
                'message_id' => $message_id,
                'text' => $message,
                'parse_mode' => 'HTML'
            ]);
            return $response->getMessageId();
        } catch (Exception  $ex) {
            $this->sendMessage($id, $ex->getMessage());
        }
        return 0;
    }

    private function confirmTerms($chatId, $message_id, $user, $mode = 'edit'){
        $keyboard =
                array(
                    array(
                        array('text' => 'Không đồng ý', 'callback_data' => 'confirmCancel'),
                        array('text' => 'Tôi đã đọc và đồng ý', 'callback_data' => 'confirmYes'),
                    )
                );
        $mess = "";
        $mess .= "<b>Điều khoản</b>" . "\n". "\n";
        $mess .= "1.	Có thể luật của nơi bạn đang sống không cho cá cược hợp pháp. Nếu bạn vào đặt cược ở đó, Công ty chúng tôi sẽ không chịu trách nhiệm về những sự cố mà khách hàng gặp phải." . "\n". "\n";
        $mess .= '2.	Khách hàng có trách nhiệm bảo mật về tài khoản của mình. Nếu khách hàng nghi ngờ rằng dữ liệu cùa mình bị đánh cắp, nên thông báo ngay cho Đại lý cấp trên hoặc Báo lỗi trên hệ thống.' . "\n". "\n";
        $mess .= "3.	Khi đặt cược là bạn đã chấp nhận theo luật chơi, trả thưởng của phía công ty. Chúng tôi không có trách nhiệm giải quyết thắc mắc, trả thưởng theo luật chơi bên phía các bạn đang hoặc đã từng cược." . "\n". "\n";
        $mess .= "4.	Công ty có quyền từ chối, hủy những mã đặt cược bất thường hoặc có biểu hiện gian lận: vào cược sau giờ mở thưởng, có dấu hiệu hack và chỉnh sửa, …" . "\n". "\n";
        $mess .= "5.	<i>Công ty và người chơi có thể hủy bỏ các mã đặt cược trước giờ quay thưởng. Người chơi xin lưu ý theo dõi để nhận thông báo các mã huỷ bỏ.</i>";
        $mess .= "";
        $trangChuMessageId = 0;
        if ($mode != 'edit')
            $trangChuMessageId = $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboard]);
        else
            $trangChuMessageId = $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $keyboard]);

        return $trangChuMessageId;
    }

    private function showTrangchu($chatId, $message_id, $user, $mode = 'edit')
    {
        if (Cache::get('stack_action_bot_tele_confirm_terms' . $user->id, true) == false){
            $this->confirmTerms($chatId,$message_id,$user,$mode);
            return;
        }
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu"], env('CACHE_TIME_BOT', 24 * 60));
        Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
        $user->auth_token = Str::random(30);
        $user->save();
        $keyboard = $this->keyboardTrangchu;
        if ($user->lock == 0) {
            $this->keyboardTrangchu[5][0]['web_app']['url'] = "https://99luckey.com/bridge-both-tele?url=https://99luckey.com/auth/token/" . $user->auth_token . "/xoso";
            $this->keyboardTrangchu[6][0]['web_app']['url'] = "https://99luckey.com/bridge-both-tele?url=https://99luckey.com/auth/token/" . $user->auth_token . "/7zball";
            $keyboard = $this->keyboardTrangchu;
        } else {
            $keyboard =
                array(
                    array(
                        array('text' => 'Kết quả', 'callback_data' => 'ketqua'),
                        array('text' => 'Thông số', 'callback_data' => 'thongso'),
                    ),
                    array(
                        array('text' => 'Nhận thông báo kq xs Miền bắc', 'url' => 'https://t.me/thongbaoketquaxosomienbac'),
                    ),
                    array(
                        array(
                            'text' => 'Xổ số miền bắc - Webapp', 'web_app' => array("url" => "https://99luckey.com")
                        ),
                    ),
                    array(
                        array(
                            'text' => 'Bóng đá - Webapp', 'web_app' => array("url" => "https://99luckey.com")
                        ),
                    )
                );
            $keyboard[2][0]['web_app']['url'] = "https://99luckey.com/bridge-both-tele?url=https://99luckey.com/auth/token/" . $user->auth_token . "/";
            $keyboard[3][0]['web_app']['url'] = "https://99luckey.com/bridge-both-tele?url=https://99luckey.com/auth/token/" . $user->auth_token . "/7zball";
        }

        $newDate = date("Y-m-d");
        if (date('H') < 11)
            $newDate = date("Y-m-d", strtotime('-1 day', strtotime($newDate)));
        $recordUser = XoSoRecordHelpers::GetByUserByDate($user, $newDate);

        // $recordUserBC = XoSoRecordHelpers::GetByUserByDate($user,$newDate);
        // print_r($recordUser);
        $somacuoc = count($recordUser);
        $thangthua = 0;
        $total = 0;
        // echo count($recordUser);
        foreach ($recordUser as $record) {
            if ($record->locationslug == 70 || $record->locationslug == 80) {
                if (isset($record->rawBet) && ($record->rawBet->paid != null || $record->rawBet->paid != 0)) {
                    $thangthua += $record->total_win_money;
                } else {
                    $total += $record->total_bet_money;
                }
            } else {
                if ($record->total_win_money == 0)
                    $total += $record->total_bet_money;
                else {
                    if ($record->total_win_money > 0) {
                        if (
                            $record->game_id > 3000 || $record->game_id == 15 || $record->game_id == 16
                            || $record->game_id == 316 || $record->game_id == 416 || $record->game_id == 516 || $record->game_id == 616
                            || $record->game_id == 115 || $record->game_id == 116
                        ) {
                            $thangthua += $record->total_win_money;
                            // || $xosorecord->game_id == 16|| $xosorecord->game_id == 19|| $xosorecord->game_id == 20 || $xosorecord->game_id == 21
                        } else
                            $thangthua += ($record->total_win_money - $record->total_bet_money);
                    } else {
                        // if ($record->game_id > 3000)
                        // $thangthua += (0-$record->total_bet_money);
                        $thangthua += $record->total_win_money;
                    }
                }
            }
            if ($record->locationslug == 70 || $record->locationslug == 80) {
                $arrBonus = explode(",", $record->bonus);
                $bonus = end($arrBonus);
                if ($bonus > 0)
                    $thangthua += $bonus;
            }
            //$thangthua += $record->total_win_money;
        }
        // $mess = '
        // <b>Thông tin tài khoản</b>
        // <b>Hội viên</b>: <i>' . $user->name . '</i>
        // <b>Hạn mức còn lại</b>: <i>' . number_format($user->remain) . '</i>
        // <b>Đang cược</b>: <i>' . number_format($total, 0) . '</i>
        // <b>Thắng thua</b>: <i>' . number_format($thangthua, 0) . '</i>';

        // $mess = "Thông tin tài khoản" . "\n";
        $mess = "<pre>";
        $data = [
            ["Thông tin", ""],
            ["Hội viên", $user->name],
            ["Tín dụng", number_format($user->remain)],
            ["Đang cược",  number_format($total, 0)],
            ["Thắng thua", number_format($thangthua, 0)]
        ];
        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();

        foreach ($table_data as $row) {
            $mess .= $row . "\n";
            // echo $row . "\n";
        }
        $mess .= "</pre>";
        $trangChuMessageId = 0;
        if ($mode != 'edit')
            $trangChuMessageId = $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboard]);
        else
            $trangChuMessageId = $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $keyboard]);

        Cache::put('latest_messageid_trangchu_bot_tele' . $user->id, $trangChuMessageId, env('CACHE_TIME_BOT', 24 * 60));
        return $trangChuMessageId;
    }

    public function renewShowTrangChu($cbId, $user, $isDelete = True)
    {
        $trangChuMessageId = Cache::get('latest_messageid_trangchu_bot_tele' . $user->id);
        if ($isDelete)
            $this->deleteMessage($cbId, $trangChuMessageId);
        $this->showTrangchu($cbId, 0, $user, "notedit");
        return;
    }

    private function xosobot_asm_callback($cbId, $cbData, $cbMessageId, $user)
    {
        switch ($cbData) {
            case 'back':
                $this->showTrangchuAdmin($cbId, $cbMessageId, $user, "edit");
                break;

            case 'thaotac':
                $this->thaotacAdmin($cbId, $cbMessageId, $user, "edit");
                break;
            case 'caidatthongbaotele':
                $this->caidatthongbaotele($cbId, $cbMessageId, $user, "edit");
                $this->thaotacAdmin($cbId, $cbMessageId, $user, "edit");
                break;

            case 'matkhau_dongy':
                $stack_action_inline_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                $user->password = Hash::make($stack_action_inline_bot_tele[1]);
                $user->save();
                Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                $this->editMessage($cbId,$cbMessageId, "Lưu mật khẩu mới thành công!!! /start để bắt đầu");  
                break;

            case 'matkhau_nhaplai':
                $this->sendMessage($cbId, "Nhập lại mật khẩu mới:");
                Cache::put('stack_action_bot_tele' . $user->id, ["request_change_pw"], env('CACHE_TIME_BOT', 24 * 60));
                break;

            default:
                break;
        }
    }

    private function xosobot_member_callback($cbId, $cbData, $cbMessageId, $user)
    {
        switch ($cbData) {
            case 'back':
                $stackActionInline = Cache::get('stack_action_inline_bot_tele' . $user->id);
                //Log::info($stackActionInline);
                switch (end($stackActionInline)) {
                    case 'thongso':
                    case 'ketqua':
                        $this->showTrangchu($cbId, $cbMessageId, $user, "edit");
                        break;

                    case 'saoke':
                    case 'bangcuoc':
                        $this->showTrangchu($cbId, $cbMessageId, $user, "edit");
                        break;

                    case 'giamua':
                    case 'gioihancuoc':
                    case 'thongsogt':
                        $this->thongso($cbId, $cbMessageId, $user, "edit");
                        break;

                    case 'chinhthongsogt':
                        $this->thongsogt($cbId, $cbMessageId, $user, "edit");
                        break;

                    case 'saoketuannay':
                        // $this->saokeTodayF($cbId,$cbMessageId,$user);
                        $this->showTrangchu($cbId, $cbMessageId, $user, "edit");
                        break;

                    case 'saoketuantruoc':
                        $this->saokeTodayF($cbId, $cbMessageId, $user);
                        break;

                    case 'saoketuannaythu_':
                        // //Log::info("go back saoketuannay");
                        $this->saoketuannay($cbId, $cbMessageId, $user);
                        break;

                    case 'saoketuantruocthu_':
                        // //Log::info("go back saoketuantruoc");
                        $this->saoketuantruoc($cbId, $cbMessageId, $user);
                        break;
                    case 'bangcuocchitiet':
                        $this->bangcuoc($cbId, $cbMessageId, $user);
                        break;
                    case 'banghuycuocchitiet':
                        $this->bangcuocchitiet($user, $cbId, $cbMessageId);
                        break;

                    default:
                        $this->showTrangchu($cbId, $cbMessageId, $user, "edit");
                        break;
                }
                break;

            case 'giamua':
                $this->thongsogiamua($cbId, $cbMessageId, $user, "edit");
                break;

            case 'gioihancuoc':
                $this->thongsogioihancuoc($cbId, $cbMessageId, $user, "edit");
                break;

            case 'thongso':
                $this->thongso($cbId, $cbMessageId, $user, "edit");
                break;

            case 'thongsogt':
                $this->thongsogt($cbId, $cbMessageId, $user, "edit");
                break;

            case 'chinhthongsogt':
                $this->chinhthongsogt($cbId, $cbMessageId, $user, "edit");
                break;
            case 'caidatgiathap':
                $this->caidatgiathap($cbId, $cbMessageId, $user, "edit");
                break;
            case 'bangcuoc':
                $this->bangcuoc($cbId, $cbMessageId, $user);
                break;

            case 'Bangcuocpreviouspage_':
                $this->bangcuoc($cbId, $cbMessageId, $user, Cache::get('bangcuocPaging_currentpage' . $user->id) - 1);
                break;

            case 'Bangcuocnextpage_':
                $this->bangcuoc($cbId, $cbMessageId, $user, Cache::get('bangcuocPaging_currentpage' . $user->id) + 1);
                break;

            case 'Banghuycuocchitietpreviouspage_':
                $this->banghuycuocchitiet($user, $cbId, $cbMessageId, Cache::get('banghuycuocchitietPaging_currentpage' . $user->id) - 1);
                break;

            case 'Banghuycuocchitietnextpage_':
                $this->banghuycuocchitiet($user, $cbId, $cbMessageId, Cache::get('banghuycuocchitietPaging_currentpage' . $user->id) + 1);
                break;

            case 'bangcuocchitiet':
                $this->bangcuocchitiet($user, $cbId, $cbMessageId);
                break;
            case 'Bangcuocchitietpreviouspage_':
                $this->bangcuocchitiet($user, $cbId, $cbMessageId, Cache::get('bangcuocchitietPaging_currentpage' . $user->id) - 1);
                break;
            case 'Bangcuocchitietnextpage_':
                $this->bangcuocchitiet($user, $cbId, $cbMessageId, Cache::get('bangcuocchitietPaging_currentpage' . $user->id) + 1);
                break;

            case 'banghuycuocchitiet':
                $this->banghuycuocchitiet($user, $cbId, $cbMessageId);
                break;

            case 'huycuoc':
                $this->huycuoc_step1($cbId, $cbMessageId, $user, "nonedit");
                break;

            case 'huycuoc_dongy':
                $this->huycuoc_dongy($cbId, $cbMessageId, $user, "nonedit");
                break;

            case 'saoke':
                $this->saokeTodayF($cbId, $cbMessageId, $user);
                break;

            case 'saoketuantruoc':
                $this->saoketuantruoc($cbId, $cbMessageId, $user);
                break;

            case 'saoketuannay':
                $this->saoketuannay($cbId, $cbMessageId, $user);
                break;

            case 'cuocnhanh':
            case 'nhaplaitin':
                if ($user->lock == 1 || $user->lock == 3) {
                    $this->sendMessage($cbId, "Tài khoản đã bị ngừng đặt.");
                    return;
                }
                Cache::put('stack_action_bot_tele' . $user->id, ["cuocnhanh"], env('CACHE_TIME_BOT', 24 * 60));
                Cache::forget('quick_bet_text_bot_tele' . $user->id);
                $mess = "Bạn đang chọn cược tin.". "\n" ."Nhập vào biểu mẫu theo đúng mẫu : " . "\n" . "Thể loại – số cược – điểm cược" . "\n";
                $mess .= "Ví dụ: đề 79,97 x 100k" . "\n";
                $mess .= "Lô, đề 79,97 100k" . "\n";
                $mess .= "2 cửa đầu 1 x 100k";

                $this->editMessageReplyMarkup($cbId, $cbMessageId, $mess,  ['inline_keyboard' => $this->keyboardHuongdancuoc]);
                break;

            case 'datcuocmanual':
                $this->datcuocmanual_step1($cbId, $cbMessageId, $user, "edit");
                break;

            case 'vaocuocmanual_xemthemgiai':
                $this->datcuocmanual_step1_more($cbId, $cbMessageId, $user, "edit");
                break;

            case 'ketqua':
            case 'reload_ketqua':
                $this->ketqua($cbId, $cbMessageId, $user);
                // $this->renewShowTrangChu($cbId,$user);
                break;

            case 'vaocuoc':
                if ($user->lock == 1 || $user->lock == 3) {
                    $this->sendMessage($cbId, "Tài khoản đã bị ngừng đặt.");
                    return;
                }
                $quick_bet_text_bot_tele = Cache::get('quick_bet_text_bot_tele' . $user->id, '');
                if ($quick_bet_text_bot_tele == "") {
                    $this->sendMessage($cbId, "Tin cược bị lỗi. Vui lòng nhập lại!");
                    return;
                }
                $this->vaocuoc($quick_bet_text_bot_tele, $user, $cbId, $cbMessageId);

                // $this->renewShowTrangChu($cbId, $user, false);
                if ($user->lock == 1 || $user->lock == 3) {
                    $this->sendMessage($cbId, "Tài khoản đã bị ngừng đặt.");
                    return;
                }
                Cache::put('stack_action_bot_tele' . $user->id, ["cuocnhanh"], env('CACHE_TIME_BOT', 24 * 60));
                Cache::forget('quick_bet_text_bot_tele' . $user->id);
                $mess = "Nhập vào biểu mẫu theo đúng mẫu : " . "\n" . "Thể loại – số cược – điểm cược" . "\n";
                $mess .= "Ví dụ: đề 79,97 x 100k" . "\n";
                $mess .= "Lô, đề 79,97 100k" . "\n";
                $mess .= "2 cửa đầu 1 x 100k";

                $this->sendMessageReplyMarkup($cbId, $mess,  ['inline_keyboard' => $this->keyboardOnlyBack]);

                break;
            case 'vaocuocmanual_nhaplaiso':
                $this->datcuocmanual_step2($cbId, $cbMessageId, $user, "edit");
                $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                array_pop($stack_action_bot_tele);
                Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
                break;

            case 'vaocuocmanual_chonlaitheloai':
                $this->datcuocmanual_step1($cbId, $cbMessageId, $user, "edit");
                // $this->deleteMessage($cbId, $cbMessageId-1);
                // $this->deleteMessage($cbId, $cbMessageId);
                break;

            case 'vaocuocmanual_huy':
                $this->deleteMessage($cbId, $cbMessageId);
                $this->renewShowTrangChu($cbId, $user, false);
                Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                break;

            case 'vaocuoc_huy':
                $this->deleteMessage($cbId, $cbMessageId);
                $this->renewShowTrangChu($cbId, $user, false);
                Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                break;

            case 'chitietcuoc':
            case 'xemlaigia':
                $quick_bet_text_bot_tele = Cache::get('quick_bet_text_bot_tele' . $user->id, '');
                if ($quick_bet_text_bot_tele == "") {
                    $this->sendMessage($cbId, "Tin cược bị lỗi. Vui lòng nhập lại!");
                    return;
                }
                $this->xemtruoccuoc($quick_bet_text_bot_tele, $user, $cbId, $cbMessageId);
                break;
            case 'matkhau_dongy':
                $stack_action_inline_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                $user->password = Hash::make($stack_action_inline_bot_tele[1]);
                $user->save();
                Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                $this->editMessage($cbId,$cbMessageId, "Lưu mật khẩu mới thành công!!! /start để bắt đầu");  
                break;

            case 'matkhau_nhaplai':
                $this->sendMessage($cbId, "Nhập lại mật khẩu mới:");
                Cache::put('stack_action_bot_tele' . $user->id, ["request_change_pw"], env('CACHE_TIME_BOT', 24 * 60));
                break;
            case 'confirmCancel':
                Cache::put('stack_action_bot_tele_confirm_terms' . $user->id, false, env('CACHE_TIME_BOT', 24 * 60));
                $this->editMessage($cbId,$cbMessageId, "Quý khách đã Không Đồng ý điều khoản cty đưa ra!!! /start để bắt đầu lại.");  
                break;
            case 'confirmYes':
                // $this->editMessage($cbId,$cbMessageId, "Quí khách đã Đồng ý điều khoản cty đưa ra!!! Bắt đầu vào hệ thống.");  
                Cache::put('stack_action_bot_tele_confirm_terms' . $user->id, true, env('CACHE_TIME_BOT', 24 * 60));
                $this->showTrangchu($cbId, $cbMessageId, $user, "edit");
                break;
            default:
                break;
        }

        // saokechitietdai_1_2023-04-03

        if (strpos($cbData, 'saokechitietdai_') === 0) {
            $cdDataSplit = substr($cbData, 16);
            $arrCdData = explode("_", $cdDataSplit);
            $dayStr = $arrCdData[1];
            $locationID = $arrCdData[0];
            $stack_action_inline_bot_tele = Cache::get('stack_action_inline_bot_tele' . $user->id);
            // saokechitietdai_1_2023-03-316
            //Log::info($stack_action_inline_bot_tele);
            if ((date('w', strtotime($dayStr))) == 0) {
                $this->saokeByTodayChitietDai($user, $cbId, $cbMessageId, date('Y-m-d', strtotime($dayStr)), $locationID, $this->keyboardOnlyBack, $customKB = true, end($stack_action_inline_bot_tele), str_replace("thu_", "_cn", $stack_action_inline_bot_tele[count($stack_action_inline_bot_tele) - 1]));
            } else
                $this->saokeByTodayChitietDai($user, $cbId, $cbMessageId, date('Y-m-d', strtotime($dayStr)), $locationID, $this->keyboardOnlyBack, $customKB = true, end($stack_action_inline_bot_tele), str_replace("thu_", "_thu", $stack_action_inline_bot_tele[count($stack_action_inline_bot_tele) - 1]) . (date('w', strtotime($dayStr)) + 1));
        }

        if (strpos($cbData, 'saokechitiettheloai_') === 0) {
            $cdDataSplit = substr($cbData, 20);
            $arrCdData = explode("_", $cdDataSplit);
            $dayStr = $arrCdData[1];
            $gameID = $arrCdData[0];

            // 0 => '7',
            //   1 => '2023-03-31',
            //Log::info($arrCdData);
            //
            // saokechitietdai_1_2023-03-31
            $game = GameHelpers::GetGameByCode($gameID);
            $stack_action_inline_bot_tele = Cache::get('stack_action_inline_bot_tele' . $user->id);
            // saokechitietdai_1_2023-03-316
            //Log::info($stack_action_inline_bot_tele);
            $this->saokeByTodayChitietTheloai($user, $cbId, $cbMessageId, date('Y-m-d', strtotime($dayStr)), $gameID, $this->keyboardOnlyBack, $customKB = true, "saokechitietdai_" . $game->locationslug . "_" . $dayStr);
        }

        if (strpos($cbData, 'saoketuannay_') === 0) {
            $thu = substr($cbData, 13);

            $now = date("Y-m-d");
            $staticstart = $now;
            //check the current day
            if (date('D') != 'Mon') {
                //take the last monday
                $staticstart = date('Y-m-d', strtotime('last Monday'));
            } else {
                $staticstart = date('Y-m-d');
            }

            switch ($thu) {
                case 'thu2':
                    $this->saokeByToday($user, $cbId, $cbMessageId, $staticstart, $this->keyboardOnlyBack, $customKB = true, "saoketuannaythu_");
                    break;

                case 'thu3':
                    $staticstart = date('Y-m-d', strtotime('+1 day', strtotime($staticstart)));
                    $this->saokeByToday($user, $cbId, $cbMessageId, $staticstart, $this->keyboardOnlyBack, $customKB = true, "saoketuannaythu_");
                    break;

                case 'thu4':
                    $staticstart = date('Y-m-d', strtotime('+2 day', strtotime($staticstart)));
                    $this->saokeByToday($user, $cbId, $cbMessageId, $staticstart, $this->keyboardOnlyBack, $customKB = true, "saoketuannaythu_");
                    break;

                case 'thu5':
                    $staticstart = date('Y-m-d', strtotime('+3 day', strtotime($staticstart)));
                    $this->saokeByToday($user, $cbId, $cbMessageId, $staticstart, $this->keyboardOnlyBack, $customKB = true, "saoketuannaythu_");
                    break;

                case 'thu6':
                    $staticstart = date('Y-m-d', strtotime('+4 day', strtotime($staticstart)));
                    $this->saokeByToday($user, $cbId, $cbMessageId, $staticstart, $this->keyboardOnlyBack, $customKB = true, "saoketuannaythu_");
                    break;

                case 'thu7':
                    $staticstart = date('Y-m-d', strtotime('+5 day', strtotime($staticstart)));
                    $this->saokeByToday($user, $cbId, $cbMessageId, $staticstart, $this->keyboardOnlyBack, $customKB = true, "saoketuannaythu_");
                    break;

                case 'cn':
                    $staticstart = date('Y-m-d', strtotime('+6 day', strtotime($staticstart)));
                    $this->saokeByToday($user, $cbId, $cbMessageId, $staticstart, $this->keyboardOnlyBack, $customKB = true, "saoketuannaythu_");
                    break;

                default:
                    # code...
                    break;
            }
        }

        // //Log::info($cbData);
        if (strpos($cbData, 'saoketuantruoc_') === 0) {
            $thu = substr($cbData, 15);

            $now = date("Y-m-d");
            $staticstart = $now;
            //check the current day
            if (date('D') != 'Mon') {
                //take the last monday
                $staticstart = date('Y-m-d', strtotime('last Monday'));
            } else {
                $staticstart = date('Y-m-d');
            }

            switch ($thu) {
                case 'thu2':
                    $staticstart = date('Y-m-d', strtotime('-7 day', strtotime($staticstart)));
                    $this->saokeByToday($user, $cbId, $cbMessageId, $staticstart, $this->keyboardOnlyBack, $customKB = true, "saoketuantruocthu_");
                    break;

                case 'thu3':
                    $staticstart = date('Y-m-d', strtotime('-6 day', strtotime($staticstart)));
                    $this->saokeByToday($user, $cbId, $cbMessageId, $staticstart, $this->keyboardOnlyBack, $customKB = true, "saoketuantruocthu_");
                    break;

                case 'thu4':
                    $staticstart = date('Y-m-d', strtotime('-5 day', strtotime($staticstart)));
                    $this->saokeByToday($user, $cbId, $cbMessageId, $staticstart, $this->keyboardOnlyBack, $customKB = true, "saoketuantruocthu_");
                    break;

                case 'thu5':
                    $staticstart = date('Y-m-d', strtotime('-4 day', strtotime($staticstart)));
                    $this->saokeByToday($user, $cbId, $cbMessageId, $staticstart, $this->keyboardOnlyBack, $customKB = true, "saoketuantruocthu_");
                    break;

                case 'thu6':
                    $staticstart = date('Y-m-d', strtotime('-3 day', strtotime($staticstart)));
                    $this->saokeByToday($user, $cbId, $cbMessageId, $staticstart, $this->keyboardOnlyBack, $customKB = true, "saoketuantruocthu_");
                    break;

                case 'thu7':
                    $staticstart = date('Y-m-d', strtotime('-2 day', strtotime($staticstart)));
                    $this->saokeByToday($user, $cbId, $cbMessageId, $staticstart, $this->keyboardOnlyBack, $customKB = true, "saoketuantruocthu_");
                    break;

                case 'cn':
                    $staticstart = date('Y-m-d', strtotime('-1 day', strtotime($staticstart)));
                    $this->saokeByToday($user, $cbId, $cbMessageId, $staticstart, $this->keyboardOnlyBack, $customKB = true, "saoketuantruocthu_");
                    break;

                default:
                    # code...
                    break;
            }
        }

        if (strpos($cbData, 'chinhthongsogt_') === 0) {
            switch ($cbData) {
                case 'chinhthongsogt_dongy':
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    $thisGame = Game::where('game_code', $stack_action_bot_tele[1][1])->first();
                    $customerType =
                        CustomerType_Game_Original::where('code_type', $user->customer_type)
                        ->where('game_id', $thisGame->game_code)
                        ->where('created_user', $user->id)->first();
                    $customerType->exchange_rates = (int)$stack_action_bot_tele[2];
                    $customerType->save();
                    Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                    //$this->editMessageReplyMarkup($cbId, $cbMessageId, "Thay đổi thành công !", ['inline_keyboard' => $this->keyboardOnlyBack]);
                    // $this->editMessage($cbId,$cbMessageId,"Thay đổi thành công !");
                    // $this->renewShowTrangChu($cbId,$user);
                    // sleep(2);
                    $this->thongsogt($cbId, $cbMessageId, $user, "edit");
                    break;

                case 'chinhthongsogt_nhaplai':
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhthongsogt") {
                        if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                            $stack_action_bot_tele = array_slice($stack_action_bot_tele, 0, 2);
                            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
                            $mess = "Nhập lại giá thấp cho " . $stack_action_bot_tele[1][0] . "\n";
                            // $this->deleteMessage($cbData,$cbMessageId);
                            // sleep(2);
                            $this->sendMessage($cbId, $mess);
                        }
                    }
                    break;

                default:
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhthongsogt") {
                        $game_code = substr($cbData, 15);
                        $thisGame = Game::where('game_code', $game_code)->first();

                        // array_push($stack_action_bot_tele, $thisGame->name);
                        $stack_action_bot_tele = array("chinhthongsogt", [$thisGame->name, $game_code]);
                        Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

                        if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                            $mess = "Nhập giá thấp cho " . $stack_action_bot_tele[1][0] . "\n";
                            // $this->deleteMessage($cbData,$cbMessageId);
                            // sleep(2);
                            $this->sendMessage($cbId, $mess);
                        }
                    }
                    break;
            }
        }

        if (strpos($cbData, 'vaocuocchonlaitheloai_') === 0) {
            $game_code = substr($cbData, 22);
            $game_name = "";

            switch ($game_code) {
                case '7':
                    $game_name = "Lô";
                    break;

                case '14':
                    $game_name = "Đề";
                    break;

                case '12':
                    $game_name = "Nhất";
                    break;
                case '9':
                    $game_name = "Xiên 2";
                    break;
                case '10':
                    $game_name = "Xiên 3";
                    break;
                case '11':
                    $game_name = "Xiên 4";
                    break;

                case '29':
                    $game_name = "Xiên nháy";
                    break;

                case '91011':
                    $game_name = "Xiên quay";
                    break;

                case '17':
                    $game_name = "3 Càng";
                    break;

                case '1412':
                    $game_name = "Đề Nhất";
                    break;

                case '18':
                    $game_name = "Lô Live";
                    break;

                case '56':
                    $game_name = "3 càng nhất";
                    break;

                case '15':
                    $game_name = "đề trượt";
                    break;

                case '16':
                    $game_name = "lô trượt 1";
                    break;

                case '19':
                    $game_name = "lô trượt 4";
                    break;

                case '20':
                    $game_name = "lô trượt 8";
                    break;

                case '21':
                    $game_name = "lô trượt 10";
                    break;

                case '25':
                    $game_name = "đầu thần tài";
                    break;

                case '26':
                    $game_name = "đuôi thần tài";
                    break;

                case '27':
                    $game_name = "đầu đặc biệt";
                    break;

                case '28':
                    $game_name = "đầu nhất";
                    break;

                case '31':
                    $game_name = "giải 2.1";
                    break;
                case '32':
                    $game_name = "giải 2.2";
                    break;
                case '33':
                    $game_name = "giải 3.1";
                    break;
                case '34':
                    $game_name = "giải 3.2";
                    break;
                case '35':
                    $game_name = "giải 3.3";
                    break;
                case '36':
                    $game_name = "giải 3.4";
                    break;
                case '37':
                    $game_name = "giải 3.5";
                    break;
                case '38':
                    $game_name = "giải 3.6";
                    break;
                case '39':
                    $game_name = "giải 4.1";
                    break;
                case '40':
                    $game_name = "giải 4.2";
                    break;
                case '41':
                    $game_name = "giải 4.3";
                    break;
                case '42':
                    $game_name = "giải 4.4";
                    break;
                case '43':
                    $game_name = "giải 5.1";
                    break;
                case '44':
                    $game_name = "giải 5.2";
                    break;
                case '45':
                    $game_name = "giải 5.3";
                    break;
                case '46':
                    $game_name = "giải 5.4";
                    break;
                case '47':
                    $game_name = "giải 5.5";
                    break;
                case '48':
                    $game_name = "giải 5.6";
                    break;
                case '498':
                    $game_name = "giải 6.1";
                    break;
                case '50':
                    $game_name = "giải 6.2";
                    break;
                case '51':
                    $game_name = "giải 6.3";
                    break;
                case '52':
                    $game_name = "giải 7.1";
                    break;
                case '53':
                    $game_name = "giải 7.2";
                    break;
                case '54':
                    $game_name = "giải 7.3";
                    break;
                case '55':
                    $game_name = "giải 7.4";


                default:
                    # code...
                    break;
            }

            $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
            if (isset($stack_action_bot_tele) && count($stack_action_bot_tele) > 0 && $stack_action_bot_tele[0] == "datcuocmanual") {
                // $tincuoc = $message;
                array_push($stack_action_bot_tele, $game_name);
                Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

                if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                    $this->datcuocmanual_step2($cbId, $cbMessageId, $game_name, "edit");
                    // $this->deleteMessage($cbId,$cbMessageId-1);
                    return;
                }
            }
        }

        return;
    }

    private function xosobot_member_trolyao_callback($cbId, $cbData, $cbMessageId, $user)
    {
        switch ($cbData) {
            case 'cuocnhanh':
            case 'nhaplaitin':
                if ($user->lock == 1 || $user->lock == 3) {
                    $this->sendMessage($cbId, "Tài khoản đã bị ngừng đặt.");
                    return;
                }
                Cache::put('stack_action_bot_tele' . $user->id, ["cuocnhanh"], env('CACHE_TIME_BOT', 24 * 60));
                Cache::forget('quick_bet_text_bot_tele' . $user->id);
                // $mess = "Nhập vào biểu mẫu theo đúng mẫu : " . "\n" . "Thể loại – số cược – điểm cược" . "\n";
                // $mess .= "Ví dụ: đề 79,97 x 100k" . "\n";
                // $mess .= "Lô, đề 79,97 100k" . "\n";
                // $mess .= "2 cửa đầu 1 x 100k";

                // $this->editMessageReplyMarkup($cbId, $cbMessageId, $mess,  ['inline_keyboard' => $this->keyboardHuongdancuoc]);
                break;

            case 'vaocuoc':
                if ($user->lock == 1 || $user->lock == 3) {
                    $this->sendMessage($cbId, "Tài khoản đã bị ngừng đặt.");
                    return;
                }
                $quick_bet_text_bot_tele = Cache::get('quick_bet_text_bot_tele' . $user->id, '');
                if ($quick_bet_text_bot_tele == "") {
                    $this->sendMessage($cbId, "Tin cược bị lỗi. Vui lòng nhập lại!");
                    return;
                }
                $this->vaocuoc_trolymb($quick_bet_text_bot_tele, $user, $cbId, $cbMessageId);

                // $this->renewShowTrangChu($cbId, $user, false);
                if ($user->lock == 1 || $user->lock == 3) {
                    $this->sendMessage($cbId, "Tài khoản đã bị ngừng đặt.");
                    return;
                }
                Cache::put('stack_action_bot_tele' . $user->id, ["cuocnhanh"], env('CACHE_TIME_BOT', 24 * 60));
                Cache::forget('quick_bet_text_bot_tele' . $user->id);
                // $mess = "Nhập vào biểu mẫu theo đúng mẫu : " . "\n" . "Thể loại – số cược – điểm cược" . "\n";
                // $mess .= "Ví dụ: đề 79,97 x 100k" . "\n";
                // $mess .= "Lô, đề 79,97 100k" . "\n";
                // $mess .= "2 cửa đầu 1 x 100k";

                // $this->sendMessageReplyMarkup($cbId, $mess,  ['inline_keyboard' => $this->keyboardOnlyBack]);

                break;

            case 'vaocuoc_huy':
                $this->deleteMessage($cbId, $cbMessageId);
                $this->renewShowTrangChu($cbId, $user, false);
                Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                break;

            default:
                break;
        }
        return;
    }

    private function xosobot_member_trolymb_message($chatId, $messageId, $message, $user)
    {
        if (strpos($message, "/start") === 0) {
            // $mess = '<b>Hãy liên kết tài khoản và bắt đầu sử dụng bằng cách lệnh /thongtin  /vaocuoc</b>';
            $this->sendMessage($chatId, "Trợ lý đang hoạt động! Hi " . $user->name);
            return;
        }
        // $this->deleteMessage($chatId, $messageId - 1);
        // $quickBet = substr($message, 9);
        $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);

        // if (isset($stack_action_bot_tele) && count($stack_action_bot_tele) > 0 && $stack_action_bot_tele[0] == "cuocnhanh") 
        {
            // $this->deleteMessage($chatId, $messageId - 1);
            // $this->deleteMessage($chatId, $messageId);
            $tincuoc = $message;
            // $old = Cache::get('quick_bet_text_bot_tele' . $user->id, '');
            // $tincuoc = $old . " " .$message;
            Cache::put('quick_bet_text_bot_tele' . $user->id, $tincuoc, env('CACHE_TIME_BOT', 24 * 60));
            // Cache::put('quick_bet_text_bot_tele' . $user->id, $tincuoc, env('CACHE_TIME_BOT', 24 * 60));
            $bet = array(array(), $tincuoc);
            try {
                $useLowPrice = Cache::get('useLowPrice_bot_tele' . $user->id, false);
                $bet = $this->quickbet->quickplaylogic($user, $tincuoc, '0', '', $useLowPrice);
            } catch (Exception $ex) {
                //Log::info($ex->getMessage() . $ex->getLine() . $ex->getFile());
                $bet = array(array(), $tincuoc);
            }

            if ($message == "Huy" || $message == "huy") {
                $this->sendMessage($chatId, "Huỷ thành công");
                Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                return;
            }

            if (count($bet[0]) == 0) {
                $mess = "Tin cược không đúng!" . "\n";
                $mess .= "Ví dụ: đề 79,97 x 100k" . "\n";
                $mess .= "Lô, đề 79,97 100k" . "\n";
                $mess .= "2 cửa đầu 1 x 100k";
                $this->sendMessage($chatId, $mess);
            } else {
                $data = [["Loại", "Số", "Tiền", "TT"]];

                foreach ($bet[0] as $record) {
                    foreach ($record['choices'] as $choices)
                        array_push($data, [$record['game_name'], $choices['name'], number_format($choices['total']), $record['status']]);
                }

                $list_tin_cuoc = [];
                $list_tin_huy = [];
                foreach ($bet[0] as $requestCuoc) {
                    if ($requestCuoc['status'] == '' || $requestCuoc['status'] == 'ok')
                        array_push($list_tin_cuoc, $requestCuoc);
                    else
                        array_push($list_tin_huy, $requestCuoc);
                }

                $tin_cuoc = $this->quickbet->revertquickplay($list_tin_cuoc, "\n");
                $tin_huy = $this->quickbet->revertquickplay($list_tin_huy, "\n");

                $mess = $tincuoc . "\n";
                $mess .= "************************************" . "\n";
                if ($tin_cuoc != "") {
                    $mess .= "<b>Tin nhận: \n" . $tin_cuoc . "</b> \n";
                }
                if ($tin_huy != "") {
                    $mess .= "<i>Huỷ: \n" . $tin_huy . "</i>";
                }

                $keyboard =
                    array(
                        array(
                            array('text' => 'Vào cược', 'callback_data' => 'vaocuoc'),
                            // array('text' => 'Chi tiết', 'callback_data' => 'chitietcuoc'),
                            // array('text' => 'Nhập lại tin', 'callback_data' => 'nhaplaitin'),
                            // array('text' => 'nhập lại', 'callback_data' => 'nhaplai'),

                        )
                        // ,
                        // array(
                        //     array('text' => 'Hủy', 'callback_data' => 'vaocuocmanual_huy'),
                        // ),
                    );

                $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboard]);

                // $this->sendMessage($chatId,$mess);
                return;
            }
        }
    }

    private function xosobot_member_message($chatId, $messageId, $message, $user)
    {
        if (strpos($message, "/start") === 0) {
            // kiem tra lock_tele
            if (Hash::check($user->name, $user->password)){
                $this->sendMessage($chatId, "Bạn chưa đặt mật khẩu. Xin vui lòng đặt mật khẩu!!!");
                $this->sendMessage($chatId, "Nhập mật khẩu mới:");
                Cache::put('stack_action_bot_tele' . $user->id, ["request_change_pw"], env('CACHE_TIME_BOT', 24 * 60));
                Cache::put('stack_action_bot_tele_confirm_terms' . $user->id, false, env('CACHE_TIME_BOT', 24 * 60));
                return;
            }
                
            if($user->lock_tele == 1){
                // $quickBet = substr($message, 9);
                Cache::put('stack_action_bot_tele' . $user->id, ["unlock_tele"], env('CACHE_TIME_BOT', 24 * 60));
                Cache::put('stack_action_bot_tele_confirm_terms' . $user->id, false, env('CACHE_TIME_BOT', 24 * 60));
                if ($user->google2fa_secret != NULL)
                    $this->sendMessage($chatId, "Vui lòng nhập OTP để sử dụng!!!");  
                else
                    $this->sendMessage($chatId, "Vui lòng nhập mật khẩu để sử dụng!!!");  
                return;
            }else{
                // $mess = '<b>Hãy liên kết tài khoản và bắt đầu sử dụng bằng cách lệnh /thongtin  /vaocuoc</b>';
                $this->showTrangchu($chatId, 0, $user, "notedit");
                return;
            }
        }

        if (strpos($message, "/kqmb") === 0) {
            // $getDate = substr($message, 5);
            // $staticDate = date('Y-m-d',strtotime($getDate));
            $this->ketqua($chatId, $messageId, $user, date('Y-m-d'));
            return;
        }

        if (strpos($message, "/datcuoc") === 0) {
            if ($user->lock == 1 || $user->lock == 3) {
                $this->sendMessage($chatId, "Tài khoản đã bị ngừng đặt.");
                return;
            }
            $keyboard =
                array(
                    array(
                        array('text' => 'Cược nhanh', 'callback_data' => 'cuocnhanh'),
                        array('text' => 'Đặt cược theo lựa chọn', 'callback_data' => 'datcuocmanual'),
                    )
                );

            $mess = '
            Chúng tôi cung cấp hai hình thức đặt cược bao gồm:
                <b>1. Cược nhanh.</b>
                <b>2. Cược theo thứ tự lựa chọn.</b>
            Hãy vui lòng lựa chọn 1 trong hai hình thức trên. Chúc bạn may mắn.';

            $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboard]);
            return;
        }

        if (strpos($message, "/vaocuoc") === 0) {
            if ($user->lock == 1 || $user->lock == 3) {
                $this->sendMessage($chatId, "Tài khoản đã bị ngừng đặt.");
                return;
            }
            $mess = "Nhập vào biểu mẫu theo đúng mẫu : " . "\n" . "Thể loại – số cược – điểm cược" . "\n";
            $mess .= "Ví dụ: đề 79,97 x 100k" . "\n";
            $mess .= "Lô, đề 79,97 100k" . "\n";
            $mess .= "2 cửa đầu 1 x 100k";

            $this->sendMessage($chatId, $mess);
            return;
        }

        // $quickBet = substr($message, 9);
        $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);

        if (isset($stack_action_bot_tele) && count($stack_action_bot_tele) > 0 && $stack_action_bot_tele[0] == "request_change_pw") {
            array_push($stack_action_bot_tele, $message);
            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

            if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                //check password hoac otp
                $this->deleteMessage($chatId,$messageId);
                $mess = "Bạn có muốn sử dụng mật khẩu: " . $stack_action_bot_tele[1] . " " . "\n";
                $keyboardMatkhauxacnhan =
                array(
                    array(
                        array('text' => 'Đồng ý', 'callback_data' => 'matkhau_dongy'),
                    ),
                    array(
                        array('text' => 'Nhập lại', 'callback_data' => 'matkhau_nhaplai'),
                    )
                );
                $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboardMatkhauxacnhan]);

                return;
            }
        }

        if (isset($stack_action_bot_tele) && count($stack_action_bot_tele) > 0 && $stack_action_bot_tele[0] == "unlock_tele") {
            array_push($stack_action_bot_tele, $message);
            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

            if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                //check password hoac otp
                $this->deleteMessage($chatId,$messageId);
                $checked = false;
                if ($user->google2fa_secret != NULL){ //check otp
                    $secret = Crypt::decrypt($user->google2fa_secret);
                    if (Google2FA::verifyKey($secret, $stack_action_bot_tele[1])) {
                        $checked=true;
                    }
                }else{
                    if (Hash::check($stack_action_bot_tele[1], $user->password))
                        $checked=true;
                }
                
                if($checked){
                    $user->lock_tele=0;
                    $user->save();
                    $this->showTrangchu($chatId, 0, $user, "notedit");
                }else{
                    if ($user->google2fa_secret != NULL)
                        $this->sendMessage($chatId, "Thông tin không chính xác. Vui lòng nhập OTP để sử dụng!!!");  
                    else
                        $this->sendMessage($chatId, "Thông tin không chính xác. Vui lòng nhập mật khẩu để sử dụng!!!");  
                    Cache::put('stack_action_bot_tele' . $user->id, ["unlock_tele"], env('CACHE_TIME_BOT', 24 * 60));          
                }
                return;
            }
        }
        
        if (isset($stack_action_bot_tele) && count($stack_action_bot_tele) > 0 && $stack_action_bot_tele[0] == "huycuoc") {
            array_push($stack_action_bot_tele, $message);
            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

            if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                $this->bangcuochuycuocchitiet($user, $chatId, $messageId);
                return;
            }
        }

        if (isset($stack_action_bot_tele) && count($stack_action_bot_tele) > 0 && $stack_action_bot_tele[0] == "chinhthongsogt") {
            array_push($stack_action_bot_tele, $message);
            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

            if (isset($stack_action_bot_tele[2])) { //chọn xong số, chuyển sang nhập điểm
                $mess = "Bạn có muốn thay giá thấp " . $stack_action_bot_tele[1][0] . " là " . $stack_action_bot_tele[2] . " " . "\n";
                $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $this->keyboardChinhthongsoxacnhan]);
                return;
            }

            if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                $mess = "Nhập giá " . $stack_action_bot_tele[1] . ": " . "\n";
                $this->sendMessage($chatId, $mess);
                return;
            }
        }

        if (isset($stack_action_bot_tele) && count($stack_action_bot_tele) > 0 && $stack_action_bot_tele[0] == "datcuocmanual") {
            // $tincuoc = $message;
            array_push($stack_action_bot_tele, $message);
            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

            if ($message == "Dong y & Vao Cuoc") {
                if ($user->lock == 1 || $user->lock == 3) {
                    $this->sendMessage($chatId, "Tài khoản đã bị ngừng đặt.");
                    return;
                }
                $this->vaocuoc($stack_action_bot_tele[1] . " " . $stack_action_bot_tele[2] . ":" . $stack_action_bot_tele[3] . 'd', $user, $chatId, null);
                // $this->sendMessage($chatId,"Vào cược thành công");
                Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                return;
            }

            if ($message == "Huy" || $message == "huy") {
                $this->sendMessage($chatId, "Huỷ thành công");
                Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                return;
            }

            if (isset($stack_action_bot_tele[3])) { //chọn xong số, chuyển sang nhập điểm
                // $mess = "Bạn lựa chọn vào cược " . $stack_action_bot_tele[1] . " mã cược " . $stack_action_bot_tele[2] . " " . $stack_action_bot_tele[3] . " điểm" . "\n";
                // $mess .= "Hãy chắc chắn bạn đã nhập đúng thông tin trước khi vào cược." . "\n";

                // $keyboard =
                //     array(
                //         array('Đồng ý & Vào Cược', "Huỷ"),
                //     );
                // $this->sendMessageReplyMarkup($chatId, $mess, ['keyboard' => $keyboard, "one_time_keyboard" => true]);
                $tincuoc = $stack_action_bot_tele[1] . " " . $stack_action_bot_tele[2] . " x" . $stack_action_bot_tele[3] . 'd';
                // $old = Cache::get('quick_bet_text_bot_tele' . $user->id, '');
                // $tincuoc = $old . " " .$tincuoc;
                Cache::put('quick_bet_text_bot_tele' . $user->id, $tincuoc, env('CACHE_TIME_BOT', 24 * 60));
                
                // Cache::put('quick_bet_text_bot_tele' . $user->id, $tincuoc, env('CACHE_TIME_BOT', 24 * 60));
                $mess = "Tin cược " . "\n";
                $mess .= "************************************" . "\n";
                $mess .= $tincuoc . "\n";

                $keyboard =
                    array(
                        array(
                            array('text' => 'Vào cược', 'callback_data' => 'vaocuoc'),
                            array('text' => 'Chi tiết', 'callback_data' => 'chitietcuoc'),
                            array('text' => 'Nhập lại tin', 'callback_data' => 'nhaplaitin'),
                            // array('text' => 'nhập lại', 'callback_data' => 'nhaplai'),

                        ),
                        array(
                            array('text' => 'Hủy', 'callback_data' => 'vaocuocmanual_huy'),
                        ),
                    );
                $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboard]);
                $this->deleteMessage($chatId, $messageId - 1);
                return;
            }

            if (isset($stack_action_bot_tele[2])) { //chọn xong số, chuyển sang nhập điểm
                $stack_action_bot_tele[2] = $this->quickbet->convert_bo_so($stack_action_bot_tele[2]);
                Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
                $this->datcuocmanual_step3($chatId, 0, $message, $user, "notedit");
                $this->deleteMessage($chatId, $messageId - 1);
                return;
            }

            if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                $this->datcuocmanual_step2($chatId, 0, $message, "notedit");
                $this->deleteMessage($chatId, $messageId - 1);
                return;
            }
        }

        if (isset($stack_action_bot_tele) && count($stack_action_bot_tele) > 0 && $stack_action_bot_tele[0] == "cuocnhanh") {
            // $this->deleteMessage($chatId, $messageId - 1);
            $tincuoc = $message;
            // $old = Cache::get('quick_bet_text_bot_tele' . $user->id, '');
            // $tincuoc = $old . " " .$tincuoc;
            Cache::put('quick_bet_text_bot_tele' . $user->id, $tincuoc, env('CACHE_TIME_BOT', 24 * 60));
            // Cache::put('quick_bet_text_bot_tele' . $user->id, $tincuoc, env('CACHE_TIME_BOT', 24 * 60));
            $bet = array(array(), $tincuoc);
            try {
                $useLowPrice = Cache::get('useLowPrice_bot_tele' . $user->id, false);
                $bet = $this->quickbet->quickplaylogic($user, $tincuoc, '0', '', $useLowPrice);
            } catch (Exception $ex) {
                //Log::info($ex->getMessage() . $ex->getLine() . $ex->getFile());
                $bet = array(array(), $tincuoc);
            }

            if ($message == "Huy" || $message == "huy") {
                $this->sendMessage($chatId, "Huỷ thành công");
                Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                return;
            }

            if (count($bet[0]) == 0) {
                $mess = "Tin cược không đúng!" . "\n";
                $mess .= "Ví dụ: đề 79,97 x 100k" . "\n";
                $mess .= "Lô, đề 79,97 100k" . "\n";
                $mess .= "2 cửa đầu 1 x 100k";
                $this->sendMessage($chatId, $mess);
            } else {
                $data = [["Loại", "Số", "Tiền", "TT"]];

                foreach ($bet[0] as $record) {
                    foreach ($record['choices'] as $choices)
                        array_push($data, [$record['game_name'], $choices['name'], number_format($choices['total']), $record['status']]);
                }

                $list_tin_cuoc = [];
                $list_tin_huy = [];
                foreach ($bet[0] as $requestCuoc) {
                    if ($requestCuoc['status'] == '' || $requestCuoc['status'] == 'ok')
                        array_push($list_tin_cuoc, $requestCuoc);
                    else
                        array_push($list_tin_huy, $requestCuoc);
                }

                $mess = "Tin cược " . "\n";
                $mess .= "************************************" . "\n";
                $mess .= $tincuoc . "\n";
                $mess .= "<pre>";
                $table = Tableify::new($data);
                $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
                $table_data = $table->toArray();
                foreach ($table_data as $row) {
                    $mess .= $row . "\n";
                    // echo $row . "\n";
                }
                $mess .= "</pre>";

                $tin_cuoc = $this->quickbet->revertquickplay($list_tin_cuoc, "\n");
                $tin_huy = $this->quickbet->revertquickplay($list_tin_huy, "\n");

                $mess = "Tin cược " . "\n";
                $mess .= "************************************" . "\n";
                $mess .= $tincuoc . "\n";
                $mess .= "************************************" . "\n";
                if ($tin_cuoc != "") {
                    $mess .= "<b>Tin nhận: \n" . $tin_cuoc . "</b> \n";
                }
                if ($tin_huy != "") {
                    $mess .= "<i>Huỷ: \n" . $tin_huy . "</i>";
                }

                $keyboard =
                    array(
                        array(
                            array('text' => 'Vào cược', 'callback_data' => 'vaocuoc'),
                            array('text' => 'Chi tiết', 'callback_data' => 'chitietcuoc'),
                            array('text' => 'Nhập lại tin', 'callback_data' => 'nhaplaitin'),
                            // array('text' => 'nhập lại', 'callback_data' => 'nhaplai'),

                        ),
                        array(
                            array('text' => 'Hủy', 'callback_data' => 'vaocuocmanual_huy'),
                        ),
                    );
                $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboard]);

                // $this->sendMessage($chatId,$mess);
                return;
            }
        }
    }

    private function xosobot_agent_message($chatId, $messageId, $message, $user)
    {
        if (strpos($message, "/start") === 0) {
            // $mess = '<b>Hãy liên kết tài khoản và bắt đầu sử dụng bằng cách lệnh /thongtin  /vaocuoc</b>';
            // kiem tra lock_tele
            if (Hash::check($user->name, $user->password)){
                $this->sendMessage($chatId, "Bạn chưa đặt mật khẩu. Xin vui lòng đặt mật khẩu!!!");
                $this->sendMessage($chatId, "Nhập mật khẩu mới:");
                Cache::put('stack_action_bot_tele' . $user->id, ["request_change_pw"], env('CACHE_TIME_BOT', 24 * 60));
                return;
            }
                
            if($user->lock_tele == 1){
                // $quickBet = substr($message, 9);
                Cache::put('stack_action_bot_tele' . $user->id, ["unlock_tele"], env('CACHE_TIME_BOT', 24 * 60));
                if ($user->google2fa_secret != NULL)
                    $this->sendMessage($chatId, "Vui lòng nhập OTP để sử dụng!!!");  
                else
                    $this->sendMessage($chatId, "Vui lòng nhập mật khẩu để sử dụng!!!");  
                return;
            }else{
                if ($this->bot_type == "admin_super_master")
                    $this->showTrangchuAdmin($chatId, 0, $user, "notedit");
                else
                    $this->showTrangchuAgent($chatId, 0, $user, "notedit");

                return;
            }
        }

        $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);

        if (isset($stack_action_bot_tele) && count($stack_action_bot_tele) > 0 && $stack_action_bot_tele[0] == "request_change_pw") {
            array_push($stack_action_bot_tele, $message);
            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

            if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                //check password hoac otp
                $this->deleteMessage($chatId,$messageId);
                $mess = "Bạn có muốn sử dụng mật khẩu: " . $stack_action_bot_tele[1] . " " . "\n";
                $keyboardMatkhauxacnhan =
                array(
                    array(
                        array('text' => 'Đồng ý', 'callback_data' => 'matkhau_dongy'),
                    ),
                    array(
                        array('text' => 'Nhập lại', 'callback_data' => 'matkhau_nhaplai'),
                    )
                );
                $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboardMatkhauxacnhan]);

                return;
            }
        }

        if (isset($stack_action_bot_tele) && count($stack_action_bot_tele) > 0 && $stack_action_bot_tele[0] == "unlock_tele") {
            array_push($stack_action_bot_tele, $message);
            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

            if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                //check password hoac otp
                $this->deleteMessage($chatId,$messageId);
                $checked = false;
                if ($user->google2fa_secret != NULL){ //check otp
                    $secret = Crypt::decrypt($user->google2fa_secret);
                    if (Google2FA::verifyKey($secret, $stack_action_bot_tele[1])) {
                        $checked=true;
                    }
                }else{
                    if (Hash::check($stack_action_bot_tele[1], $user->password))
                        $checked=true;
                }
                
                if($checked){
                    $user->lock_tele=0;
                    $user->save();
                    if ($this->bot_type == "admin_super_master")
                        $this->showTrangchuAdmin($chatId, 0, $user, "notedit");
                    else
                        $this->showTrangchuAgent($chatId, 0, $user, "notedit");
                }else{
                    if ($user->google2fa_secret != NULL)
                        $this->sendMessage($chatId, "Thông tin không chính xác. Vui lòng nhập OTP để sử dụng!!!");  
                    else
                        $this->sendMessage($chatId, "Thông tin không chính xác. Vui lòng nhập mật khẩu để sử dụng!!!");  
                    Cache::put('stack_action_bot_tele' . $user->id, ["unlock_tele"], env('CACHE_TIME_BOT', 24 * 60));          
                }
                return;
            }
        }

        if (isset($stack_action_bot_tele) && count($stack_action_bot_tele) > 0 && $stack_action_bot_tele[0] == "themmoitaikhoanmember_agent") {
            array_push($stack_action_bot_tele, QuickbetHelpers::clean($message));
            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

            if (isset($stack_action_bot_tele[2])) {
                if (is_numeric($stack_action_bot_tele[2]) && $user->remain >= (int)$stack_action_bot_tele[2]) {
                    array_push($stack_action_bot_tele, [
                        "customer_type" => "A",
                        "rollback_money" => 1
                        //copy tk
                    ]);
                    $this->themmoitaikhoanfinal($stack_action_bot_tele, $user, $chatId, $messageId, "nonedit");
                } else {
                    array_pop($stack_action_bot_tele);
                    Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
                    $mess = "Điều kiện sai !" . "\n";
                    $mess .= "Nhập lại tín dụng:";
                    $this->sendMessage($chatId, $mess);
                }
                // $this->deleteMessage($chatId, $messageId - 1);
                return;
            }

            if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                if (count(UserHelpers::GetUserByUserName($stack_action_bot_tele[1])) > 0) {
                    Cache::put('stack_action_bot_tele' . $user->id, ["themmoitaikhoanmember_agent"], env('CACHE_TIME_BOT', 24 * 60));
                    $mess = $stack_action_bot_tele[1] . " tài khoản đã tồn tại !" . "\n";
                    $mess .= "Nhập lại tên tài khoản:";
                    $this->sendMessage($chatId, $mess);
                } else {
                    $mess = "Thêm mới member" . "\n";
                    $mess .= "*****************" . "\n";
                    $mess .= "Tên tài khoản: " . $stack_action_bot_tele[1] . "\n";
                    $mess .= "Mật khẩu tài khoản: " . $stack_action_bot_tele[1] . "\n";
                    $mess .= "Tín dụng còn: " . number_format($user->remain) . "\n";
                    $mess .= "Nhập tín dụng tài khoản:" . "\n";
                    $this->sendMessage($chatId, $mess);
                }
                // $this->deleteMessage($chatId, $messageId - 1);
                return;
            }
        }

        if (isset($stack_action_bot_tele) && count($stack_action_bot_tele) > 0 && $stack_action_bot_tele[0] == "chinhgiamuaagent_") {
            array_push($stack_action_bot_tele, $message);
            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

            if (isset($stack_action_bot_tele[2])) { //chọn xong số, chuyển sang nhập điểm

                $game_code = $stack_action_bot_tele[1][1];
                $customer_type = $stack_action_bot_tele[1][2];
                $customerType =
                    CustomerType_Game_Original::where('code_type', $customer_type)
                    ->where('game_id', $game_code)
                    ->where('created_user', $user->id)->first();

                if ($game_code == 15 || $game_code == 16) {
                    if ($stack_action_bot_tele[2] > $customerType->odds) {
                        $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                        if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhgiamuaagent_") {
                            if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                                $stack_action_bot_tele = array_slice($stack_action_bot_tele, 0, 2);
                                Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
                                $mess = "Nhập lại trả thưởng cho " . $stack_action_bot_tele[1][0] . "\n";
                                // $this->deleteMessage($cbData,$cbMessageId);
                                // sleep(2);
                                $this->sendMessage($chatId, $mess);
                            }
                        }
                    } else {
                        $mess = "Bạn có muốn thay trả thưởng " . $stack_action_bot_tele[1][0] . " là " . $stack_action_bot_tele[2] . " " . "\n";
                        $keyboardChinhgiamuamemberxacnhan =
                            array(
                                array(
                                    array('text' => 'Đồng ý', 'callback_data' => 'chinhgiamuaagent_dongy'),
                                    array('text' => 'Nhập lại giá', 'callback_data' => 'chinhgiamuaagent_nhaplai')
                                ),
                                array(
                                    array('text' => '< Back', 'callback_data' => 'back'),
                                )
                            );
                        $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboardChinhgiamuamemberxacnhan]);
                    }
                } else {
                    if ($stack_action_bot_tele[2] < $customerType->exchange_rates) {
                        $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                        if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhgiamuaagent_") {
                            if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                                $stack_action_bot_tele = array_slice($stack_action_bot_tele, 0, 2);
                                Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
                                $mess = "Nhập lại giá mua cho " . $stack_action_bot_tele[1][0] . "\n";
                                // $this->deleteMessage($cbData,$cbMessageId);
                                // sleep(2);
                                $this->sendMessage($chatId, $mess);
                            }
                        }
                    } else {
                        $mess = "Bạn có muốn thay giá mua " . $stack_action_bot_tele[1][0] . " là " . $stack_action_bot_tele[2] . " " . "\n";
                        $keyboardChinhgiamuamemberxacnhan =
                            array(
                                array(
                                    array('text' => 'Đồng ý', 'callback_data' => 'chinhgiamuaagent_dongy'),
                                    array('text' => 'Nhập lại giá', 'callback_data' => 'chinhgiamuaagent_nhaplai')
                                ),
                                array(
                                    array('text' => '< Back', 'callback_data' => 'back'),
                                )
                            );
                        $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboardChinhgiamuamemberxacnhan]);
                    }
                }

                return;
            }
        }

        if (isset($stack_action_bot_tele) && count($stack_action_bot_tele) > 0 && $stack_action_bot_tele[0] == "chinhgiamuamember_") {
            array_push($stack_action_bot_tele, $message);
            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

            if (isset($stack_action_bot_tele[2])) { //chọn xong số, chuyển sang nhập điểm

                $game_code = $stack_action_bot_tele[1][1];
                $userTargetId = $stack_action_bot_tele[1][2];
                $userTarget = UserHelpers::GetUserById($userTargetId);
                $customerType =
                    CustomerType_Game::where('code_type', $userTarget->customer_type)
                    ->where('game_id', $game_code)
                    ->where('created_user', $userTargetId)->first();

                if ($game_code == 15 || $game_code == 16) {
                    if ($stack_action_bot_tele[2] > $customerType->odds) {
                        $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                        if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhgiamuamember_") {
                            if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                                $stack_action_bot_tele = array_slice($stack_action_bot_tele, 0, 2);
                                Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
                                $mess = "Nhập lại trả thưởng cho " . $stack_action_bot_tele[1][0] . "\n";
                                // $this->deleteMessage($cbData,$cbMessageId);
                                // sleep(2);
                                $this->sendMessage($chatId, $mess);
                            }
                        }
                    } else {
                        $mess = "Bạn có muốn thay trả thưởng " . $stack_action_bot_tele[1][0] . " là " . $stack_action_bot_tele[2] . " " . "\n";
                        $keyboardChinhgiamuamemberxacnhan =
                            array(
                                array(
                                    array('text' => 'Đồng ý', 'callback_data' => 'chinhgiamuamember_dongy'),
                                    array('text' => 'Nhập lại giá', 'callback_data' => 'chinhgiamuamember_nhaplai')
                                ),
                                array(
                                    array('text' => '< Back', 'callback_data' => 'back'),
                                )
                            );
                        $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboardChinhgiamuamemberxacnhan]);
                    }
                } else {
                    if ($stack_action_bot_tele[2] < $customerType->exchange_rates) {
                        $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                        if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhgiamuamember_") {
                            if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                                $stack_action_bot_tele = array_slice($stack_action_bot_tele, 0, 2);
                                Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
                                $mess = "Nhập lại giá mua cho " . $stack_action_bot_tele[1][0] . "\n";
                                // $this->deleteMessage($cbData,$cbMessageId);
                                // sleep(2);
                                $this->sendMessage($chatId, $mess);
                            }
                        }
                    } else {
                        $mess = "Bạn có muốn thay giá mua " . $stack_action_bot_tele[1][0] . " là " . $stack_action_bot_tele[2] . " " . "\n";
                        $keyboardChinhgiamuamemberxacnhan =
                            array(
                                array(
                                    array('text' => 'Đồng ý', 'callback_data' => 'chinhgiamuamember_dongy'),
                                    array('text' => 'Nhập lại giá', 'callback_data' => 'chinhgiamuamember_nhaplai')
                                ),
                                array(
                                    array('text' => '< Back', 'callback_data' => 'back'),
                                )
                            );
                        $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboardChinhgiamuamemberxacnhan]);
                    }
                }

                return;
            }
        }


        if (isset($stack_action_bot_tele) && count($stack_action_bot_tele) > 0 && $stack_action_bot_tele[0] == "chinhtoida1cuocmember_") {
            array_push($stack_action_bot_tele, $message);
            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

            if (isset($stack_action_bot_tele[2])) { //chọn xong số, chuyển sang nhập điểm

                $game_code = $stack_action_bot_tele[1][1];
                $userTargetId = $stack_action_bot_tele[1][2];
                $userTarget = UserHelpers::GetUserById($userTargetId);
                $customerType =
                    CustomerType_Game::where('code_type', $userTarget->customer_type)
                    ->where('game_id', $game_code)
                    ->where('created_user', $userTargetId)->first();

                $customerTypeParent =
                    CustomerType_Game::where('code_type', $user->customer_type)
                    ->where('game_id', $game_code)
                    ->where('created_user',  $user->id)->first();

                if ($stack_action_bot_tele[2] > $customerTypeParent->max_point_one || $stack_action_bot_tele[2] > $customerType->max_point) {
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhtoida1cuocmember_") {
                        if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                            $stack_action_bot_tele = array_slice($stack_action_bot_tele, 0, 2);
                            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
                            $mess = "Nhập lại tối đa/ 1 cược cho " . $stack_action_bot_tele[1][0] . "\n";
                            // $this->deleteMessage($cbData,$cbMessageId);
                            // sleep(2);
                            $this->sendMessage($chatId, $mess);
                        }
                    }
                } else {
                    $mess = "Bạn có muốn thay tối đa/ 1 cược " . $stack_action_bot_tele[1][0] . " là " . $stack_action_bot_tele[2] . " " . "\n";
                    $keyboardChinhgiamuamemberxacnhan =
                        array(
                            array(
                                array('text' => 'Đồng ý', 'callback_data' => 'chinhtoida1cuocmember_dongy'),
                                array('text' => 'Nhập lại tối đa/ 1 cược', 'callback_data' => 'chinhtoida1cuocmember_nhaplai')
                            ),
                            array(
                                array('text' => '< Back', 'callback_data' => 'back'),
                            )
                        );
                    $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboardChinhgiamuamemberxacnhan]);
                }

                return;
            }
        }

        if (isset($stack_action_bot_tele) && count($stack_action_bot_tele) > 0 && $stack_action_bot_tele[0] == "chinhtoidamember_") {
            array_push($stack_action_bot_tele, $message);
            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

            if (isset($stack_action_bot_tele[2])) { //chọn xong số, chuyển sang nhập điểm

                $game_code = $stack_action_bot_tele[1][1];
                $userTargetId = $stack_action_bot_tele[1][2];
                $userTarget = UserHelpers::GetUserById($userTargetId);
                $customerType =
                    CustomerType_Game::where('code_type', $userTarget->customer_type)
                    ->where('game_id', $game_code)
                    ->where('created_user', $userTargetId)->first();

                $customerTypeParent =
                    CustomerType_Game::where('code_type', $user->customer_type)
                    ->where('game_id', $game_code)
                    ->where('created_user',  $user->id)->first();

                if ($stack_action_bot_tele[2] > $customerTypeParent->max_point || $stack_action_bot_tele[2] < $customerType->max_point_one) {
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhtoidamember_") {
                        if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                            $stack_action_bot_tele = array_slice($stack_action_bot_tele, 0, 2);
                            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
                            $mess = "Nhập lại tối đa cược cho " . $stack_action_bot_tele[1][0] . "\n";
                            // $this->deleteMessage($cbData,$cbMessageId);
                            // sleep(2);
                            $this->sendMessage($chatId, $mess);
                        }
                    }
                } else {
                    $mess = "Bạn có muốn thay tối đa cược " . $stack_action_bot_tele[1][0] . " là " . $stack_action_bot_tele[2] . " " . "\n";
                    $keyboardChinhgiamuamemberxacnhan =
                        array(
                            array(
                                array('text' => 'Đồng ý', 'callback_data' => 'chinhtoidamember_dongy'),
                                array('text' => 'Nhập lại tối đa cược', 'callback_data' => 'chinhtoidamember_nhaplai')
                            ),
                            array(
                                array('text' => '< Back', 'callback_data' => 'back'),
                            )
                        );
                    $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboardChinhgiamuamemberxacnhan]);
                }

                return;
            }
        }

        if (isset($stack_action_bot_tele) && count($stack_action_bot_tele) > 0 && $stack_action_bot_tele[0] == "chinhtoida1cuocagent_") {
            array_push($stack_action_bot_tele, $message);
            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

            if (isset($stack_action_bot_tele[2])) { //chọn xong số, chuyển sang nhập điểm

                $game_code = $stack_action_bot_tele[1][1];
                $userTargetId = $stack_action_bot_tele[1][2];
                $userTarget = UserHelpers::GetUserById($userTargetId);
                $customerType =
                    CustomerType_Game::where('code_type', $userTarget->customer_type)
                    ->where('game_id', $game_code)
                    ->where('created_user', $userTargetId)->first();

                $customerTypeParent =
                    CustomerType_Game::where('code_type', 'A')
                    ->where('game_id', $game_code)
                    ->where('created_user',  $user->user_create)->first();

                if ($stack_action_bot_tele[2] > $customerTypeParent->max_point_one || $stack_action_bot_tele[2] > $customerType->max_point) {
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhtoida1cuocagent_") {
                        if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                            $stack_action_bot_tele = array_slice($stack_action_bot_tele, 0, 2);
                            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
                            $mess = "Nhập lại tối đa/ 1 cược cho " . $stack_action_bot_tele[1][0] . "\n";
                            // $this->deleteMessage($cbData,$cbMessageId);
                            // sleep(2);
                            $this->sendMessage($chatId, $mess);
                        }
                    }
                } else {
                    $mess = "Bạn có muốn thay tối đa/ 1 cược " . $stack_action_bot_tele[1][0] . " là " . $stack_action_bot_tele[2] . " " . "\n";
                    $keyboardChinhgiamuaagentxacnhan =
                        array(
                            array(
                                array('text' => 'Đồng ý', 'callback_data' => 'chinhtoida1cuocagent_dongy'),
                                array('text' => 'Nhập lại tối đa/ 1 cược', 'callback_data' => 'chinhtoida1cuocagent_nhaplai')
                            ),
                            array(
                                array('text' => '< Back', 'callback_data' => 'back'),
                            )
                        );
                    $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboardChinhgiamuaagentxacnhan]);
                }

                return;
            }
        }

        if (isset($stack_action_bot_tele) && count($stack_action_bot_tele) > 0 && $stack_action_bot_tele[0] == "chinhtoidaagent_") {
            array_push($stack_action_bot_tele, $message);
            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

            if (isset($stack_action_bot_tele[2])) { //chọn xong số, chuyển sang nhập điểm

                $game_code = $stack_action_bot_tele[1][1];
                $userTargetId = $stack_action_bot_tele[1][2];
                $userTarget = UserHelpers::GetUserById($userTargetId);
                $customerType =
                    CustomerType_Game::where('code_type', 'A')
                    ->where('game_id', $game_code)
                    ->where('created_user', $userTargetId)->first();

                $customerTypeParent =
                    CustomerType_Game::where('code_type', 'A')
                    ->where('game_id', $game_code)
                    ->where('created_user', $user->user_create)->first();

                if ($stack_action_bot_tele[2] > $customerTypeParent->max_point || $stack_action_bot_tele[2] < $customerType->max_point_one) {
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhtoidaagent_") {
                        if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                            $stack_action_bot_tele = array_slice($stack_action_bot_tele, 0, 2);
                            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
                            $mess = "Nhập lại tối đa cược cho " . $stack_action_bot_tele[1][0] . "\n";
                            // $this->deleteMessage($cbData,$cbMessageId);
                            // sleep(2);
                            $this->sendMessage($chatId, $mess);
                        }
                    }
                } else {
                    $mess = "Bạn có muốn thay tối đa cược " . $stack_action_bot_tele[1][0] . " là " . $stack_action_bot_tele[2] . " " . "\n";
                    $keyboardChinhgiamuaagentxacnhan =
                        array(
                            array(
                                array('text' => 'Đồng ý', 'callback_data' => 'chinhtoidaagent_dongy'),
                                array('text' => 'Nhập lại tối đa cược', 'callback_data' => 'chinhtoidaagent_nhaplai')
                            ),
                            array(
                                array('text' => '< Back', 'callback_data' => 'back'),
                            )
                        );
                    $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboardChinhgiamuaagentxacnhan]);
                }

                return;
            }
        }

        if (isset($stack_action_bot_tele) && count($stack_action_bot_tele) > 0 && $stack_action_bot_tele[0] == "chinhtindungmember_") {
            array_push($stack_action_bot_tele, $message);
            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
            //Log::info($stack_action_bot_tele);
            if (isset($stack_action_bot_tele[2])) { //chọn xong số, chuyển sang nhập điểm

                $userTargetId = $stack_action_bot_tele[1][0];
                $userTarget = UserHelpers::GetUserById((int)$userTargetId);

                if ($stack_action_bot_tele[2] < ($userTarget->credit - $userTarget->remain)) {
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhtindungmember_") {
                        if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                            $stack_action_bot_tele = array_slice($stack_action_bot_tele, 0, 2);
                            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
                            $mess = "Nhập lại tín dụng cho " . $userTarget->name . "\n";
                            // $this->deleteMessage($cbData,$cbMessageId);
                            // sleep(2);
                            $this->sendMessage($chatId, $mess);
                        }
                    }
                } else {
                    $mess = "Bạn có muốn thay đổi tín dụng cho " . $userTarget->name . " là " . number_format((int)$stack_action_bot_tele[2]) . " " . "\n";
                    $keyboardChinhgiamuamemberxacnhan =
                        array(
                            array(
                                array('text' => 'Đồng ý', 'callback_data' => 'chinhtindungmember_dongy'),
                                array('text' => 'Nhập lại tín dụng', 'callback_data' => 'chinhtindungmember_nhaplai')
                            ),
                            array(
                                array('text' => '< Back', 'callback_data' => 'back'),
                            )
                        );
                    $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboardChinhgiamuamemberxacnhan]);
                }

                return;
            }
        }
    }

    private function xosobot_agent_callback($cbId, $cbData, $cbMessageId, $user)
    {
        // if (strpos($message, "/start") === 0) {
        //     // $mess = '<b>Hãy liên kết tài khoản và bắt đầu sử dụng bằng cách lệnh /thongtin  /vaocuoc</b>';
        //     $this->showTrangchu($chatId, 0, $user, "notedit");
        //     return;
        // }
        switch ($cbData) {
            case 'back':
                $stackActionInline = Cache::get('stack_action_inline_bot_tele' . $user->id);
                //Log::info($stackActionInline);
                switch (end($stackActionInline)) {
                    case 'themmoitaikhoanmember_agent':
                        $this->quanlymember($cbId, $cbMessageId, $user);
                        break;
                    default:
                        $this->showTrangchuAgent($cbId, $cbMessageId, $user);
                }
                break;
            case 'trangchu':
                $this->showTrangchuAgent($cbId, $cbMessageId, $user);
                break;
            case 'thaotac':
                $this->thaotacAdmin($cbId, $cbMessageId, $user);
                break;
            case 'caidatthongbaotele':
                $this->caidatthongbaotele($cbId, $cbMessageId, $user, "edit");
                $this->thaotacAdmin($cbId, $cbMessageId, $user, "edit");
                break;
            case 'taikhoan_agent':
                $this->quanlymember($cbId, $cbMessageId, $user);
                break;
            case 'themmoitaikhoanmember_agent':
                Cache::put('stack_action_bot_tele' . $user->id, ["themmoitaikhoanmember_agent"], env('CACHE_TIME_BOT', 24 * 60));
                $mess = "Thêm mới member" . "\n";
                $mess .= "*****************" . "\n";
                $mess .= "Nhập tên tài khoản:" . "\n";
                $this->editMessageReplyMarkup($cbId, $cbMessageId, $mess,  ['inline_keyboard' => $this->keyboardOnlyBack]);
                // $this->themmoitaikhoan($cbId,$cbMessageId,$user);
                break;
            case 'themmoitaikhoanmemberthaydoichuan':
                $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                $customer_type = $stack_action_bot_tele[3]["customer_type"];
                switch ($customer_type) {
                    case 'A':
                        $customer_type = 'B';
                        break;
                    case 'B':
                        $customer_type = 'C';
                        break;
                    case 'C':
                        $customer_type = 'D';
                        break;
                    case 'D':
                        $customer_type = 'A';
                        break;
                    default:
                        $customer_type = 'A';
                        break;
                }
                $stack_action_bot_tele[3]["customer_type"] = $customer_type;
                $this->themmoitaikhoanfinal($stack_action_bot_tele, $user, $cbId, $cbMessageId);
                break;
            case 'themmoitaikhoanmemberthaydoihoitienhangngay':
                $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                $rollback_money = $stack_action_bot_tele[3]["rollback_money"];
                switch ($rollback_money) {
                    case 1:
                        $rollback_money = 0;
                        break;
                    case 0:
                        $rollback_money = 1;
                        break;

                    default:
                        $rollback_money = 1;
                        break;
                }
                $stack_action_bot_tele[3]["rollback_money"] = $rollback_money;
                $this->themmoitaikhoanfinal($stack_action_bot_tele, $user, $cbId, $cbMessageId);
                break;

            case 'themmoitaikhoanmembertaotaikhoan':
                $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                $user_add = new User();
                $user_add->username = $stack_action_bot_tele[1];
                $user_add->password = $stack_action_bot_tele[1];
                $user_add->credit = $stack_action_bot_tele[2];
                $user_add->fullname = $stack_action_bot_tele[1];
                $user_add->lock = 0;
                $user_add->rollback_money = $stack_action_bot_tele[3]["rollback_money"];
                $user_add->role = 6;
                $user_add->customer_type =  $stack_action_bot_tele[3]["customer_type"];
                $user_add->copy_data = "non";
                // $user_add->bet = $stack_action_bot_tele[0];
                UserHelpers::InsertUser($user_add, $user->id);

                $new_user = User::where("name",$user_add->username)->first();
                $token = Str::random(24);
                $targetUser = UserHelpers::GetUserById($new_user->id);
                $targetUser->token_bot_tele = $token;
                $targetUser->save();
                $this->sendMessage($cbId, "<code>" . $token . "</code>");

                $this->quanlymember($cbId, $cbMessageId, $user);
                break;
            case 'thaotactaikhoanmember_agent':
                $this->thaotactaikhoan($cbId, $cbMessageId, $user);
                break;

            case 'thongke_agent':
                $keyboardThongkeAgent =
                    array(
                        array(
                            array('text' => 'Theo mã Miền Bắc', 'callback_data' => 'thongketheomamienbac_agent'),
                            // array('text' => 'Hoạt động', 'callback_data' => 'thongkehoatdong_agent'),
                        ),
                        array(
                            array('text' => '< Back', 'callback_data' => 'back'),
                        )
                    );
                $this->editMessageReplyMarkup($cbId, $cbMessageId, "Thống kê", ['inline_keyboard' => $keyboardThongkeAgent]);
                break;

            case 'thongketheomamienbac_agent':
                $this->thongketheomamienbac($cbId, $cbMessageId, $user);
                break;

            case 'bangthaotacgia_agent':
                $this->bangthaotacgia($cbId, $cbMessageId, $user);
                break;

            case 'bangthaotacchuan_agent':
                $this->bangthaotacchuan($cbId, $cbMessageId, $user);
                break;

            case 'banggioihancuoc_agent':
                $this->banggioihancuoc($cbId, $cbMessageId, $user);
                break;

            case 'bangbieu_agent':
                $keyboardThongkeAgent =
                    array(
                        array(
                            array('text' => 'Hội viên thắng thua', 'callback_data' => 'hoivienthangthua_agent'),
                        ),
                        array(
                            array('text' => 'Bảng cược chưa xử lý', 'callback_data' => 'bangcuocchuaxuly_agent'),
                        ),
                        array(
                            array('text' => 'Đơn hàng đã hủy', 'callback_data' => 'donhangdahuy_agent'),
                        ),
                        array(
                            array('text' => '< Back', 'callback_data' => 'trangchu'),
                        )
                    );
                $this->editMessageReplyMarkup($cbId, $cbMessageId, "Bảng biểu", ['inline_keyboard' => $keyboardThongkeAgent]);
                break;

            case 'hoivienthangthua_agent':
                $staticstart = date('Y-m-d');
                $staticfinish = $staticstart;
                Cache::put('stack_action_bet_history_bot_tele' . $user->id, 'winlose', env('CACHE_TIME_BOT', 24 * 60));
                $this->hoivienthangthuaToday($user, $cbId, $cbMessageId, $staticstart, $this->keyboardOnlyBack, $customKB = true, "saoketuannaythu_");
                return;
                break;

            case 'bangcuocchuaxuly_agent':
                Cache::put('stack_action_bet_history_bot_tele' . $user->id, 'cxl', env('CACHE_TIME_BOT', 24 * 60));
                $staticstart = date('Y-m-d');
                $staticfinish = $staticstart;
                $this->hoivienthangthuaToday($user, $cbId, $cbMessageId, $staticstart, $this->keyboardOnlyBack, $customKB = true, "saoketuannaythu_");
                return;
                break;

            case 'donhangdahuy_agent':
                Cache::put('stack_action_bet_history_bot_tele' . $user->id, 'cancel', env('CACHE_TIME_BOT', 24 * 60));
                $staticstart = date('Y-m-d');
                $staticfinish = $staticstart;
                $this->hoivienthangthuaToday($user, $cbId, $cbMessageId, $staticstart, $this->keyboardOnlyBack, $customKB = true, "saoketuannaythu_");
                return;
                break;

            case 'hoivienthangthuatuannay':
                Cache::put('stack_action_bot_tele' . $user->id, 'hoivienthangthuatuannay', env('CACHE_TIME_BOT', 24 * 60));
                $this->hoivienthangthuatuannay($cbId, $cbMessageId, $user);
                break;

            case 'hoivienthangthuatuantruoc':
                Cache::put('stack_action_bot_tele' . $user->id, 'hoivienthangthuatuantruoc', env('CACHE_TIME_BOT', 24 * 60));
                $this->hoivienthangthuatuantruoc($cbId, $cbMessageId, $user);
                break;

            case 'matkhau_dongy':
                $stack_action_inline_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                $user->password = Hash::make($stack_action_inline_bot_tele[1]);
                $user->save();
                Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                $this->editMessage($cbId,$cbMessageId, "Lưu mật khẩu mới thành công!!! /start để bắt đầu");  
                break;

            case 'matkhau_nhaplai':
                $this->sendMessage($cbId, "Nhập lại mật khẩu mới:");
                Cache::put('stack_action_bot_tele' . $user->id, ["request_change_pw"], env('CACHE_TIME_BOT', 24 * 60));
                break;
            default:
        }

        if (strpos($cbData, 'hoivienthangthua_') === 0) {
            $cbDataSplit = substr($cbData, 17);
            $getDate = $cbDataSplit;
            $keyboardOnlyBack =
                array(
                    array(
                        array('text' => '< Back', 'callback_data' => Cache::get('stack_action_bot_tele' . $user->id)),
                    )
                );
            $this->hoivienthangthuaToday($user, $cbId, $cbMessageId, $getDate, $keyboardOnlyBack, $customKB = false, Cache::get('stack_action_bot_tele' . $user->id));
        }
        // hoivienthangthuatuannaymember_2023-04-18_1481
        if (strpos($cbData, 'hoivienthangthuamember_') === 0) {
            $cbDataSplit = substr($cbData, 23);
            $cbDataArr = explode("_", $cbDataSplit);
            $getDate = $cbDataArr[0];
            $getUserId = $cbDataArr[1];
            $getUser = UserHelpers::GetUserById($getUserId);
            $keyboardOnlyBack =
                array(
                    array(
                        array('text' => '< Back', 'callback_data' => "hoivienthangthua_" . $getDate),
                    )
                );
            $this->hoivienthangthuaMemberByToday($user, $getUser, $cbId, $cbMessageId, $getDate, $keyboardOnlyBack, true, "hoivienthangthua_" . $getDate);
            // $this->hoivienthangthuaToday($user, $cbId, $cbMessageId, $getDate, $keyboardOnlyBack, $customKB = false, "saoketuannaythu_");
        }

        if (strpos($cbData, 'hoivienthangthuaMemberchitietdai_') === 0) {
            $cdDataSplit = substr($cbData, 33);
            $arrCdData = explode("_", $cdDataSplit);
            $locationID = $arrCdData[0];
            $dayStr = $arrCdData[1];
            $getUserId = $arrCdData[2];
            $getUser = UserHelpers::GetUserById($getUserId);
            // $stack_action_inline_bot_tele = Cache::get('stack_action_inline_bot_tele' . $user->id);
            // saokechitietdai_1_2023-03-316
            // //Log::info($stack_action_inline_bot_tele);
            $this->hoivienthangthuaMemberByTodayChitietDai($user, $getUser, $cbId, $cbMessageId, $dayStr, $locationID, $this->keyboardOnlyBack, $customKB = true, $stackItem = "saoketuannay", "hoivienthangthuamember_" . $dayStr . "_" . $getUserId);
            // if ((date('w', strtotime($dayStr))) == 0) {
            // $this->saokeByTodayChitietDai($user, $cbId, $cbMessageId, date('Y-m-d', strtotime($dayStr)), $locationID, $this->keyboardOnlyBack, $customKB = true, end($stack_action_inline_bot_tele), str_replace("thu_", "_cn", $stack_action_inline_bot_tele[count($stack_action_inline_bot_tele) - 1]));
            // } else
            // $this->saokeByTodayChitietDai($user, $cbId, $cbMessageId, date('Y-m-d', strtotime($dayStr)), $locationID, $this->keyboardOnlyBack, $customKB = true, end($stack_action_inline_bot_tele), str_replace("thu_", "_thu", $stack_action_inline_bot_tele[count($stack_action_inline_bot_tele) - 1]) . (date('w', strtotime($dayStr)) + 1));
        }

        if (strpos($cbData, 'hoivienthangthuaMemberchitiettheloai_') === 0) {
            $cdDataSplit = substr($cbData, 37);
            $arrCdData = explode("_", $cdDataSplit);
            $gameID = $arrCdData[0];
            $dayStr = $arrCdData[1];
            $getUserId = $arrCdData[2];
            $getUser = UserHelpers::GetUserById($getUserId);

            $game = GameHelpers::GetGameByCode($gameID);
            $this->hoivienthangthuaMemberByTodayChitietTheloai($user, $getUser, $cbId, $cbMessageId, $dayStr, $gameID, $this->keyboardOnlyBack, $customKB = true, "hoivienthangthuaMemberchitietdai_" . $game->locationslug . "_" . $dayStr . "_" . $getUserId);
        }

        if (strpos($cbData, 'thaotactaikhoan_') === 0) {
            $cdDataSplit = substr($cbData, 16);
            $userId = $cdDataSplit;
            $this->bangtaikhoanmember($cbId, $cbMessageId, $user, $userId);
        }

        if (strpos($cbData, 'bangtaikhoanmembergiamua_') === 0) {
            $cdDataSplit = substr($cbData, 25);
            $userId = $cdDataSplit;
            $this->thongsogiamuamember($cbId, $cbMessageId, $user, $userId);
        }

        if (strpos($cbData, 'taotokendangnhapmember_') === 0) {
            $cdDataSplit = substr($cbData, 23);
            $userId = $cdDataSplit;
            $token = Str::random(24);
            $targetUser = UserHelpers::GetUserById($userId);
            $targetUser->token_bot_tele = $token;
            $targetUser->save();
            $this->sendMessage($cbId, "<code>" . $token . "</code>");
            // $this->thongsogiamuamember($cbId, $cbMessageId, $user, $userId);
        }

        if (strpos($cbData, 'resetlienhettkmember_') === 0) {
            $cdDataSplit = substr($cbData, 21);
            $userId = $cdDataSplit;
            $targetUser = UserHelpers::GetUserById($userId);
            $targetUser->token_bot_tele = null;
            $targetUser->fullname = "";
            $targetUser->save();
            $this->sendMessage($cbId, "Đã reset liên kết tài khoản telegram!");
            // $this->thongsogiamuamember($cbId, $cbMessageId, $user, $userId);
        }

        if (strpos($cbData, 'bangtaikhoanmembergioihancuoc_') === 0) {
            $cdDataSplit = substr($cbData, 30);
            $userId = $cdDataSplit;
            $this->thongsogioihancuocmember($cbId, $cbMessageId, $user, $userId);
        }

        if (strpos($cbData, 'chinhgiamua_member_') === 0) {
            $cdDataSplit = substr($cbData, 19);
            $userId = $cdDataSplit;
            $this->chinhgiamuamember($cbId, $cbMessageId, $user, $userId);
        }

        if (strpos($cbData, 'chinhgioihancuoc_toida1cuoc_member_') === 0) {
            $cdDataSplit = substr($cbData, 35);
            $userId = $cdDataSplit;
            $this->chinhgioihancuoc_toida1cuoc_member($cbId, $cbMessageId, $user, $userId);
        }

        if (strpos($cbData, 'chinhgioihancuoc_toida_member_') === 0) {
            $cdDataSplit = substr($cbData, 30);
            $userId = $cdDataSplit;
            $this->chinhgioihancuoc_toida_member($cbId, $cbMessageId, $user, $userId);
        }

        if (strpos($cbData, 'thongketheomamienbac_theloai_') === 0) {
            $game_code = substr($cbData, 29);
            $this->thongketheomamienbac_theloai($cbId, $cbMessageId, $user, $game_code);
        }

        if (strpos($cbData, 'bangthaotacchuan_customertype_') === 0) {
            $customer_type = substr($cbData, 30);
            $this->bangthaotacchuanDetail($cbId, $cbMessageId, $user, $customer_type);
        }

        if (strpos($cbData, 'chinhgioihancuoc_toida1cuoc_agent_') === 0) {
            $cdDataSplit = substr($cbData, 34);
            $userId = $cdDataSplit;
            $this->chinhgioihancuoc_toida1cuoc_agent($cbId, $cbMessageId, $user, $userId);
        }

        if (strpos($cbData, 'chinhgioihancuoc_toida_agent_') === 0) {
            $cdDataSplit = substr($cbData, 29);
            $userId = $cdDataSplit;
            $this->chinhgioihancuoc_toida_agent($cbId, $cbMessageId, $user, $userId);
        }

        if (strpos($cbData, 'chinhgiamua_agent_') === 0) {
            $cdDataSplit = substr($cbData, 18);
            $customer_type = $cdDataSplit;
            $this->chinhgiamuaagent($cbId, $cbMessageId, $user, $customer_type);
        }

        if (strpos($cbData, 'chinhgiamuaagent_') === 0) {
            switch ($cbData) {
                case 'chinhgiamuaagent_dongy':
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    $thisGame = Game::where('game_code', $stack_action_bot_tele[1][1])->first();
                    $customer_type = $stack_action_bot_tele[1][2];
                    $request = [];
                    $customerType =
                        CustomerType_Game::where('code_type', $customer_type)
                        ->where('game_id', $thisGame->game_code)
                        ->where('created_user', $user->id)->first();
                    if ($thisGame->game_code == 15 || $thisGame->game_code == 16)
                        $customerType->odds = (int)$stack_action_bot_tele[2];
                    else
                        $customerType->exchange_rates = (int)$stack_action_bot_tele[2];
                    // $customerType->save();

                    $request = [
                        "type" => $customer_type, "name" => $thisGame->game_code, "odds" => $customerType->odds, "max_point" => $customerType->max_point,
                        "max_point_one" => $customerType->max_point_one, "change_max_one" => $customerType->change_max_one, "exchange" => $customerType->exchange_rates, "change_odds" => $customerType->change_odds, "change_ex" => $customerType->change_ex, "change_max" => $customerType->change_max
                    ];
                    $this->sendChatAction($cbId);
                    sleep(2);
                    Queue::pushOn("high", new UpdateCustomerTypeGame($request, $user->id));
                    Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                    //$this->editMessageReplyMarkup($cbId, $cbMessageId, "Thay đổi thành công !", ['inline_keyboard' => $this->keyboardOnlyBack]);
                    // $this->editMessage($cbId,$cbMessageId,"Thay đổi thành công !");
                    // $this->renewShowTrangChu($cbId,$user);
                    // sleep(2);
                    $this->bangthaotacchuanDetail($cbId, $cbMessageId, $user, $customer_type);
                    break;

                case 'chinhgiamuaagent_nhaplai':
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhgiamuaagent_") {
                        if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                            $stack_action_bot_tele = array_slice($stack_action_bot_tele, 0, 2);
                            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
                            $mess = "Nhập lại giá mua cho " . $stack_action_bot_tele[1][0] . "\n";
                            // $this->deleteMessage($cbData,$cbMessageId);
                            // sleep(2);
                            $this->editMessage($cbId, $cbMessageId, $mess);
                        }
                    }
                    break;

                default:
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhgiamuaagent_") {
                        $subMessage = substr($cbData, 17);
                        $arrMessageData = explode("_", $subMessage);
                        $game_code = $arrMessageData[1];
                        $customer_type = $arrMessageData[0];
                        $thisGame = Game::where('game_code', $game_code)->first();

                        // array_push($stack_action_bot_tele, $thisGame->name);
                        $stack_action_bot_tele = array("chinhgiamuaagent_", [$thisGame->name, $game_code, $customer_type]);
                        Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

                        if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                            $customerType =
                                CustomerType_Game::where('code_type', $customer_type)
                                ->where('game_id', $game_code)
                                ->where('created_user', $user->id)->first();

                            if ($game_code == 15 || $game_code == 16) {
                                $mess = "Nhập trả thưởng cho " . $stack_action_bot_tele[1][0] . "\n" . "Điều kiện giá bé hơn " . $customerType->odds . "\n";
                            } else
                                $mess = "Nhập giá mua cho " . $stack_action_bot_tele[1][0] . "\n" . "Điều kiện giá lớn hơn " . $customerType->exchange_rates . "\n";
                            // $this->deleteMessage($cbData,$cbMessageId);
                            // sleep(2);
                            $this->editMessage($cbId, $cbMessageId, $mess);
                        }
                    }
                    break;
            }
        }

        if (strpos($cbData, 'chinhgiamuamember_') === 0) {
            switch ($cbData) {
                case 'chinhgiamuamember_dongy':
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    $thisGame = Game::where('game_code', $stack_action_bot_tele[1][1])->first();
                    $userTargetId = $stack_action_bot_tele[1][2];
                    $userTarget = UserHelpers::GetUserById($userTargetId);
                    $customerType =
                        CustomerType_Game::where('code_type', $userTarget->customer_type)
                        ->where('game_id', $thisGame->game_code)
                        ->where('created_user', $userTargetId)->first();
                    if ($thisGame->game_code == 15 || $thisGame->game_code == 16)
                        $customerType->odds = (int)$stack_action_bot_tele[2];
                    else
                        $customerType->exchange_rates = (int)$stack_action_bot_tele[2];
                    $customerType->save();
                    Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                    //$this->editMessageReplyMarkup($cbId, $cbMessageId, "Thay đổi thành công !", ['inline_keyboard' => $this->keyboardOnlyBack]);
                    // $this->editMessage($cbId,$cbMessageId,"Thay đổi thành công !");
                    // $this->renewShowTrangChu($cbId,$user);
                    // sleep(2);
                    $this->thongsogiamuamember($cbId, $cbMessageId, $user, $userTargetId);
                    break;

                case 'chinhgiamuamember_nhaplai':
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhgiamuamember_") {
                        if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                            $stack_action_bot_tele = array_slice($stack_action_bot_tele, 0, 2);
                            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
                            $mess = "Nhập lại giá mua cho " . $stack_action_bot_tele[1][0] . "\n";
                            // $this->deleteMessage($cbData,$cbMessageId);
                            // sleep(2);
                            $this->editMessage($cbId, $cbMessageId, $mess);
                        }
                    }
                    break;

                default:
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhgiamuamember_") {
                        $subMessage = substr($cbData, 18);
                        $arrMessageData = explode("_", $subMessage);
                        $game_code = $arrMessageData[1];
                        $userTargetId = $arrMessageData[0];
                        $userTarget = UserHelpers::GetUserById($userTargetId);
                        $thisGame = Game::where('game_code', $game_code)->first();

                        // array_push($stack_action_bot_tele, $thisGame->name);
                        $stack_action_bot_tele = array("chinhgiamuamember_", [$thisGame->name, $game_code, $userTargetId]);
                        Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

                        if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                            $customerType =
                                CustomerType_Game_Original::where('code_type', $userTarget->customer_type)
                                ->where('game_id', $game_code)
                                ->where('created_user', $userTargetId)->first();

                            if ($game_code == 15 || $game_code == 16) {
                                $mess = "Nhập trả thưởng cho " . $stack_action_bot_tele[1][0] . "\n" . "Điều kiện giá bé hơn " . $customerType->odds . "\n";
                            } else
                                $mess = "Nhập giá mua cho " . $stack_action_bot_tele[1][0] . "\n" . "Điều kiện giá lớn hơn " . $customerType->exchange_rates . "\n";
                            // $this->deleteMessage($cbData,$cbMessageId);
                            // sleep(2);
                            $this->editMessage($cbId, $cbMessageId, $mess);
                        }
                    }
                    break;
            }
        }

        if (strpos($cbData, 'chinhtoida1cuocagent_') === 0) {
            switch ($cbData) {
                case 'chinhtoida1cuocagent_dongy':
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    $thisGame = Game::where('game_code', $stack_action_bot_tele[1][1])->first();
                    $userTargetId = $stack_action_bot_tele[1][2];
                    $userTarget = UserHelpers::GetUserById($userTargetId);
                    $customerType =
                        CustomerType_Game::where('code_type', 'A')
                        ->where('game_id', $thisGame->game_code)
                        ->where('created_user', $userTargetId)->first();

                    $customerType->max_point_one = (int)$stack_action_bot_tele[2];

                    $request = ["name" => $thisGame->game_code, "odds" => $customerType->odds, "max_point" => $customerType->max_point, "max_point_one" => $customerType->max_point_one, "change_max_one" => $customerType->change_max_one];
                    $this->sendChatAction($cbId);
                    sleep(2);
                    Queue::pushOn("high", new UpdateCustomerTypeGameABCMAXPOINTV2($request, $user->id));

                    Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                    //$this->editMessageReplyMarkup($cbId, $cbMessageId, "Thay đổi thành công !", ['inline_keyboard' => $this->keyboardOnlyBack]);
                    // $this->editMessage($cbId,$cbMessageId,"Thay đổi thành công !");
                    // $this->renewShowTrangChu($cbId,$user);
                    // sleep(2);
                    $this->sendChatAction($cbId);
                    sleep(2);
                    $this->banggioihancuoc($cbId, $cbMessageId, $user);
                    break;

                case 'chinhtoida1cuocagent_nhaplai':
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhgiamuaagent_") {
                        if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                            $stack_action_bot_tele = array_slice($stack_action_bot_tele, 0, 2);
                            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
                            $mess = "Nhập lại tối đa/1 cược cho " . $stack_action_bot_tele[1][0] . "\n";
                            // $this->deleteMessage($cbData,$cbMessageId);
                            // sleep(2);
                            $this->editMessage($cbId, $cbMessageId, $mess);
                        }
                    }
                    break;

                default:
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    //Log::info($stack_action_bot_tele);

                    if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhtoida1cuocagent_") {
                        $subMessage = substr($cbData, 21);
                        $arrMessageData = explode("_", $subMessage);
                        $game_code = $arrMessageData[1];
                        $userTargetId = $arrMessageData[0];
                        $userTarget = UserHelpers::GetUserById($userTargetId);
                        $thisGame = Game::where('game_code', $game_code)->first();

                        // array_push($stack_action_bot_tele, $thisGame->name);
                        $stack_action_bot_tele = array("chinhtoida1cuocagent_", [$thisGame->name, $game_code, $userTargetId]);
                        Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

                        if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                            $customerType =
                                CustomerType_Game::where('code_type', $userTarget->customer_type)
                                ->where('game_id', $game_code)
                                ->where('created_user', $userTargetId)->first();

                            $customerTypeParent =
                                CustomerType_Game::where('code_type', $user->customer_type)
                                ->where('game_id', $game_code)
                                ->where('created_user',  $user->user_create)->first();

                            $maxOfmin = $customerTypeParent->max_point_one > $customerType->max_point ? $customerType->max_point : $customerTypeParent->max_point_one;
                            $mess = "Nhập tối đa/ 1 cược cho " . $stack_action_bot_tele[1][0] . "\n" . "Điều kiện giá bé hơn " . $maxOfmin . "\n";
                            // $this->deleteMessage($cbData,$cbMessageId);
                            // sleep(2);
                            $this->editMessage($cbId, $cbMessageId, $mess);
                        }
                    }
                    break;
            }
        }

        if (strpos($cbData, 'chinhtoida1cuocmember_') === 0) {
            switch ($cbData) {
                case 'chinhtoida1cuocmember_dongy':
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    $thisGame = Game::where('game_code', $stack_action_bot_tele[1][1])->first();
                    $userTargetId = $stack_action_bot_tele[1][2];
                    $userTarget = UserHelpers::GetUserById($userTargetId);
                    $customerType =
                        CustomerType_Game::where('code_type', $userTarget->customer_type)
                        ->where('game_id', $thisGame->game_code)
                        ->where('created_user', $userTargetId)->first();

                    $customerType->max_point_one = (int)$stack_action_bot_tele[2];
                    $customerType->save();
                    Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                    //$this->editMessageReplyMarkup($cbId, $cbMessageId, "Thay đổi thành công !", ['inline_keyboard' => $this->keyboardOnlyBack]);
                    // $this->editMessage($cbId,$cbMessageId,"Thay đổi thành công !");
                    // $this->renewShowTrangChu($cbId,$user);
                    // sleep(2);
                    $this->thongsogioihancuocmember($cbId, $cbMessageId, $user, $userTargetId);
                    break;

                case 'chinhtoida1cuocmember_nhaplai':
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhtoida1cuocmember_") {
                        if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                            $stack_action_bot_tele = array_slice($stack_action_bot_tele, 0, 2);
                            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
                            $mess = "Nhập lại tối đa/1 cược cho " . $stack_action_bot_tele[1][0] . "\n";
                            // $this->deleteMessage($cbData,$cbMessageId);
                            // sleep(2);
                            $this->editMessage($cbId, $cbMessageId, $mess);
                        }
                    }
                    break;

                default:
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    //Log::info($stack_action_bot_tele);
                    if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhtoida1cuocmember_") {
                        $subMessage = substr($cbData, 22);
                        $arrMessageData = explode("_", $subMessage);
                        $game_code = $arrMessageData[1];
                        $userTargetId = $arrMessageData[0];
                        $userTarget = UserHelpers::GetUserById($userTargetId);
                        $thisGame = Game::where('game_code', $game_code)->first();

                        // array_push($stack_action_bot_tele, $thisGame->name);
                        $stack_action_bot_tele = array("chinhtoida1cuocmember_", [$thisGame->name, $game_code, $userTargetId]);
                        Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

                        if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                            $customerType =
                                CustomerType_Game::where('code_type', $userTarget->customer_type)
                                ->where('game_id', $game_code)
                                ->where('created_user', $userTargetId)->first();

                            $customerTypeParent =
                                CustomerType_Game::where('code_type', $user->customer_type)
                                ->where('game_id', $game_code)
                                ->where('created_user',  $user->id)->first();

                            $maxOfmin = $customerTypeParent->max_point_one > $customerType->max_point ? $customerType->max_point : $customerTypeParent->max_point_one;
                            $mess = "Nhập tối đa/ 1 cược cho " . $stack_action_bot_tele[1][0] . "\n" . "Điều kiện giá bé hơn " . $maxOfmin . "\n";
                            // $this->deleteMessage($cbData,$cbMessageId);
                            // sleep(2);
                            $this->editMessage($cbId, $cbMessageId, $mess);
                        }
                    }
                    break;
            }
        }

        if (strpos($cbData, 'chinhtoidamember_') === 0) {
            switch ($cbData) {
                case 'chinhtoidamember_dongy':
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    $thisGame = Game::where('game_code', $stack_action_bot_tele[1][1])->first();
                    $userTargetId = $stack_action_bot_tele[1][2];
                    $userTarget = UserHelpers::GetUserById($userTargetId);
                    $customerType =
                        CustomerType_Game::where('code_type', $userTarget->customer_type)
                        ->where('game_id', $thisGame->game_code)
                        ->where('created_user', $userTargetId)->first();

                    $customerType->max_point = (int)$stack_action_bot_tele[2];
                    $customerType->save();
                    Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                    //$this->editMessageReplyMarkup($cbId, $cbMessageId, "Thay đổi thành công !", ['inline_keyboard' => $this->keyboardOnlyBack]);
                    // $this->editMessage($cbId,$cbMessageId,"Thay đổi thành công !");
                    // $this->renewShowTrangChu($cbId,$user);
                    // sleep(2);
                    $this->thongsogioihancuocmember($cbId, $cbMessageId, $user, $userTargetId);
                    break;

                case 'chinhtoidamember_nhaplai':
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhtoidamember_") {
                        if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                            $stack_action_bot_tele = array_slice($stack_action_bot_tele, 0, 2);
                            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
                            $mess = "Nhập lại tối đa cược cho " . $stack_action_bot_tele[1][0] . "\n";
                            // $this->deleteMessage($cbData,$cbMessageId);
                            // sleep(2);
                            $this->editMessage($cbId, $cbMessageId, $mess);
                        }
                    }
                    break;

                default:
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    //Log::info($stack_action_bot_tele);
                    if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhtoidamember_") {
                        $subMessage = substr($cbData, 17);
                        $arrMessageData = explode("_", $subMessage);
                        $game_code = $arrMessageData[1];
                        $userTargetId = $arrMessageData[0];
                        $thisGame = Game::where('game_code', $game_code)->first();
                        $userTarget = UserHelpers::GetUserById($userTargetId);
                        // array_push($stack_action_bot_tele, $thisGame->name);
                        $stack_action_bot_tele = array("chinhtoidamember_", [$thisGame->name, $game_code, $userTargetId]);
                        Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

                        if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                            $customerType =
                                CustomerType_Game::where('code_type', $userTarget->customer_type)
                                ->where('game_id', $game_code)
                                ->where('created_user', $userTargetId)->first();

                            $customerTypeParent =
                                CustomerType_Game::where('code_type', $user->customer_type)
                                ->where('game_id', $game_code)
                                ->where('created_user',  $user->id)->first();

                            // $maxOfmin = $customerTypeParent->max_point > $customerType->max_point ? $customerType->max_point : $customerTypeParent->max_point_one;
                            $mess = "Nhập tối đa cược cho " . $stack_action_bot_tele[1][0] . "\n" . "Điều kiện giá bé hơn " . $customerTypeParent->max_point . " và lớn hơn " . $customerType->max_point_one . "\n";
                            // $this->deleteMessage($cbData,$cbMessageId);
                            // sleep(2);
                            $this->editMessage($cbId, $cbMessageId, $mess);
                        }
                    }
                    break;
            }
        }

        if (strpos($cbData, 'chinhtoidaagent_') === 0) {
            switch ($cbData) {
                case 'chinhtoidaagent_dongy':
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    $thisGame = Game::where('game_code', $stack_action_bot_tele[1][1])->first();
                    $userTargetId = $stack_action_bot_tele[1][2];
                    $userTarget = UserHelpers::GetUserById($userTargetId);
                    $customerType =
                        CustomerType_Game::where('code_type', 'A')
                        ->where('game_id', $thisGame->game_code)
                        ->where('created_user', $userTargetId)->first();

                    $customerType->max_point = (int)$stack_action_bot_tele[2];

                    $request = ["name" => $thisGame->game_code, "odds" => $customerType->odds, "max_point" => $customerType->max_point, "max_point_one" => $customerType->max_point_one, "change_max_one" => $customerType->change_max_one];
                    $this->sendChatAction($cbId);
                    sleep(2);
                    Queue::pushOn("high", new UpdateCustomerTypeGameABCMAXPOINTV2($request, $user->id));
                    Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                    //$this->editMessageReplyMarkup($cbId, $cbMessageId, "Thay đổi thành công !", ['inline_keyboard' => $this->keyboardOnlyBack]);
                    // $this->editMessage($cbId,$cbMessageId,"Thay đổi thành công !");
                    // $this->renewShowTrangChu($cbId,$user);
                    // sleep(2);
                    $this->sendChatAction($cbId);
                    sleep(2);
                    $this->banggioihancuoc($cbId, $cbMessageId, $user);
                    break;

                case 'chinhtoidaagent_nhaplai':
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhtoidaagent_") {
                        if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                            $stack_action_bot_tele = array_slice($stack_action_bot_tele, 0, 2);
                            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
                            $mess = "Nhập lại tối đa cược cho " . $stack_action_bot_tele[1][0] . "\n";
                            // $this->deleteMessage($cbData,$cbMessageId);
                            // sleep(2);
                            $this->editMessage($cbId, $cbMessageId, $mess);
                        }
                    }
                    break;

                default:
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    //Log::info($stack_action_bot_tele);
                    if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhtoidaagent_") {
                        $subMessage = substr($cbData, 16);
                        $arrMessageData = explode("_", $subMessage);
                        $game_code = $arrMessageData[1];
                        $userTargetId = $arrMessageData[0];
                        $thisGame = Game::where('game_code', $game_code)->first();
                        $userTarget = UserHelpers::GetUserById($userTargetId);
                        // array_push($stack_action_bot_tele, $thisGame->name);
                        $stack_action_bot_tele = array("chinhtoidaagent_", [$thisGame->name, $game_code, $userTargetId]);
                        Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

                        if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                            $customerType =
                                CustomerType_Game::where('code_type', $userTarget->customer_type)
                                ->where('game_id', $game_code)
                                ->where('created_user', $userTargetId)->first();

                            $customerTypeParent =
                                CustomerType_Game::where('code_type', $user->customer_type)
                                ->where('game_id', $game_code)
                                ->where('created_user',  $user->user_create)->first();

                            // $maxOfmin = $customerTypeParent->max_point > $customerType->max_point ? $customerType->max_point : $customerTypeParent->max_point_one;
                            $mess = "Nhập tối đa cược cho " . $stack_action_bot_tele[1][0] . "\n" . "Điều kiện giá bé hơn " . $customerTypeParent->max_point . " và lớn hơn " . $customerType->max_point_one . "\n";
                            // $this->deleteMessage($cbData,$cbMessageId);
                            // sleep(2);
                            $this->editMessage($cbId, $cbMessageId, $mess);
                        }
                    }
                    break;
            }
        }

        if (strpos($cbData, 'chinhtindungmember_') === 0) {
            //Log::info("cbData2: " . $cbData);
            switch ($cbData) {
                case 'chinhtindungmember_dongy':
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    $userTargetId = $stack_action_bot_tele[1][0];
                    $request = (object) ["credit" => $stack_action_bot_tele[2], "type" => "credit", "id" => $userTargetId, "user_create" => $user->id];
                    UserHelpers::UpdateUser($request, $userTargetId, $user->id);
                    Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                    //$this->editMessageReplyMarkup($cbId, $cbMessageId, "Thay đổi thành công !", ['inline_keyboard' => $this->keyboardOnlyBack]);
                    // $this->editMessage($cbId,$cbMessageId,"Thay đổi thành công !");
                    // $this->renewShowTrangChu($cbId,$user);
                    // sleep(2);
                    $this->bangtaikhoanmember($cbId, $cbMessageId, $user, $userTargetId);
                    break;

                case 'chinhtindungmember_nhaplai':
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    $userTargetId = $stack_action_bot_tele[1][0];
                    $userTarget = UserHelpers::GetUserById($userTargetId);
                    if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhtindungmember_") {
                        if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                            $stack_action_bot_tele = array_slice($stack_action_bot_tele, 0, 2);
                            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
                            $mess = "Nhập lại tín dụng mới cho " . $userTarget->name . "\n";
                            // $this->deleteMessage($cbData,$cbMessageId);
                            // sleep(2);
                            $this->editMessage($cbId, $cbMessageId, $mess);
                        }
                    }
                    break;

                default:
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);

                    // if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhtindungmember_") 
                    {
                        $userTargetId = substr($cbData, 19);
                        $userTarget = UserHelpers::GetUserById($userTargetId);
                        // $arrMessageData = explode("_", $subMessage);
                        // $game_code = $arrMessageData[1];
                        // $userTargetId = $arrMessageData[0];
                        // $thisGame = Game::where('game_code', $game_code)->first();

                        // array_push($stack_action_bot_tele, $thisGame->name);
                        $stack_action_bot_tele = array("chinhtindungmember_", [$userTargetId]);
                        Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

                        $mess = "Nhập tín dụng mới cho tài khoản :" . $userTarget->name . "\n" . "Điều kiện tín dụng lớn hơn " . number_format($userTarget->credit - $userTarget->remain) . "\n";
                        $this->editMessage($cbId, $cbMessageId, $mess);
                    }
                    break;
            }
        }

        if (strpos($cbData, 'chinhchuanmember_') === 0) {

            switch ($cbData) {
                case 'chinhchuanmember_dongy':
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    $userTargetId = $stack_action_bot_tele[1][0];
                    $request = (object) ["customer_type" => $stack_action_bot_tele[2], "type" => "customer_type", "id" => $userTargetId, "user_create" => $user->id];
                    UserHelpers::UpdateUser($request, $userTargetId, $user->id);
                    $userMe  = User::where('id', $userTargetId)->first();
                    $this->sendChatAction($cbId);
                    sleep(2);
                    Queue::pushOn("high", new UpdateCustomerTypeByUserIdService($stack_action_bot_tele[2], $userMe));
                    Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                    //$this->editMessageReplyMarkup($cbId, $cbMessageId, "Thay đổi thành công !", ['inline_keyboard' => $this->keyboardOnlyBack]);
                    // $this->editMessage($cbId,$cbMessageId,"Thay đổi thành công !");
                    // $this->renewShowTrangChu($cbId,$user);
                    // sleep(2);
                    $this->bangtaikhoanmember($cbId, $cbMessageId, $user, $userTargetId);
                    break;

                case 'chinhchuanmember_nhaplai':
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
                    $userTargetId = $stack_action_bot_tele[1][0];
                    $userTarget = UserHelpers::GetUserById($userTargetId);
                    if (isset($stack_action_bot_tele) && $stack_action_bot_tele[0] == "chinhchuanmember_") {
                        if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                            $stack_action_bot_tele = array_slice($stack_action_bot_tele, 0, 2);
                            Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
                            $mess = "Nhập lại chuẩn cho " . $userTarget->name . "\n";
                            // $this->deleteMessage($cbData,$cbMessageId);
                            // sleep(2);
                            $keyboardChinhchuan =
                                array(
                                    array(
                                        array('text' => 'Chuẩn A', 'callback_data' => 'chinhchuanmember_A'),
                                        array('text' => 'Chuẩn B', 'callback_data' => 'chinhchuanmember_B'),
                                    ),
                                    array(
                                        array('text' => 'Chuẩn C', 'callback_data' => 'chinhchuanmember_C'),
                                        array('text' => 'Chuẩn D', 'callback_data' => 'chinhchuanmember_D'),
                                    ),
                                    array(
                                        array('text' => '< Back', 'callback_data' => 'thaotactaikhoanmember_agent'),
                                    )
                                );

                            $this->editMessageReplyMarkup($cbId, $cbMessageId, $mess, ["inline_keyboard" => $keyboardChinhchuan]);
                        }
                    }
                    break;

                default:
                    $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);

                    if (isset($stack_action_bot_tele) && isset($stack_action_bot_tele[0]) && $stack_action_bot_tele[0] == "chinhchuanmember_" && isset($stack_action_bot_tele[1])) {
                        $userTargetId = $stack_action_bot_tele[1][0];
                        $customer_type = substr($cbData, 17);
                        $userTarget = UserHelpers::GetUserById($userTargetId);
                        array_push($stack_action_bot_tele, $customer_type);
                        Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

                        $mess = "Bạn có muốn thay đổi chuẩn của " . $userTarget->name . " là " . $customer_type . " " . "\n";

                        $keyboardChinhchuanmemberxacnhan =
                            array(
                                array(
                                    array('text' => 'Đồng ý', 'callback_data' => 'chinhchuanmember_dongy'),
                                    array('text' => 'Nhập lại chuẩn', 'callback_data' => 'chinhchuanmember_nhaplai')
                                ),
                                array(
                                    array('text' => '< Back', 'callback_data' => 'thaotactaikhoan_' . $userTargetId),
                                )
                            );
                        $this->editMessageReplyMarkup($cbId, $cbMessageId, $mess, ['inline_keyboard' => $keyboardChinhchuanmemberxacnhan]);
                    } else {

                        // //Log::info($stack_action_bot_tele);
                        // if (!isset($stack_action_bot_tele) || !isset($stack_action_bot_tele[0])) {
                        $userTargetId = substr($cbData, 17);
                        $userTarget = UserHelpers::GetUserById($userTargetId);
                        // $arrMessageData = explode("_", $subMessage);
                        // $game_code = $arrMessageData[1];
                        // $userTargetId = $arrMessageData[0];
                        // $thisGame = Game::where('game_code', $game_code)->first();

                        // array_push($stack_action_bot_tele, $thisGame->name);
                        $stack_action_bot_tele = array("chinhchuanmember_", [$userTargetId]);
                        Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

                        $mess = "Chọn chuẩn cho tài khoản :" . $userTarget->name . "\n";
                        $keyboardChinhchuan =
                            array(
                                array(
                                    array('text' => 'Chuẩn A', 'callback_data' => 'chinhchuanmember_A'),
                                    array('text' => 'Chuẩn B', 'callback_data' => 'chinhchuanmember_B'),
                                ),
                                array(
                                    array('text' => 'Chuẩn C', 'callback_data' => 'chinhchuanmember_C'),
                                    array('text' => 'Chuẩn D', 'callback_data' => 'chinhchuanmember_D'),
                                ),
                                array(
                                    array('text' => '< Back', 'callback_data' => 'thaotactaikhoanmember_agent'),
                                )
                            );

                        $this->editMessageReplyMarkup($cbId, $cbMessageId, $mess, ["inline_keyboard" => $keyboardChinhchuan]);
                    }
                    break;
            }
        }
        return;
    }

    private function thongketheomamienbac_theloai($cbId, $cbMessageId, $user, $game_code, $mode = 'edit')
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "thongketheomamienbac"], env('CACHE_TIME_BOT', 24 * 60));

        $gameList = (new GameHelpers())->GetAllGameByParentID(0, 1);
        $totalall = 0;

        $arrUser = UserHelpers::GetAllUserV2($user);
        $rs = DB::table('xoso_record')
            ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
                    IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
                    ) AS sumwin'), 'games.name as game_name', 'bet_number')
            ->orderBy('sumbet', 'desc')
            ->where('isDelete', false)
            ->where('date', date('Y-m-d'))
            // ->where('date','<=',$endDate)
            ->where('game_id', $game_code)
            ->whereIn('user_id', $arrUser)
            ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
            ->groupBy('bet_number')
            ->get();

        $mess = "Thống kê " . $user->name . "\n";
        $data = [
            ["Số", "Tổng tiền"],
        ];
        $totalBet = 0;

        $custom_keyboard =
            array(
                array()
            );

        foreach ($rs as $game) {
            array_push($data, [$game->bet_number, number_format($game->sumbet)]);
            $totalBet += $game->sumbet;
        }

        $array_Btn_inline = Cache::get('stack_action_bot_tele' . $user->id);

        $i = 0;
        $count = 0;
        //Log::info($array_Btn_inline);
        foreach ($array_Btn_inline as $btn_inline) {
            if ($game_code == $btn_inline['game_code']) continue;
            $count++;
            if ($count > 3) {
                $i++;
                $count = 1;
                array_push($custom_keyboard, []);
            }
            array_push($custom_keyboard[$i], array('text' => $btn_inline['name'], 'callback_data' => 'thongketheomamienbac_theloai_' . $btn_inline['game_code']));
        }

        array_push($custom_keyboard, array(array('text' => '< Back', 'callback_data' => "thongketheomamienbac_agent")));
        $mess .= "<pre>";

        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();

        foreach ($table_data as $row) {
            $mess .= $row . "\n";
            // echo $row . "\n";
        }
        $mess .= "</pre>";
        $mess .= "\n" . "Tổng: <b>" . number_format($totalBet) . "</b>";

        $this->editMessageReplyMarkup($cbId, $cbMessageId, $mess, ["inline_keyboard" => $custom_keyboard]);
    }

    private function bangthaotacgia($chatId, $message_id, $user, $mode = 'edit')
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "bangthaotacgia"], env('CACHE_TIME_BOT', 24 * 60));
        $keyboardThongkeAgent =
            array(
                array(
                    array('text' => 'Bảng thao tác chuẩn', 'callback_data' => 'bangthaotacchuan_agent'),
                ),
                array(
                    array('text' => 'Bảng giới hạn cược', 'callback_data' => 'banggioihancuoc_agent'),
                ),
                array(
                    array('text' => '< Back', 'callback_data' => 'back'),
                )
            );
        $this->editMessageReplyMarkup($chatId, $message_id, "Bảng thao tác giá", ['inline_keyboard' => $keyboardThongkeAgent]);
    }

    private function bangthaotacchuan($chatId, $message_id, $user, $mode = 'edit')
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "bangthaotacchuan"], env('CACHE_TIME_BOT', 24 * 60));
        $keyboardThongkeAgent =
            array(
                array(
                    array('text' => 'Chuẩn A', 'callback_data' => 'bangthaotacchuan_customertype_A'),
                    array('text' => 'Chuẩn B', 'callback_data' => 'bangthaotacchuan_customertype_B'),
                ),
                array(
                    array('text' => 'Chuẩn C', 'callback_data' => 'bangthaotacchuan_customertype_C'),
                    array('text' => 'Chuẩn D', 'callback_data' => 'bangthaotacchuan_customertype_D'),
                ),
                array(
                    array('text' => '< Back', 'callback_data' => 'bangthaotacgia_agent'),
                )
            );
        $this->editMessageReplyMarkup($chatId, $message_id, "Bảng thao tác chuẩn", ['inline_keyboard' => $keyboardThongkeAgent]);
    }

    private function bangthaotacchuanDetail($chatId, $message_id, $user, $customer_type, $mode = 'edit')
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "bangthaotacchuan", "bangthaotacchuanDetail"], env('CACHE_TIME_BOT', 24 * 60));

        $data = [["Thể loại", "Giá mua", "Trả thưởng"]];

        $games = GameHelpers::GetAllGameByCusType($customer_type, $user->id, 0);
        $count = 0;
        foreach ($games as $game) {
            if ($game['game_code'] >= 100) {
                $count++;
                continue;
            }
            if ($game['game_code'] >= 31 && $game['game_code'] <= 55) {
                $count++;
                continue;
            }
            if ($game['game_code'] == 18) continue;
            array_push($data, [$game['game_name'], number_format($game['exchange_rates']), number_format($game['odds'])]);
        }

        $mess = "Bảng giá " . $user->name . " Chuẩn " . $customer_type . "\n";
        $mess .= "<pre>";
        $table = Tableify::new($data);
        // Tableify::new($data)->center()->seperatorPadding(2)->seperator('*')->headerCharacter('@')->make()->toArray();
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();
        foreach ($table_data as $row) {
            $mess .= $row . "\n";
        }
        $mess .= "</pre>";

        $keyboardChinhgiamua =
            array(
                array(
                    array('text' => 'Chỉnh giá mua', 'callback_data' => 'chinhgiamua_agent_' . $customer_type),
                ),
                array(
                    array('text' => '< Back', 'callback_data' => 'bangthaotacchuan_agent'),
                )
            );

        if ($mode != "edit")
            $this->sendMessage($chatId, $mess);
        else
            $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $keyboardChinhgiamua]);
    }

    private function banggioihancuoc($chatId, $message_id, $user, $mode = "edit")
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "quanlymember", "thaotac", $user->id, "gioihancuoc"], env('CACHE_TIME_BOT', 24 * 60));

        $data = [["Thể loại", "TĐ/1cược", "Tối đa"]];

        $games = GameHelpers::GetAllGameByCusType($user->customer_type, $user->id, 0);
        $count = 0;
        foreach ($games as $game) {
            if ($game['game_code'] >= 100) {
                $count++;
                continue;
            }
            if ($game['game_code'] >= 31 && $game['game_code'] <= 55) {
                $count++;
                continue;
            }
            if ($game['game_code'] == 18) continue;
            array_push($data, [$game['game_name'], number_format($game['max_point_one']), number_format($game['max_point'])]);
        }

        $mess = "Giới hạn cược " . $user->name . "\n";
        $mess .= "<pre>";
        $table = Tableify::new($data);
        // Tableify::new($data)->center()->seperatorPadding(2)->seperator('*')->headerCharacter('@')->make()->toArray();
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();
        foreach ($table_data as $row) {
            $mess .= $row . "\n";
        }
        $mess .= "</pre>";

        $keyboardChinhgiamua =
            array(
                array(
                    array('text' => 'Chỉnh Tối đa/1 cược', 'callback_data' => 'chinhgioihancuoc_toida1cuoc_agent_' . $user->id),
                ),
                array(
                    array('text' => 'Chỉnh Tối đa', 'callback_data' => 'chinhgioihancuoc_toida_agent_' . $user->id),
                ),
                array(
                    array('text' => '< Back', 'callback_data' => 'bangthaotacgia_agent'),
                )
            );

        if ($mode != "edit")
            $this->sendMessage($chatId, $mess);
        else
            $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $keyboardChinhgiamua]);
    }

    private function thongketheomamienbac($chatId, $message_id, $user, $mode = 'edit')
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "thongketheomamienbac"], env('CACHE_TIME_BOT', 24 * 60));

        $gameList = (new GameHelpers())->GetAllGameByParentID(0, 1);
        $totalall = 0;

        $arrUser = UserHelpers::GetAllUserV2($user);
        $rs = DB::table('xoso_record')
            ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
                    IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
                    ) AS sumwin'), 'games.name as game_name')
            ->orderBy('sumbet', 'desc')
            ->where('isDelete', false)
            ->where('date', date('Y-m-d'))
            // ->where('date','<=',$endDate)
            // ->whereIn('game_id', [7,12,14])
            ->whereIn('user_id', $arrUser)
            ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
            ->groupBy('game_id')
            ->get();

        $mess = "Thống kê " . $user->name . "\n";
        $data = [
            ["Thể loại", "Tổng tiền"],
        ];
        $totalBet = 0;

        $custom_keyboard =
            array(
                array()
            );
        $i = 0;
        $count = 0;
        $array_Btn_inline = [];
        foreach ($rs as $game) {
            array_push($data, [$game->game_name, number_format($game->sumbet)]);
            $totalBet += $game->sumbet;
            $count++;
            if ($count > 3) {
                $i++;
                $count = 1;
                array_push($custom_keyboard, []);
            }
            array_push($custom_keyboard[$i], array('text' => $game->game_name, 'callback_data' => 'thongketheomamienbac_theloai_' . $game->game_id));
            array_push($array_Btn_inline, ["name" => $game->game_name, "game_code" => $game->game_id]);
        }

        Cache::put('stack_action_bot_tele' . $user->id, $array_Btn_inline, env('CACHE_TIME_BOT', 24 * 60));

        array_push($custom_keyboard, array(array('text' => '< Back', 'callback_data' => "thongke_agent")));
        $mess .= "<pre>";

        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();

        foreach ($table_data as $row) {
            $mess .= $row . "\n";
            // echo $row . "\n";
        }
        $mess .= "</pre>";
        $mess .= "\n" . "Tổng: <b>" . number_format($totalBet) . "</b>";

        $this->editMessageReplyMarkup($chatId, $message_id, $mess, ["inline_keyboard" => $custom_keyboard]);
    }

    private function bangtaikhoanmember($chatId, $message_id, $user, $memberId, $mode = 'edit')
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
        $userTarget = UserHelpers::GetUserById($memberId);
        $mess = "Thao tác " . $userTarget->name . "\n";
        $mess .= "Quyền: " . XoSoRecordHelpers::GetRoleName($userTarget->roleid) . "\n";
        $mess .= "Loại: " . $userTarget->customer_type . "\n";
        $mess .= "Tín dụng: " . number_format($userTarget->credit) . "\n";
        $keyboardOnlyBack = array();
        if (isset($userTarget->fullname) && $userTarget->fullname != "") {
            $keyboardOnlyBack =
                array(
                    array(
                        array('text' => 'Giá mua', 'callback_data' => 'bangtaikhoanmembergiamua_' . $memberId),
                        array('text' => 'Giới hạn cược', 'callback_data' => 'bangtaikhoanmembergioihancuoc_' . $memberId),
                    ),
                    array(
                        array('text' => 'Chỉnh tín dụng', 'callback_data' => 'chinhtindungmember_' . $memberId),
                        array('text' => 'Chuẩn ' . $userTarget->customer_type, 'callback_data' => 'chinhchuanmember_' . $memberId),
                    ),
                    array(
                        array('text' => 'Tạo token đăng nhập', 'callback_data' => 'taotokendangnhapmember_' . $memberId),
                    ),
                    array(
                        array('text' => 'Reset liên kết tk', 'callback_data' => 'resetlienhettkmember_' . $memberId),
                    ),
                    array(
                        array('text' => '< Back', 'callback_data' => 'thaotactaikhoanmember_agent'),
                    )
                );
        } else
            $keyboardOnlyBack =
                array(
                    array(
                        array('text' => 'Giá mua', 'callback_data' => 'bangtaikhoanmembergiamua_' . $memberId),
                        array('text' => 'Giới hạn cược', 'callback_data' => 'bangtaikhoanmembergioihancuoc_' . $memberId),
                    ),
                    array(
                        array('text' => 'Chỉnh tín dụng', 'callback_data' => 'chinhtindungmember_' . $memberId),
                        array('text' => 'Chuẩn ' . $userTarget->customer_type, 'callback_data' => 'chinhchuanmember_' . $memberId),
                    ),
                    array(
                        array('text' => 'Tạo token đăng nhập', 'callback_data' => 'taotokendangnhapmember_' . $memberId),
                    ),
                    array(
                        array('text' => '< Back', 'callback_data' => 'thaotactaikhoanmember_agent'),
                    )
                );

        $this->editMessageReplyMarkup($chatId, $message_id, $mess, ["inline_keyboard" => $keyboardOnlyBack]);
        return;
    }

    private function showTrangchuAgent($chatId, $message_id, $user, $mode = 'edit')
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu"], env('CACHE_TIME_BOT', 24 * 60));
        Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
        $user->auth_token = Str::random(30);
        $user->save();
        $keyboard = $this->keyboardTrangchuAgent;
        // $keyboard[3][0]['web_app']['url'] = "https://ag.99luckey.com/auth/token/" . $user->auth_token;
        $keyboard[5][0]['web_app']['url'] = "https://ag.99luckey.com/auth/token/" . $user->auth_token;
        // $keyboard[] = 
        // array(
        //     array('text' => 'Nhận thông báo cược: Đang ' . ($user->active_noti_tele ? "Bật" : "Tắt"), 'callback_data' => 'caidatthongbaotele'),
        // );
        $newDate = date("Y-m-d");
        if (date('H') < 11)
            $newDate = date("Y-m-d", strtotime('-1 day', strtotime($newDate)));

        //navigate tpk
        if ($user->per == 1){
            $userOrg = User::where("name",$user->usfollow)->first();
            $user = $userOrg;
        }

        $userChild = UserHelpers::GetAllUser($user);

        $counttkactive = [0, 0, 0, 0, 0, 0, 0];
        $counttkkhoa = [0, 0, 0, 0, 0, 0, 0];
        $counttkngungdat = [0, 0, 0, 0, 0, 0, 0];

        $counttiendadung = 0;
        $countsuper = 0;
        $countagent = 0;
        $countmaster = 0;
        $countmember = 0;


        foreach ($userChild as $child) {
            if ($child->lock == 0 && $child->active == 0)
                $counttkactive[$child->roleid]++;
            if ($child->lock == 1 && $child->active == 0)
                $counttkngungdat[$child->roleid]++;
            if ($child->lock == 2 && $child->active == 0)
                $counttkkhoa[$child->roleid]++;

            if ($child->user_create == $user->id)
                $counttiendadung += $child->credit;

            switch ($child->roleid) {
                case 2:
                    $countsuper++;
                    break;

                case 4:
                    $countmaster++;
                    break;

                case 5:
                    $countagent++;
                    break;

                case 6:
                    $countmember++;
                    break;

                default:
                    # code...
                    break;
            }
        }

        $newDate = date("Y-m-d");
        $newDateShow = date("d-m-Y");
        $rs = [];
        // $arrUser = [];
        // foreach($userChild as $item){
        // 	array_push($arrUser,$item->id);
        // }
        // $arrUser = Cache::remember('UserHelpers-GetAllUserV2'.$user->id, env('CACHE_TIME_SHORT', 0), function () use ($user) {
        // 	return UserHelpers::GetAllUserV2($user);
        // });
        $arrUser = UserHelpers::GetAllUserV2($user);
        if (date('H') < 11) {

            $newDate = date("Y-m-d", strtotime('-1 day', strtotime($newDate)));
            $newDateShow = date("d-m-Y", strtotime('-1 day', strtotime(date("d-m-Y"))));
        }

        $rs = Cache::remember('Homepage' . $user->id . '-' . $newDate, env('CACHE_TIME_SHORT', 0), function () use ($newDate, $arrUser) {
            return DB::table('xoso_record')
                // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
                // ->orderBy('sumbet', 'desc')
                ->where('isDelete', 0)
                // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
                // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
                ->where('date', '>=', $newDate)
                ->where('date', '<=', date("Y-m-d", strtotime('+1 day', strtotime($newDate))))
                // ->where('game_id', 7)
                ->whereIn('user_id', $arrUser)
                // ->groupBy('game_id')
                ->get();
        });

        $H_7zBall_record = DB::table('history_7zball_bet')
            ->where('createdate', '>=', date("Y-m-d", strtotime($newDate)) . ' 11:00:00')
            ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($newDate))) . ' 11:00:00')
            ->join('games', 'history_7zball_bet.gametype', '=', 'games.game_code')
            ->join('users', 'users.name', '=', 'history_7zball_bet.username')
            ->whereIn('users.id', $arrUser)
            ->select('*', 'users.*', 'games.name as game')
            ->get();
        // echo date("Y-m-d",strtotime('+1 day',strtotime($date))) .' 11:00:00';
        foreach ($H_7zBall_record as $value) {
            $dataResults = json_decode($value->jsoninfo);
            $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0,0,0";
            $record7zBall = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"' . $bonus . '","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":0,"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"7zBall","locationslug":"70", "SerialID": "' . "" . '", "result": "' . "" . '"}'));
            $record7zBall->rawBet = $dataResults;
            array_push($rs, $record7zBall);
        }

        try {
            $H_minigame_record = DB::table('history_minigame_bet')
                // ->where('createdate','<',date("Y-m-d",strtotime('+1 day',strtotime($date))) .' 11:00:00')
                ->where('createdate', '>=', date("Y-m-d", strtotime($newDate)) . ' 11:00:00')
                ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($newDate))) . ' 11:00:00')
                ->join('users', 'users.name', '=', 'history_minigame_bet.username')
                ->join('games', 'history_minigame_bet.gametype', '=', 'games.game_code')
                ->whereIn('users.id', $arrUser)
                ->select('*', 'games.name as game')
                ->orderBy("history_minigame_bet.id", "desc")
                ->get();
            foreach ($H_minigame_record as $value) {
                $dataResults = json_decode($value->jsoninfo);
                $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0,0,0";
                $recordminigame = json_decode('{"bonus":"' . $bonus . '","game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":' . (isset($dataResults->odd) ? $dataResults->odd : 0) . ',"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"minigame","locationslug":"80", "SerialID": "' . "" . '", "result": "' . "" . '"}');
                $recordminigame->rawBet = $dataResults;
                array_push($xoso_record, $recordminigame);
            }
        } catch (Exception $ex) {
            Log::info($ex->getMessage());
        }


        $totalCXL = 0;
        $totalWinLose = 0;
        $totalBet = 0;
        foreach ($rs as $record) {
            if ($record->total_win_money == 0) {
                if (!(isset($record->locationslug) && $record->locationslug == 70 && $record->rawBet->paid == 1)) {
                    $totalCXL += $record->total_bet_money;
                }
            } else {
                if ($record->total_win_money > 0 && $record->game_id < 100) {
                    if (
                        $record->game_id == 15 || $record->game_id == 16 ||
                        $record->game_id == 316 || $record->game_id == 416 || $record->game_id == 516 || $record->game_id == 616 || $record->game_id == 115 || $record->game_id == 116
                    ) {
                        $totalWinLose += ($record->total_win_money);
                    } else
                        $totalWinLose += ($record->total_win_money - $record->total_bet_money);
                } else {
                    $totalWinLose += ($record->total_win_money);
                }

                $bonus = explode(',', $record->bonus);
                $totalWinLose += array_sum($bonus);
            }
            $totalBet += $record->total_bet_money;
        }

        $mess = "<pre>";
        $data = [
            ["Thông tin", ""],
            // ["Hội viên", $user->name],
            ["Hạn mức tối đa", number_format($user->credit)],
            ["Tín dụng đã dùng", number_format($user->credit - $user->remain)],
            ["Tài khoản đang có", number_format($countmember)],
            ["Tiền đang cược",  number_format($totalCXL, 0)],
            ["Thắng thua", number_format($totalWinLose, 0)]
        ];
        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();

        foreach ($table_data as $row) {
            $mess .= $row . "\n";
            // echo $row . "\n";
        }

        $mess .= "</pre>";
        $trangChuMessageId = 0;
        if ($mode != 'edit')
            $trangChuMessageId = $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboard]);
        else
            $trangChuMessageId = $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $keyboard]);

        Cache::put('latest_messageid_trangchu_bot_tele' . $user->id, $trangChuMessageId, env('CACHE_TIME_BOT', 24 * 60));
        return $trangChuMessageId;
    }

    private function showTrangchuAdmin($chatId, $message_id, $user, $mode = 'edit')
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu"], env('CACHE_TIME_BOT', 24 * 60));
        Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
        $user->auth_token = Str::random(30);
        $user->save();
        $keyboard = $this->keyboardTrangchuAdmin;
        // $keyboard[1][0]['web_app']['url'] = "https://ag.99luckey.com/auth/token/" . $user->auth_token;
        $keyboard[3][0]['web_app']['url'] = "https://ag.99luckey.com/auth/token/" . $user->auth_token;
        // $keyboard[4][0]['url'] = "https://ag.99luckey.com/auth/token/" . $user->auth_token;
        // $keyboard[4][0]['web_app']['url'] = "https://ag.99luckey.com/auth/token/" . $user->auth_token;
        $newDate = date("Y-m-d");
        if (date('H') < 11)
            $newDate = date("Y-m-d", strtotime('-1 day', strtotime($newDate)));

        $userChild = UserHelpers::GetAllUser($user);

        $counttkactive = [0, 0, 0, 0, 0, 0, 0];
        $counttkkhoa = [0, 0, 0, 0, 0, 0, 0];
        $counttkngungdat = [0, 0, 0, 0, 0, 0, 0];

        $counttiendadung = 0;
        $countsuper = 0;
        $countagent = 0;
        $countmaster = 0;
        $countmember = 0;


        foreach ($userChild as $child) {
            if ($child->lock == 0 && $child->active == 0)
                $counttkactive[$child->roleid]++;
            if ($child->lock == 1 && $child->active == 0)
                $counttkngungdat[$child->roleid]++;
            if ($child->lock == 2 && $child->active == 0)
                $counttkkhoa[$child->roleid]++;

            if ($child->user_create == $user->id)
                $counttiendadung += $child->credit;

            switch ($child->roleid) {
                case 2:
                    $countsuper++;
                    break;

                case 4:
                    $countmaster++;
                    break;

                case 5:
                    $countagent++;
                    break;

                case 6:
                    $countmember++;
                    break;

                default:
                    # code...
                    break;
            }
        }

        $newDate = date("Y-m-d");
        $newDateShow = date("d-m-Y");
        $rs = [];
        // $arrUser = [];
        // foreach($userChild as $item){
        // 	array_push($arrUser,$item->id);
        // }
        // $arrUser = Cache::remember('UserHelpers-GetAllUserV2'.$user->id, env('CACHE_TIME_SHORT', 0), function () use ($user) {
        // 	return UserHelpers::GetAllUserV2($user);
        // });
        $arrUser = UserHelpers::GetAllUserV2($user);
        if (date('H') < 11) {

            $newDate = date("Y-m-d", strtotime('-1 day', strtotime($newDate)));
            $newDateShow = date("d-m-Y", strtotime('-1 day', strtotime(date("d-m-Y"))));
        }

        $rs = Cache::remember('Homepage' . $user->id . '-' . $newDate, env('CACHE_TIME_SHORT', 0), function () use ($newDate, $arrUser) {
            return DB::table('xoso_record')
                // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
                // ->orderBy('sumbet', 'desc')
                ->where('isDelete', 0)
                // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
                // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
                ->where('date', '>=', $newDate)
                ->where('date', '<=', date("Y-m-d", strtotime('+1 day', strtotime($newDate))))
                // ->where('game_id', 7)
                ->whereIn('user_id', $arrUser)
                // ->groupBy('game_id')
                ->get();
        });

        $H_7zBall_record = DB::table('history_7zball_bet')
            ->where('createdate', '>=', date("Y-m-d", strtotime($newDate)) . ' 11:00:00')
            ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($newDate))) . ' 11:00:00')
            ->join('games', 'history_7zball_bet.gametype', '=', 'games.game_code')
            ->join('users', 'users.name', '=', 'history_7zball_bet.username')
            ->whereIn('users.id', $arrUser)
            ->select('*', 'users.*', 'games.name as game')
            ->get();
        // echo date("Y-m-d",strtotime('+1 day',strtotime($date))) .' 11:00:00';
        foreach ($H_7zBall_record as $value) {
            $dataResults = json_decode($value->jsoninfo);
            $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0,0,0";
            $record7zBall = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"' . $bonus . '","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":0,"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"7zBall","locationslug":"70", "SerialID": "' . "" . '", "result": "' . "" . '"}'));
            $record7zBall->rawBet = $dataResults;
            array_push($rs, $record7zBall);
        }

        $totalCXL = 0;
        $totalWinLose = 0;
        $totalBet = 0;
        foreach ($rs as $record) {
            if ($record->total_win_money == 0) {
                if (!(isset($record->locationslug) && $record->locationslug == 70 && $record->rawBet->paid == 1)) {
                    $totalCXL += $record->total_bet_money;
                }
            } else {
                if ($record->total_win_money > 0 && $record->game_id < 100) {
                    if (
                        $record->game_id == 15 || $record->game_id == 16 ||
                        $record->game_id == 316 || $record->game_id == 416 || $record->game_id == 516 || $record->game_id == 616 || $record->game_id == 115 || $record->game_id == 116
                    ) {
                        $totalWinLose += ($record->total_win_money);
                    } else
                        $totalWinLose += ($record->total_win_money - $record->total_bet_money);
                } else {
                    $totalWinLose += ($record->total_win_money);
                }

                $bonus = explode(',', $record->bonus);
                $totalWinLose += array_sum($bonus);
            }
            $totalBet += $record->total_bet_money;
        }

        $mess = "<pre>";
        $data = [
            ["Thông tin", ""],
            // ["Hội viên", $user->name],
            ["Hạn mức tối đa", number_format($user->credit)],
            ["Tín dụng đã dùng", number_format($user->credit - $user->remain)],
            ["Tài khoản đang có", number_format($countmember)],
            ["Tiền đang cược",  number_format($totalCXL, 0)],
            ["Thắng thua", number_format($totalWinLose, 0)]
        ];
        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();

        foreach ($table_data as $row) {
            $mess .= $row . "\n";
            // echo $row . "\n";
        }

        $mess .= "</pre>";
        $trangChuMessageId = 0;
        if ($mode != 'edit')
            $trangChuMessageId = $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboard]);
        else
            $trangChuMessageId = $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $keyboard]);

        Cache::put('latest_messageid_trangchu_bot_tele' . $user->id, $trangChuMessageId, env('CACHE_TIME_BOT', 24 * 60));
        return $trangChuMessageId;
    }

    private function thaotacAdmin($chatId, $message_id, $user, $mode = 'edit')
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu"], env('CACHE_TIME_BOT', 24 * 60));
        $keyboard = array(
            array(),
            array(
                array('text' => '< Back', 'callback_data' => 'back'),
            )
        );
        $keyboard[0] = array(
            array('text' => 'Nhận thông báo cược: Đang ' . ($user->active_noti_tele ? "Bật" : "Tắt"), 'callback_data' => 'caidatthongbaotele'),
        );

        $mess = "Thao tác.";

        $trangChuMessageId = 0;
        if ($mode != 'edit')
            $trangChuMessageId = $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboard]);
        else
            $trangChuMessageId = $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $keyboard]);
        return $trangChuMessageId;
    }

    private function themmoitaikhoanfinal($stack_action_bot_tele, $user, $chatId, $message_id, $mode = "edit")
    {
        $mess = "Thêm mới member" . "\n";
        $mess .= "*****************" . "\n";
        $mess .= "Tên tài khoản: " . $stack_action_bot_tele[1] . "\n";
        $mess .= "Tín dụng: " . $stack_action_bot_tele[2] . "\n";

        $keyboard =
            array(
                array(
                    array('text' => 'Chuẩn ' . $stack_action_bot_tele[3]["customer_type"], 'callback_data' => 'themmoitaikhoanmemberthaydoichuan'),
                    array('text' => $stack_action_bot_tele[3]["rollback_money"] == 1 ? 'Hồi tiền hàng ngày' : 'Không hồi tiền', 'callback_data' => 'themmoitaikhoanmemberthaydoihoitienhangngay'),
                    // array('text' => 'nhập lại', 'callback_data' => 'nhaplai'),

                ),
                // array(
                //     array('text' => 'Copy thông số(Đang phát triển)', 'callback_data' => ''),
                // ),
                array(
                    array('text' => '< Back', 'callback_data' => 'back'),
                    array('text' => 'Tạo tài khoản', 'callback_data' => 'themmoitaikhoanmembertaotaikhoan'),
                ),
            );

        Cache::put('stack_action_bot_tele' . $user->id, $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
        if ($mode == "edit")
            $this->editMessageReplyMarkup($chatId, $message_id, $mess, ["inline_keyboard" => $keyboard]);
        else
            $this->sendMessageReplyMarkup($chatId, $mess, ["inline_keyboard" => $keyboard]);
        // $this->sendMessage($chatId,$mess);
    }

    private function quanlymember($chatId, $message_id, $user, $mode = 'edit')
    {
        $data = [
            ["Tài khoản", "Tín dụng", "Trạng thái"],
        ];

        $userChild = UserHelpers::GetAllUserChild($user);
        foreach ($userChild as $child) {
            $statusName = "";
            switch ($child->lock) {
                case 0:
                    $statusName = "Mở";
                    break;

                case 1:
                    $statusName = "Ngừng đặt";
                    break;

                case 2:
                    $statusName = "Đóng";
                    break;

                case 3:
                    $statusName = "Đóng/Ngừng đặt";
                    break;

                default:
                    break;
            }
            array_push($data, array($child->name, number_format($child->remain), "(" . $child->customer_type . ") " . $statusName));
            array_push($data, array("", "/" . number_format($child->credit), ""));
        }

        $mess = "Quản lý members" . "\n";
        $mess .= "<pre>";

        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();

        foreach ($table_data as $row) {
            $mess .= $row . "\n";
            // echo $row . "\n";
        }
        $mess .= "</pre>";

        return $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $this->keyboardQuanlymemberAgent]);
    }

    private function thaotactaikhoan($chatId, $message_id, $user, $mode = 'edit')
    {
        $data = [
            ["Tài khoản", "Tín dụng", "Trạng thái"],
        ];

        $keyboardQuanlymemberAgent =
            array(
                array()
            );
        $userChild = UserHelpers::GetAllUserChild($user);
        $i = 0;
        $count = 0;
        foreach ($userChild as $child) {
            $statusName = "";
            switch ($child->lock) {
                case 0:
                    $statusName = "Mở";
                    break;

                case 1:
                    $statusName = "Ngừng đặt";
                    break;

                case 2:
                    $statusName = "Đóng";
                    break;

                case 3:
                    $statusName = "Đóng/Ngừng đặt";
                    break;

                default:
                    break;
            }
            array_push($data, array($child->name, number_format($child->remain), "(" . $child->customer_type . ") " . $statusName));
            array_push($data, array("", "/" . number_format($child->credit), ""));

            $count++;
            if ($count > 2) {
                $i++;
                $count = 1;
                array_push($keyboardQuanlymemberAgent, []);
            }
            array_push($keyboardQuanlymemberAgent[$i], array('text' => $child->name, 'callback_data' => 'thaotactaikhoan_' . $child->id));
        }
        array_push($keyboardQuanlymemberAgent, array(array('text' => '< Back', 'callback_data' => 'taikhoan_agent')));
        // array(
        //     array('text' => '< Back', 'callback_data' => 'back'),
        // )
        $mess = "Thao tác members" . "\n";
        $mess .= "<pre>";

        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();

        foreach ($table_data as $row) {
            $mess .= $row . "\n";
            // echo $row . "\n";
        }
        $mess .= "</pre>";

        return $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $keyboardQuanlymemberAgent]);
    }

    public function xosobot_agent_member()
    {
        try {
            $update = json_decode(file_get_contents("php://input"), TRUE);
            if (!isset($this->quickbet)) {
                $this->quickbet = new QuickbetHelpers();
            }
            Log::info($update);
            if ($this->callback($update)) {
                $cbId = $update["callback_query"]["from"]["id"]; //'';//
                $cbData = $update["callback_query"]["data"]; //'';//
                $cbText = $update["callback_query"]["message"]["text"];
                $cbMessageId = $update["callback_query"]["message"]["message_id"];
                $username = $update["callback_query"]["from"]["username"]; //'';//
                $roleIds = [6];
                // switch ($this->bot_type) {
                //     case 'agent_member':
                //         $roleIds = [6,5];
                //         break;
                //     case 'admin_super_master':
                //             $roleIds = [1,2,4];
                //             break;
                //     default:
                //         # code...
                //         break;
                // }
                $user = UserHelpers::GetUserByFullNameRoleIds($username, $roleIds);

                if (!isset($user)) {
                    $this->sendMessage($cbId, "Tài khoản chưa được liên kết hãy nhập token từ quản lý.");
                    return;
                } else if ($user->lock == 2 || $user->lock == 3) {
                    $this->sendMessage($cbId, "Tài khoản đã bị khoá.");
                    return;
                }

                if ($this->bot_type == "agent_member_1" || $this->bot_type == "agent_member_2")
                    if ($user->roleid != 6) {
                        $this->sendMessage($cbId, "Phiên bản chỉ hỗ trợ cho member.");
                        return;
                    }
                if ($this->bot_type == "admin_super_master")
                    if ($user->roleid != 1 && $user->roleid != 2 && $user->roleid != 4) {
                        $this->sendMessage($cbId, "Phiên bản chỉ hỗ trợ cho admin, super và master.");
                        return;
                    }
                $user->latestlogin = date("Y-m-d H:i:s");
                $user->chat_id = $cbId;
                $user->bot_tele_type = $this->bot_type;
                $user->save();
                $this->appendMessageID2Queue($cbMessageId,$cbId);
                switch ($user->roleid) {
                    case 6:
                        $this->xosobot_member_callback($cbId, $cbData, $cbMessageId, $user);
                        break;

                    case 5:
                        $this->xosobot_agent_callback($cbId, $cbData, $cbMessageId, $user);
                        break;
                    default:
                        # code...
                        break;
                }
                return;
            }

            if (isset($update["inline_query"])) {
                $chatId = $update["inline_query"]["from"]["id"]; //5381486859;//
                $username = $update["inline_query"]["from"]["username"]; //'';//
                $query = $update["inline_query"]["query"];
                $roleIds = [6];
                switch ($this->bot_type) {
                    case 'agent_member_1': case 'agent_member_2':
                        $roleIds = [6];
                        break;
                    case 'admin_super_master':
                        $roleIds = [1, 2, 4];
                        break;
                    default:
                        # code...
                        break;
                }
                $user = UserHelpers::GetUserByFullNameRoleIds($username, $roleIds);
                if (!isset($user)) {
                    $this->sendMessage($chatId, "Tài khoản chưa được liên kết hãy nhập token từ quản lý.");
                } else
                if ($user->lock == 2 || $user->lock == 3)
                    $this->sendMessage($chatId, "Tài khoản đã bị khoá.");
                else
                    $this->sendMessage($chatId, $user->name);
                return;
            }

            if (isset($update["message"])) {
                $chatId = isset($update["message"]) ? $update["message"]["chat"]["id"] : ''; //5381486859;//
                $message = isset($update["message"]) ? $update["message"]["text"] : ''; //'';//
                $messageId = isset($update["message"]) ? $update["message"]["message_id"] : ''; //'';//
                $username = isset($update["message"]["from"]["username"]) ? $update["message"]["from"]["username"] : ''; //'';//
                if ($username == '') {
                    $this->sendMessage($chatId, 'Hãy cài đặt username của tài khoản telegram theo hướng dẫn https://www.youtube.com/watch?v=FKMTzgJ1Cww và /start để bắt đầu sử dụng.');
                    return;
                }
                $roleIds = [6];
                // switch ($this->bot_type) {
                //     case 'agent_member':
                //         $roleIds = [6,5];
                //         break;
                //     case 'admin_super_master':
                //             $roleIds = [1,2,4];
                //             break;
                //     default:
                //         # code...
                //         break;
                // }
                $user = UserHelpers::GetUserByFullNameRoleIds($username, $roleIds);
                if (!isset($user)) {
                    $checkTokenUser = User::where('token_bot_tele', $message)->whereIn('roleid', $roleIds)->first();

                    if (isset($checkTokenUser)) {
                        $checkTokenUser->fullname = $username;
                        $checkTokenUser->chat_id = $chatId;
                        $checkTokenUser->bot_tele_type = $this->bot_type;
                        $checkTokenUser->save();
                        $user = $checkTokenUser;
                        $this->sendMessage($chatId, 'Đã liên kết thành công. /start để bắt đầu sử dụng.');
                    } else {
                        $this->sendMessage($chatId, 'Tài khoản chưa được cấp quyền truy cập bot. Hãy nhập token được gửi từ quản lý.');
                        return;
                    }
                } {
                    if ($user->lock == 2 || $user->lock == 3) {
                        $this->sendMessage($chatId, "Tài khoản đã bị khoá.");
                        return;
                    }

                    if ($user->roleid != 6) {
                        $this->sendMessage($chatId, "Phiên bản chỉ hỗ trợ cho member.");
                        return;
                    }

                    if ($this->bot_type == "agent_member_1" || $this->bot_type == "agent_member_2")
                        if ($user->roleid != 6) {
                            $this->sendMessage($chatId, "Phiên bản chỉ hỗ trợ cho member.");
                            return;
                        }
                    if ($this->bot_type == "admin_super_master")
                        if ($user->roleid != 1 && $user->roleid != 2 && $user->roleid != 4) {
                            $this->sendMessage($chatId, "Phiên bản chỉ hỗ trợ cho admin, super và master.");
                            return;
                        }
                    $this->appendMessageID2Queue($messageId,$chatId);
                    switch ($user->roleid) {
                        case 6:
                            $this->xosobot_member_message($chatId, $messageId, $message, $user);
                            break;

                        case 5:
                            $this->xosobot_agent_message($chatId, $messageId, $message, $user);
                            break;
                        default:
                            # code...
                            break;
                    }

                    $user->chat_id = $chatId;
                    $user->bot_tele_type = $this->bot_type;
                    $user->save();
                    return;
                }
            }
        } catch (\Exception $ex) {
            Log::info($ex->getMessage() . ' ' . $ex->getFile() . ' ' . $ex->getLine());
        }
    }

    public function xosobot_agent()
    {
        try {
            $update = json_decode(file_get_contents("php://input"), TRUE);
            if (!isset($this->quickbet)) {
                $this->quickbet = new QuickbetHelpers();
            }
            // Log::info($update);
            if ($this->callback($update)) {
                $cbId = $update["callback_query"]["from"]["id"]; //'';//
                $cbData = $update["callback_query"]["data"]; //'';//
                $cbText = $update["callback_query"]["message"]["text"];
                $cbMessageId = $update["callback_query"]["message"]["message_id"];
                $username = $update["callback_query"]["from"]["username"]; //'';//
                $roleIds = [5];
                // switch ($this->bot_type) {
                //     case 'agent_member':
                //         $roleIds = [6,5];
                //         break;
                //     case 'admin_super_master':
                //             $roleIds = [1,2,4];
                //             break;
                //     default:
                //         # code...
                //         break;
                // }
                $user = UserHelpers::GetUserByFullNameRoleIds($username, $roleIds);

                if (!isset($user)) {
                    $this->sendMessage($cbId, "Tài khoản chưa được liên kết hãy nhập token từ quản lý.");
                    return;
                } else if ($user->lock == 2 || $user->lock == 3) {
                    $this->sendMessage($cbId, "Tài khoản đã bị khoá.");
                    return;
                }

                if ($this->bot_type == "agent")
                    if ($user->roleid != 5) {
                        $this->sendMessage($cbId, "Phiên bản chỉ hỗ trợ cho member và agent.");
                        return;
                    }
                if ($this->bot_type == "admin_super_master")
                    if ($user->roleid != 1 && $user->roleid != 2 && $user->roleid != 4) {
                        $this->sendMessage($cbId, "Phiên bản chỉ hỗ trợ cho admin, super và master.");
                        return;
                    }
                $user->latestlogin = date("Y-m-d H:i:s");
                $user->chat_id = $cbId;
                $user->bot_tele_type = $this->bot_type;
                $user->save();
                switch ($user->roleid) {
                    case 6:
                        $this->xosobot_member_callback($cbId, $cbData, $cbMessageId, $user);
                        break;

                    case 5:
                        $this->xosobot_agent_callback($cbId, $cbData, $cbMessageId, $user);
                        break;
                    default:
                        # code...
                        break;
                }
                return;
            }

            if (isset($update["inline_query"])) {
                $chatId = $update["inline_query"]["from"]["id"]; //5381486859;//
                $username = $update["inline_query"]["from"]["username"]; //'';//
                $query = $update["inline_query"]["query"];
                $roleIds = [6, 5];
                switch ($this->bot_type) {
                    case 'agent':
                        $roleIds = [5];
                        break;
                    case 'admin_super_master':
                        $roleIds = [1, 2, 4];
                        break;
                    default:
                        # code...
                        break;
                }
                $user = UserHelpers::GetUserByFullNameRoleIds($username, $roleIds);
                if (!isset($user)) {
                    $this->sendMessage($chatId, "Tài khoản chưa được liên kết hãy nhập token từ quản lý.");
                } else
                if ($user->lock == 2 || $user->lock == 3)
                    $this->sendMessage($chatId, "Tài khoản đã bị khoá.");
                else
                    $this->sendMessage($chatId, $user->name);
                return;
            }

            if (isset($update["message"])) {
                $chatId = isset($update["message"]) ? $update["message"]["chat"]["id"] : ''; //5381486859;//
                $message = isset($update["message"]) ? $update["message"]["text"] : ''; //'';//
                $messageId = isset($update["message"]) ? $update["message"]["message_id"] : ''; //'';//
                $username = isset($update["message"]["from"]["username"]) ? $update["message"]["from"]["username"] : ''; //'';//
                if ($username == '') {
                    $this->sendMessage($chatId, 'Hãy cài đặt username của tài khoản telegram theo hướng dẫn https://www.youtube.com/watch?v=FKMTzgJ1Cww và /start để bắt đầu sử dụng.');
                    return;
                }
                $roleIds = [5];
                // switch ($this->bot_type) {
                //     case 'agent_member':
                //         $roleIds = [6,5];
                //         break;
                //     case 'admin_super_master':
                //             $roleIds = [1,2,4];
                //             break;
                //     default:
                //         # code...
                //         break;
                // }
                $user = UserHelpers::GetUserByFullNameRoleIds($username, $roleIds);
                if (!isset($user)) {
                    $checkTokenUser = User::where('token_bot_tele', $message)->whereIn('roleid', $roleIds)->first();

                    if (isset($checkTokenUser)) {
                        $checkTokenUser->fullname = $username;
                        $checkTokenUser->chat_id = $chatId;
                        $checkTokenUser->bot_tele_type = $this->bot_type;
                        $checkTokenUser->save();
                        $user = $checkTokenUser;
                        $this->sendMessage($chatId, 'Đã liên kết thành công. /start để bắt đầu sử dụng.');
                    } else {
                        $this->sendMessage($chatId, 'Tài khoản chưa được cấp quyền truy cập bot. Hãy nhập token được gửi từ quản lý.');
                        return;
                    }
                } {
                    if ($user->lock == 2 || $user->lock == 3) {
                        $this->sendMessage($chatId, "Tài khoản đã bị khoá.");
                        return;
                    }

                    if ($user->roleid != 5) {
                        $this->sendMessage($chatId, "Phiên bản chỉ hỗ trợ cho agent.");
                        return;
                    }

                    if ($this->bot_type == "agent")
                        if ($user->roleid != 5) {
                            $this->sendMessage($chatId, "Phiên bản chỉ hỗ trợ cho agent.");
                            return;
                        }
                    if ($this->bot_type == "admin_super_master")
                        if ($user->roleid != 1 && $user->roleid != 2 && $user->roleid != 4) {
                            $this->sendMessage($chatId, "Phiên bản chỉ hỗ trợ cho admin, super và master.");
                            return;
                        }

                    switch ($user->roleid) {
                        case 6:
                            $this->xosobot_member_message($chatId, $messageId, $message, $user);
                            break;

                        case 5:
                            $this->xosobot_agent_message($chatId, $messageId, $message, $user);
                            break;
                        default:
                            # code...
                            break;
                    }

                    $user->chat_id = $chatId;
                    $user->bot_tele_type = $this->bot_type;
                    $user->save();
                    return;
                }
            }
        } catch (\Exception $ex) {
            Log::info($ex->getMessage() . ' ' . $ex->getFile() . ' ' . $ex->getLine());
        }
    }

    public function xosobot_trolymb()
    {
        try {
            $update = json_decode(file_get_contents("php://input"), TRUE);
            if (!isset($this->quickbet)) {
                $this->quickbet = new QuickbetHelpers();
            }
            // Log::info($update);
            if ($this->callback($update)) {
                $cbId = $update["callback_query"]["from"]["id"]; //'';//
                $cbData = $update["callback_query"]["data"]; //'';//
                $cbText = $update["callback_query"]["message"]["text"];
                $cbMessageId = $update["callback_query"]["message"]["message_id"];
                $username = $update["callback_query"]["from"]["username"]; //'';//
                $roleIds = [6];
                // switch ($this->bot_type) {
                //     case 'agent_member':
                //         $roleIds = [6,5];
                //         break;
                //     case 'admin_super_master':
                //             $roleIds = [1,2,4];
                //             break;
                //     default:
                //         # code...
                //         break;
                // }
                $user = UserHelpers::GetUserByFullNameRoleIds($username, $roleIds);

                if (!isset($user)) {
                    $this->sendMessage($cbId, "Tài khoản chưa được liên kết hãy nhập token từ quản lý.");
                    return;
                } else if ($user->lock == 2 || $user->lock == 3) {
                    $this->sendMessage($cbId, "Tài khoản đã bị khoá.");
                    return;
                }

                if ($this->bot_type == "trolymb")
                    if ($user->roleid != 6) {
                        $this->sendMessage($cbId, "Phiên bản chỉ hỗ trợ cho member.");
                        return;
                    }

                $user->latestlogin = date("Y-m-d H:i:s");
                $user->chat_id = $cbId;
                $user->bot_tele_type = $this->bot_type;
                $user->save();
                switch ($user->roleid) {
                    case 6:
                        $this->xosobot_member_trolyao_callback($cbId, $cbData, $cbMessageId, $user);
                        break;
                    default:
                        # code...
                        break;
                }
                return;
            }

            if (isset($update["inline_query"])) {
                $chatId = $update["inline_query"]["from"]["id"]; //5381486859;//
                $username = $update["inline_query"]["from"]["username"]; //'';//
                $query = $update["inline_query"]["query"];
                $roleIds = [6];
                switch ($this->bot_type) {
                    case 'trolymb':
                        $roleIds = [6];
                        break;
                    default:
                        # code...
                        break;
                }
                $user = UserHelpers::GetUserByFullNameRoleIds($username, $roleIds);
                if (!isset($user)) {
                    $this->sendMessage($chatId, "Bạn chưa kích hoạt trợ lý ảo. Hãy nhập key ở dưới để kích hoạt !");
                } else
                if ($user->lock == 2 || $user->lock == 3)
                    $this->sendMessage($chatId, "Tài khoản đã bị khoá.");
                else
                    $this->sendMessage($chatId, $user->name);
                return;
            }

            if (isset($update["message"])) {
                $chatId = isset($update["message"]) ? $update["message"]["chat"]["id"] : ''; //5381486859;//
                $message = isset($update["message"]) ? $update["message"]["text"] : ''; //'';//
                $messageId = isset($update["message"]) ? $update["message"]["message_id"] : ''; //'';//
                $username = isset($update["message"]["from"]["username"]) ? $update["message"]["from"]["username"] : ''; //'';//
                if ($username == '') {
                    $this->sendMessage($chatId, 'Bạn chưa có Username telegram. Hãy đặt Username và khởi động lại trợ lý ảo. Cài đặt username của tài khoản telegram theo hướng dẫn https://www.youtube.com/watch?v=FKMTzgJ1Cww và /start để bắt đầu sử dụng.');
                    return;
                }
                $roleIds = [6];
                // switch ($this->bot_type) {
                //     case 'agent_member':
                //         $roleIds = [6,5];
                //         break;
                //     case 'admin_super_master':
                //             $roleIds = [1,2,4];
                //             break;
                //     default:
                //         # code...
                //         break;
                // }
                $user = UserHelpers::GetUserByFullNameRoleIds($username, $roleIds);
                if (!isset($user)) {
                    $checkTokenUser = User::where('token_bot_tele', $message)->whereIn('roleid', $roleIds)->first();

                    if (isset($checkTokenUser)) {
                        $checkTokenUser->fullname = $username;
                        $checkTokenUser->chat_id = $chatId;
                        $checkTokenUser->bot_tele_type = $this->bot_type;
                        $checkTokenUser->save();
                        $user = $checkTokenUser;
                        $this->sendMessage($chatId, 'Trợ lý ảo đang hoạt động. Xin nhập yêu cầu bên dưới. (/start để bắt đầu sử dụng)');
                    } else {
                        $this->sendMessage($chatId, "Bạn chưa kích hoạt trợ lý ảo. Hãy nhập key ở dưới để kích hoạt !");
                        return;
                    }
                } {
                    if ($user->lock == 2 || $user->lock == 3) {
                        $this->sendMessage($chatId, "Tài khoản đã bị khoá.");
                        return;
                    }

                    if ($user->roleid != 6) {
                        $this->sendMessage($chatId, "Phiên bản chỉ hỗ trợ cho member.");
                        return;
                    }

                    switch ($user->roleid) {
                        case 6:
                            $this->xosobot_member_trolymb_message($chatId, $messageId, $message, $user);
                            break;
                        default:
                            # code...
                            break;
                    }

                    $user->chat_id = $chatId;
                    $user->bot_tele_type = $this->bot_type;
                    $user->save();
                    return;
                }
            }
        } catch (\Exception $ex) {
            Log::info($ex->getMessage() . ' ' . $ex->getFile() . ' ' . $ex->getLine());
        }
    }

    public function xosobot_admin_super_master()
    {
        try {
            $update = json_decode(file_get_contents("php://input"), TRUE);
            if (!isset($this->quickbet)) {
                $this->quickbet = new QuickbetHelpers();
            }
            // Log::info($update);
            if ($this->callback($update)) {
                $cbId = $update["callback_query"]["from"]["id"]; //'';//
                $cbData = $update["callback_query"]["data"]; //'';//
                $cbText = $update["callback_query"]["message"]["text"];
                $cbMessageId = $update["callback_query"]["message"]["message_id"];
                $username = $update["callback_query"]["from"]["username"]; //'';//
                $roleIds = [1, 2, 4];
                // switch ($this->bot_type) {
                //     case 'agent_member':
                //         $roleIds = [6,5];
                //         break;
                //     case 'admin_super_master':
                //             $roleIds = [1,2,4];
                //             break;
                //     default:
                //         # code...
                //         break;
                // }
                $user = UserHelpers::GetUserByFullNameRoleIds($username, $roleIds);

                if (!isset($user)) {
                    $this->sendMessage($cbId, "Tài khoản chưa được liên kết hãy nhập token từ quản lý.");
                    return;
                } else if ($user->lock == 2 || $user->lock == 3) {
                    $this->sendMessage($cbId, "Tài khoản đã bị khoá.");
                    return;
                }

                // if($this->bot_type == "agent_member")
                //     if ($user->roleid != 6 && $user->roleid != 5) {
                //         $this->sendMessage($cbId, "Phiên bản chỉ hỗ trợ cho member và agent.");
                //         return;
                //     }
                // if($this->bot_type == "admin_super_master")
                if ($user->roleid != 1 && $user->roleid != 2 && $user->roleid != 4) {
                    $this->sendMessage($cbId, "Phiên bản chỉ hỗ trợ cho admin, super và master.");
                    return;
                }
                $user->latestlogin = date("Y-m-d H:i:s");
                $user->chat_id = $cbId;
                $user->bot_tele_type = $this->bot_type;
                $user->save();

                $this->xosobot_asm_callback($cbId, $cbData, $cbMessageId, $user);
                // switch ($user->roleid) {
                //     case 6:
                //         $this->xosobot_member_callback($cbId, $cbData, $cbMessageId, $user);
                //         break;

                //     case 5:
                //         $this->xosobot_agent_callback($cbId, $cbData, $cbMessageId, $user);
                //         break;
                //     default:
                //         # code...
                //         break;
                // }
                return;
            }

            if (isset($update["inline_query"])) {
                $chatId = $update["inline_query"]["from"]["id"]; //5381486859;//
                $username = $update["inline_query"]["from"]["username"]; //'';//
                $query = $update["inline_query"]["query"];
                $roleIds = [1, 2, 4];
                // switch ($this->bot_type) {
                //     case 'agent_member':
                //         $roleIds = [6,5];
                //         break;
                //     case 'admin_super_master':
                //             $roleIds = [1,2,4];
                //             break;
                //     default:
                //         # code...
                //         break;
                // }
                $user = UserHelpers::GetUserByFullNameRoleIds($username, $roleIds);
                if (!isset($user)) {
                    $this->sendMessage($chatId, "Tài khoản chưa được liên kết hãy nhập token từ quản lý.");
                } else
                if ($user->lock == 2 || $user->lock == 3)
                    $this->sendMessage($chatId, "Tài khoản đã bị khoá.");
                else
                    $this->sendMessage($chatId, $user->name);
                return;
            }

            if (isset($update["message"])) {
                $chatId = isset($update["message"]) ? $update["message"]["chat"]["id"] : ''; //5381486859;//
                $message = isset($update["message"]) ? $update["message"]["text"] : ''; //'';//
                $messageId = isset($update["message"]) ? $update["message"]["message_id"] : ''; //'';//
                $username = isset($update["message"]) ? $update["message"]["from"]["username"] : ''; //'';//
                $roleIds = [1, 2, 4];
                // switch ($this->bot_type) {
                //     case 'agent_member':
                //         $roleIds = [6,5];
                //         break;
                //     case 'admin_super_master':
                //             $roleIds = [1,2,4];
                //             break;
                //     default:
                //         # code...
                //         break;
                // }
                $user = UserHelpers::GetUserByFullNameRoleIds($username, $roleIds);
                if (!isset($user)) {
                    $checkTokenUser = User::where('token_bot_tele', $message)->whereIn('roleid', $roleIds)->first();

                    if (isset($checkTokenUser)) {
                        $checkTokenUser->fullname = $username;
                        $checkTokenUser->chat_id = $chatId;
                        $checkTokenUser->bot_tele_type = $this->bot_type;
                        $checkTokenUser->save();
                        $user = $checkTokenUser;
                        $this->sendMessage($chatId, 'Đã liên kết thành công. /start để bắt đầu sử dụng.');
                    } else {
                        $this->sendMessage($chatId, 'Tài khoản chưa được cấp quyền truy cập bot. Hãy nhập token được gửi từ quản lý.');
                        return;
                    }
                } {
                    if ($user->lock == 2 || $user->lock == 3) {
                        $this->sendMessage($chatId, "Tài khoản đã bị khoá.");
                        return;
                    }

                    // if($this->bot_type == "agent_member")
                    //     if ($user->roleid != 6 && $user->roleid != 5) {
                    //         $this->sendMessage($chatId, "Phiên bản chỉ hỗ trợ cho member và agent.");
                    //         return;
                    //     }
                    // if($this->bot_type == "admin_super_master")
                    if ($user->roleid != 1 && $user->roleid != 2 && $user->roleid != 4) {
                        $this->sendMessage($chatId, "Phiên bản chỉ hỗ trợ cho admin, super và master.");
                        return;
                    }

                    $this->xosobot_agent_message($chatId, $messageId, $message, $user);

                    // $this->sendMessage($chatId, $chatId);
                    $user->chat_id = $chatId;
                    $user->bot_tele_type = $this->bot_type;
                    $user->save();
                    // switch ($user->roleid) {
                    //     case 6:
                    //         $this->xosobot_member_message($chatId, $messageId, $message, $user);
                    //         break;

                    //     case 5:
                    //         $this->xosobot_agent_message($chatId, $messageId, $message, $user);
                    //         break;
                    //     default:
                    //         # code...
                    //         break;
                    // }

                    return;
                }
            }
        } catch (\Exception $ex) {
            Log::info($ex->getMessage() . ' ' . $ex->getFile() . ' ' . $ex->getLine());
        }
    }

    public function ketqua($chatId, $message_id, $user, $date = null)
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "ketqua"], env('CACHE_TIME_BOT', 24 * 60));
        if ($date == null) $date = date('Y-m-d');
        $now = $date;
        $kqxs = null;

        $datetime = new DateTime('yesterday');
        $yesterday = $datetime->format('Y-m-d');
        $message = "";
        $xs = new XoSo();
        if ($now == date('Y-m-d')) {
            if (date("H") > 18 || (date("H") == 18 && date("i") > 13)) {
                $message = "<i>Xổ số Miền Bắc</i>   <b>Ngày " . date('d-m-Y', strtotime($now)) . "</b> \n";
                $kqxs = $xs->getKetQuaToArr(1, $now);
                // XoSoResult::where('location_id', 1)
                //     ->where('date', $now)->get();
            } else {
                $message = "<i>Xổ số Miền Bắc</i>   <b>Ngày " . date('d-m-Y', strtotime($yesterday)) . "</b> \n";
                $kqxs = $xs->getKetQuaToArr(1, $yesterday);
                // $kqxs = XoSoResult::where('location_id', 1)
                //     ->where('date', $yesterday)->get();
            }
        } else {
            $message = "<i>Xổ số Miền Bắc</i>   <b>Ngày " . date('d-m-Y', strtotime($now)) . "</b> \n";
            $kqxs = $xs->getKetQuaToArr(1, $now);
            // $kqxs = XoSoResult::where('location_id', 1)
            //     ->where('date', $now)->get();
        }

        // Xổ số Miền Bắc - Ngày 27/03/2023
        //        3PL-15PL-9PL-2PL-7PL-11PL
        // Giải Đặc biệt:              83230
        // Giải Nhất:                    27431
        // Giải Nhì:          64284       |           25717
        // Giải Ba: 00542     |     39077    |     03807 
        //             48505     |      81972    |     52585
        // Giải Tư: 3688   |   5158   |  4819   |    5996
        // Giải Năm: 1953    |     7838      |     0600                         
        //                 4430     |    1433       |    9408
        // Giải Sáu:   989       |      736       |     316
        // Giải Bảy: 91     |    85      |      30    |    55
        // Thần tài:                         4054
        // //Log::info($kqxs);
        // $now = DateTime::createFromFormat('U.u', microtime(true));
        $t = explode(" ", microtime());
        $message .= "<i>Ký hiệu Đặc biệt:</i> <b>" . $kqxs['spec_character'] . "</b>\n";
        $message .= "<i>Đặc biệt:</i>              <b>" . $kqxs['DB'] . "</b>\n";
        $message .= "<i>Nhất:</i>                    <b>" . $kqxs['1'] . "</b>\n";
        $message .= "<i>Nhì:</i>          <b>" . $kqxs['2'][0] . "</b>       |           <b>" . $kqxs['2'][1]   . "</b>\n";
        $message .= "<i>Ba:</i> <b>" . $kqxs['3'][0] . "</b>     |     <b>" . $kqxs['3'][1] . "</b>     |     <b>" . $kqxs['3'][2] . "</b>\n";
        $message .= "       <b>" . $kqxs['3'][3] . "</b>     |     <b>" . $kqxs['3'][4] . "</b>     |     <b>" . $kqxs['3'][5] . "</b>\n";
        $message .= "<i>Tư:</i> <b>" . $kqxs['4'][0] . "</b>   |   <b>" . $kqxs['4'][1] . "</b>   |   <b>" . $kqxs['4'][2] . "</b>   |   <b>" . $kqxs['4'][3] . "</b>\n";
        $message .= "<i>Năm:</i> <b>" . $kqxs['5'][0] . "</b>    |     <b>" . $kqxs['5'][1] . "</b>    |     <b>" . $kqxs['5'][2] . "</b>\n";
        $message .= "           <b>" . $kqxs['5'][3] . "</b>    |     <b>" . $kqxs['5'][4] . "</b>    |     <b>" . $kqxs['5'][5] . "</b>\n";
        $message .= "<i>Sáu:</i>   <b>" . $kqxs['6'][0] . "</b>       |      <b>" . $kqxs['6'][1] . "</b>       |      <b>" . $kqxs['6'][2] . "</b>\n";
        $message .= "<i>Bảy:</i>    <b>" . $kqxs['7'][0] . "</b>     |    <b>" . $kqxs['7'][1] . "</b>     |    <b>" . $kqxs['7'][2] . "</b>     |    <b>" . $kqxs['7'][3] . "</b>\n";
        $message .= "<i>Thần tài:</i>                  <b>" . $kqxs['than_tai'] . "</b>\n";
        $message .= "<i>Cập nhật:</i> <b>" . date("H:i:s", $t[1]) . substr((string)$t[0], 1, 3) . "</b>" . "\n";
        // $this->sendMessage($chatId, $message);
        $this->editMessageReplyMarkup($chatId, $message_id, $message, ['inline_keyboard' => $this->keyboardKetqua]);
    }

    public function thongso($chatId, $message_id, $user, $mode = "edit")
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "thongso"], env('CACHE_TIME_BOT', 24 * 60));

        // $data = [["Game", "Buy", "Odd", "Max bet", "Max 1 bet"]];

        // $games = GameHelpers::GetAllGameByCusType($user->customer_type, $user->id, 0);
        // $count = 0;
        // foreach ($games as $game) {
        //     if ($game['game_code'] >= 100) {
        //         $count++;
        //         continue;
        //     }
        //     if ($game['game_code'] >= 31 && $game['game_code'] <= 55) {
        //         $count++;
        //         continue;
        //     }
        //     if ($game['game_code'] == 18) continue;
        //     array_push($data, [$this->vn_to_str($game['game_name']), number_format($game['exchange_rates']), number_format($game['odds']), number_format($game['max_point']), number_format($game['max_point_one'])]);
        // }

        // $table = Tableify::new($data);
        // $table = $table->right()->make();
        // $table_data = $table->toArray();
        // $mess = "Thông số tài khoản " . $user->name . " Chuẩn " . $user->customer_type . "\n";
        // $mess .= "<pre>";
        // $table = Tableify::new($data);
        // $table = $table->right()->make();
        // $table_data = $table->toArray();
        // foreach ($table_data as $row) {
        //     $mess .= $row . "\n";
        // }
        // $mess .= "</pre>";
        $mess = "Thông số tài khoản:" . " Chuẩn " . $user->customer_type;

        if ($mode != "edit")
            $this->sendMessage($chatId, $mess);
        else {
            $useLowPrice = Cache::get('useLowPrice_bot_tele' . $user->id, false);
            $customKeyboardThongso =
                array(
                    array(
                        array('text' => 'Giá mua', 'callback_data' => 'giamua'),
                        array('text' => 'Giới hạn cược', 'callback_data' => 'gioihancuoc'),
                    ),
                    array(
                        array('text' => 'Giá thấp: Đang ' . ($useLowPrice ? "Bật" : "Tắt"), 'callback_data' => 'caidatgiathap'),
                        array('text' => 'Thông số giá thấp', 'callback_data' => 'thongsogt'),
                    ),
                    array(
                        array('text' => 'Chỉnh thông số giá thấp', 'callback_data' => 'chinhthongsogt'),
                    ),
                    array(
                        array('text' => '< Back', 'callback_data' => 'back'),
                    )
                );
            $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $customKeyboardThongso]);
        }
    }

    public function thongsogioihancuoc($chatId, $message_id, $user, $mode = "edit")
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "thongso", "gioihancuoc"], env('CACHE_TIME_BOT', 24 * 60));

        $data = [["Thể loại", "TĐ/1cược", "Tối đa"]];

        $games = GameHelpers::GetAllGameByCusType($user->customer_type, $user->id, 0);
        $count = 0;
        foreach ($games as $game) {
            if ($game['game_code'] >= 100) {
                $count++;
                continue;
            }
            if ($game['game_code'] >= 31 && $game['game_code'] <= 55) {
                $count++;
                continue;
            }
            if ($game['game_code'] == 18) continue;
            array_push($data, [$game['game_name'], number_format($game['max_point_one']), number_format($game['max_point'])]);
        }

        $mess = "Bảng giới hạn cược" . "\n";
        $mess .= "<pre>";
        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();
        foreach ($table_data as $row) {
            $mess .= $row . "\n";
        }
        $mess .= "</pre>";

        if ($mode != "edit")
            $this->sendMessage($chatId, $mess);
        else
            $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $this->keyboardOnlyBack]);
    }

    public function thongsogiamua($chatId, $message_id, $user, $mode = "edit")
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "thongso", "giamua"], env('CACHE_TIME_BOT', 24 * 60));

        $data = [["Thể loại", "Giá", "Trả thưởng"]];

        $games = GameHelpers::GetAllGameByCusType($user->customer_type, $user->id, 0);
        $count = 0;
        foreach ($games as $game) {
            if ($game['game_code'] >= 100) {
                $count++;
                continue;
            }
            if ($game['game_code'] >= 31 && $game['game_code'] <= 55) {
                $count++;
                continue;
            }
            if ($game['game_code'] == 18) continue;
            array_push($data, [$game['game_name'], number_format($game['exchange_rates']), number_format($game['odds'])]);
        }

        $mess = "Bảng thông số" . "\n";
        $mess .= "<pre>";
        $table = Tableify::new($data);
        // Tableify::new($data)->center()->seperatorPadding(2)->seperator('*')->headerCharacter('@')->make()->toArray();
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();
        foreach ($table_data as $row) {
            $mess .= $row . "\n";
        }
        $mess .= "</pre>";

        if ($mode != "edit")
            $this->sendMessage($chatId, $mess);
        else
            $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $this->keyboardOnlyBack]);
    }

    public function thongsogiamuamember($chatId, $message_id, $user, $userId, $mode = "edit")
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "quanlymember", "thaotac", $user->id, "giamua"], env('CACHE_TIME_BOT', 24 * 60));

        $userTarget = UserHelpers::GetUserById($userId);

        $data = [["Thể loại", "Giá", "Trả thưởng"]];

        $games = GameHelpers::GetAllGameByCusType($userTarget->customer_type, $userTarget->id, 0);
        $count = 0;
        foreach ($games as $game) {
            if ($game['game_code'] >= 100) {
                $count++;
                continue;
            }
            if ($game['game_code'] >= 31 && $game['game_code'] <= 55) {
                $count++;
                continue;
            }
            if ($game['game_code'] == 18) continue;
            array_push($data, [$game['game_name'], number_format($game['exchange_rates']), number_format($game['odds'])]);
        }

        $mess = "Bảng giá " . $userTarget->name . "\n";
        $mess .= "<pre>";
        $table = Tableify::new($data);
        // Tableify::new($data)->center()->seperatorPadding(2)->seperator('*')->headerCharacter('@')->make()->toArray();
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();
        foreach ($table_data as $row) {
            $mess .= $row . "\n";
        }
        $mess .= "</pre>";

        $keyboardChinhgiamua =
            array(
                array(
                    array('text' => 'Chỉnh giá mua', 'callback_data' => 'chinhgiamua_member_' . $userTarget->id),
                ),
                array(
                    array('text' => '< Back', 'callback_data' => 'thaotactaikhoan_' . $userTarget->id),
                )
            );

        if ($mode != "edit")
            $this->sendMessage($chatId, $mess);
        else
            $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $keyboardChinhgiamua]);
    }

    public function thongsogioihancuocmember($chatId, $message_id, $user, $userId, $mode = "edit")
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "quanlymember", "thaotac", $user->id, "gioihancuoc"], env('CACHE_TIME_BOT', 24 * 60));

        $userTarget = UserHelpers::GetUserById($userId);

        $data = [["Thể loại", "TĐ/1cược", "Tối đa"]];

        $games = GameHelpers::GetAllGameByCusType($userTarget->customer_type, $userTarget->id, 0);
        $count = 0;
        foreach ($games as $game) {
            if ($game['game_code'] >= 100) {
                $count++;
                continue;
            }
            if ($game['game_code'] >= 31 && $game['game_code'] <= 55) {
                $count++;
                continue;
            }
            if ($game['game_code'] == 18) continue;
            array_push($data, [$game['game_name'], number_format($game['max_point_one']), number_format($game['max_point'])]);
        }

        $mess = "Giới hạn cược " . $userTarget->name . "\n";
        $mess .= "<pre>";
        $table = Tableify::new($data);
        // Tableify::new($data)->center()->seperatorPadding(2)->seperator('*')->headerCharacter('@')->make()->toArray();
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();
        foreach ($table_data as $row) {
            $mess .= $row . "\n";
        }
        $mess .= "</pre>";

        $keyboardChinhgiamua =
            array(
                array(
                    array('text' => 'Chỉnh Tối đa/1 cược', 'callback_data' => 'chinhgioihancuoc_toida1cuoc_member_' . $userTarget->id),
                ),
                array(
                    array('text' => 'Chỉnh Tối đa', 'callback_data' => 'chinhgioihancuoc_toida_member_' . $userTarget->id),
                ),
                array(
                    array('text' => '< Back', 'callback_data' => 'thaotactaikhoan_' . $userTarget->id),
                )
            );

        if ($mode != "edit")
            $this->sendMessage($chatId, $mess);
        else
            $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $keyboardChinhgiamua]);
    }

    public function thongsogt($chatId, $message_id, $user, $mode = "edit")
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "thongso", "thongsogt"], env('CACHE_TIME_BOT', 24 * 60));

        $data = [["Thể loại", "Giá gốc", "Giá thấp"]];

        $games1 = GameHelpers::GetAllGameByCusType($user->customer_type, $user->id, 0);
        $games2 = GameHelpers::GetAllGameParentByCusType($user->customer_type, $user->id);
        $count = 0;
        foreach ($games1 as $game) {
            if ($game['game_code'] >= 100) {
                $count++;
                continue;
            }
            if ($game['game_code'] >= 31 && $game['game_code'] <= 55) {
                $count++;
                continue;
            }
            if ($game['game_code'] == 18) {
                $count++;
                continue;
            }
            array_push($data, [$game['game_name'], number_format($game['exchange_rates']), number_format($games2[$count]['exchange_rates'])]);
            $count++;
            //,number_format($game['odds']),number_format($game['max_point']),number_format($game['max_point_one'])
        }

        $mess = "Thông số giá thấp" . "\n";
        $mess .= "<pre>";
        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();
        foreach ($table_data as $row) {
            $mess .= $row . "\n";
        }
        $mess .= "</pre>";

        if ($mode != "edit")
            $this->sendMessage($chatId, $mess);
        else
            $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $this->keyboardThongsogt]);
    }

    public function chinhthongsogt($chatId, $message_id, $user, $mode = "edit")
    {
        Cache::put('stack_action_bot_tele' . $user->id, ["chinhthongsogt"], env('CACHE_TIME_BOT', 24 * 60));
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "thongso", "chinhthongsogt"], env('CACHE_TIME_BOT', 24 * 60));
        $mess = "Chọn thể loại chỉnh giá thấp";

        $games = GameHelpers::GetAllGameParentByCusType($user->customer_type, $user->id);
        $keyboard = array();

        $temp = array();
        $count = 0;
        foreach ($games as $game) {
            if ($game['game_code'] >= 100) continue;
            if ($game['game_code'] >= 31 && $game['game_code'] <= 55) continue;
            array_push($temp, array('text' => $game->name, 'callback_data' => 'chinhthongsogt_' . $game['game_code']));
            $count++;
            if ($count == 4) {
                array_push($keyboard, $temp);
                $temp = array();
                $count = 0;
            }
        }

        if ($count > 0) {
            array_push($keyboard, $temp);
            $temp = array();
            $count = 0;
        }

        array_push($temp, array('text' => '< Back', 'callback_data' => 'back'));
        array_push($keyboard, $temp);

        if ($mode != "edit")
            $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboard]);
        else
            $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $keyboard]);
    }

    public function chinhgiamuamember($chatId, $message_id, $user, $userId, $mode = "edit")
    {
        Cache::put('stack_action_bot_tele' . $user->id, ["chinhgiamuamember_"], env('CACHE_TIME_BOT', 24 * 60));
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "thongso", "chinhthongsogt"], env('CACHE_TIME_BOT', 24 * 60));
        $mess = "Chọn thể loại chỉnh giá";
        $userTarget = UserHelpers::GetUserById($userId);
        $games = GameHelpers::GetAllGameParentByCusType($userTarget->customer_type, $userTarget->id);
        $keyboard = array();

        $temp = array();
        $count = 0;
        foreach ($games as $game) {
            if ($game['game_code'] >= 100) continue;
            if ($game['game_code'] >= 31 && $game['game_code'] <= 55) continue;
            array_push($temp, array('text' => $game->name, 'callback_data' => 'chinhgiamuamember_' . $userId . "_" . $game['game_code']));
            $count++;
            if ($count == 4) {
                array_push($keyboard, $temp);
                $temp = array();
                $count = 0;
            }
        }

        if ($count > 0) {
            array_push($keyboard, $temp);
            $temp = array();
            $count = 0;
        }

        array_push($temp, array('text' => '< Back', 'callback_data' => 'bangtaikhoanmembergiamua_' . $userId));
        array_push($keyboard, $temp);

        if ($mode != "edit")
            $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboard]);
        else
            $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $keyboard]);
    }

    public function chinhgiamuaagent($chatId, $message_id, $user, $customer_type, $mode = "edit")
    {
        Cache::put('stack_action_bot_tele' . $user->id, ["chinhgiamuaagent_"], env('CACHE_TIME_BOT', 24 * 60));
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "thongso", "chinhthongsogt"], env('CACHE_TIME_BOT', 24 * 60));
        $mess = "Chọn thể loại chỉnh giá";
        $games = GameHelpers::GetAllGameParentByCusType($customer_type, $user->id);
        $keyboard = array();

        $temp = array();
        $count = 0;
        foreach ($games as $game) {
            if ($game['game_code'] >= 100) continue;
            if ($game['game_code'] >= 31 && $game['game_code'] <= 55) continue;
            array_push($temp, array('text' => $game->name, 'callback_data' => 'chinhgiamuaagent_' . $customer_type . "_" . $game['game_code']));
            $count++;
            if ($count == 4) {
                array_push($keyboard, $temp);
                $temp = array();
                $count = 0;
            }
        }

        if ($count > 0) {
            array_push($keyboard, $temp);
            $temp = array();
            $count = 0;
        }

        array_push($temp, array('text' => '< Back', 'callback_data' => 'bangthaotacchuan_customertype_' . $customer_type));
        array_push($keyboard, $temp);

        if ($mode != "edit")
            $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboard]);
        else
            $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $keyboard]);
    }

    public function chinhgioihancuoc_toida1cuoc_member($chatId, $message_id, $user, $userId, $mode = "edit")
    {
        Cache::put('stack_action_bot_tele' . $user->id, ["chinhtoida1cuocmember_"], env('CACHE_TIME_BOT', 24 * 60));
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "thongso", "chinhthongsogt"], env('CACHE_TIME_BOT', 24 * 60));
        $mess = "Chọn thể loại chỉnh Tối đa/ 1 cược";
        $userTarget = UserHelpers::GetUserById($userId);
        $games = GameHelpers::GetAllGameParentByCusType($userTarget->customer_type, $userTarget->id);
        $keyboard = array();

        $temp = array();
        $count = 0;
        foreach ($games as $game) {
            if ($game['game_code'] >= 100) continue;
            if ($game['game_code'] >= 31 && $game['game_code'] <= 55) continue;
            array_push($temp, array('text' => $game->name, 'callback_data' => 'chinhtoida1cuocmember_' . $userId . "_" . $game['game_code']));
            $count++;
            if ($count == 4) {
                array_push($keyboard, $temp);
                $temp = array();
                $count = 0;
            }
        }

        if ($count > 0) {
            array_push($keyboard, $temp);
            $temp = array();
            $count = 0;
        }

        array_push($temp, array('text' => '< Back', 'callback_data' => 'bangtaikhoanmembergioihancuoc_' . $userId));
        array_push($keyboard, $temp);

        if ($mode != "edit")
            $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboard]);
        else
            $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $keyboard]);
    }

    public function chinhgioihancuoc_toida1cuoc_agent($chatId, $message_id, $user, $userId, $mode = "edit")
    {
        Cache::put('stack_action_bot_tele' . $user->id, ["chinhtoida1cuocagent_"], env('CACHE_TIME_BOT', 24 * 60));
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "thongso", "chinhthongsogt"], env('CACHE_TIME_BOT', 24 * 60));
        $mess = "Chọn thể loại chỉnh Tối đa/ 1 cược";
        $userTarget = UserHelpers::GetUserById($userId);
        $games = GameHelpers::GetAllGameParentByCusType($userTarget->customer_type, $userTarget->id);
        $keyboard = array();

        $temp = array();
        $count = 0;
        foreach ($games as $game) {
            if ($game['game_code'] >= 100) continue;
            if ($game['game_code'] >= 31 && $game['game_code'] <= 55) continue;
            array_push($temp, array('text' => $game->name, 'callback_data' => 'chinhtoida1cuocagent_' . $userId . "_" . $game['game_code']));
            $count++;
            if ($count == 4) {
                array_push($keyboard, $temp);
                $temp = array();
                $count = 0;
            }
        }

        if ($count > 0) {
            array_push($keyboard, $temp);
            $temp = array();
            $count = 0;
        }

        array_push($temp, array('text' => '< Back', 'callback_data' => 'bangtaikhoanmembergioihancuoc_' . $userId));
        array_push($keyboard, $temp);

        if ($mode != "edit")
            $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboard]);
        else
            $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $keyboard]);
    }

    public function chinhgioihancuoc_toida_member($chatId, $message_id, $user, $userId, $mode = "edit")
    {
        Cache::put('stack_action_bot_tele' . $user->id, ["chinhtoidamember_"], env('CACHE_TIME_BOT', 24 * 60));
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "thongso", "chinhthongsogt"], env('CACHE_TIME_BOT', 24 * 60));
        $mess = "Chọn thể loại chỉnh Tối đa cược";
        $userTarget = UserHelpers::GetUserById($userId);
        $games = GameHelpers::GetAllGameParentByCusType($userTarget->customer_type, $userTarget->id);
        $keyboard = array();

        $temp = array();
        $count = 0;
        foreach ($games as $game) {
            if ($game['game_code'] >= 100) continue;
            if ($game['game_code'] >= 31 && $game['game_code'] <= 55) continue;
            array_push($temp, array('text' => $game->name, 'callback_data' => 'chinhtoidamember_' . $userId . "_" . $game['game_code']));
            $count++;
            if ($count == 4) {
                array_push($keyboard, $temp);
                $temp = array();
                $count = 0;
            }
        }

        if ($count > 0) {
            array_push($keyboard, $temp);
            $temp = array();
            $count = 0;
        }

        array_push($temp, array('text' => '< Back', 'callback_data' => 'bangtaikhoanmembergioihancuoc_' . $userId));
        array_push($keyboard, $temp);

        if ($mode != "edit")
            $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboard]);
        else
            $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $keyboard]);
    }

    public function chinhgioihancuoc_toida_agent($chatId, $message_id, $user, $userId, $mode = "edit")
    {
        Cache::put('stack_action_bot_tele' . $user->id, ["chinhtoidaagent_"], env('CACHE_TIME_BOT', 24 * 60));
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "thongso", "chinhthongsogt"], env('CACHE_TIME_BOT', 24 * 60));
        $mess = "Chọn thể loại chỉnh Tối đa cược";
        $userTarget = UserHelpers::GetUserById($userId);
        $games = GameHelpers::GetAllGameParentByCusType($userTarget->customer_type, $userTarget->id);
        $keyboard = array();

        $temp = array();
        $count = 0;
        foreach ($games as $game) {
            if ($game['game_code'] >= 100) continue;
            if ($game['game_code'] >= 31 && $game['game_code'] <= 55) continue;
            array_push($temp, array('text' => $game->name, 'callback_data' => 'chinhtoidaagent_' . $userId . "_" . $game['game_code']));
            $count++;
            if ($count == 4) {
                array_push($keyboard, $temp);
                $temp = array();
                $count = 0;
            }
        }

        if ($count > 0) {
            array_push($keyboard, $temp);
            $temp = array();
            $count = 0;
        }

        array_push($temp, array('text' => '< Back', 'callback_data' => 'bangtaikhoanmembergioihancuoc_' . $userId));
        array_push($keyboard, $temp);

        if ($mode != "edit")
            $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboard]);
        else
            $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $keyboard]);
    }

    public function caidatgiathap($chatId, $message_id, $user, $mode = "edit")
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "thongso"], env('CACHE_TIME_BOT', 24 * 60));

        $mess = "Thông số tài khoản " . $user->name . " Chuẩn " . $user->customer_type;

        if ($mode != "edit")
            $this->sendMessage($chatId, $mess);
        else {
            $currentUseLowPrice = Cache::get('useLowPrice_bot_tele' . $user->id, false);
            $newUseLowPrice = !$currentUseLowPrice;
            Cache::put('useLowPrice_bot_tele' . $user->id, $newUseLowPrice, env('CACHE_TIME_BOT', 24 * 60));

            $customKeyboardThongso =
                array(
                    array(
                        array('text' => 'Giá mua', 'callback_data' => 'giamua'),
                        array('text' => 'Giới hạn cược', 'callback_data' => 'gioihancuoc'),
                    ),
                    array(
                        array('text' => 'Giá thấp: Đang ' . ($newUseLowPrice ? "Bật" : "Tắt"), 'callback_data' => 'caidatgiathap'),
                        array('text' => 'Thông số giá thấp', 'callback_data' => 'thongsogt'),
                    ),
                    array(
                        array('text' => 'Chỉnh thông số giá thấp', 'callback_data' => 'chinhthongsogt'),
                    ),
                    array(
                        array('text' => '< Back', 'callback_data' => 'back'),
                    )
                );
            $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $customKeyboardThongso]);
        }
    }

    public function caidatthongbaotele($chatId, $message_id, $user, $mode = "edit")
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "thongso"], env('CACHE_TIME_BOT', 24 * 60));

        // $mess = "Thông số tài khoản " . $user->name . " Chuẩn " . $user->customer_type;

        // if ($mode != "edit")
        //     $this->sendMessage($chatId, $mess);
        // else 
        {
            $currentUseLowPrice = $user->active_noti_tele;
            $newUseLowPrice = !$currentUseLowPrice;
            // Cache::put('useLowPrice_bot_tele' . $user->id, $newUseLowPrice, env('CACHE_TIME_BOT', 24 * 60));
            $user->active_noti_tele = $newUseLowPrice;
            $user->save();
            // $customKeyboardThongso =
            //     array(
            //         array(
            //             array('text' => 'Giá mua', 'callback_data' => 'giamua'),
            //             array('text' => 'Giới hạn cược', 'callback_data' => 'gioihancuoc'),
            //         ),
            //         array(
            //             array('text' => 'Giá thấp: Đang ' . ($newUseLowPrice ? "Bật" : "Tắt"), 'callback_data' => 'caidatgiathap'),
            //             array('text' => 'Thông số giá thấp', 'callback_data' => 'thongsogt'),
            //         ),
            //         array(
            //             array('text' => 'Chỉnh thông số giá thấp', 'callback_data' => 'chinhthongsogt'),
            //         ),
            //         array(
            //             array('text' => '< Back', 'callback_data' => 'back'),
            //         )
            //     );
            // $this->editMessageReplyMarkup($chatId, $message_id, $mess, ['inline_keyboard' => $customKeyboardThongso]);
        }
    }

    public function saoketuannay($cbId, $cbMessageId, $user, $stackItem = "saoketuannay")
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "saoke", $stackItem], env('CACHE_TIME_BOT', 24 * 60));

        $now = date("Y-m-d");

        $staticstart = $now;
        $staticfinish = $now;

        // check the current day
        if (date('D') != 'Mon') {
            //take the last monday
            $staticstart = date('Y-m-d', strtotime('last Monday'));
        } else {
            $staticstart = date('Y-m-d');
        }
        //always next saturday

        if (date('D') != 'Sun') {
            $staticfinish = date('Y-m-d', strtotime('next Sunday'));
        } else {
            $staticfinish = date('Y-m-d');
        }

        // if (Cache::get('staticstart_week_bet' . $user->id) == $staticstart )
        //     return;

        Cache::put('staticstart_week_bet' . $user->id, $staticstart, env('CACHE_TIME_BOT', 24 * 60));
        Cache::put('staticfinish_week_bet' . $user->id, $staticfinish, env('CACHE_TIME_BOT', 24 * 60));

        // $staticstart = date('Y-m-d',strtotime('-7 day', strtotime($staticstart))); 
        // $staticfinish = date('Y-m-d',strtotime('-7 day', strtotime($staticfinish))); 
        $this->saokeByDay($user, $cbId, $cbMessageId, $staticstart, $staticfinish, $this->keyboardSaoketuannay, $stackItem);
        return;
    }

    public function hoivienthangthuatuannay($cbId, $cbMessageId, $user, $stackItem = "hoivienthangthuatuannay")
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "saoke", $stackItem], env('CACHE_TIME_BOT', 24 * 60));

        $now = date("Y-m-d");

        $staticstart = $now;
        $staticfinish = $now;

        // check the current day
        if (date('D') != 'Mon') {
            //take the last monday
            $staticstart = date('Y-m-d', strtotime('last Monday'));
        } else {
            $staticstart = date('Y-m-d');
        }
        //always next saturday

        if (date('D') != 'Sun') {
            $staticfinish = date('Y-m-d', strtotime('next Sunday'));
        } else {
            $staticfinish = date('Y-m-d');
        }

        // if (Cache::get('staticstart_week_bet' . $user->id) == $staticstart )
        //     return;

        Cache::put('staticstart_week_bet' . $user->id, $staticstart, env('CACHE_TIME_BOT', 24 * 60));
        Cache::put('staticfinish_week_bet' . $user->id, $staticfinish, env('CACHE_TIME_BOT', 24 * 60));

        // $staticstart = date('Y-m-d',strtotime('-7 day', strtotime($staticstart))); 
        // $staticfinish = date('Y-m-d',strtotime('-7 day', strtotime($staticfinish))); 

        $keyboardHoivienthangthuatuannay =
            array(
                array(),
            );
        // $begin = new DateTime($staticstart);
        // $end = new DateTime($staticfinish);
        // if ($end > (new DateTime()))
        //     $end = new DateTime();
        // $end->modify('+1 day');
        // $count = 0;
        // $i = 0;
        // $interval = DateInterval::createFromDateString('1 day');
        // $period = new DatePeriod($begin, $interval, $end);
        // setlocale(LC_TIME, "vi_VN");
        // foreach ($period as $dt) {
        //     $stDateTemp = $dt->format("d-m-Y");
        //     if ($stDateTemp > date('d-m-Y')) continue;
        //     $count++;
        //     if ($count > 2) {
        //         $i++;
        //         $count = 1;
        //         array_push($keyboardHoivienthangthuatuannay, []);
        //     }
        //     array_push($keyboardHoivienthangthuatuannay[$i], array('text' => strftime("%A", $dt->getTimestamp()), 'callback_data' => 'hoivienthangthua_' . $dt->format("Y-m-d")));
        // }
        // switch (Cache::get('stack_action_bet_history_bot_tele' . $user->id)) {
        //     case 'winlose':
        //         array_push($keyboardHoivienthangthuatuannay, array(array('text' => '< Back', 'callback_data' => 'hoivienthangthua_agent')));
        //         break;
        //     case 'cxl':
        //         array_push($keyboardHoivienthangthuatuannay, array(array('text' => '< Back', 'callback_data' => 'bangcuocchuaxuly_agent')));
        //         break;
        //     case 'cancel':
        //         array_push($keyboardHoivienthangthuatuannay, array(array('text' => '< Back', 'callback_data' => 'donhangdahuy_agent')));
        //         break;
        //     default:
        //         # code...
        //         break;
        // }

        $this->hoivienthangthuaByName($user, $cbId, $cbMessageId, $staticstart, $staticfinish, $keyboardHoivienthangthuatuannay, $stackItem);
        return;
    }

    public function hoivienthangthuatuantruoc($cbId, $cbMessageId, $user, $stackItem = "hoivienthangthuatuantruoc")
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "saoke", $stackItem], env('CACHE_TIME_BOT', 24 * 60));

        $now = date("Y-m-d");

        $staticstart = $now;
        $staticfinish = $now;

        // check the current day
        if (date('D') != 'Mon') {
            //take the last monday
            $staticstart = date('Y-m-d', strtotime('last Monday'));
        } else {
            $staticstart = date('Y-m-d');
        }
        //always next saturday

        if (date('D') != 'Sun') {
            $staticfinish = date('Y-m-d', strtotime('next Sunday'));
        } else {
            $staticfinish = date('Y-m-d');
        }

        $staticstart = date('Y-m-d', strtotime('-7 day', strtotime($staticstart)));
        $staticfinish = date('Y-m-d', strtotime('-7 day', strtotime($staticfinish)));

        $keyboardHoivienthangthuatuantruoc =
            array(
                array(),
            );
        $begin = new DateTime($staticstart);
        $end = new DateTime($staticfinish);
        if ($end > (new DateTime()))
            $end = new DateTime();
        $end->modify('+1 day');
        $count = 0;
        $i = 0;
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        setlocale(LC_TIME, "vi_VN");
        foreach ($period as $dt) {
            $stDateTemp = $dt->format("d-m-Y");
            $count++;
            if ($count > 2) {
                $i++;
                $count = 1;
                array_push($keyboardHoivienthangthuatuantruoc, []);
            }
            array_push($keyboardHoivienthangthuatuantruoc[$i], array('text' => strftime("%A", $dt->getTimestamp()), 'callback_data' => 'hoivienthangthua_' . $dt->format("Y-m-d")));
        }
        array_push($keyboardHoivienthangthuatuantruoc, array(array('text' => '< Back', 'callback_data' => 'hoivienthangthua_agent')));
        $this->hoivienthangthuaByName($user, $cbId, $cbMessageId, $staticstart, $staticfinish, $keyboardHoivienthangthuatuantruoc, $stackItem);
        return;
    }

    public function saokeTodayF($cbId, $cbMessageId, $user, $stackItem = "saoketuantruoc")
    {
        try {
            $now = date("Y-m-d");
            $staticstart = $now;
            $staticfinish = $now;
            Cache::put('staticstart_week_bet' . $user->id, $staticstart, env('CACHE_TIME_BOT', 24 * 60));
            Cache::put('staticfinish_week_bet' . $user->id, $staticfinish, env('CACHE_TIME_BOT', 24 * 60));
            $keyboard =
                array(
                    array(
                        array('text' => 'Tuần trước', 'callback_data' => 'saoketuantruoc'),
                        array('text' => 'Tuần này', 'callback_data' => 'saoketuannay'),
                    ),
                    array(
                        array('text' => '< Back', 'callback_data' => 'back'),
                    )
                );
            $this->saokeByToday($user, $cbId, $cbMessageId, $staticstart, $keyboard, $customKB = false);
        } catch (Exception $ex) {
            $this->showTrangchu($cbId, $cbMessageId, $user, "edit");
        }
    }

    public function saoketuantruoc($cbId, $cbMessageId, $user, $stackItem = "saoketuantruoc")
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "saoke", $stackItem], env('CACHE_TIME_BOT', 24 * 60));
        $now = date("Y-m-d");

        $staticstart = $now;
        $staticfinish = $now;

        // check the current day
        if (date('D') != 'Mon') {
            //take the last monday
            $staticstart = date('Y-m-d', strtotime('last Monday'));
        } else {
            $staticstart = date('Y-m-d');
        }
        //always next saturday

        if (date('D') != 'Sun') {
            $staticfinish = date('Y-m-d', strtotime('next Sunday'));
        } else {
            $staticfinish = date('Y-m-d');
        }

        // $staticstart = Cache::get('staticstart_week_bet' . $user->id);
        // $staticfinish = Cache::get('staticfinish_week_bet' . $user->id); 
        $staticstart = date('Y-m-d', strtotime('-7 day', strtotime($staticstart)));
        $staticfinish = date('Y-m-d', strtotime('-7 day', strtotime($staticfinish)));

        Cache::put('staticstart_week_bet' . $user->id, $staticstart, env('CACHE_TIME_BOT', 24 * 60));
        Cache::put('staticfinish_week_bet' . $user->id, $staticfinish, env('CACHE_TIME_BOT', 24 * 60));


        $this->saokeByDay($user, $cbId, $cbMessageId, $staticstart, $staticfinish, $this->keyboardSaoketuantruoc);
        return;
    }

    private function hoivienthangthua_step1($user, $cbId, $cbMessageId, $today, $keyboard, $customKB = false, $stackItem = "saoketuannay")
    {
        if ($today == date("Y-m-d"))
            Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "saoke", $stackItem], env('CACHE_TIME_BOT', 24 * 60));
        else
            Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "saoke", $stackItem], env('CACHE_TIME_BOT', 24 * 60));
        $rs =
            DB::table('xoso_record')
            ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
                IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
                ) AS sumwin'), 'location.name as location_name', 'location.id as location_id')
            ->orderBy('sumbet', 'desc')
            ->where('isDelete', false)
            ->where('date', $today)
            // ->where('date','<=',$endDate)
            // ->whereIn('game_id', [7,12,14])
            ->where('user_id', $user->id)
            ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
            ->join('location', 'location.slug', '=', 'games.location_id')
            ->groupBy('location.slug')
            ->get();

        $data = [["Đài", "Tiền cược", "Thắng thua"]];
        $totalSumbet = 0;
        $totalSumwin = 0;

        $custom_keyboard =
            array(
                array(
                    // array('text' => 'Tuần trước', 'callback_data' => 'saoketuantruoc'),
                    // array('text' => 'Tuần này', 'callback_data' => 'saoketuannay'),
                ),
                array(
                    array('text' => '< Back', 'callback_data' => 'back'),
                )
            );

        foreach ($rs as $record) {
            array_push($data, [$record->location_name, number_format($record->sumbet), number_format($record->sumwin)]);
            $totalSumbet += $record->sumbet;
            $totalSumwin += $record->sumwin;
            array_push($custom_keyboard[0], array('text' => $record->location_name, 'callback_data' => 'saokechitietdai_' . $record->location_id . '_' . $today));
        }
        array_push($data, ["Tổng", number_format($totalSumbet), number_format($totalSumwin)]);
        setlocale(LC_TIME, "vi_VN");
        $mess = "Chi tiết " .  strftime("%A (%d-%m)", strtotime($today))  . "" . "\n";
        $mess .= "<pre>";
        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();
        foreach ($table_data as $row) {
            $mess .= $row . "\n";
            // echo $row . "\n";
        }
        $mess .= "</pre>";
        // $this->sendMessage($cbId, $mess);
        if ($customKB) $keyboard = $custom_keyboard;
        $this->editMessageReplyMarkup($cbId, $cbMessageId, $mess, ['inline_keyboard' => $keyboard]);
        // $this->sendMessage($cbId, $mess);
    }

    private function hoivienthangthuaToday($user, $cbId, $cbMessageId, $today, $keyboard, $customKB = false, $stackItem = "saoketuannay")
    {
        if ($today == date("Y-m-d"))
            Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "saoke", $stackItem], env('CACHE_TIME_BOT', 24 * 60));
        else
            Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "saoke", $stackItem], env('CACHE_TIME_BOT', 24 * 60));
        $arrUser = UserHelpers::GetAllUserV2($user);
        $rs = [];
        switch (Cache::get('stack_action_bet_history_bot_tele' . $user->id)) {
            case 'winlose':
                $rs =
                    DB::table('xoso_record')
                    ->select('date', 'game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
                    IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
                    ) AS sumwin'), 'games.short_name as game_name', 'users.name as user_name', 'user_id')
                    ->orderBy('date', 'desc')
                    ->where('isDelete', false)
                    ->where('total_win_money', '<>', 0)
                    ->where('date', '=', $today)
                    ->whereIn('user_id', $arrUser)
                    ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
                    ->join('users', 'xoso_record.user_id', '=', 'users.id')
                    ->groupBy('users.name')
                    ->get();
                break;
            case 'cxl':
                $rs =
                    DB::table('xoso_record')
                    ->select('date', 'game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
                        IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
                        ) AS sumwin'), 'games.short_name as game_name', 'users.name as user_name', 'user_id')
                    ->orderBy('date', 'desc')
                    ->where('isDelete', false)
                    ->where('total_win_money', 0)
                    ->where('date', '=', $today)
                    ->whereIn('user_id', $arrUser)
                    ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
                    ->join('users', 'xoso_record.user_id', '=', 'users.id')
                    ->groupBy('users.name')
                    ->get();
                break;
            case 'cancel':
                $rs =
                    DB::table('xoso_record')
                    ->select('date', 'game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
                            IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
                            ) AS sumwin'), 'games.short_name as game_name', 'users.name as user_name', 'user_id')
                    ->orderBy('date', 'desc')
                    ->where('isDelete', true)
                    ->where('date', '=', $today)
                    ->whereIn('user_id', $arrUser)
                    ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
                    ->join('users', 'xoso_record.user_id', '=', 'users.id')
                    ->groupBy('users.name')
                    ->get();
                break;
            default:
                # code...
                break;
        }


        $data = [["TK", "Tiền cược", "Thắng/Thua"]];
        $totalSumbet = 0;
        $totalSumwin = 0;

        $custom_keyboard =
            array(
                array(
                    array('text' => 'Tuần trước', 'callback_data' => 'hoivienthangthuatuantruoc'),
                    array('text' => 'Tuần này', 'callback_data' => 'hoivienthangthuatuannay'),
                ),
                array(
                    array('text' => '< Back', 'callback_data' => 'bangbieu_agent'),
                )
            );

        $count = 0;
        $i = 0;
        $custom_keyboard2 =
            array(
                array(),
            );

        foreach ($rs as $record) {
            array_push($data, [$record->user_name, number_format($record->sumbet), number_format($record->sumwin)]);
            $totalSumbet += $record->sumbet;
            $totalSumwin += $record->sumwin;
            // array_push($custom_keyboard[0], array('text' => $record->location_name, 'callback_data' => 'saokechitietdai_' . $record->location_id . '_' . $today));
            $count++;
            if ($count > 2) {
                $i++;
                $count = 1;
                array_push($custom_keyboard2, []);
            }
            array_push($custom_keyboard2[$i], array('text' => $record->user_name, 'callback_data' => 'hoivienthangthuamember_' . $today . "_" . $record->user_id));
        }

        setlocale(LC_TIME, "vi_VN");
        $mess = "Member " . $user->user_name . " thắng thua " .  strftime("%A (%d-%m)", strtotime($today))  . "" . "\n";
        $mess .= "<pre>";
        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();
        foreach ($table_data as $row) {
            $mess .= $row . "\n";
            // echo $row . "\n";
        }
        $mess .= "</pre>";
        // $this->sendMessage($cbId, $mess);
        if ($customKB) $keyboard = $custom_keyboard;
        else $keyboard = array_merge($custom_keyboard2, $keyboard);
        $this->editMessageReplyMarkup($cbId, $cbMessageId, $mess, ['inline_keyboard' => $keyboard]);
        // $this->sendMessage($cbId, $mess);
    }

    public function saoke($user, $cbId, $message_id, $staticstart, $staticfinish, $isInline_keyboard = true, $mode = "edit")
    {
        $rs =
            DB::table('xoso_record')
            // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
            // ->orderBy('sumbet', 'desc')
            ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            ->where('isDelete', 0)
            // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
            // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
            ->where('date', '>=', $staticstart)
            ->where('date', '<=', $staticfinish)
            ->where('user_id', $user->id)
            ->select('xoso_record.*', 'games.short_name as game')
            // ->groupBy('game_id')
            // ->limit(50)
            ->orderBy('created_at', 'des')
            ->get();

        $data = [["Time", "Game", "Number", "Money", "Win/lose"]];

        foreach ($rs as $record) {
            array_push($data, [date("d-m H:i", strtotime($record->created_at)), $this->vn_to_str($record->game), $record->bet_number, number_format($record->total_bet_money), number_format($record->total_win_money)]);
        }
        $mess = "Sao kê từ " . $staticstart . " đến " . $staticfinish . "" . "\n";
        $mess .= "<pre>";
        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();
        foreach ($table_data as $row) {
            $mess .= $row . "\n";
            // echo $row . "\n";
        }
        $mess .= "</pre>";
        // $this->sendMessage($cbId, $mess);

        $keyboard =
            array(
                array(
                    array('text' => 'Tuần trước', 'callback_data' => 'saoketuantruoc'),
                    array('text' => 'Tuần này', 'callback_data' => 'saoketuannay'),
                    array('text' => 'Tuần kế tiếp', 'callback_data' => 'saoketuanketiep'),
                )
            );
        if ($mode != "edit") {
            if ($isInline_keyboard)
                $this->sendMessageReplyMarkup($cbId, $mess, ['inline_keyboard' => $keyboard]);
            else {
                $this->sendMessage($cbId, $mess);
            }
        } else {
            if ($isInline_keyboard)
                $this->editMessageReplyMarkup($cbId, $message_id, $mess, ['inline_keyboard' => $keyboard]);
            else {
                $this->editMessage($cbId, $message_id, $mess);
            }
        }
    }

    public function editsaoke($user, $cbId, $message_id, $staticstart, $staticfinish, $keyboard)
    {
        $rs =
            DB::table('xoso_record')
            // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
            // ->orderBy('sumbet', 'desc')
            ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            ->where('isDelete', 0)
            // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
            // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
            ->where('date', '>=', $staticstart)
            ->where('date', '<=', $staticfinish)
            ->where('user_id', $user->id)
            ->select('xoso_record.*', 'games.short_name as game')
            // ->groupBy('game_id')
            // ->limit(50)
            ->orderBy('created_at', 'des')
            ->get();

        $data = [["Time", "Game", "Number", "Money", "Win/lose"]];

        foreach ($rs as $record) {
            array_push($data, [date("d-m H:i", strtotime($record->created_at)), $this->vn_to_str($record->game), $record->bet_number, number_format($record->total_bet_money), number_format($record->total_win_money)]);
        }
        $mess = "Sao kê từ " . $staticstart . " đến " . $staticfinish . "" . "\n";
        $mess .= "<pre>";
        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();
        foreach ($table_data as $row) {
            $mess .= $row . "\n";
            // echo $row . "\n";
        }
        $mess .= "</pre>";
        // $this->sendMessage($cbId, $mess);

        $this->editMessageReplyMarkup($cbId, $message_id, $mess, ['inline_keyboard' => $keyboard]);
        // $this->sendMessage($cbId, $mess);

    }

    public function saokeByDay($user, $cbId, $message_id, $staticstart, $staticfinish, $keyboard, $stackItem = "saoketuannay")
    {
        // Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu","saoke",$stackItem], env('CACHE_TIME_BOT', 24 * 60));
        // OR game_id = 19 OR game_id = 20 OR game_id = 21
        $rs =
            DB::table('xoso_record')
            ->select('date', 'game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
                IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
                ) AS sumwin'), 'games.short_name as game_name')
            ->orderBy('date', 'desc')
            ->where('isDelete', false)
            ->where('date', '>=', $staticstart)
            ->where('date', '<=', $staticfinish)
            // ->where('date','<=',$endDate)
            // ->whereIn('game_id', [7,12,14])
            ->where('user_id', $user->id)
            ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
            ->groupBy('date')
            ->get();

        $data = [["Thứ", "Tiền", "Thắng thua"]];
        $totalSumbet = 0;
        $totalSumwin = 0;
        setlocale(LC_TIME, 'vi_VN');
        foreach ($rs as $record) {
            array_push($data, [strftime("%A", strtotime($record->date)), number_format($record->sumbet), number_format($record->sumwin)]);
            $totalSumbet += $record->sumbet;
            $totalSumwin += $record->sumwin;
        }
        array_push($data, ["Tổng", number_format($totalSumbet), number_format($totalSumwin)]);
        $mess = "Sao kê từ " . date("d-m-Y", strtotime($staticstart)) . " đến " . date("d-m-Y", strtotime($staticfinish)) . "" . "\n";
        $mess .= "<pre>";
        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();
        foreach ($table_data as $row) {
            $mess .= $row . "\n";
            // echo $row . "\n";
        }
        $mess .= "</pre>";
        // $this->sendMessage($cbId, $mess);

        $this->editMessageReplyMarkup($cbId, $message_id, $mess, ['inline_keyboard' => $keyboard]);
        // $this->sendMessage($cbId, $mess);

    }

    public function hoivienthangthuaByName($user, $cbId, $message_id, $staticstart, $staticfinish, $keyboard, $stackItem = "saoketuannay")
    {
        // Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu","saoke",$stackItem], env('CACHE_TIME_BOT', 24 * 60));
        $arrUser = UserHelpers::GetAllUserV2($user);
        $rs = [];
        //Log::info(Cache::get('stack_action_bet_history_bot_tele' . $user->id));
        switch (Cache::get('stack_action_bet_history_bot_tele' . $user->id)) {
            case 'winlose':
                $rs =
                    DB::table('xoso_record')
                    ->select('date', 'game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
                IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
                ) AS sumwin'), 'games.short_name as game_name', 'users.name as user_name')
                    ->orderBy('date', 'desc')
                    ->where('isDelete', false)
                    ->where('total_win_money', "<>", 0)
                    ->where('date', '>=', $staticstart)
                    ->where('date', '<=', $staticfinish)
                    ->whereIn('user_id', $arrUser)
                    ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
                    ->join('users', 'xoso_record.user_id', '=', 'users.id')
                    ->groupBy('date')
                    ->get();
                break;
            case 'cxl':
                $rs =
                    $rs =
                    DB::table('xoso_record')
                    ->select('date', 'game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
                    IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
                    ) AS sumwin'), 'games.short_name as game_name', 'users.name as user_name')
                    ->orderBy('date', 'desc')
                    ->where('isDelete', false)
                    ->where('total_win_money', 0)
                    ->where('date', '>=', $staticstart)
                    ->where('date', '<=', $staticfinish)
                    ->whereIn('user_id', $arrUser)
                    ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
                    ->join('users', 'xoso_record.user_id', '=', 'users.id')
                    ->groupBy('date')
                    ->get();
                break;
            case 'cancel':
                $rs =
                    $rs =
                    DB::table('xoso_record')
                    ->select('date', 'game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
                    IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
                    ) AS sumwin'), 'games.short_name as game_name', 'users.name as user_name')
                    ->orderBy('date', 'desc')
                    ->where('isDelete', true)
                    ->where('date', '>=', $staticstart)
                    ->where('date', '<=', $staticfinish)
                    ->whereIn('user_id', $arrUser)
                    ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
                    ->join('users', 'xoso_record.user_id', '=', 'users.id')
                    ->groupBy('date')
                    ->get();
                break;
            default:
                # code...
                break;
        }

        $data = [["TK", "Tiền", "Thắng/Thua"]];
        $totalSumbet = 0;
        $totalSumwin = 0;
        setlocale(LC_TIME, 'vi_VN');

        $keyboardHoivienthangthuatuannay =
            array(
                array(),
            );
        // $begin = new DateTime($staticstart);
        // $end = new DateTime($staticfinish);
        // if ($end > (new DateTime()))
        //     $end = new DateTime();
        // $end->modify('+1 day');
        // $count = 0;
        // $i = 0;
        // $interval = DateInterval::createFromDateString('1 day');
        // $period = new DatePeriod($begin, $interval, $end);
        // setlocale(LC_TIME, "vi_VN");
        // foreach ($period as $dt) {
        //     $stDateTemp = $dt->format("d-m-Y");
        //     if ($stDateTemp > date('d-m-Y')) continue;
        //     $count++;
        //     if ($count > 2) {
        //         $i++;
        //         $count = 1;
        //         array_push($keyboardHoivienthangthuatuannay, []);
        //     }
        //     array_push($keyboardHoivienthangthuatuannay[$i], array('text' => strftime("%A", $dt->getTimestamp()), 'callback_data' => 'hoivienthangthua_' . $dt->format("Y-m-d")));
        // }

        $count = 0;
        $i = 0;
        foreach ($rs as $record) {
            array_push($data, [strftime("%A", strtotime($record->date)), number_format($record->sumbet), number_format($record->sumwin)]);
            $totalSumbet += $record->sumbet;
            $totalSumwin += $record->sumwin;
            $count++;
            if ($count > 2) {
                $i++;
                $count = 1;
                array_push($keyboardHoivienthangthuatuannay, []);
            }
            array_push($keyboardHoivienthangthuatuannay[$i], array('text' => strftime("%A", strtotime($record->date)), 'callback_data' => 'hoivienthangthua_' . date("Y-m-d", strtotime($record->date))));
        }

        switch (Cache::get('stack_action_bet_history_bot_tele' . $user->id)) {
            case 'winlose':
                array_push($keyboardHoivienthangthuatuannay, array(array('text' => '< Back', 'callback_data' => 'hoivienthangthua_agent')));
                break;
            case 'cxl':
                array_push($keyboardHoivienthangthuatuannay, array(array('text' => '< Back', 'callback_data' => 'bangcuocchuaxuly_agent')));
                break;
            case 'cancel':
                array_push($keyboardHoivienthangthuatuannay, array(array('text' => '< Back', 'callback_data' => 'donhangdahuy_agent')));
                break;
            default:
                # code...
                break;
        }
        array_push($data, ["Tổng", number_format($totalSumbet), number_format($totalSumwin)]);
        $mess = "Sao kê từ " . date("d-m-Y", strtotime($staticstart)) . " đến " . date("d-m-Y", strtotime($staticfinish)) . "" . "\n";
        $mess .= "<pre>";
        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();
        foreach ($table_data as $row) {
            $mess .= $row . "\n";
            // echo $row . "\n";
        }
        $mess .= "</pre>";
        // $this->sendMessage($cbId, $mess);

        $this->editMessageReplyMarkup($cbId, $message_id, $mess, ['inline_keyboard' => $keyboardHoivienthangthuatuannay]);
        // $this->sendMessage($cbId, $mess);

    }

    public function saokeByToday($user, $cbId, $message_id, $today, $keyboard, $customKB = false, $stackItem = "saoketuannay")
    {
        if ($today == date("Y-m-d"))
            Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "saoke", $stackItem], env('CACHE_TIME_BOT', 24 * 60));
        else
            Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "saoke", $stackItem], env('CACHE_TIME_BOT', 24 * 60));
        $rs =
            DB::table('xoso_record')
            ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
                IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
                ) AS sumwin'), 'location.name as location_name', 'location.id as location_id')
            ->orderBy('sumbet', 'desc')
            ->where('isDelete', false)
            ->where('date', $today)
            // ->where('date','<=',$endDate)
            // ->whereIn('game_id', [7,12,14])
            ->where('user_id', $user->id)
            ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
            ->join('location', 'location.slug', '=', 'games.location_id')
            ->groupBy('location.slug')
            ->get();

        $data = [["Đài", "Tiền cược", "Thắng thua"]];
        $totalSumbet = 0;
        $totalSumwin = 0;

        $custom_keyboard =
            array(
                array(
                    // array('text' => 'Tuần trước', 'callback_data' => 'saoketuantruoc'),
                    // array('text' => 'Tuần này', 'callback_data' => 'saoketuannay'),
                ),
                array(
                    array('text' => '< Back', 'callback_data' => 'back'),
                )
            );

        foreach ($rs as $record) {
            array_push($data, [$record->location_name, number_format($record->sumbet), number_format($record->sumwin)]);
            $totalSumbet += $record->sumbet;
            $totalSumwin += $record->sumwin;
            array_push($custom_keyboard[0], array('text' => $record->location_name, 'callback_data' => 'saokechitietdai_' . $record->location_id . '_' . $today));
        }
        array_push($data, ["Tổng", number_format($totalSumbet), number_format($totalSumwin)]);
        setlocale(LC_TIME, "vi_VN");
        $mess = "Chi tiết " .  strftime("%A (%d-%m)", strtotime($today))  . "" . "\n";
        $mess .= "<pre>";
        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();
        foreach ($table_data as $row) {
            $mess .= $row . "\n";
            // echo $row . "\n";
        }
        $mess .= "</pre>";
        // $this->sendMessage($cbId, $mess);
        if ($customKB) $keyboard = $custom_keyboard;
        $this->editMessageReplyMarkup($cbId, $message_id, $mess, ['inline_keyboard' => $keyboard]);
        // $this->sendMessage($cbId, $mess);
    }

    public function hoivienthangthuaMemberByToday($currentUser, $user, $cbId, $message_id, $today, $keyboard, $customKB = false, $stackItem = "saoketuannay")
    {
        //Log::info(Cache::get('stack_action_bet_history_bot_tele' . $currentUser->id));
        if ($today == date("Y-m-d"))
            Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "saoke", $stackItem], env('CACHE_TIME_BOT', 24 * 60));
        else
            Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "saoke", $stackItem], env('CACHE_TIME_BOT', 24 * 60));
        $rs = [];
        switch (Cache::get('stack_action_bet_history_bot_tele' . $currentUser->id)) {
            case 'winlose':
                $rs =
                    DB::table('xoso_record')
                    ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
                IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
                ) AS sumwin'), 'location.name as location_name', 'location.id as location_id')
                    ->orderBy('sumbet', 'desc')
                    ->where('isDelete', false)
                    ->where('date', $today)
                    ->where('total_win_money', "<>", 0)
                    // ->where('date','<=',$endDate)
                    // ->whereIn('game_id', [7,12,14])
                    ->where('user_id', $user->id)
                    ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
                    ->join('location', 'location.slug', '=', 'games.location_id')
                    ->groupBy('location.slug')
                    ->get();
                break;
            case 'cxl':
                $rs =
                    DB::table('xoso_record')
                    ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
                        IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
                        ) AS sumwin'), 'location.name as location_name', 'location.id as location_id')
                    ->orderBy('sumbet', 'desc')
                    ->where('isDelete', false)
                    ->where('total_win_money', 0)
                    ->where('date', $today)
                    // ->where('date','<=',$endDate)
                    // ->whereIn('game_id', [7,12,14])
                    ->where('user_id', $user->id)
                    ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
                    ->join('location', 'location.slug', '=', 'games.location_id')
                    ->groupBy('location.slug')
                    ->get();
                break;
            case 'cancel':
                $rs =
                    DB::table('xoso_record')
                    ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
                IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
                ) AS sumwin'), 'location.name as location_name', 'location.id as location_id')
                    ->orderBy('sumbet', 'desc')
                    ->where('isDelete', true)
                    ->where('date', $today)
                    // ->where('date','<=',$endDate)
                    // ->whereIn('game_id', [7,12,14])
                    ->where('user_id', $user->id)
                    ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
                    ->join('location', 'location.slug', '=', 'games.location_id')
                    ->groupBy('location.slug')
                    ->get();
                break;
            default:
                # code...
                break;
        }


        $data = [["Đài", "Tiền cược", "Thắng thua"]];
        $totalSumbet = 0;
        $totalSumwin = 0;

        $custom_keyboard =
            array(
                array(),
                array(
                    array('text' => '< Back', 'callback_data' => $stackItem),
                )
            );

        foreach ($rs as $record) {
            array_push($data, [$record->location_name, number_format($record->sumbet), number_format($record->sumwin)]);
            $totalSumbet += $record->sumbet;
            $totalSumwin += $record->sumwin;
            array_push($custom_keyboard[0], array('text' => $record->location_name, 'callback_data' => 'hoivienthangthuaMemberchitietdai_' . $record->location_id . '_' . $today . '_' . $user->id));
        }
        array_push($data, ["Tổng", number_format($totalSumbet), number_format($totalSumwin)]);
        setlocale(LC_TIME, "vi_VN");
        $mess = "Chi tiết " . $user->name . " " .  strftime("%A (%d-%m)", strtotime($today))  . "" . "\n";
        $mess .= "<pre>";
        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();
        foreach ($table_data as $row) {
            $mess .= $row . "\n";
            // echo $row . "\n";
        }
        $mess .= "</pre>";
        // $this->sendMessage($cbId, $mess);
        if ($customKB) $keyboard = $custom_keyboard;
        $this->editMessageReplyMarkup($cbId, $message_id, $mess, ['inline_keyboard' => $keyboard]);
        // $this->sendMessage($cbId, $mess);
    }

    public function saokeByTodayChitietDai($user, $cbId, $message_id, $today, $locationID, $keyboard, $customKB = false, $stackItem = "saoketuannay", $backEvent)
    {
        if ($today == date("Y-m-d"))
            Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "saoke", $stackItem], env('CACHE_TIME_BOT', 24 * 60));
        else
            Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "saoke", $stackItem], env('CACHE_TIME_BOT', 24 * 60));

        $rs =
            DB::table('xoso_record')
            ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
                IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
                ) AS sumwin'), 'location.name as location_name', 'location.id as location_id', 'games.short_name as game_name')
            ->orderBy('games.order', 'desc')
            ->where('isDelete', false)
            ->where('date', $today)
            ->where('games.location_id', $locationID)
            // ->where('date','<=',$endDate)
            // ->whereIn('game_id', [7,12,14])
            ->where('user_id', $user->id)
            ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
            ->join('location', 'location.slug', '=', 'games.location_id')
            ->groupBy('games.short_name')
            ->get();

        $data = [["Loại", "Tiền cược", "Thắng thua"]];
        $totalSumbet = 0;
        $totalSumwin = 0;

        $custom_keyboard =
            array(
                array()
            );
        $location_name = "";
        $i = 0;
        $count = 0;
        foreach ($rs as $record) {
            array_push($data, [$record->game_name, number_format($record->sumbet), number_format($record->sumwin)]);
            $totalSumbet += $record->sumbet;
            $totalSumwin += $record->sumwin;
            $location_name = $record->location_name;
            $count++;
            if ($count > 3) {
                $i++;
                $count = 1;
                array_push($custom_keyboard, []);
            }
            array_push($custom_keyboard[$i], array('text' => $record->game_name, 'callback_data' => 'saokechitiettheloai_' . $record->game_id . '_' . $today));
        }
        array_push($custom_keyboard, array(array('text' => '< Back', 'callback_data' => $backEvent)));

        array_push($data, ["Tổng", number_format($totalSumbet), number_format($totalSumwin)]);
        setlocale(LC_TIME, "vi_VN");
        $mess = "Chi tiết " . $location_name . ": " .  strftime("%A (%d-%m)", strtotime($today))  . "" . "\n";
        $mess .= "<pre>";
        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();
        foreach ($table_data as $row) {
            $mess .= $row . "\n";
            // echo $row . "\n";
        }
        $mess .= "</pre>";
        // $this->sendMessage($cbId, $mess);
        if ($customKB) $keyboard = $custom_keyboard;
        $this->editMessageReplyMarkup($cbId, $message_id, $mess, ['inline_keyboard' => $keyboard]);
        // $this->sendMessage($cbId, $mess);
    }

    public function hoivienthangthuaMemberByTodayChitietDai($currentUser, $user, $cbId, $message_id, $today, $locationID, $keyboard, $customKB = false, $stackItem = "saoketuannay", $backEvent)
    {
        if ($today == date("Y-m-d"))
            Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "saoke", $stackItem], env('CACHE_TIME_BOT', 24 * 60));
        else
            Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "saoke", $stackItem], env('CACHE_TIME_BOT', 24 * 60));

        $rs = [];
        switch (Cache::get('stack_action_bet_history_bot_tele' . $currentUser->id)) {
            case 'winlose':
                $rs =
                    DB::table('xoso_record')
                    ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
                IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
                ) AS sumwin'), 'location.name as location_name', 'location.id as location_id', 'games.short_name as game_name')
                    ->orderBy('games.order', 'desc')
                    ->where('isDelete', false)
                    ->where('total_win_money', "<>", 0)
                    ->where('date', $today)
                    ->where('games.location_id', $locationID)
                    // ->where('date','<=',$endDate)
                    // ->whereIn('game_id', [7,12,14])
                    ->where('user_id', $user->id)
                    ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
                    ->join('location', 'location.slug', '=', 'games.location_id')
                    ->groupBy('games.short_name')
                    ->get();
                break;
            case 'cxl':
                $rs =
                    DB::table('xoso_record')
                    ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
                IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
                ) AS sumwin'), 'location.name as location_name', 'location.id as location_id', 'games.short_name as game_name')
                    ->orderBy('games.order', 'desc')
                    ->where('isDelete', false)
                    ->where('total_win_money', 0)
                    ->where('date', $today)
                    ->where('games.location_id', $locationID)
                    // ->where('date','<=',$endDate)
                    // ->whereIn('game_id', [7,12,14])
                    ->where('user_id', $user->id)
                    ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
                    ->join('location', 'location.slug', '=', 'games.location_id')
                    ->groupBy('games.short_name')
                    ->get();
                break;
            case 'cancel':
                $rs =
                    DB::table('xoso_record')
                    ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
                IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
                ) AS sumwin'), 'location.name as location_name', 'location.id as location_id', 'games.short_name as game_name')
                    ->orderBy('games.order', 'desc')
                    ->where('isDelete', true)
                    ->where('date', $today)
                    ->where('games.location_id', $locationID)
                    // ->where('date','<=',$endDate)
                    // ->whereIn('game_id', [7,12,14])
                    ->where('user_id', $user->id)
                    ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
                    ->join('location', 'location.slug', '=', 'games.location_id')
                    ->groupBy('games.short_name')
                    ->get();
                break;
            default:
                # code...
                break;
        }

        $data = [["Loại", "Tiền cược", "Thắng thua"]];
        $totalSumbet = 0;
        $totalSumwin = 0;

        $custom_keyboard =
            array(
                array()
            );
        $location_name = "";
        $i = 0;
        $count = 0;
        foreach ($rs as $record) {
            array_push($data, [$record->game_name, number_format($record->sumbet), number_format($record->sumwin)]);
            $totalSumbet += $record->sumbet;
            $totalSumwin += $record->sumwin;
            $location_name = $record->location_name;
            $count++;
            if ($count > 3) {
                $i++;
                $count = 1;
                array_push($custom_keyboard, []);
            }
            array_push($custom_keyboard[$i], array('text' => $record->game_name, 'callback_data' => 'hoivienthangthuaMemberchitiettheloai_' . $record->game_id . '_' . $today . '_' . $user->id));
        }
        array_push($custom_keyboard, array(array('text' => '< Back', 'callback_data' => $backEvent)));

        array_push($data, ["Tổng", number_format($totalSumbet), number_format($totalSumwin)]);
        setlocale(LC_TIME, "vi_VN");
        $mess = "Chi tiết " . $location_name . ": " .  strftime("%A (%d-%m)", strtotime($today))  . "" . "\n";
        $mess .= "<pre>";
        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();
        foreach ($table_data as $row) {
            $mess .= $row . "\n";
            // echo $row . "\n";
        }
        $mess .= "</pre>";
        // $this->sendMessage($cbId, $mess);
        if ($customKB) $keyboard = $custom_keyboard;
        $this->editMessageReplyMarkup($cbId, $message_id, $mess, ['inline_keyboard' => $keyboard]);
        // $this->sendMessage($cbId, $mess);
    }

    public function saokeByTodayChitietTheloai($user, $cbId, $message_id, $today, $gameid, $keyboard, $customKB = false, $stackItem = "saoketuannay")
    {
        // if ($today == date("Y-m-d"))
        //     Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu","saoke"], env('CACHE_TIME_BOT', 24 * 60));
        // else{
        //     $stack_action_inline_bot_tele = Cache::get('stack_action_inline_bot_tele' . $user->id);
        //     array_push($stack_action_inline_bot_tele,$stackItem);
        //     Cache::put('stack_action_inline_bot_tele' . $user->id,  $stack_action_inline_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
        // }


        $rs =
            DB::table('xoso_record')
            // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
            // ->orderBy('sumbet', 'desc')
            ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            ->where('isDelete', 0)
            // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
            // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
            ->where('date', $today)
            ->where('xoso_record.game_id', $gameid)
            ->where('user_id', $user->id)
            ->select('xoso_record.*', 'games.short_name as game')
            ->orderBy('created_at', 'des')
            // ->groupBy('game_id')
            ->get();
        // DB::table('history')
        // // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
        // // ->orderBy('sumbet', 'desc')
        // // ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
        // // ->where('isDelete', 0)
        // // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
        // // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
        // ->where('date', $now)
        // ->where('user_create', $user->id)
        // ->select('*')
        // ->orderBy('created_at', 'des')
        // // ->groupBy('game_id')
        // ->get();

        $data = [["Tg", "Số", "Điểm", "Thắng/thua"]];
        $count = 1;
        $game_name =  "";
        $maxLengthSplit = 5;
        $pointBet = 0;
        $pointWin = 0;
        foreach ($rs as $record) {
            $point = $record->total_bet_money / $record->exchange_rates / $this->Cal_Ank($record->game_id, $record->bet_number);
            $pointBet += $point;
            $totalWin = 0;
            if ($record->game_id == 15 || $record->game_id == 16 || $record->game_id == 19 || $record->game_id == 20 || $record->game_id == 21)
                $totalWin = $record->total_win_money;
            else
                $totalWin = $record->total_win_money > 0 ? $record->total_win_money - $record->total_bet_money : $record->total_win_money;

            if ($record->total_win_money > 0)
                $pointWin += $record->total_win_money / $record->odds / $this->Cal_Ank($record->game_id, $record->bet_number);

            if (strlen($record->bet_number) > $maxLengthSplit) {
                $temp = $record->bet_number;
                $isShowName = true;
                $countWhile = 0;
                while (true) {
                    $countWhile++;
                    if ($countWhile > 100) break;
                    $sub_temp = substr($temp, 0, $maxLengthSplit);
                    $temp = substr($temp, $maxLengthSplit);
                    array_push($data, [$isShowName ? date("H:i", strtotime($record->created_at)) : "", $sub_temp, $isShowName ? number_format($point) : "", $isShowName ? number_format($totalWin) : ""]);
                    $isShowName = false;
                    if (strlen($temp) == 0) break;
                    if ($temp[0] == ",") $temp = substr($temp, 1);
                    if (strlen($temp) <= $maxLengthSplit) {
                        $sub_temp = substr($temp, 0, strlen($temp));
                        array_push($data, [$isShowName ? date("H:i", strtotime($record->created_at)) : "", $sub_temp, $isShowName ? number_format($point) : "", $isShowName ? number_format($totalWin) : ""]);
                        break;
                    }
                }
            } else
                // array_push($data, [$record->game, $record->bet_number, number_format($record->total_bet_money / $record->exchange_rates), number_format($record->exchange_rates)]);
                array_push($data, [date("H:i", strtotime($record->created_at)), $record->bet_number, number_format($point), number_format($totalWin)]);


            $game_name =  $record->game;
            // $mess .= "Tin ". $count . "(" . date("H:i", strtotime($record->created_at)) . "): " . trim($record->content) . "\n"; 
        }


        $custom_keyboard =
            array(
                array(
                    // array('text' => 'Tuần trước', 'callback_data' => 'saoketuantruoc'),
                    // array('text' => 'Tuần này', 'callback_data' => 'saoketuannay'),
                ),
                array(
                    array('text' => '< Back', 'callback_data' => $stackItem),
                )
            );

        setlocale(LC_TIME, "vi_VN");
        $mess = "Chi tiết " . $game_name . ": " .  strftime("%A (%d-%m)", strtotime($today))  . "" . "\n";
        $mess .= "<pre>";
        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();
        $indexC = 0;
        while ($indexC < count($table_data)) {
            $row = $table_data[$indexC];
            $mess .= $row . "\n";

            if ($indexC % 100 == 0 && $indexC != 0) {
                $mess .= "</pre>";
                $this->sendMessage($cbId, $mess);
                sleep(1);
                $mess = "<pre>";
            }
            $indexC++;
        }
        // foreach ($table_data as $row) {
        //     $mess .= $row . "\n";
        //     // echo $row . "\n";
        // }
        $mess .= "</pre>";

        $mess .= "Tổng điểm: <b>" . number_format($pointBet) . " (" . number_format($pointWin) . ")</b>";
        // $this->sendMessage($cbId, $mess);
        if ($customKB) $keyboard = $custom_keyboard;
        $this->sendMessageReplyMarkup($cbId, $mess, ['inline_keyboard' => $keyboard]);
        // $this->sendMessage($cbId, $mess);
    }

    public function hoivienthangthuaMemberByTodayChitietTheloai($currentUser, $user, $cbId, $message_id, $today, $gameid, $keyboard, $customKB = false, $stackItem = "saoketuannay")
    {
        // if ($today == date("Y-m-d"))
        //     Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu","saoke"], env('CACHE_TIME_BOT', 24 * 60));
        // else{
        //     $stack_action_inline_bot_tele = Cache::get('stack_action_inline_bot_tele' . $user->id);
        //     array_push($stack_action_inline_bot_tele,$stackItem);
        //     Cache::put('stack_action_inline_bot_tele' . $user->id,  $stack_action_inline_bot_tele, env('CACHE_TIME_BOT', 24 * 60));
        // }
        $rs = [];
        switch (Cache::get('stack_action_bet_history_bot_tele' . $currentUser->id)) {
            case 'winlose':
                $rs =
                    DB::table('xoso_record')
                    // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
                    // ->orderBy('sumbet', 'desc')
                    ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
                    ->where('isDelete', 0)
                    ->where('total_win_money', "<>", 0)
                    // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
                    // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
                    ->where('date', $today)
                    ->where('xoso_record.game_id', $gameid)
                    ->where('user_id', $user->id)
                    ->select('xoso_record.*', 'games.short_name as game')
                    ->orderBy('created_at', 'des')
                    // ->groupBy('game_id')
                    ->get();
                break;
            case 'cxl':
                $rs =
                    DB::table('xoso_record')
                    // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
                    // ->orderBy('sumbet', 'desc')
                    ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
                    ->where('isDelete', 0)
                    ->where('total_win_money', 0)
                    // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
                    // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
                    ->where('date', $today)
                    ->where('xoso_record.game_id', $gameid)
                    ->where('user_id', $user->id)
                    ->select('xoso_record.*', 'games.short_name as game')
                    ->orderBy('created_at', 'des')
                    // ->groupBy('game_id')
                    ->get();
                break;
            case 'cancel':
                $rs =
                    DB::table('xoso_record')
                    // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
                    // ->orderBy('sumbet', 'desc')
                    ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
                    ->where('isDelete', 1)
                    // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
                    // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
                    ->where('date', $today)
                    ->where('xoso_record.game_id', $gameid)
                    ->where('user_id', $user->id)
                    ->select('xoso_record.*', 'games.short_name as game')
                    ->orderBy('created_at', 'des')
                    // ->groupBy('game_id')
                    ->get();
                break;
            default:
                # code...
                break;
        }

        // DB::table('history')
        // // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
        // // ->orderBy('sumbet', 'desc')
        // // ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
        // // ->where('isDelete', 0)
        // // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
        // // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
        // ->where('date', $now)
        // ->where('user_create', $user->id)
        // ->select('*')
        // ->orderBy('created_at', 'des')
        // // ->groupBy('game_id')
        // ->get();

        $data = [["Tg", "Số", "Điểm", "Thắng/thua"]];
        $count = 1;
        $game_name =  "";
        $maxLengthSplit = 5;
        $pointBet = 0;
        $pointWin = 0;
        foreach ($rs as $record) {
            $point = $record->total_bet_money / $record->exchange_rates / $this->Cal_Ank($record->game_id, $record->bet_number);
            $pointBet += $point;
            $totalWin = 0;
            if ($record->game_id == 15 || $record->game_id == 16 || $record->game_id == 19 || $record->game_id == 20 || $record->game_id == 21)
                $totalWin = $record->total_win_money;
            else
                $totalWin = $record->total_win_money > 0 ? $record->total_win_money - $record->total_bet_money : $record->total_win_money;

            if ($record->total_win_money > 0)
                $pointWin += $record->total_win_money / $record->odds / $this->Cal_Ank($record->game_id, $record->bet_number);

            if (strlen($record->bet_number) > $maxLengthSplit) {
                $temp = $record->bet_number;
                $isShowName = true;
                $countWhile = 0;
                while (true) {
                    $countWhile++;
                    if ($countWhile > 100) break;
                    $sub_temp = substr($temp, 0, $maxLengthSplit);
                    $temp = substr($temp, $maxLengthSplit);
                    array_push($data, [$isShowName ? date("H:i", strtotime($record->created_at)) : "", $sub_temp, $isShowName ? number_format($point) : "", $isShowName ? number_format($totalWin) : ""]);
                    $isShowName = false;
                    if (strlen($temp) == 0) break;
                    if ($temp[0] == ",") $temp = substr($temp, 1);
                    if (strlen($temp) <= $maxLengthSplit) {
                        $sub_temp = substr($temp, 0, strlen($temp));
                        array_push($data, [$isShowName ? date("H:i", strtotime($record->created_at)) : "", $sub_temp, $isShowName ? number_format($point) : "", $isShowName ? number_format($totalWin) : ""]);
                        break;
                    }
                }
            } else
                // array_push($data, [$record->game, $record->bet_number, number_format($record->total_bet_money / $record->exchange_rates), number_format($record->exchange_rates)]);
                array_push($data, [date("H:i", strtotime($record->created_at)), $record->bet_number, number_format($point), number_format($totalWin)]);


            $game_name =  $record->game;
            // $mess .= "Tin ". $count . "(" . date("H:i", strtotime($record->created_at)) . "): " . trim($record->content) . "\n"; 
        }


        $custom_keyboard =
            array(
                array(
                    // array('text' => 'Tuần trước', 'callback_data' => 'saoketuantruoc'),
                    // array('text' => 'Tuần này', 'callback_data' => 'saoketuannay'),
                ),
                array(
                    array('text' => '< Back', 'callback_data' => $stackItem),
                )
            );

        setlocale(LC_TIME, "vi_VN");
        $mess = "Chi tiết " . $game_name . ": " .  strftime("%A (%d-%m)", strtotime($today))  . "" . "\n";
        $mess .= "<pre>";
        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();
        $indexC = 0;
        while ($indexC < count($table_data)) {
            $row = $table_data[$indexC];
            $mess .= $row . "\n";

            if ($indexC % 100 == 0 && $indexC != 0) {
                $mess .= "</pre>";
                $this->sendMessage($cbId, $mess);
                sleep(1);
                $mess = "<pre>";
            }
            $indexC++;
        }
        $mess .= "</pre>";

        $mess .= "Tổng điểm: <b>" . number_format($pointBet) . " (" . number_format($pointWin) . ")</b>";
        // $this->sendMessage($cbId, $mess);
        if ($customKB) $keyboard = $custom_keyboard;
        $this->sendMessageReplyMarkup($cbId, $mess, ['inline_keyboard' => $keyboard]);
        // $this->sendMessage($cbId, $mess);
    }

    public function bangcuoc($cbId, $message_id, $user, $page = 0, $limit = 100)
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "bangcuoc"], env('CACHE_TIME_BOT', 24 * 60));
        $now = date("Y-m-d");
        if ($page == 0) {
            $page_info = DB::table('history')
                ->where('date', $now)
                ->where('money', '>', 0)
                ->where('user_create', $user->id)
                ->select('*')
                ->orderBy('created_at', 'des')
                ->paginate($limit);

            Cache::put('bangcuocPaging_total' . $user->id, $page_info->lastPage() - 1, env('CACHE_TIME_BOT', 24 * 60));
            //Log::info("bangcuocPaging_total " . $page_info->lastPage());
        }
        $rs =
            // DB::table('xoso_record')
            // // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
            // // ->orderBy('sumbet', 'desc')
            // ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            // ->where('isDelete', 0)
            // // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
            // // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
            // ->where('date', $now)
            // ->where('user_id', $user->id)
            // ->select('xoso_record.*', 'games.short_name as game')
            // ->orderBy('created_at', 'des')
            // // ->groupBy('game_id')
            // ->get();
            DB::table('history')
            // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
            // ->orderBy('sumbet', 'desc')
            // ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            // ->where('isDelete', 0)
            // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
            // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
            ->where('date', $now)
            ->where('money', '>', 0)
            ->where('user_create', $user->id)
            ->select('*')
            ->orderBy('created_at', 'des')
            // ->groupBy('game_id')
            ->skip($page * $limit)
            ->take($limit)
            ->get();

        // $data = [["Time", "Game", "Number", "Money", "Win/lose"]];
        $mess = "Tin cược " . "\n";
        $mess .= "************************************" . "\n";
        $count = count($rs);
        foreach ($rs as $record) {
            // array_push($data, [date("H:i", strtotime($record->created_at)), $this->vn_to_str($record->game), $record->bet_number, number_format($record->total_bet_money), number_format($record->total_win_money)]);
            $mess .= "Tin " . $count . "(" . date("H:i", strtotime($record->created_at)) . "): " . "<b>" . trim($record->content) . "</b>" . (isset($record->cancel) && strlen($record->cancel) > 0 ? "<i> Hủy " . $record->cancel . "</i>" : "") . "\n";
            $count--;
        }

        Cache::put('bangcuocPaging_currentpage' . $user->id, $page, env('CACHE_TIME_BOT', 24 * 60));
        $totalPage = Cache::get('bangcuocPaging_total' . $user->id, 1);
        $customkeyboardBangcuocPaging = array();

        if (count($rs) > 0) {
            if ($page > 0 && $page < $totalPage)
                $customkeyboardBangcuocPaging = array(
                    array(
                        array('text' => '<< Trước', 'callback_data' => 'Bangcuocpreviouspage_'),
                        array('text' => 'Sau >>', 'callback_data' => 'Bangcuocnextpage_'),
                    ),
                    array(
                        array('text' => '< Back', 'callback_data' => 'back'),
                        array('text' => 'Chi tiết', 'callback_data' => 'bangcuocchitiet'),
                    )
                );
            else if ($page == $totalPage)
                $customkeyboardBangcuocPaging = array(
                    array(
                        array('text' => '<< Trước', 'callback_data' => 'Bangcuocpreviouspage_'),
                    ),
                    array(
                        array('text' => '< Back', 'callback_data' => 'back'),
                        array('text' => 'Chi tiết', 'callback_data' => 'bangcuocchitiet'),
                    )
                );
            else if ($page == 0)
                $customkeyboardBangcuocPaging = array(
                    array(
                        array('text' => 'Sau >>', 'callback_data' => 'Bangcuocnextpage_'),
                    ),
                    array(
                        array('text' => '< Back', 'callback_data' => 'back'),
                        array('text' => 'Chi tiết', 'callback_data' => 'bangcuocchitiet'),
                    )
                );
            if ($totalPage == 0)
                $customkeyboardBangcuocPaging = array(
                    array(
                        array('text' => '< Back', 'callback_data' => 'back'),
                        array('text' => 'Chi tiết', 'callback_data' => 'bangcuocchitiet'),
                    )
                );
            //Log::info("current " . $page);
            $this->editMessageReplyMarkup($cbId, $message_id, $mess, ['inline_keyboard' => $customkeyboardBangcuocPaging]);
        } else {
            $keyboardBangcuoc =
                array(
                    array(
                        array('text' => '< Back', 'callback_data' => 'back'),
                        array('text' => 'Chi tiết', 'callback_data' => 'bangcuocchitiet'),
                    )
                );
            $this->editMessageReplyMarkup($cbId, $message_id, $mess, ['inline_keyboard' => $keyboardBangcuoc]);
        }
    }

    public function huycuoc_dongy($cbId, $message_id, $user, $mode = "edit")
    {
        $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
        $ids = explode(",", $stack_action_bot_tele[1]);
        //Log::info($stack_action_bot_tele[1]);
        if (count($ids) < 0) $ids = [$ids];
        foreach ($ids as $id)
            XoSoRecordHelpers::DeleteLotoByUser($id, $user);


        $this->banghuycuocchitiet($user, $cbId, $message_id);
        sleep(1);
        $this->deleteMessage($cbId, $message_id - 2);
        sleep(1);
        $this->deleteMessage($cbId, $message_id - 3);
        return;
    }

    public function huycuoc_step1($cbId, $message_id, $user, $mode = "edit")
    {
        Cache::put('stack_action_bot_tele' . $user->id, ["huycuoc"], env('CACHE_TIME_BOT', 24 * 60));
        $mess = "Nhập Id cược muốn hủy (có thể nhiều Id : ví dụ 1,2,3)";
        if ($mode == "edit")
            $this->editMessage($cbId, $message_id, $mess);
        else
            $this->sendMessage($cbId, $mess);
        return;
    }

    public function bangcuocchitiet($user, $cbId, $message_id, $page = 0, $limit = 100)
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "bangcuoc", "bangcuocchitiet"], env('CACHE_TIME_BOT', 24 * 60));
        $now = date("Y-m-d");
        if ($page == 0) {
            $page_info = DB::table('xoso_record')
                // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
                // ->orderBy('sumbet', 'desc')
                ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
                ->where('isDelete', 0)
                ->where('total_win_money', 0)
                // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
                // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
                ->where('date', $now)
                ->where('user_id', $user->id)
                ->select('xoso_record.*', 'games.short_name as game')
                ->orderBy('id', 'des')
                // ->skip($page*$limit)
                // ->take($limit)
                // ->paginate()
                // ->groupBy('game_id')
                ->paginate($limit);

            Cache::put('bangcuocchitietPaging_total' . $user->id, $page_info->lastPage() - 1, env('CACHE_TIME_BOT', 24 * 60));
            //Log::info($page_info->lastPage());
        }
        $rs =
            DB::table('xoso_record')
            // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
            // ->orderBy('sumbet', 'desc')
            ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            ->where('isDelete', 0)
            ->where('total_win_money', 0)
            // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
            // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
            ->where('date', $now)
            ->where('user_id', $user->id)
            ->select('xoso_record.*', 'games.short_name as game')
            ->orderBy('id', 'des')
            ->skip($page * $limit)
            ->take($limit)
            // ->paginate()
            // ->groupBy('game_id')
            ->get();
        // DB::table('history')
        // // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
        // // ->orderBy('sumbet', 'desc')
        // // ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
        // // ->where('isDelete', 0)
        // // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
        // // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
        // ->where('date', $now)
        // ->where('user_create', $user->id)
        // ->select('*')
        // ->orderBy('created_at', 'des')
        // // ->groupBy('game_id')
        // ->get();

        $data = [["Loại", "Số", "Điểm", "Giá"]];
        $mess = "Bảng cược chi tiết" . "\n";
        $count = 1;
        $maxLengthSplit = 5;
        foreach ($rs as $record) {
            $isSubMoney = false;
            $cancel_money = 0;
            $game_ = GameHelpers::GetGameByCode($record->game_id);
            if ($record->game_id >= 31 and $record->game_id <= 55) {
                $game_ = GameHelpers::GetGameByCode(24);
                $game_->close = "18:00";
            }
            $isShowName = true;
            if (
                $record->game_id < 100
                // && (strtotime($record->created_at) + (60 * 5) < strtotime(Carbon::now())
                //     || strtotime($record->created_at) + (60 * 5) > strtotime($game_->close)
                // )

            ) {
                if (strtotime($record->created_at) + (60 * 5) < strtotime(Carbon::now())) {
                    $isSubMoney = true;
                }
                if (strtotime($record->created_at) + (60 * 5) > strtotime($game_->close) || date("H") >= 18) {
                    $isSubMoney = false;
                    $cancel_money = "x";
                }
                // if ($record->total_win_money != 0 ){
                //     $isSubMoney = false;
                //     $cancel_money = "x";
                // }
            }

            if ($isSubMoney) {
                $ank = $this->Cal_Ank($record->game_id, $record->bet_number);
                // $y3 = GameHelpers::GetGameByGameCode($record->game_id)->y3;
                $y3 = 0;
                if ($record->game_id >= 31 and $record->game_id <= 55)
                    $y3 = GameHelpers::GetGameByGameCode(24)->y3;
                else {
                    switch ($record->game_id) {
                        case 17:
                        case 29:
                        case 56:
                        case 15:
                            $y3 = GameHelpers::GetGameByGameCode(14)->y3;
                            break;
                        case 19:
                        case 20:
                        case 21:
                            $y3 = GameHelpers::GetGameByGameCode(9)->y3;
                            break;
                        default:
                            $y3 = GameHelpers::GetGameByGameCode($record->game_id)->y3;
                            break;
                    }
                }
                $cancel_money = number_format(0 - $record->total_bet_money / $record->exchange_rates / $ank * $y3);
            } else {
                // $cancel_money = 0;
            }

            // if ($record->total_win_money != 0 ){
            //     $isSubMoney = false;
            //     $cancel_money = "x";
            // }

            if (strlen($record->bet_number) > $maxLengthSplit) {
                $temp = $record->bet_number;
                $countWhile = 0;
                while (true) {
                    $countWhile++;
                    if ($countWhile > 100) break;
                    $sub_temp = substr($temp, 0, $maxLengthSplit);
                    $temp = substr($temp, $maxLengthSplit);
                    array_push($data, [$isShowName ? $record->game : "", $sub_temp, $isShowName ? number_format($record->total_bet_money / $record->exchange_rates) : "", $isShowName ? number_format($record->exchange_rates) : ""]); //, $isShowName ? number_format($record->total_bet_money) : ""
                    $isShowName = false;
                    if (strlen($temp) == 0) break;
                    if ($temp[0] == ",") $temp = substr($temp, 1);
                    if (strlen($temp) <= $maxLengthSplit) {
                        $sub_temp = substr($temp, 0, strlen($temp));
                        array_push($data, [$isShowName ? $record->game : "", $sub_temp, $isShowName ? number_format($record->total_bet_money / $record->exchange_rates) : "", $isShowName ? number_format($record->exchange_rates) : ""]); //, $isShowName ? number_format($record->total_bet_money) : ""
                        break;
                    }
                }
            } else
                array_push($data, [$record->game, $record->bet_number, number_format($record->total_bet_money / $record->exchange_rates), number_format($record->exchange_rates)]); //, number_format($record->total_bet_money)
            // array_push($data, [$record['game_name'], $choices['name'], number_format($choices['total']), $record['status']]);


            // $mess .= "Tin ". $count . "(" . date("H:i", strtotime($record->created_at)) . "): " . trim($record->content) . "\n"; 
        }


        if (count($rs) > 0) {
            $mess .= "<pre>";
            $table = Tableify::new($data);
            $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
            $table_data = $table->toArray();
            foreach ($table_data as $row) {
                $mess .= $row . "\n";
                // echo $row . "\n";
            }
            $mess .= "</pre>";
            Cache::put('bangcuocchitietPaging_currentpage' . $user->id, $page, env('CACHE_TIME_BOT', 24 * 60));
            $totalPage = Cache::get('bangcuocchitietPaging_total' . $user->id, 1);
            $customkeyboardBangcuocchitietPaging = array();

            if ($page > 0 && $page < $totalPage)
                $customkeyboardBangcuocchitietPaging = array(
                    array(
                        array('text' => '<< Trước', 'callback_data' => 'Bangcuocchitietpreviouspage_'),
                        array('text' => 'Sau >>', 'callback_data' => 'Bangcuocchitietnextpage_'),
                    ),
                    array(
                        array('text' => '< Back', 'callback_data' => 'back'),
                        array('text' => 'Bảng hủy cược', 'callback_data' => 'banghuycuocchitiet'),
                    )
                );
            else if ($page == $totalPage)
                $customkeyboardBangcuocchitietPaging = array(
                    array(
                        array('text' => '<< Trước', 'callback_data' => 'Bangcuocchitietpreviouspage_'),
                        // array('text' => 'Sau >>', 'callback_data' => 'nextpage_'),
                    ),
                    array(
                        array('text' => '< Back', 'callback_data' => 'back'),
                        array('text' => 'Bảng hủy cược', 'callback_data' => 'banghuycuocchitiet'),
                    )
                );
            else if ($page == 0)
                $customkeyboardBangcuocchitietPaging = array(
                    array(
                        // array('text' => '<< Trước', 'callback_data' => 'previouspage_'),
                        array('text' => 'Sau >>', 'callback_data' => 'Bangcuocchitietnextpage_'),
                    ),
                    array(
                        array('text' => '< Back', 'callback_data' => 'back'),
                        array('text' => 'Bảng hủy cược', 'callback_data' => 'banghuycuocchitiet'),
                    )
                );
            if ($totalPage == 0)
                $customkeyboardBangcuocchitietPaging = array(
                    // array(
                    //     // array('text' => '<< Trước', 'callback_data' => 'previouspage_'),
                    //     array('text' => 'Sau >>', 'callback_data' => 'Bangcuocchitietnextpage_'),
                    // ),
                    array(
                        array('text' => '< Back', 'callback_data' => 'back'),
                        array('text' => 'Bảng hủy cược', 'callback_data' => 'banghuycuocchitiet'),
                    )
                );
            //Log::info("current " . $page);
            $this->editMessageReplyMarkup($cbId, $message_id, $mess, ['inline_keyboard' => $customkeyboardBangcuocchitietPaging]);
        } else {
            $this->editMessageReplyMarkup($cbId, $message_id, $mess, ['inline_keyboard' => $this->keyboardOnlyBack]);
        }
    }

    public function banghuycuocchitiet($user, $cbId, $message_id, $page = 0, $limit = 100)
    {
        Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "bangcuoc", "banghuycuocchitiet"], env('CACHE_TIME_BOT', 24 * 60));
        $now = date("Y-m-d");
        if ($page == 0) {
            $page_info = DB::table('xoso_record')
                // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
                // ->orderBy('sumbet', 'desc')
                ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
                ->where('isDelete', 0)
                ->where('total_win_money', 0)
                // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
                // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
                ->where('date', $now)
                ->where('user_id', $user->id)
                ->select('xoso_record.*', 'games.short_name as game')
                ->orderBy('id', 'des')
                // ->skip($page*$limit)
                // ->take($limit)
                // ->paginate()
                // ->groupBy('game_id')
                ->paginate($limit);

            Cache::put('banghuycuocchitietPaging_total' . $user->id, $page_info->lastPage() - 1, env('CACHE_TIME_BOT', 24 * 60));
            //Log::info($page_info->lastPage());
        }
        $rs =
            DB::table('xoso_record')
            // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
            // ->orderBy('sumbet', 'desc')
            ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            ->where('isDelete', 0)
            ->where('total_win_money', 0)
            // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
            // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
            ->where('date', $now)
            ->where('user_id', $user->id)
            ->select('xoso_record.*', 'games.short_name as game')
            ->orderBy('id', 'des')
            ->skip($page * $limit)
            ->take($limit)
            // ->paginate()
            // ->groupBy('game_id')
            ->get();
        // DB::table('history')
        // // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
        // // ->orderBy('sumbet', 'desc')
        // // ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
        // // ->where('isDelete', 0)
        // // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
        // // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
        // ->where('date', $now)
        // ->where('user_create', $user->id)
        // ->select('*')
        // ->orderBy('created_at', 'des')
        // // ->groupBy('game_id')
        // ->get();

        $data = [["Id", "Loại", "Số", "Điểm", "Phí hủy"]];
        $mess = "Bảng cược chi tiết" . "\n";
        $count = 1;
        $maxLengthSplit = 5;
        foreach ($rs as $record) {
            $isSubMoney = false;
            $cancel_money = 0;
            $game_ = GameHelpers::GetGameByCode($record->game_id);
            if ($record->game_id >= 31 and $record->game_id <= 55) {
                $game_ = GameHelpers::GetGameByCode(24);
                $game_->close = "18:00";
            }
            $isShowName = true;
            if (
                $record->game_id < 100
                // && (strtotime($record->created_at) + (60 * 5) < strtotime(Carbon::now())
                //     || strtotime($record->created_at) + (60 * 5) > strtotime($game_->close)
                // )

            ) {
                if (strtotime($record->created_at) + (60 * 5) < strtotime(Carbon::now())) {
                    $isSubMoney = true;
                }
                if (strtotime($record->created_at) + (60 * 5) > strtotime($game_->close) || date("H") >= 18) {
                    $isSubMoney = false;
                    $cancel_money = "x";
                    continue;
                }
                // if ($record->total_win_money != 0 ){
                //     $isSubMoney = false;
                //     $cancel_money = "x";
                // }
            }
            $count++;
            if ($isSubMoney) {
                $ank = $this->Cal_Ank($record->game_id, $record->bet_number);
                // $y3 = GameHelpers::GetGameByGameCode($record->game_id)->y3;
                $y3 = 0;
                if ($record->game_id >= 31 and $record->game_id <= 55)
                    $y3 = GameHelpers::GetGameByGameCode(24)->y3;
                else {
                    switch ($record->game_id) {
                        case 17:
                        case 29:
                        case 56:
                        case 15:
                            $y3 = GameHelpers::GetGameByGameCode(14)->y3;
                            break;
                        case 19:
                        case 20:
                        case 21:
                            $y3 = GameHelpers::GetGameByGameCode(9)->y3;
                            break;
                        default:
                            $y3 = GameHelpers::GetGameByGameCode($record->game_id)->y3;
                            break;
                    }
                }
                $cancel_money = number_format(0 - $record->total_bet_money / $record->exchange_rates * $y3); /// $ank
            } else {
                // $cancel_money = 0;
            }

            // if ($record->total_win_money != 0 ){
            //     $isSubMoney = false;
            //     $cancel_money = "x";
            // }

            if (strlen($record->bet_number) > $maxLengthSplit) {
                $temp = $record->bet_number;
                $countWhile = 0;
                while (true) {
                    $countWhile++;
                    if ($countWhile > 100) break;
                    $sub_temp = substr($temp, 0, $maxLengthSplit);
                    $temp = substr($temp, $maxLengthSplit);
                    array_push($data, [$isShowName ? $record->id : "", $isShowName ? $record->game : "", $sub_temp, $isShowName ? number_format($record->total_bet_money / $record->exchange_rates) : "", $isShowName ? $cancel_money : ""]);
                    $isShowName = false;
                    if (strlen($temp) == 0) break;
                    if ($temp[0] == ",") $temp = substr($temp, 1);
                    if (strlen($temp) <= $maxLengthSplit) {
                        $sub_temp = substr($temp, 0, strlen($temp));
                        array_push($data, [$isShowName ? $record->id : "", $isShowName ? $record->game : "", $sub_temp, $isShowName ? number_format($record->total_bet_money / $record->exchange_rates) : "", $isShowName ? $cancel_money : ""]);
                        break;
                    }
                }
            } else
                array_push($data, [$record->id, $record->game, $record->bet_number, number_format($record->total_bet_money / $record->exchange_rates), $cancel_money]);
            // array_push($data, [$record['game_name'], $choices['name'], number_format($choices['total']), $record['status']]);


            // $mess .= "Tin ". $count . "(" . date("H:i", strtotime($record->created_at)) . "): " . trim($record->content) . "\n"; 
        }


        if (count($rs) > 0) {
            $mess .= "<pre>";
            $table = Tableify::new($data);
            $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
            $table_data = $table->toArray();
            foreach ($table_data as $row) {
                $mess .= $row . "\n";
                // echo $row . "\n";
            }
            $mess .= "</pre>";
            Cache::put('banghuycuocchitietPaging_currentpage' . $user->id, $page, env('CACHE_TIME_BOT', 24 * 60));
            $totalPage = Cache::get('banghuycuocchitietPaging_total' . $user->id, 1);
            $customkeyboardBangcuocchitietPaging = array();

            if ($page > 0 && $page < $totalPage)
                $customkeyboardBangcuocchitietPaging = array(
                    array(
                        array('text' => '<< Trước', 'callback_data' => 'Banghuycuocchitietpreviouspage_'),
                        array('text' => 'Sau >>', 'callback_data' => 'Banghuycuocchitietnextpage_'),
                    ),
                    array(
                        array('text' => '< Back', 'callback_data' => 'back'),
                        array('text' => 'Hủy cược', 'callback_data' => 'huycuoc'),
                    )
                );
            else if ($page == $totalPage)
                $customkeyboardBangcuocchitietPaging = array(
                    array(
                        array('text' => '<< Trước', 'callback_data' => 'Banghuycuocchitietpreviouspage_'),
                        // array('text' => 'Sau >>', 'callback_data' => 'nextpage_'),
                    ),
                    array(
                        array('text' => '< Back', 'callback_data' => 'back'),
                        array('text' => 'Hủy cược', 'callback_data' => 'huycuoc'),
                    )
                );
            else if ($page == 0)
                $customkeyboardBangcuocchitietPaging = array(
                    array(
                        // array('text' => '<< Trước', 'callback_data' => 'previouspage_'),
                        array('text' => 'Sau >>', 'callback_data' => 'Banghuycuocchitietnextpage_'),
                    ),
                    array(
                        array('text' => '< Back', 'callback_data' => 'back'),
                        array('text' => 'Hủy cược', 'callback_data' => 'huycuoc'),
                    )
                );
            if ($totalPage == 0)
                $customkeyboardBangcuocchitietPaging = array(
                    // array(
                    //     // array('text' => '<< Trước', 'callback_data' => 'previouspage_'),
                    //     array('text' => 'Sau >>', 'callback_data' => 'Bangcuocchitietnextpage_'),
                    // ),
                    array(
                        array('text' => '< Back', 'callback_data' => 'back'),
                        array('text' => 'Hủy cược', 'callback_data' => 'huycuoc'),
                    )
                );
            //Log::info("current " . $page);
            $this->editMessageReplyMarkup($cbId, $message_id, $mess, ['inline_keyboard' => $customkeyboardBangcuocchitietPaging]);
        } else {
            $this->editMessageReplyMarkup($cbId, $message_id, $mess, ['inline_keyboard' => $this->keyboardOnlyBack]);
        }
    }

    public function bangcuochuycuocchitiet($user, $cbId, $message_id)
    {
        // Cache::put('stack_action_inline_bot_tele' . $user->id, ["trangchu", "bangcuoc", "bangcuocchitiet"], env('CACHE_TIME_BOT', 24 * 60));
        $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
        $ids = explode(",", $stack_action_bot_tele[1]);
        if (count($ids) < 0) $ids = [$ids];
        $now = date("Y-m-d");
        $rs =
            DB::table('xoso_record')
            ->select('xoso_record.*', 'games.short_name as game')
            ->whereIn('xoso_record.id', $ids)
            ->where('isDelete', 0)
            ->where('total_win_money', 0)
            ->where('date', $now)
            ->where('user_id', $user->id)
            // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
            // ->orderBy('sumbet', 'desc')
            ->join('games', 'xoso_record.game_id', '=', 'games.game_code')

            // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
            // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')


            ->orderBy('created_at', 'des')
            // ->groupBy('game_id')
            ->get();
        // DB::table('history')
        // // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
        // // ->orderBy('sumbet', 'desc')
        // // ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
        // // ->where('isDelete', 0)
        // // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
        // // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
        // ->where('date', $now)
        // ->where('user_create', $user->id)
        // ->select('*')
        // ->orderBy('created_at', 'des')
        // // ->groupBy('game_id')
        // ->get();
        // //Log::info($rs);
        // return;
        $data = [["Id", "Loại", "Số", "Điểm", "Giá", "Hủy"]];
        $mess = "Bảng hủy cược chi tiết" . "\n";
        $count = 1;
        $maxLengthSplit = 5;
        foreach ($rs as $record) {
            $isSubMoney = false;
            $cancel_money = "";
            $game_ = GameHelpers::GetGameByCode($record->game_id);
            if ($record->game_id >= 31 and $record->game_id <= 55) {
                $game_ = GameHelpers::GetGameByCode(24);
                $game_->close = "18:00";
            }

            $isShowName = true;
            if ($record->game_id < 100) {
                if (strtotime($record->created_at) + (60 * 5) < strtotime(Carbon::now())) {
                    $isSubMoney = true;
                }
                if (strtotime($record->created_at) + (60 * 5) > strtotime($game_->close)) {
                    $isSubMoney = false;
                    $cancel_money = "x";
                }

                // if ($record->total_win_money != 0 ){
                //     $isSubMoney = false;
                //     $cancel_money = "x";
                // }
            }

            if ($isSubMoney) {
                $ank = $this->Cal_Ank($record->game_id, $record->bet_number);
                $y3 = 0;
                if ($record->game_id >= 31 and $record->game_id <= 55)
                    $y3 = GameHelpers::GetGameByGameCode(24)->y3;
                else {
                    switch ($record->game_id) {
                        case 17:
                        case 29:
                            $y3 = GameHelpers::GetGameByGameCode(14)->y3;
                            break;
                        case 19:
                        case 20:
                        case 21:
                            $y3 = GameHelpers::GetGameByGameCode(9)->y3;
                            break;
                        default:
                            $y3 = GameHelpers::GetGameByGameCode($record->game_id)->y3;
                            break;
                    }
                }
                $cancel_money = number_format(0 - $record->total_bet_money / $record->exchange_rates / $ank * $y3);
            } else {
                // $cancel_money = 0;
            }
            // if ($record->total_win_money != 0 ){
            //             $isSubMoney = false;
            //             $cancel_money = "x";
            //         }
            if (strlen($record->bet_number) > $maxLengthSplit) {
                $temp = $record->bet_number;
                $countWhile = 0;
                while (true) {
                    $countWhile++;
                    if ($countWhile > 100) break;
                    $sub_temp = substr($temp, 0, $maxLengthSplit);
                    $temp = substr($temp, $maxLengthSplit);
                    array_push($data, [$isShowName ? $record->id : "", $isShowName ? $record->game : "", $sub_temp, $isShowName ? number_format($record->total_bet_money / $record->exchange_rates) : "", $isShowName ? number_format($record->exchange_rates) : "", $isShowName ? $cancel_money : ""]);
                    $isShowName = false;
                    if (strlen($temp) == 0) break;
                    if ($temp[0] == ",") $temp = substr($temp, 1);
                    if (strlen($temp) <= $maxLengthSplit) {
                        $sub_temp = substr($temp, 0, strlen($temp));
                        array_push($data, [$isShowName ? $record->id : "", $isShowName ? $record->game : "", $sub_temp, $isShowName ? number_format($record->total_bet_money / $record->exchange_rates) : "", $isShowName ? number_format($record->exchange_rates) : "", $isShowName ? $cancel_money : ""]);
                        break;
                    }
                }
            } else
                array_push($data, [$record->id, $record->game, $record->bet_number, number_format($record->total_bet_money / $record->exchange_rates), number_format($record->exchange_rates), $cancel_money]);
            // array_push($data, [$record['game_name'], $choices['name'], number_format($choices['total']), $record['status']]);


            // $mess .= "Tin ". $count . "(" . date("H:i", strtotime($record->created_at)) . "): " . trim($record->content) . "\n"; 
        }

        $mess .= "<pre>";
        if (count($rs) > 0) {
            $table = Tableify::new($data);
            $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
            $table_data = $table->toArray();
            foreach ($table_data as $row) {
                $mess .= $row . "\n";
                // echo $row . "\n";
            }
        }
        $mess .= "</pre>";
        $this->sendMessageReplyMarkup($cbId, $mess, ['inline_keyboard' => $this->keyboardHuycuocxacnhan]);
    }

    public function xemtruoccuoc($cbText, $user, $cbId, $cbMessageId)
    {
        $tincuoc = str_replace("Tin cược ", "", $cbText);
        $bet = array(array(), $tincuoc);
        try {
            $useLowPrice = Cache::get('useLowPrice_bot_tele' . $user->id, false);
            $bet = $this->quickbet->quickplaylogic($user, $tincuoc, '0', '', $useLowPrice);
        } catch (Exception $ex) {
            $bet = array(array(), $tincuoc);
        }
        if (count($bet[0]) == 0) {
            $mess = "Tin cược không đúng!" . "\n";
            $mess .= "Ví dụ: đề 79,97 x 100k" . "\n";
            $mess .= "Lô, đề 79,97 100k" . "\n";
            $mess .= "2 cửa đầu 1 x 100k";
            $this->sendMessage($cbId, $mess);
        } else {
            $data = [["Loại", "Số", "Điểm", "Giá", "TT"]];
            $maxLengthSplit = 5;
            foreach ($bet[0] as $record) {
                foreach ($record['choices'] as $choices)
                    if (strlen($choices['name'] > $maxLengthSplit)) {
                        $temp = $choices['name'];
                        $isShowName = true;
                        $countWhile = 0;
                        while (true) {
                            $countWhile++;
                            if ($countWhile > 100) break;
                            $sub_temp = substr($temp, 0, $maxLengthSplit);
                            $temp = substr($temp, $maxLengthSplit);
                            array_push($data, [$isShowName ? $record['game_name'] : "", $sub_temp, $isShowName ? number_format($choices['point']) : "", $isShowName ? number_format($choices['exchange']) : "", $isShowName ? $record['status'] : ""]);
                            $isShowName = false;
                            if (strlen($temp) == 0) break;
                            if ($temp[0] == ",") $temp = substr($temp, 1);
                            if (strlen($temp) <= $maxLengthSplit) {
                                $sub_temp = substr($temp, 0, strlen($temp));
                                array_push($data, [$isShowName ? $record['game_name'] : "", $sub_temp, $isShowName ? number_format($choices['point']) : "", $isShowName ? number_format($choices['exchange']) : "", $isShowName ? $record['status'] : ""]);
                                break;
                            }
                        }
                    } else
                        array_push($data, [$record['game_name'], $choices['name'], number_format($choices['point']), number_format($choices['exchange']), $record['status']]);
            }
            $list_tin_cuoc = [];
            $list_tin_huy = [];
            foreach ($bet[0] as $requestCuoc) {
                if ($requestCuoc['status'] == '' || $requestCuoc['status'] == 'ok')
                    array_push($list_tin_cuoc, $requestCuoc);
                else
                    array_push($list_tin_huy, $requestCuoc);
            }

            $mess = "Chi tiết tin cược " . "\n";
            $mess .= "************************************" . "\n";
            $mess .= $tincuoc . "\n";
            $mess .= "************************************" . "\n";
            $this->sendMessage($cbId, $mess);
            sleep(2);
            $mess = "";
            $tin_cuoc = $this->quickbet->revertquickplay($list_tin_cuoc, "\n");
            $tin_huy = $this->quickbet->revertquickplay($list_tin_huy, "\n");

            if ($tin_cuoc != "") {
                $mess1 = "<b>Tin nhận: \n" . $tin_cuoc . "</b> \n";
                // $mess .= "<b>Tin nhận: \n" . $tin_cuoc . "</b> \n";
                $this->sendMessage($cbId, $mess1);
                sleep(2);
            }
            if ($tin_huy != "") {
                $mess2 = "<i>Huỷ: \n" . $tin_huy . "</i>";
                // $mess .= "<i>Huỷ: \n" . $tin_huy . "</i>";
                $this->sendMessage($cbId, $mess2);
                sleep(2);
            }


            $mess .= "<pre>";
            $table = Tableify::new($data);
            $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
            $table_data = $table->toArray();
            $count = 0;
            foreach ($table_data as $key=>$row) {
                $mess .= $row . "\n";
                $count++;
                if ($count > 50){
                    $count=0;
                    $mess .= "</pre>" . "\n";
                    $this->sendMessage($cbId, $mess);
                    $mess = "";
                    $mess .= "<pre>";
                    sleep(1);
                }
            }
            $mess .= "</pre>" . "\n";
            $mess .= "Cập nhật: <b>" . date("H:i:s") . ":" . random_int(0, 1000) . "</b>";
            $this->deleteMessage($cbId, $cbMessageId);
            $this->sendMessageReplyMarkup($cbId, $mess, ['inline_keyboard' => $this->keyboardChitiettincuoc]);
        }
        return;
    }

    public function datcuocmanual_step1($cbId, $message_id, $user, $mode = "edit")
    {
        Cache::put('stack_action_bot_tele' . $user->id, ["datcuocmanual"], env('CACHE_TIME_BOT', 24 * 60));
        $mess = "Chọn thể loại cược";
        // $keyboard =
        //     array(
        //         array('Đề', "Lô","Nhất"),
        //         array('Xiên 2', "Xiên 3", "Xiên 4"),
        //         array('Xiên nháy','Xiên quay'),
        //         array('3 càng', 'Đề Nhất')
        //     );

        $keyboardChonTheloai =
            array(
                array(
                    array('text' => 'Đề', 'callback_data' => 'vaocuocchonlaitheloai_14'),
                    array('text' => 'Lô', 'callback_data' => 'vaocuocchonlaitheloai_7'),
                    array('text' => 'Nhất', 'callback_data' => 'vaocuocchonlaitheloai_12'),
                ),
                array(
                    array('text' => 'Xiên 2', 'callback_data' => 'vaocuocchonlaitheloai_9'),
                    array('text' => 'Xiên 3', 'callback_data' => 'vaocuocchonlaitheloai_10'),
                    array('text' => 'Xiên 4', 'callback_data' => 'vaocuocchonlaitheloai_11'),
                ),
                array(
                    array('text' => 'Xiên nháy', 'callback_data' => 'vaocuocchonlaitheloai_29'),
                    array('text' => 'Xiên quay', 'callback_data' => 'vaocuocchonlaitheloai_91011'),
                ),
                array(
                    array('text' => '3 càng', 'callback_data' => 'vaocuocchonlaitheloai_17'),
                    array('text' => 'Đề Nhất', 'callback_data' => 'vaocuocchonlaitheloai_1412'),
                ),

                // array(
                //     array('text' => 'Lô live', 'callback_data' => 'vaocuocchonlaitheloai_18'),
                //     array('text' => '3 càng nhất', 'callback_data' => 'vaocuocchonlaitheloai_56'),
                // ),

                // array(
                //     array('text' => 'Đề trượt', 'callback_data' => 'vaocuocchonlaitheloai_15'),
                // ),

                // array(
                //     array('text' => 'Lô trượt 1', 'callback_data' => 'vaocuocchonlaitheloai_16'),
                //     array('text' => 'Lô trượt', 'callback_data' => 'vaocuocchonlaitheloai_19'),
                //     array('text' => 'Lô trượt', 'callback_data' => 'vaocuocchonlaitheloai_20'),
                //     array('text' => 'Lô trượt', 'callback_data' => 'vaocuocchonlaitheloai_21'),
                // ),

                // array(
                //     array('text' => 'đầu thần tài', 'callback_data' => 'vaocuocchonlaitheloai_25'),
                //     array('text' => 'đuôi thần tài', 'callback_data' => 'vaocuocchonlaitheloai_26'),
                //     array('text' => 'đầu đặc biệt', 'callback_data' => 'vaocuocchonlaitheloai_27'),
                //     array('text' => 'đầu nhất', 'callback_data' => 'vaocuocchonlaitheloai_28'),
                // ),

                // array(
                //     array('text' => 'Giải 2.1', 'callback_data' => 'vaocuocchonlaitheloai_31'),
                //     array('text' => 'Giải 2.2', 'callback_data' => 'vaocuocchonlaitheloai_32'),
                // ),
                // array(
                //     array('text' => 'Giải 3.1', 'callback_data' => 'vaocuocchonlaitheloai_33'),
                //     array('text' => 'Giải 3.2', 'callback_data' => 'vaocuocchonlaitheloai_34'),
                //     array('text' => 'Giải 3.3', 'callback_data' => 'vaocuocchonlaitheloai_35'),
                //     array('text' => 'Giải 3.4', 'callback_data' => 'vaocuocchonlaitheloai_36'),
                //     array('text' => 'Giải 3.5', 'callback_data' => 'vaocuocchonlaitheloai_37'),
                //     array('text' => 'Giải 3.6', 'callback_data' => 'vaocuocchonlaitheloai_38'),
                // ),
                // array(
                //     array('text' => 'Giải 4.1', 'callback_data' => 'vaocuocchonlaitheloai_39'),
                //     array('text' => 'Giải 4.2', 'callback_data' => 'vaocuocchonlaitheloai_40'),
                //     array('text' => 'Giải 4.3', 'callback_data' => 'vaocuocchonlaitheloai_41'),
                //     array('text' => 'Giải 4.4', 'callback_data' => 'vaocuocchonlaitheloai_42'),
                // ),
                // array(
                //     array('text' => 'Giải 5.1', 'callback_data' => 'vaocuocchonlaitheloai_43'),
                //     array('text' => 'Giải 5.2', 'callback_data' => 'vaocuocchonlaitheloai_44'),
                //     array('text' => 'Giải 5.3', 'callback_data' => 'vaocuocchonlaitheloai_45'),
                //     array('text' => 'Giải 5.4', 'callback_data' => 'vaocuocchonlaitheloai_46'),
                //     array('text' => 'Giải 5.5', 'callback_data' => 'vaocuocchonlaitheloai_47'),
                //     array('text' => 'Giải 5.6', 'callback_data' => 'vaocuocchonlaitheloai_48'),
                // ),
                // array(
                //     array('text' => 'Giải 6.1', 'callback_data' => 'vaocuocchonlaitheloai_49'),
                //     array('text' => 'Giải 6.2', 'callback_data' => 'vaocuocchonlaitheloai_50'),
                //     array('text' => 'Giải 6.3', 'callback_data' => 'vaocuocchonlaitheloai_51'),
                // ),
                // array(
                //     array('text' => 'Giải 7.1', 'callback_data' => 'vaocuocchonlaitheloai_52'),
                //     array('text' => 'Giải 7.2', 'callback_data' => 'vaocuocchonlaitheloai_53'),
                //     array('text' => 'Giải 7.3', 'callback_data' => 'vaocuocchonlaitheloai_54'),
                //     array('text' => 'Giải 7.4', 'callback_data' => 'vaocuocchonlaitheloai_55'),

                // ),
                array(
                    array('text' => 'Xem thêm giải', 'callback_data' => 'vaocuocmanual_xemthemgiai'),
                ),

                array(
                    array('text' => '< Back', 'callback_data' => 'vaocuocmanual_huy'),
                ),
            );

        if ($mode == "edit")
            $this->editMessageReplyMarkup($cbId, $message_id, $mess, ['inline_keyboard' => $keyboardChonTheloai]);
        else
            $this->sendMessageReplyMarkup($cbId, $mess, ['inline_keyboard' => $keyboardChonTheloai]);
        return;
    }

    public function datcuocmanual_step1_more($cbId, $message_id, $user, $mode = "edit")
    {
        Cache::put('stack_action_bot_tele' . $user->id, ["datcuocmanual"], env('CACHE_TIME_BOT', 24 * 60));
        $mess = "Chọn thể loại cược";
        // $keyboard =
        //     array(
        //         array('Đề', "Lô","Nhất"),
        //         array('Xiên 2', "Xiên 3", "Xiên 4"),
        //         array('Xiên nháy','Xiên quay'),
        //         array('3 càng', 'Đề Nhất')
        //     );

        $keyboardChonTheloai =
            array(
                array(
                    array('text' => 'Đề', 'callback_data' => 'vaocuocchonlaitheloai_14'),
                    array('text' => 'Lô', 'callback_data' => 'vaocuocchonlaitheloai_7'),
                    array('text' => 'Nhất', 'callback_data' => 'vaocuocchonlaitheloai_12'),
                ),
                array(
                    array('text' => 'Xiên 2', 'callback_data' => 'vaocuocchonlaitheloai_9'),
                    array('text' => 'Xiên 3', 'callback_data' => 'vaocuocchonlaitheloai_10'),
                    array('text' => 'Xiên 4', 'callback_data' => 'vaocuocchonlaitheloai_11'),
                ),
                array(
                    array('text' => 'Xiên nháy', 'callback_data' => 'vaocuocchonlaitheloai_29'),
                    array('text' => 'Xiên quay', 'callback_data' => 'vaocuocchonlaitheloai_91011'),
                ),
                array(
                    array('text' => '3 càng', 'callback_data' => 'vaocuocchonlaitheloai_17'),
                    array('text' => 'Đề Nhất', 'callback_data' => 'vaocuocchonlaitheloai_1412'),
                ),

                array(
                    array('text' => 'Lô live', 'callback_data' => 'vaocuocchonlaitheloai_18'),
                    array('text' => '3 càng nhất', 'callback_data' => 'vaocuocchonlaitheloai_56'),
                ),

                array(
                    array('text' => 'Đề trượt', 'callback_data' => 'vaocuocchonlaitheloai_15'),
                ),

                array(
                    array('text' => 'Lô trượt 1', 'callback_data' => 'vaocuocchonlaitheloai_16'),
                    array('text' => 'Lô trượt 4', 'callback_data' => 'vaocuocchonlaitheloai_19'),
                ),
                array(
                    array('text' => 'Lô trượt 8', 'callback_data' => 'vaocuocchonlaitheloai_20'),
                    array('text' => 'Lô trượt 10', 'callback_data' => 'vaocuocchonlaitheloai_21'),
                ),

                array(
                    array('text' => 'đầu thần tài', 'callback_data' => 'vaocuocchonlaitheloai_25'),
                    array('text' => 'đuôi thần tài', 'callback_data' => 'vaocuocchonlaitheloai_26'),
                ),
                array(
                    array('text' => 'đầu đặc biệt', 'callback_data' => 'vaocuocchonlaitheloai_27'),
                    array('text' => 'đầu nhất', 'callback_data' => 'vaocuocchonlaitheloai_28'),
                ),

                array(
                    array('text' => 'Giải 2.1', 'callback_data' => 'vaocuocchonlaitheloai_31'),
                    array('text' => 'Giải 2.2', 'callback_data' => 'vaocuocchonlaitheloai_32'),
                ),
                array(
                    array('text' => 'Giải 3.1', 'callback_data' => 'vaocuocchonlaitheloai_33'),
                    array('text' => 'Giải 3.2', 'callback_data' => 'vaocuocchonlaitheloai_34'),
                    array('text' => 'Giải 3.3', 'callback_data' => 'vaocuocchonlaitheloai_35'),
                ),
                array(
                    array('text' => 'Giải 3.4', 'callback_data' => 'vaocuocchonlaitheloai_36'),
                    array('text' => 'Giải 3.5', 'callback_data' => 'vaocuocchonlaitheloai_37'),
                    array('text' => 'Giải 3.6', 'callback_data' => 'vaocuocchonlaitheloai_38'),
                ),
                array(
                    array('text' => 'Giải 4.1', 'callback_data' => 'vaocuocchonlaitheloai_39'),
                    array('text' => 'Giải 4.2', 'callback_data' => 'vaocuocchonlaitheloai_40'),
                    array('text' => 'Giải 4.3', 'callback_data' => 'vaocuocchonlaitheloai_41'),
                    array('text' => 'Giải 4.4', 'callback_data' => 'vaocuocchonlaitheloai_42'),
                ),
                array(
                    array('text' => 'Giải 5.1', 'callback_data' => 'vaocuocchonlaitheloai_43'),
                    array('text' => 'Giải 5.2', 'callback_data' => 'vaocuocchonlaitheloai_44'),
                    array('text' => 'Giải 5.3', 'callback_data' => 'vaocuocchonlaitheloai_45'),
                ),
                array(
                    array('text' => 'Giải 5.4', 'callback_data' => 'vaocuocchonlaitheloai_46'),
                    array('text' => 'Giải 5.5', 'callback_data' => 'vaocuocchonlaitheloai_47'),
                    array('text' => 'Giải 5.6', 'callback_data' => 'vaocuocchonlaitheloai_48'),
                ),
                array(
                    array('text' => 'Giải 6.1', 'callback_data' => 'vaocuocchonlaitheloai_49'),
                    array('text' => 'Giải 6.2', 'callback_data' => 'vaocuocchonlaitheloai_50'),
                    array('text' => 'Giải 6.3', 'callback_data' => 'vaocuocchonlaitheloai_51'),
                ),
                array(
                    array('text' => 'Giải 7.1', 'callback_data' => 'vaocuocchonlaitheloai_52'),
                    array('text' => 'Giải 7.2', 'callback_data' => 'vaocuocchonlaitheloai_53'),
                    array('text' => 'Giải 7.3', 'callback_data' => 'vaocuocchonlaitheloai_54'),
                    array('text' => 'Giải 7.4', 'callback_data' => 'vaocuocchonlaitheloai_55'),

                ),

                array(
                    array('text' => '< Back', 'callback_data' => 'vaocuocmanual_huy'),
                ),
            );

        if ($mode == "edit")
            $this->editMessageReplyMarkup($cbId, $message_id, $mess, ['inline_keyboard' => $keyboardChonTheloai]);
        else
            $this->sendMessageReplyMarkup($cbId, $mess, ['inline_keyboard' => $keyboardChonTheloai]);
        return;
    }

    public function datcuocmanual_step2($chatId, $message_id, $message, $mode = "edit")
    {
        $mess = "Bạn đang chọn cược " . $message . ": " . "\n";
        $mess .= "Nhập số cần cược : ";
        if ($mode == "edit")
            $this->editMessageReplyMarkup($chatId, $message_id, $mess, ["inline_keyboard" => $this->keyboardVaocuocTheloai]);
        else
            $this->sendMessageReplyMarkup($chatId, $mess, ["inline_keyboard" => $this->keyboardVaocuocTheloai]);
    }

    public function datcuocmanual_step3($chatId, $message_id, $message, $user, $mode = "edit")
    {
        $stack_action_bot_tele = Cache::get('stack_action_bot_tele' . $user->id);
        $mess = "Thể loại : " . $stack_action_bot_tele[1] . "\n";
        $mess .= "Số cược : " . $stack_action_bot_tele[2] . "\n";
        $mess .= "Nhập số điểm : ";
        if ($mode == "edit")
            $this->editMessageReplyMarkup($chatId, $message_id, $mess, ["inline_keyboard" => $this->keyboardVaocuocSochon]);
        else
            $this->sendMessageReplyMarkup($chatId, $mess, ["inline_keyboard" => $this->keyboardVaocuocSochon]);
    }

    public function vaocuoc($cbText, $user, $cbId, $cbMessageId)
    {
        $tincuoc = str_replace("Tin cược", "", $cbText);
        $tincuoc = trim($tincuoc);
        $bet = array(array(), $tincuoc);
        try {
            $useLowPrice = Cache::get('useLowPrice_bot_tele' . $user->id, false);
            $bet = $this->quickbet->quickplaylogic($user, $tincuoc, '1', '', $useLowPrice);
        } catch (Exception $ex) {
            $bet = array(array(), $tincuoc);
        }

        if (count($bet[0]) == 0) {
            $mess = "Tin cược không đúng!" . "\n";
            $mess .= "Ví dụ: đề 79,97 x 100k" . "\n";
            $mess .= "Lô, đề 79,97 100k" . "\n";
            $mess .= "2 cửa đầu 1 x 100k";
            $this->sendMessage($cbId, $mess);
        } else {
            $data = [["Loại", "Số", "Tiền cược", "TT"]];
            $maxLengthSplit = 5;
            $totalBet = 0;
            foreach ($bet[0] as $record) {
                foreach ($record['choices'] as $choices) {
                    if (strlen($choices['name'] > $maxLengthSplit)) {
                        $temp = $choices['name'];
                        $isShowName = true;
                        $countWhile = 0;
                        while (true) {
                            $countWhile++;
                            if ($countWhile > 100) break;
                            $sub_temp = substr($temp, 0, $maxLengthSplit);
                            $temp = substr($temp, $maxLengthSplit);
                            array_push($data, [$isShowName ? $record['game_name'] : "", $sub_temp, $isShowName ? number_format($choices['total']) : "", $isShowName ? $record['status'] : ""]);
                            $isShowName = false;
                            if (strlen($temp) == 0) break;
                            if ($temp[0] == ",") $temp = substr($temp, 1);
                            if (strlen($temp) <= $maxLengthSplit) {
                                $sub_temp = substr($temp, 0, strlen($temp));
                                array_push($data, [$isShowName ? $record['game_name'] : "", $sub_temp, $isShowName ? number_format($choices['total']) : "", $isShowName ? $record['status'] : ""]);
                                break;
                            }
                        }
                    } else
                        array_push($data, [$record['game_name'], $choices['name'], number_format($choices['total']), $record['status']]);

                    if ($record['status'] == '' || $record['status'] == 'ok') {
                        $totalBet += $choices['total'];
                    }
                }
            }

            $list_tin_cuoc = [];
            $list_tin_huy = [];
            foreach ($bet[0] as $requestCuoc) {
                if ($requestCuoc['status'] == '' || $requestCuoc['status'] == 'ok')
                    array_push($list_tin_cuoc, $requestCuoc);
                else
                    array_push($list_tin_huy, $requestCuoc);
            }
            $tin_cuoc = $this->quickbet->revertquickplay($list_tin_cuoc, "\n");
            $tin_huy = $this->quickbet->revertquickplay($list_tin_huy, "\n");

            $mess = "Thông tin vào cược " . "\n";
            $mess .= "************************************" . "\n";
            $mess .= $tincuoc . "\n";
            $mess .= "************************************" . "\n";

            // $this->sendMessage($cbId, $mess);
            // sleep(2);
            $mess = "";

            if ($tin_cuoc != "") {
                $mess .= "<b>Tin nhận: \n" . $tin_cuoc . "</b> \n";
                // $mess1 = "<b>Tin nhận: \n" . $tin_cuoc . "</b> \n";
                // $this->sendMessage($cbId, $mess1);
                // sleep(2);
            }

            $mess .= "<pre>";
            $table = Tableify::new($data);
            $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
            $table_data = $table->toArray();
            $count = 0;
            foreach ($table_data as $row) {
                $mess .= $row . "\n";
                $count++;
                if ($count > 50){
                    $count=0;
                    $mess .= "</pre>" . "\n";
                    $this->sendMessage($cbId, $mess);
                    $mess = "";
                    $mess .= "<pre>";
                    sleep(1);
                }
            }
            $mess .= "</pre>" . "\n";

            $mess .= "<b>Tổng $: " . number_format($totalBet) . "</b>";

            // $tin_cuoc = $this->revertquickplay($list_tin_cuoc);
            // $tin_huy = $this->revertquickplay($list_tin_huy);

            // $mess = "Tin cược " . "\n";
            // $mess .= $tin_cuoc . "\n" . "\n";
            // if ($tin_huy != ""){
            //     $mess .= "<i>Huỷ:";
            //     $mess .= $tin_huy . "</i>";
            // }

            // $keyboard = 
            // array(
            //     array(
            //         array('text' => 'vào cược', 'callback_data' => 'vaocuoc'),
            //         array('text' => 'chi tiết', 'callback_data' => 'chitietcuoc'),
            //         // array('text' => 'nhập lại', 'callback_data' => 'nhaplai'),

            //     ),
            //     // array(
            //     //     array('text' => 'Kết quả', 'url' => 'https://google.com'),
            //     //     array('text' => 'Thông số', 'url' => 'https://google.com'),
            //     // ),
            // );
            // $this->sendMessageReplyMarkup($cbId,$mess,['inline_keyboard' => $keyboard]);
            if (isset($cbMessageId))
                $this->deleteMessage($cbId, $cbMessageId);
            $this->sendMessage($cbId, $mess);

            if ($tin_huy != "") {
                $mess = "<i>Huỷ: \n" . $tin_huy . "</i>";
                sleep(1);
                $this->sendMessage($cbId, $mess);
            }

            return;
        }
    }

    public function vaocuoc_trolymb($cbText, $user, $cbId, $cbMessageId)
    {
        $tincuoc = str_replace("Tin cược", "", $cbText);
        $tincuoc = trim($tincuoc);
        $bet = array(array(), $tincuoc);
        try {
            $useLowPrice = Cache::get('useLowPrice_bot_tele' . $user->id, false);
            $bet = $this->quickbet->quickplaylogic($user, $tincuoc, '1', '', $useLowPrice);
        } catch (Exception $ex) {
            $bet = array(array(), $tincuoc);
        }

        if (count($bet[0]) == 0) {
            $mess = "Tin cược không đúng!" . "\n";
            $mess .= "Ví dụ: đề 79,97 x 100k" . "\n";
            $mess .= "Lô, đề 79,97 100k" . "\n";
            $mess .= "2 cửa đầu 1 x 100k";
            $this->sendMessage($cbId, $mess);
        } else {
            $data = [["Loại", "Số", "Tiền cược", "TT"]];
            $maxLengthSplit = 5;
            $totalBet = 0;
            foreach ($bet[0] as $record) {
                foreach ($record['choices'] as $choices) {
                    if (strlen($choices['name'] > $maxLengthSplit)) {
                        $temp = $choices['name'];
                        $isShowName = true;
                        $countWhile = 0;
                        while (true) {
                            $countWhile++;
                            if ($countWhile > 100) break;
                            $sub_temp = substr($temp, 0, $maxLengthSplit);
                            $temp = substr($temp, $maxLengthSplit);
                            array_push($data, [$isShowName ? $record['game_name'] : "", $sub_temp, $isShowName ? number_format($choices['total']) : "", $isShowName ? $record['status'] : ""]);
                            $isShowName = false;
                            if (strlen($temp) == 0) break;
                            if ($temp[0] == ",") $temp = substr($temp, 1);
                            if (strlen($temp) <= $maxLengthSplit) {
                                $sub_temp = substr($temp, 0, strlen($temp));
                                array_push($data, [$isShowName ? $record['game_name'] : "", $sub_temp, $isShowName ? number_format($choices['total']) : "", $isShowName ? $record['status'] : ""]);
                                break;
                            }
                        }
                    } else
                        array_push($data, [$record['game_name'], $choices['name'], number_format($choices['total']), $record['status']]);

                    if ($record['status'] == '' || $record['status'] == 'ok') {
                        $totalBet += $choices['total'];
                    }
                }
            }

            $list_tin_cuoc = [];
            $list_tin_huy = [];
            foreach ($bet[0] as $requestCuoc) {
                if ($requestCuoc['status'] == '' || $requestCuoc['status'] == 'ok')
                    array_push($list_tin_cuoc, $requestCuoc);
                else
                    array_push($list_tin_huy, $requestCuoc);
            }
            $tin_cuoc = $this->quickbet->revertquickplay($list_tin_cuoc, "\n");
            $tin_huy = $this->quickbet->revertquickplay($list_tin_huy, "\n");

            $rs =
                DB::table('history')
                ->where('date', date('Y-m-d'))
                ->where('user_create', $user->id)
                ->select('id')
                ->get();

            $count = count($rs);
            $mess = "Tin " . $count . ": " . $tincuoc . "\n";
            $mess .= "************************************" . "\n";
            if ($tin_cuoc != "") {
                $mess .= "<b>Đã nhận: \n" . $tin_cuoc . "</b> \n";
            }
            if (isset($cbMessageId))
                $this->deleteMessage($cbId, $cbMessageId);
            $this->sendMessage($cbId, $mess);

            if ($tin_huy != "") {
                $mess = "<i>Trả lại: \n" . $tin_huy . "</i>";
                sleep(1);
                $this->sendMessage($cbId, $mess);
            }
            // $mess .= "<pre>";
            // $table = Tableify::new($data);
            // $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
            // $table_data = $table->toArray();
            // foreach ($table_data as $row) {
            //     $mess .= $row . "\n";
            //     // echo $row . "\n";
            // }
            // $mess .= "</pre>" . "\n";

            // $mess .= "<b>Tổng $: " . number_format($totalBet) . "</b>";

            // $tin_cuoc = $this->revertquickplay($list_tin_cuoc);
            // $tin_huy = $this->revertquickplay($list_tin_huy);

            // $mess = "Tin cược " . "\n";
            // $mess .= $tin_cuoc . "\n" . "\n";
            // if ($tin_huy != ""){
            //     $mess .= "<i>Huỷ:";
            //     $mess .= $tin_huy . "</i>";
            // }

            // $keyboard = 
            // array(
            //     array(
            //         array('text' => 'vào cược', 'callback_data' => 'vaocuoc'),
            //         array('text' => 'chi tiết', 'callback_data' => 'chitietcuoc'),
            //         // array('text' => 'nhập lại', 'callback_data' => 'nhaplai'),

            //     ),
            //     // array(
            //     //     array('text' => 'Kết quả', 'url' => 'https://google.com'),
            //     //     array('text' => 'Thông số', 'url' => 'https://google.com'),
            //     // ),
            // );
            // $this->sendMessageReplyMarkup($cbId,$mess,['inline_keyboard' => $keyboard]);
            // if (isset($cbMessageId))
            //     $this->deleteMessage($cbId, $cbMessageId);
            // $this->sendMessage($cbId, $mess);
            return;
        }
    }

    private function trathuongmienbac($cbId, $cbMessageId, $dateNow){
        $this->editMessage($cbId, $cbMessageId, "Đang xử lý!");
        ini_set('memory_limit', '-1');
        $xoso = new XoSo();
        $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
        $channelid = "-1001667315543";
        
        // Tra thuong mien bắc
        $rs = $xoso->getKetQua(1,$dateNow);
        $records = XoSoRecordHelpers::GetByDate($dateNow,1);

        $xoso = new XoSo();

        $now = $dateNow;
        $kqxs = XoSoResult::where('location_id', 1)
            ->where('date', $now)->get();

        $yesterday = date('Y-m-d', strtotime($now. ' -1 day'));

        $kqxs_yesterday = XoSoResult::where('location_id', 1)
        ->where('date', $yesterday)->get();
        // // var_dump($kqxs);
        if (count($kqxs) > 0 && $xoso->fullKq($kqxs) && (isset($kqxs[0]->DB) && $kqxs_yesterday[0]->DB != $kqxs[0]->DB ) ) {
            XoSoRecordHelpers::trathuong($records,$rs,$now);
            if (count($records) > 0){
                HistoryHelpers::notification2User();
                NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, 'Trả thưởng Miền Bắc hoàn thành! ' . $dateNow);
                $this->editMessage($cbId, $cbMessageId, "Trả thưởng Miền Bắc hoàn thành! " . $dateNow);
            }
        }else{
            NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, 'Trả thưởng Miền Bắc không hoàn thành! Lỗi kết quả ' . $dateNow);
            $this->editMessage($cbId, $cbMessageId, "Trả thưởng Miền Bắc không hoàn thành! Lỗi kết quả. " . $dateNow);
        }
        
    }

    public function xosobot_quanlyso()
    {
        try {
            $update = json_decode(file_get_contents("php://input"), TRUE);

            // Log::info($update);
            if ($this->callback($update)) {
                $cbId = $update["callback_query"]["from"]["id"]; //'';//
                $cbData = $update["callback_query"]["data"]; //'';//
                $cbText = $update["callback_query"]["message"]["text"];
                $cbMessageId = $update["callback_query"]["message"]["message_id"];
                $username = $update["callback_query"]["from"]["username"]; //'';//
                $roleIds = [1];

                // $user = UserHelpers::GetUserByFullNameRoleIds($username, $roleIds);

                // if (!isset($user)) {
                //     $this->sendMessage($cbId, "Tài khoản chưa được liên kết hãy nhập token từ quản lý.");
                //     return;
                // } else if ($user->lock == 2 || $user->lock == 3) {
                //     $this->sendMessage($cbId, "Tài khoản đã bị khoá.");
                //     return;
                // }

                // if ($this->bot_type == "trolymb")
                //     if ($user->roleid != 1) {
                //         $this->sendMessage($cbId, "Phiên bản chỉ hỗ trợ cho admin.");
                //         return;
                //     }

                // $user->latestlogin = date("Y-m-d H:i:s");
                // $user->chat_id = $cbId;
                // $user->save();
                if ($username != "Zokerzzz888" && $username != "satoshiyama"){
                    $this->sendMessage($cbId, "Tài khoản chưa được liên kết.");
                    return;
                }

                switch ($cbData) {
                    case 'nhapsohuy':
                        $this->editMessage($cbId, $cbMessageId, "Nhập số huỷ: ");
                        Cache::put('stack_action_bot_tele_Zokerzzz888', ['nhapsohuy'], env('CACHE_TIME_BOT', 24 * 60));
                        break;
                    case 'sokhoado':
                        $this->editMessage($cbId, $cbMessageId, "Nhập số khoá đỏ : ");
                        Cache::put('stack_action_bot_tele_Zokerzzz888', ['sokhoado'], env('CACHE_TIME_BOT', 24 * 60));
                        break;

                    case 'xacnhan_nhapsohuy':
                        $this->editMessage($cbId, $cbMessageId, "Đang Xử lý hủy cược ...");
                        // $game = Game::where('game_code',7)->first();
                        $stack_action_bot_tele = Cache::get('stack_action_bot_tele_Zokerzzz888');
                        $locknumberred = $stack_action_bot_tele[1];
                        $locknumberred = str_replace(" ","",$locknumberred);
                        // $game->locknumberred = $locknumberred;
                        // $game->save();
                        $totalBetCount = XoSoRecordHelpers::CheckXosoRecord($locknumberred);
                        $this->editMessage($cbId, $cbMessageId, "Xử lý hủy cược thành công ". $totalBetCount . " mã!");
                        break;
                    case 'xacnhan_sokhoado':
                        Cache::put('xacnhan_sokhoado_bot', true, env('CACHE_TIME', 12*60));
                        $game = Game::where('game_code',7)->first();
                        $stack_action_bot_tele = Cache::get('stack_action_bot_tele_Zokerzzz888');
                        $locknumberred = $stack_action_bot_tele[1];
                        $locknumberred = str_replace(" ","",$locknumberred);
                        $game->locknumberred = $locknumberred;
                        $game->save();
                        $this->editMessage($cbId, $cbMessageId, "Đã lưu khóa đỏ!");
                        break;

                    case 'xacnhan_huysokhoado':
                        Cache::put('xacnhan_sokhoado_bot', true, env('CACHE_TIME', 12*60));
                        $game = Game::where('game_code',7)->first();
                        $game->locknumberred = null;
                        $game->save();
                        $this->editMessage($cbId, $cbMessageId, "Đã hủy toàn bộ số khóa đỏ!");
                        break;

                    case 'xacnhan_trathuong':
                        $now = date('Y-m-d');
                        $hour = date('H');
                        $minus = date('i');
                        if($hour>18 || ($hour==18 && $minus>35)){
                            $this->trathuongmienbac($cbId, $cbMessageId, date("Y-m-d"));
                        }else{
                            if ($hour >= 0 && $hour <= 10){
                                $datetime = new DateTime('yesterday');
            	                $yesterday = $datetime->format('Y-m-d');
                                $this->trathuongmienbac($cbId, $cbMessageId, $yesterday);
                            }else
                                $this->editMessage($cbId, $cbMessageId, "Chưa đủ kết quả để Trả thưởng Miền Bắc!");
                        }
                        break;

                    case 'back':
                        $this->editMessage($cbId, $cbMessageId, "Hi!");
                        break;
                    default:
                }
                return;
            }

            if (isset($update["inline_query"])) {
                $chatId = $update["inline_query"]["from"]["id"]; //5381486859;//
                $username = $update["inline_query"]["from"]["username"]; //'';//
                $query = $update["inline_query"]["query"];
                $roleIds = [6];
                switch ($this->bot_type) {
                    case 'quanlyso':
                        $roleIds = [1];
                        break;
                    default:
                        # code...
                        break;
                }
                // $user = UserHelpers::GetUserByFullNameRoleIds($username, $roleIds);
                // if (!isset($user)) {
                //     $this->sendMessage($chatId, "Bạn chưa kích hoạt trợ lý ảo. Hãy nhập key ở dưới để kích hoạt !");
                // } else
                // if ($user->lock == 2 || $user->lock == 3)
                //     $this->sendMessage($chatId, "Tài khoản đã bị khoá.");
                // else
                //     $this->sendMessage($chatId, $user->name);
                // return;
                if ($username != "Zokerzzz888" && $username != "satoshiyama"){
                    $this->sendMessage($chatId, "Tài khoản chưa được liên kết.");
                    return;
                }
            }

            if (isset($update["message"])) {
                $chatId = isset($update["message"]) ? $update["message"]["chat"]["id"] : ''; //5381486859;//
                $message = isset($update["message"]) ? $update["message"]["text"] : ''; //'';//
                $messageId = isset($update["message"]) ? $update["message"]["message_id"] : ''; //'';//
                $username = isset($update["message"]["from"]["username"]) ? $update["message"]["from"]["username"] : ''; //'';//
                if ($username == '') {
                    $this->sendMessage($chatId, 'Bạn chưa có Username telegram. Hãy đặt Username và khởi động lại trợ lý ảo. Cài đặt username của tài khoản telegram theo hướng dẫn https://www.youtube.com/watch?v=FKMTzgJ1Cww và /start để bắt đầu sử dụng.');
                    return;
                }
                $roleIds = [1];
                // switch ($this->bot_type) {
                //     case 'agent_member':
                //         $roleIds = [6,5];
                //         break;
                //     case 'admin_super_master':
                //             $roleIds = [1,2,4];
                //             break;
                //     default:
                //         # code...
                //         break;
                // }
                // $user = UserHelpers::GetUserByFullNameRoleIds($username, $roleIds);
                // if (!isset($user)) {
                //     $checkTokenUser = User::where('token_bot_tele', $message)->whereIn('roleid', $roleIds)->first();

                //     if (isset($checkTokenUser)) {
                //         $checkTokenUser->fullname = $username;
                //         $checkTokenUser->chat_id = $chatId;
                //         $checkTokenUser->save();
                //         $user = $checkTokenUser;
                //         $this->sendMessage($chatId, 'Bot Quản lý số đang hoạt động. Xin nhập yêu cầu bên dưới. (/start để bắt đầu sử dụng)');
                //     } else {
                //         $this->sendMessage($chatId, "Bạn chưa kích hoạt Bot Quản lý số. Hãy nhập key ở dưới để kích hoạt !");
                //         return;
                //     }
                // } {
                //     if ($user->lock == 2 || $user->lock == 3) {
                //         $this->sendMessage($chatId, "Tài khoản đã bị khoá.");
                //         return;
                //     }

                //     if ($user->roleid != 1) {
                //         $this->sendMessage($chatId, "Phiên bản chỉ hỗ trợ cho admin.");
                //         return;
                //     }

                //     $this->xosobot_quanlyso_message($chatId, $messageId, $message, $user);

                //     $user->chat_id = $chatId;
                //     $user->save();
                //     return;
                // }
                if ($username != "Zokerzzz888" && $username != "satoshiyama"){
                    $this->sendMessage($chatId, "Tài khoản chưa được liên kết.");
                    return;
                }
                $this->xosobot_quanlyso_message($chatId, $messageId, $message, "Zokerzzz888");
            }
        } catch (\Exception $ex) {
            Log::info($ex->getMessage() . ' ' . $ex->getFile() . ' ' . $ex->getLine());
        }
    }

    private function xosobot_quanlyso_message($chatId, $messageId, $message, $user)
    {
        if (strpos($message, "/start") === 0) {
            // $mess = '<b>Hãy liên kết tài khoản và bắt đầu sử dụng bằng cách lệnh /thongtin  /vaocuoc</b>';
            $this->sendMessage($chatId, "Bot quản lý số đang hoạt động! Hi " . $user);
            return;
        }

        if (strpos($message, "/nhapsohuy") === 0) {
            // $mess = '<b>Hãy liên kết tài khoản và bắt đầu sử dụng bằng cách lệnh /thongtin  /vaocuoc</b>';
            $this->sendMessage($chatId, "Nhập số huỷ: ");
            Cache::put('stack_action_bot_tele_Zokerzzz888', ['nhapsohuy'], env('CACHE_TIME_BOT', 24 * 60));
            return;
        }

        if (strpos($message, "/sokhoado") === 0) {
            // $mess = '<b>Hãy liên kết tài khoản và bắt đầu sử dụng bằng cách lệnh /thongtin  /vaocuoc</b>';
            $this->sendMessage($chatId, "Nhập số khoá đỏ : ");
            Cache::put('stack_action_bot_tele_Zokerzzz888', ['sokhoado'], env('CACHE_TIME_BOT', 24 * 60));
            return;
        }

        if (strpos($message, "/huykhoado") === 0) {
            // $mess = '<b>Hãy liên kết tài khoản và bắt đầu sử dụng bằng cách lệnh /thongtin  /vaocuoc</b>';
            $keyboardXacnhanNhapsohuy =
                    array(
                        array(
                            array('text' => 'Hủy số khóa đỏ', 'callback_data' => 'xacnhan_huysokhoado'),
                        ),
                        array(
                            array('text' => 'Bỏ qua', 'callback_data' => 'back'),
                        )
                    );

            $mess = "Bạn có muốn hủy số khoá đỏ?";
            $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboardXacnhanNhapsohuy]);

            return;
        }

        if (strpos($message, "/trathuong") === 0) {
            // $mess = '<b>Hãy liên kết tài khoản và bắt đầu sử dụng bằng cách lệnh /thongtin  /vaocuoc</b>';
            $keyboardXacnhanNhapsohuy =
                    array(
                        array(
                            array('text' => 'Trả thưởng', 'callback_data' => 'xacnhan_trathuong'),
                        ),
                        array(
                            array('text' => 'Bỏ qua', 'callback_data' => 'back'),
                        )
                    );

            $mess = "Bạn có muốn trả thưởng?";
            $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboardXacnhanNhapsohuy]);

            return;
        }

        $stack_action_bot_tele = Cache::get('stack_action_bot_tele_Zokerzzz888');

        if (isset($stack_action_bot_tele) && count($stack_action_bot_tele) > 0 && $stack_action_bot_tele[0] == "nhapsohuy") {
            array_push($stack_action_bot_tele, $message);
            Cache::put('stack_action_bot_tele_Zokerzzz888', $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

            if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số

                $keyboardXacnhanNhapsohuy =
                    array(
                        array(
                            array('text' => 'Huỷ cược', 'callback_data' => 'xacnhan_nhapsohuy'),
                        ),
                        array(
                            array('text' => 'Nhập lại', 'callback_data' => 'nhapsohuy'),
                        )
                    );

                $mess = "Đã Nhập số huỷ: " . $stack_action_bot_tele[1];
                $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboardXacnhanNhapsohuy]);
                // Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                return;
            }
        }

        if (isset($stack_action_bot_tele) && count($stack_action_bot_tele) > 0 && $stack_action_bot_tele[0] == "sokhoado") {
            array_push($stack_action_bot_tele, $message);
            Log::info($message);
            Log::info($stack_action_bot_tele);
            Cache::put('stack_action_bot_tele_Zokerzzz888', $stack_action_bot_tele, env('CACHE_TIME_BOT', 24 * 60));

            if (isset($stack_action_bot_tele[1])) { //chọn xong thể loại, chuyển sang nhập số
                // $this->bangcuochuycuocchitiet($user, $chatId, $messageId);
                $keyboardXacnhanNhapsokhoado =
                    array(
                        array(
                            array('text' => 'Khóa đỏ', 'callback_data' => 'xacnhan_sokhoado'),
                        ),
                        array(
                            array('text' => 'Nhập lại', 'callback_data' => 'sokhoado'),
                        )
                    );

                $mess = "Đã Nhập số khoá đỏ: " . $stack_action_bot_tele[1];
                $this->sendMessageReplyMarkup($chatId, $mess, ['inline_keyboard' => $keyboardXacnhanNhapsokhoado]);
                // Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                return;
            }
        }

        $this->sendMessage($chatId, "Bot quản lý số đang hoạt động! Hi " . $user . ".");
        return;
    }

    public function xosobot_nhantinmb()
    {
        try {
            $update = json_decode(file_get_contents("php://input"), TRUE);
            if (!isset($this->quickbet)) {
                $this->quickbet = new QuickbetHelpers();
            }
            // Log::info($update);
            if ($this->callback($update)) {
                $cbId = $update["callback_query"]["from"]["id"]; //'';//
                $cbData = $update["callback_query"]["data"]; //'';//
                $cbText = $update["callback_query"]["message"]["text"];
                $cbMessageId = $update["callback_query"]["message"]["message_id"];
                $username = $update["callback_query"]["from"]["username"]; //'';//
                $roleIds = [6];
                // switch ($this->bot_type) {
                //     case 'agent_member':
                //         $roleIds = [6,5];
                //         break;
                //     case 'admin_super_master':
                //             $roleIds = [1,2,4];
                //             break;
                //     default:
                //         # code...
                //         break;
                // }
                $user = UserHelpers::GetUserByFullNameRoleIds($username, $roleIds);

                if (!isset($user)) {
                    $this->sendMessage($cbId, "Tài khoản chưa được liên kết hãy nhập token từ quản lý.");
                    return;
                } else if ($user->lock == 2 || $user->lock == 3) {
                    $this->sendMessage($cbId, "Tài khoản đã bị khoá.");
                    return;
                }

                if ($this->bot_type == "trolymb")
                    if ($user->roleid != 6) {
                        $this->sendMessage($cbId, "Phiên bản chỉ hỗ trợ cho member.");
                        return;
                    }

                $user->latestlogin = date("Y-m-d H:i:s");
                $user->chat_id = $cbId;
                $user->bot_tele_type = $this->bot_type;
                $user->save();
                switch ($user->roleid) {
                    case 6:
                        $this->xosobot_member_nhantin_callback($cbId, $cbData, $cbMessageId, $user);
                        break;
                    default:
                        # code...
                        break;
                }
                return;
            }

            if (isset($update["inline_query"])) {
                $chatId = $update["inline_query"]["from"]["id"]; //5381486859;//
                $username = $update["inline_query"]["from"]["username"]; //'';//
                $query = $update["inline_query"]["query"];
                $roleIds = [6];
                switch ($this->bot_type) {
                    case 'trolymb':
                        $roleIds = [6];
                        break;
                    default:
                        # code...
                        break;
                }
                $user = UserHelpers::GetUserByFullNameRoleIds($username, $roleIds);
                if (!isset($user)) {
                    $this->sendMessage($chatId, "Bạn chưa kích hoạt trợ lý ảo. Hãy nhập key ở dưới để kích hoạt !");
                } else
                if ($user->lock == 2 || $user->lock == 3)
                    $this->sendMessage($chatId, "Tài khoản đã bị khoá.");
                else
                    $this->sendMessage($chatId, $user->name);
                return;
            }

            if (isset($update["message"])) {
                $chatId = isset($update["message"]) ? $update["message"]["chat"]["id"] : ''; //5381486859;//
                $message = isset($update["message"]) ? $update["message"]["text"] : ''; //'';//
                $messageId = isset($update["message"]) ? $update["message"]["message_id"] : ''; //'';//
                $username = isset($update["message"]["from"]["username"]) ? $update["message"]["from"]["username"] : ''; //'';//
                if ($username == '') {
                    $this->sendMessage($chatId, 'Bạn chưa có Username telegram. Hãy đặt Username và khởi động lại trợ lý ảo. Cài đặt username của tài khoản telegram theo hướng dẫn https://www.youtube.com/watch?v=FKMTzgJ1Cww và /start để bắt đầu sử dụng.');
                    return;
                }
                $roleIds = [6];
                // switch ($this->bot_type) {
                //     case 'agent_member':
                //         $roleIds = [6,5];
                //         break;
                //     case 'admin_super_master':
                //             $roleIds = [1,2,4];
                //             break;
                //     default:
                //         # code...
                //         break;
                // }
                $user = UserHelpers::GetUserByFullNameRoleIds($username, $roleIds);
                if (!isset($user)) {
                    $checkTokenUser = User::where('token_bot_tele', $message)->whereIn('roleid', $roleIds)->first();

                    if (isset($checkTokenUser)) {
                        $checkTokenUser->fullname = $username;
                        $checkTokenUser->chat_id = $chatId;
                        $checkTokenUser->bot_tele_type = $this->bot_type;
                        $checkTokenUser->save();
                        $user = $checkTokenUser;
                        $this->sendMessage($chatId, 'Trợ lý ảo đang hoạt động. Xin nhập yêu cầu bên dưới. (/start để bắt đầu sử dụng)');
                    } else {
                        $this->sendMessage($chatId, "Bạn chưa kích hoạt trợ lý ảo. Hãy nhập key ở dưới để kích hoạt !");
                        return;
                    }
                } {
                    if ($user->lock == 2 || $user->lock == 3) {
                        $this->sendMessage($chatId, "Tài khoản đã bị khoá.");
                        return;
                    }

                    if ($user->roleid != 6) {
                        $this->sendMessage($chatId, "Phiên bản chỉ hỗ trợ cho member.");
                        return;
                    }

                    switch ($user->roleid) {
                        case 6:
                            $this->xosobot_member_nhantinmb_message($chatId, $messageId, $message, $user);
                            break;
                        default:
                            # code...
                            break;
                    }

                    $user->chat_id = $chatId;
                    $user->bot_tele_type = $this->bot_type;
                    $user->save();
                    return;
                }
            }
        } catch (\Exception $ex) {
            Log::info($ex->getMessage() . ' ' . $ex->getFile() . ' ' . $ex->getLine());
        }
    }

    private function xosobot_member_nhantinmb_message($chatId, $messageId, $message, $user)
    {
        if (strpos($message, "/start") === 0) {
            // $mess = '<b>Hãy liên kết tài khoản và bắt đầu sử dụng bằng cách lệnh /thongtin  /vaocuoc</b>';
            $this->sendMessage($chatId, "Nhắn tin Miền Bắc đang hoạt động! Hi " . $user->name);
            return;
        }
        // $this->deleteMessage($chatId, $messageId - 1);
        $keyboard =
            array(
                array(
                    array('text' => 'Gửi tin', 'callback_data' => 'vaocuoc'),
                    array('text' => 'Hủy', 'callback_data' => 'vaocuoc_huy'),
                    // array('text' => 'Nhập lại tin', 'callback_data' => 'nhaplaitin'),
                    // array('text' => 'nhập lại', 'callback_data' => 'nhaplai'),

                )
                // ,
                // array(
                //     array('text' => 'Hủy', 'callback_data' => 'vaocuocmanual_huy'),
                // ),
            );
        // $old = Cache::get('quick_bet_text_bot_tele' . $user->id, '');
        // $mess = $old . " " . $message;
        Cache::put('quick_bet_text_bot_tele' . $user->id, $message, env('CACHE_TIME_BOT', 24 * 60));

        //$this->vaocuoc_trolymb($quick_bet_text_bot_tele, $user, $cbId, $cbMessageId);
        $data = new History();
        $data->date = date("Y-m-d");
        $data->type = "Miền Bắc";
        $data->content = $message;
        // $data->ids = $ids;
        // $data->money = $money;
        $data->user_create = $user->id;
        $data->is_done = 0;
        $data->id_inday = History::where("user_create",$user->id)->where("date",date("Y-m-d"))->count() + 1;
        $data->source_bet = 1;
        // $data->cancel = $text_cancel;

        try{
            $bet_edit = $message;
            $quickbet = new QuickbetHelpers();
            $userMember = UserHelpers::GetUserById($data->user_create);
            $inBet = $quickbet->quickplaylogic($userMember, $bet_edit, '0', '', false);
            if (count($inBet[0]) == 0) {
            } else {
                $list_tin_cuoc = [];
                $list_tin_huy = [];
                $notes = "";
                foreach ($inBet[0] as $requestCuoc) {
                    if ($requestCuoc['status'] == '' || $requestCuoc['status'] == 'ok')
                        array_push($list_tin_cuoc, $requestCuoc);
                    else{
                        array_push($list_tin_huy, $requestCuoc);
                        $notes .= $requestCuoc['status'] . "\n";
                    }
                }

                $tin_cuoc = $quickbet->revertquickplay($list_tin_cuoc, "\n");
                $tin_huy = $quickbet->revertquickplay($list_tin_huy, "\n");

                $data->money = $inBet[2];
                if($tin_huy != "") {
                    $data->transition = 'Trả lại tin: '.$tin_cuoc;  
                    $data->transition .= '<br>' . 'Tin hủy: '.$tin_huy;
                    $message = "Tin gửi: ". $message . "\n". "Tin hủy: " .$tin_huy . $notes;
                    $this->sendMessageReplyMarkup($chatId, $message, ['inline_keyboard' => $keyboard]);
                    return;
                }else{
                    $data->transition = $tin_cuoc; 
                }
            }
        }catch(Exception $ex){
            $data->transition = "Tin dịch lỗi.";
            Log::info($ex->getMessage());
        }
        $data->save();
        Cache::put('stack_action_bot_tele' . $user->id, ["cuocnhanh"], env('CACHE_TIME_BOT', 24 * 60));
        Cache::forget('quick_bet_text_bot_tele' . $user->id);

        $this->sendMessage($chatId, "Đã gửi tin ". $data->id_inday .". Chờ xác nhận!");

        $message = "Có tin ". $data->id_inday ." của " . $user->name ." gửi đến. Chờ xác nhận!";
        HistoryHelpers::sendMessageToMembersTreeAgent($user,$message);

        // $this->sendMessage($chatId,$mess);
        return;
    }
    
    private function xosobot_member_nhantin_callback($cbId, $cbData, $cbMessageId, $user)
    {
        switch ($cbData) {
            case 'cuocnhanh':
            case 'nhaplaitin':
                if ($user->lock == 1 || $user->lock == 3) {
                    $this->sendMessage($cbId, "Tài khoản đã bị ngừng đặt.");
                    return;
                }
                Cache::put('stack_action_bot_tele' . $user->id, ["cuocnhanh"], env('CACHE_TIME_BOT', 24 * 60));
                Cache::forget('quick_bet_text_bot_tele' . $user->id);
                // $mess = "Nhập vào biểu mẫu theo đúng mẫu : " . "\n" . "Thể loại – số cược – điểm cược" . "\n";
                // $mess .= "Ví dụ: đề 79,97 x 100k" . "\n";
                // $mess .= "Lô, đề 79,97 100k" . "\n";
                // $mess .= "2 cửa đầu 1 x 100k";

                // $this->editMessageReplyMarkup($cbId, $cbMessageId, $mess,  ['inline_keyboard' => $this->keyboardHuongdancuoc]);
                break;

            case 'vaocuoc':
                $this->vaocuocNhantinmb($user,$cbId,$cbMessageId);
                break;

            case 'vaocuoc_huy':
                $quick_bet_text_bot_tele = Cache::get('quick_bet_text_bot_tele' . $user->id, '');
                $this->editMessage($cbId, $cbMessageId, "Huỷ thành công: ".$quick_bet_text_bot_tele);
                Cache::put('stack_action_bot_tele' . $user->id, [], env('CACHE_TIME_BOT', 24 * 60));
                Cache::forget('quick_bet_text_bot_tele' . $user->id);
                break;

            default:
                break;
        }
        return;
    }

    private function vaocuocNhantinmb($user,$cbId,$cbMessageId){
        if ($user->lock == 1 || $user->lock == 3) {
            $this->sendMessage($cbId, "Tài khoản đã bị ngừng đặt.");
            return;
        }
        $quick_bet_text_bot_tele = Cache::get('quick_bet_text_bot_tele' . $user->id, '');
        if ($quick_bet_text_bot_tele == "") {
            $this->sendMessage($cbId, "Tin cược bị lỗi. Vui lòng nhập lại!");
            return;
        }
        //$this->vaocuoc_trolymb($quick_bet_text_bot_tele, $user, $cbId, $cbMessageId);
        $data = new History();
        $data->date = date("Y-m-d");
        $data->type = "Miền Bắc";
        $data->content = $quick_bet_text_bot_tele;
        // $data->ids = $ids;
        // $data->money = $money;
        $data->user_create = $user->id;
        $data->is_done = 0;
        $data->id_inday = History::where("user_create",$user->id)->where("date",date("Y-m-d"))->count() + 1;
        $data->source_bet = 1;
        // $data->cancel = $text_cancel;

        try{
            $bet_edit = $quick_bet_text_bot_tele;
            $quickbet = new QuickbetHelpers();
            $userMember = UserHelpers::GetUserById($data->user_create);
            $inBet = $quickbet->quickplaylogic($userMember, $bet_edit, '0', '', false);
            if (count($inBet[0]) == 0) {
            } else {
                $list_tin_cuoc = [];
                $list_tin_huy = [];
                $notes = "";
                foreach ($inBet[0] as $requestCuoc) {
                    if ($requestCuoc['status'] == '' || $requestCuoc['status'] == 'ok')
                        array_push($list_tin_cuoc, $requestCuoc);
                    else{
                        array_push($list_tin_huy, $requestCuoc);
                        $notes .= $requestCuoc['status'] . "\n";
                    }
                }

                $tin_cuoc = $quickbet->revertquickplay($list_tin_cuoc, "\n");
                $tin_huy = $quickbet->revertquickplay($list_tin_huy, "\n");

                $data->transition = 'Tin nhận: '.$tin_cuoc;  
                $data->money = $inBet[2];
                if($tin_huy != "")   $data->transition .= '<br>' . 'Tin hủy: '.$tin_huy;  
                if($tin_huy != "") {
                    // $message = "Trả lại tin!". "\n". "Tin hủy: " .$tin_huy . $notes;
                    // $this->editMessage($cbId, $cbMessageId, $message);
                    // return;
                }
            }
        }catch(Exception $ex){
            $data->transition = "Tin dịch lỗi.";
            Log::info($ex->getMessage());
        }
        $data->save();
        Cache::put('stack_action_bot_tele' . $user->id, ["cuocnhanh"], env('CACHE_TIME_BOT', 24 * 60));
        Cache::forget('quick_bet_text_bot_tele' . $user->id);

        $this->editMessage($cbId, $cbMessageId, "Đã gửi tin ". $data->id_inday .". Chờ xác nhận!");

        $message = "Có tin ". $data->id_inday ." của " . $user->name ." gửi đến. Chờ xác nhận!";
        HistoryHelpers::sendMessageToMembersTreeAgent($user,$message);
        return;
    }
}
