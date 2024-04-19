<?php
/**
 * Created by PhpStorm.
 * User: Satoshi
 * Date: 9/21/2016
 * Time: 11:02 AM
 */

namespace App;
use App\Location;
use App\Role;
use App\User;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Helpers\UserHelpers;
use App\Helpers\RoleHelpers;

class ChucNang
{
    public $chucnangs =array
    (
        'Home' =>array
        (
            'code'=>'-1',
            'name'=>'Bàn làm việc',
            'url'=>'admin',
            'active'=>'',
            'icon'=>'ti-panel',
            'selectedkey'=>'#',
            'children'=>array()
        ),
        'Thongke' =>array
        (
            'code'=>'100',
            'name'=>'THỐNG KÊ',
            'url'=>'#',
            'active'=>'',
            'icon'=>'fa fa-book',
            'selectedkey'=>'thongke',
            'children'=>array(
                '0'=>array
                (
                    'code'=>'101',
                    'name'=>'THEO MÃ MIỀN BẮC',
                    'url'=>'admin/thongketheoma',
                    'active'=>'',
                    'selectedkey'=>'thongketheoma',
                ),
                '1'=>array
                (
                    'code'=>'102',
                    'name'=>'HOẠT ĐỘNG',
                    'url'=>'admin/thongkehoatdong',
                    'active'=>'',
                    'selectedkey'=>'thongkehoatdong',
                ),
                // '3'=>array
                // (
                //     'code'=>'103',
                //     'name'=>'BẢNG THẦU',
                //     'url'=>'admin/bangthau',
                //     'active'=>'',
                //     'selectedkey'=>'bangthau',
                // ),
            )
        ),
        'Thau' =>array
        (
            'code'=>'400',
            'name'=>'Quản lý thầu',
            'url'=>'#',
            'active'=>'',
            'icon'=>'fa fa-book',
            'selectedkey'=>'thau',
            'children'=>array(
                '0'=>array
                (
                    'code'=>'401',
                    'name'=>'BẢNG THẦU',
                    'url'=>'admin/bangthau',
                    'active'=>'',
                    'selectedkey'=>'bangthau1',
                ),
                '1'=>array
                (
                    'code'=>'402',
                    'name'=>'XUẤT SỐ',
                    'url'=>'admin/xuatso',
                    'active'=>'',
                    'selectedkey'=>'xuatso',
                )
            )
        ),
        'Thongbao' =>array
        (
            'code'=>'300',
            'name'=>'THÔNG BÁO',
            'url'=>'#',
            'active'=>'',
            'icon'=>'fa fa-bell',
            'selectedkey'=>'thongbao',
            'children'=>array(
                '0'=>array
                (
                    'code'=>'301',
                    'name'=>'THÔNG BÁO CƯỢC',
                    'url'=>'notification/list-bet',
                    'active'=>'',
                    'selectedkey'=>'list-bet',
                )
            )
        ),
        'Item' =>array
        (
            'code'=>'1',
            'name'=>'TÀI KHOẢN',
            'url'=>'#',
            'active'=>'',
            'icon'=>'fa fa-user',
            'selectedkey'=>'users',
            'children'=>array(
                '0'=>array
                (
                    'code'=>'11',
                    'name'=>'Quản lý tài khoản',
                    'url'=>'users',
                    'active'=>'',
                    'selectedkey'=>'users',
                ),
                // '1'=>array
                // (
                //     'code'=>'16',
                //     'name'=>'Thông số tài khoản',
                //     'url'=>'customer-type/original',
                //     'active'=>'',
                //     'selectedkey'=>'customer-type/original',
                // ),
                '2'=>array
                (
                    'code'=>'17',
                    'name'=>'Tài khoản phụ',
                    'url'=>'users/user-second',
                    'active'=>'',
                    'selectedkey'=>'users/user-second',
                ),

                
                // '2'=>array
                // (
                //     'code'=>'14',
                //     'name'=>'Tài khoản phụ',
                //     'url'=>'users',
                //     'active'=>'',
                //     'selectedkey'=>'users',
                // ),
                '4'=>array
                (
                    'code'=>'15',
                    'name'=>'Thông số tài khoản',
                    'url'=>'users11',
                    'active'=>'',
                    'selectedkey'=>'users11',
                ),
                '5'=>array
                (
                    'code'=>'12',
                    'name'=>'Phân quyền',
                    'url'=>'role',
                    'active'=>'',
                    'selectedkey'=>'role',
                ),
                '6'=>array
                (
                    'code'=>'18',
                    'name'=>'Quản lý bộ số',
                    'url'=>'/qlboso',
                    'active'=>'',
                    'selectedkey'=>'qlboso',
                ),
                '7'=>array
                (
                    'code'=>'19',
                    'name'=>'Thay đổi mật khẩu',
                    'url'=>'/changepw',
                    'active'=>'',
                    'selectedkey'=>'changepw',
                ),
                '8'=>array
                (
                    'code'=>'41',
                    'name'=>'Thay đổi OTP',
                    'url'=>'/ggauth',
                    'active'=>'',
                    'selectedkey'=>'ggauth',
                ),
            )
        ),
        'ControlP' =>array
        (
            'code'=>'2',
            'name'=>'BẢNG THAO TÁC',
            'url'=>'#',
            'active'=>'',
            'icon'=>'fa fa-list-alt',
            'selectedkey'=>'controlP',
            'children'=>array(
                '0'=>array
                (
                    'code'=>'21',
                    'name'=>'Bảng thao tác giá',
                    'url'=>'control-price',
                    'active'=>'',
                    'selectedkey'=>'control-price',
                ),
                '1'=>array
                (
                    'code'=>'22',
                    'name'=>'Bảng thao tác chuẩn',
                    'url'=>'customer-type',
                    'active'=>'',
                    'selectedkey'=>'customer-type',
                ),
                '2'=>array
                (
                    'code'=>'23',
                    'name'=>'Bảng thao tác giới hạn cược',
                    'url'=>'control-max',
                    'active'=>'',
                    'selectedkey'=>'control-max',
                ),
                '3'=>array
                (
                    'code'=>'24',
                    'name'=>'Bảng thao tác tự động lên giá',
                    'url'=>'control-auto-price',
                    'active'=>'',
                    'selectedkey'=>'control-auto-price',
                ),
                // '4'=>array
                // (
                //     'code'=>'25',
                //     'name'=>'Bảng thao tác giới hạn lên giá',
                //     'url'=>'control-ex',
                //     'active'=>'',
                //     'selectedkey'=>'control-ex',
                // ),

                // '4'=>array
                // (
                //     'code'=>'211',
                //     'name'=>'Bảng thao tác giá XS Ảo',
                //     'url'=>'control-price/xs-ao',
                //     'active'=>'',
                //     'selectedkey'=>'control-price/xs-ao',
                // ),

                // '5'=>array
                // (
                //     'code'=>'221',
                //     'name'=>'Bảng thao tác chuẩn XS Ảo',
                //     'url'=>'customer-type/normal-xs-ao',
                //     'active'=>'',
                //     'selectedkey'=>'customer-type/normal-xs-ao',
                // ),
                // '6'=>array
                // (
                //     'code'=>'231',
                //     'name'=>'Bảng thao tác giới hạn cược XS Ảo',
                //     'url'=>'control-max/xs-ao',
                //     'active'=>'',
                //     'selectedkey'=>'control-max/xs-ao',
                // ),
                // '7'=>array
                // (
                //     'code'=>'241',
                //     'name'=>'Bảng thao tác tự động lên giá XS Ảo',
                //     'url'=>'control-auto-price/xs-ao',
                //     'active'=>'',
                //     'selectedkey'=>'control-auto-price/xs-ao',
                // ),
            )
        ),
        'ConfirmBet' =>array
        (
            'code'=>'6',
            'name'=>'QUẢN LÝ TIN CƯỢC',
            'url'=>'games/confirm-bet',
            'active'=>'',
            'icon'=>'fa fa-list-alt',
            'selectedkey'=>'confirmBet',
            'children'=>array(
                // '0'=>array
                // (
                //     'code'=>'61',
                //     'name'=>'Tin cược chờ',
                //     'url'=>'games/confirm-bet?status=0',
                //     'active'=>'',
                //     'selectedkey'=>'confirm-bet?status=0',
                // ),
                // '1'=>array
                // (
                //     'code'=>'62',
                //     'name'=>'Tin cược đã xử lý',
                //     'url'=>'games/confirm-bet?status=1',
                //     'active'=>'',
                //     'selectedkey'=>'confirm-bet?status=1',
                // )
            )
        ),
        'Customer-Type' =>array
        (
            'code'=>'5',
            'name'=>'BẢNG THAO TÁC CHUẨN',
            'url'=>'customer-type',
            'active'=>'',
            'icon'=>'fa fa-list-alt',
            'selectedkey'=>'customer-type-normal',
            'children'=>array()
        ),
        'Report' =>array
        (
            'code'=>'3',
            'name'=>'BẢNG BIỂU',
            'url'=>'#',
            'active'=>'',
            'icon'=>'fa fa-calendar',
            'selectedkey'=>'#',
            'children'=>array
            (
                '0'=>array
                (
                    'code'=>'32',
                    'name'=>'Bảng cược chưa xử lý',
                    'url'=>'rp/bettoday',
                    'active'=>'',
                    'selectedkey'=>'rp/bettoday',
                ),
                '1'=>array
                (
                    'code'=>'33',
                    'name'=>'Đơn hàng đã hủy',
                    'url'=>'rp/betcancel',
                    'active'=>'',
                    'selectedkey'=>'rp/betcancel',
                ),
                '2'=>array
                (
                    'code'=>'31',
                    'name'=>'Hội viên thắng thua',
                    'url'=>'rp/winlose',
                    'active'=>'',
                    'selectedkey'=>'rp/winlose',
                ),
            )
        ),
        // 'System' =>array
        // (
        //     'code'=>'4',
        //     'name'=>'THIẾT LẬP BẢO MẬT',
        //     'url'=>'#',
        //     'active'=>'',
        //     'icon'=>'fa fa-lock',
        //     'selectedkey'=>'ggauth',
        //     'children'=>array
        //     (
                
        //         // '0'=>array
        //         // (
        //         //     'code'=>'40',
        //         //     'name'=>'Thay đổi mật khẩu',
        //         //     'url'=>'#',
        //         //     'active'=>'',
        //         //     'selectedkey'=>'#',
        //         // ),
        //         '0'=>array
        //         (
        //             'code'=>'41',
        //             'name'=>'Thay đổi OTP',
        //             'url'=>'/ggauth',
        //             'active'=>'',
        //             'selectedkey'=>'#',
        //         )
        //     )
        // ),

        'Notification' =>array
        (
            'code'=>'200',
            'name'=>'THIẾT LẬP THÔNG BÁO',
            'url'=>'/notification',
            'active'=>'',
            'icon'=>'fa fa-lock',
            'selectedkey'=>'notification',
            'children'=>array
            (
                '0'=>array
                (
                    'code'=>'201',
                    'name'=>'Tạo thông báo',
                    'url'=>'notification/create',
                    'active'=>'',
                    'selectedkey'=>'#create_notification',
                ),
                '1'=>array
                (
                    'code'=>'202',
                    'name'=>'Danh sách thông báo',
                    'url'=>'notification/list',
                    'active'=>'',
                    'selectedkey'=>'#list_notification',
                )
            )
        ),

    );
    /*
     *Hàm xử lí việc check user đã login hay chưa và gọi hàm BuildMenu
     * */
    public function LoadMenuAndCheckLogin()
    {
        $userName = \Session::get('username');

        $users = UserHelpers::GetUserByUserName($userName);
        $stack = array();
        foreach ($users as $user)
        {
            $functions = RoleHelpers::getFunctionInRole($user->roleid);
            $stack = $this->buildLeftMenu($functions);
        }
        return $stack;

    }

