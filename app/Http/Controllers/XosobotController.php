<?php

namespace App\Http\Controllers;

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
use App\Helpers\XosobotHelpers;
use App\Helpers\XoSoRecordHelpers;
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
use Illuminate\Support\Str;

class XosobotController extends Controller
{
    private $tokenBot_agent_member = "6031440319:AAENrVaJ_TOLxQ75L8e2eDjW0_A_bzTnS2Q";
    private $tokenBot_admin_super_master = "6241857190:AAHCxFRIcZItEvFDnk7ntXAZx978Y5wykNg";
    private $tokenBot_super_trolymb = "6329319864:AAEaIGUAnzIlxP2lCpMEt5w4QOS4dQPLOn0";
    private $tokenBot_luckey = "6625058071:AAEgdD1qZ033OWSR8nzNk4EXXh15XG0kl5o";
    private $tokenBot_agent = "5863257778:AAHOs8X9Cjsr3IcUBQy5QSO1piG4wvU5FnQ";
    private $tokenBot_quanlyso = "6498493818:AAGzOgpykLtCEWhQrsNH1GYWLdyc37O4_bo";
    private $tokenBot_nhantinmb = "6690018393:AAG8W2f_upUTJOufNBFLa81xnA1YbBHXoi8";
    
    public function xosobot()
    {
        $xosobC = new XosobotHelpers($this->tokenBot_agent_member,"agent_member_1");
        $xosobC->xosobot_agent_member();
        return "xosobot";
    }

    public function xosobot_agent()
    {
        $xosobC = new XosobotHelpers($this->tokenBot_agent,"agent");
        $xosobC->xosobot_agent();
        return "xosobot_agent";
    }

    public function luckeybot()
    {
        $xosobC = new XosobotHelpers($this->tokenBot_luckey,"agent_member_2");
        $xosobC->xosobot_agent_member();
        return "luckeybot";
    }

    public function xosobotasm()
    {
        $xosobC = new XosobotHelpers($this->tokenBot_admin_super_master,"admin_super_master");
        $xosobC->xosobot_admin_super_master();
        return "xosobotasm";
    }

    public function xosobottrolymb()
    {
        $xosobC = new XosobotHelpers($this->tokenBot_super_trolymb,"trolymb");
        $xosobC->xosobot_trolymb();
        return "xosobottrolymb";
    }

    public function xosobotquanlyso()
    {
        $xosobC = new XosobotHelpers($this->tokenBot_quanlyso,"quanlyso");
        $xosobC->xosobot_quanlyso();
        return "xosobotquanlyso";
    }

    public function xosobotnhantinmb()
    {
        $xosobC = new XosobotHelpers($this->tokenBot_nhantinmb,"nhantinmb");
        $xosobC->xosobot_nhantinmb();
        return "xosobotnhantinmb";
    }
}