    public function handleUserSecond($funcID){
        if (Session::get('usersecondper') == 0)
            return true; 
            
        if (Session::get('usersecondper') == 1 && Session::get('usersecondrole2') == 1 
             && $funcID !='4' && $funcID !='17'){ //&& $funcID !='41'
            return true;
        }

        if (Session::get('usersecondper') == 1 && Session::get('usersecondrole2') == 2){
            if ($funcID=='1'  || $funcID=='11' ||$funcID=='18' ||$funcID=='41')
                return true;
        }

        if (Session::get('usersecondper') == 1 && Session::get('usersecondrole2') == 3){
            if ($funcID=='3' ||$funcID=='31' ||$funcID=='32' ||$funcID=='33' ||$funcID=='41')
                return true;
        }

        return false;
    }    /*
     * Hàm xử lí Build lại menu theo các điều kiện active và theo role
     * */
    public function buildLeftMenu($functions)
    {
        $stack = array();
        $current_url= Route::getFacadeRoot()->current()->uri();
        
        $userName = \Session::get('username');

        $users = UserHelpers::GetUserByUserName($userName)[0];
        // echo $users->name;
        foreach ($this->chucnangs as $cn)
        {

            if (!$this->handleUserSecond($cn['code'])){
                    continue;
            }
            if(strpos($functions,",".$cn['code'].","))
            {
                $new_cn =$cn;
                $new_children=array();
                foreach ($cn['children'] as $child)
                {
                    if (!$this->handleUserSecond($child['code'])){
                        continue;
                    }
                    
                    if(strpos($current_url, $child['selectedkey']) !== false ){
                        $new_cn['active']='active';
                        $child['active']='active';
                    }
                    
                    array_push($new_children,$child);
                }
                if(strpos($current_url, $new_cn['selectedkey']) !== false ){
                    $new_cn['active']='active';
                }
                $new_cn['children']=$new_children;
                array_push($stack,$new_cn);
            }
            else
            {
                $new_children=array();
                $activeFlag = false;
                foreach ($cn['children'] as $child)
                {
                    if (!$this->handleUserSecond($child['code'])){
                        continue;
                    }
                    if(strpos($functions,$child['code']))
                    {
                        if(strpos($current_url, $child['selectedkey']) !== false)
                        {
                            
                            if (strpos($current_url,'original') !==false ){
                                $activeFlag = $child['code']=='16' ? true : false;
                            }else{
                                $activeFlag = true;
                            }
                            
                            if (strpos($current_url,'user-second') !==false ){
                                if($child['code']=='17'){
                                    $child['active']='active';
                                }
                            }else{
                                $child['active']='active';
                            }
                            
                        }
                        
                        array_push($new_children,$child);
                    }
                }
                if(count($new_children)>0)
                {
                    $new_cn =$cn;
                    $new_cn['children']=$new_children;
                    if($activeFlag)
                    {
                        $new_cn['active']='active';
                    }
                    array_push($stack,$new_cn);
                }
            }
        }
        // var_dump($stack);
        // die();
        return $stack;
    }
}