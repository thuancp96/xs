@extends('frontend.frontend-template')
@section('content')

<style>
    :root {
        --portal-wap-primary: 76 158 234;
    }

    *,
    :after,
    :before {
        --tw-border-spacing-x: 0;
        --tw-border-spacing-y: 0;
        --tw-translate-x: 0;
        --tw-translate-y: 0;
        --tw-rotate: 0;
        --tw-skew-x: 0;
        --tw-skew-y: 0;
        --tw-scale-x: 1;
        --tw-scale-y: 1;
        --tw-pan-x: ;
        --tw-pan-y: ;
        --tw-pinch-zoom: ;
        --tw-scroll-snap-strictness: proximity;
        --tw-gradient-from-position: ;
        --tw-gradient-via-position: ;
        --tw-gradient-to-position: ;
        --tw-ordinal: ;
        --tw-slashed-zero: ;
        --tw-numeric-figure: ;
        --tw-numeric-spacing: ;
        --tw-numeric-fraction: ;
        --tw-ring-inset: ;
        --tw-ring-offset-width: 0px;
        --tw-ring-offset-color: #fff;
        --tw-ring-color: rgba(59, 130, 246, .5);
        --tw-ring-offset-shadow: 0 0 #0000;
        --tw-ring-shadow: 0 0 #0000;
        --tw-shadow: 0 0 #0000;
        --tw-shadow-colored: 0 0 #0000;
        --tw-blur: ;
        --tw-brightness: ;
        --tw-contrast: ;
        --tw-grayscale: ;
        --tw-hue-rotate: ;
        --tw-invert: ;
        --tw-saturate: ;
        --tw-sepia: ;
        --tw-drop-shadow: ;
        --tw-backdrop-blur: ;
        --tw-backdrop-brightness: ;
        --tw-backdrop-contrast: ;
        --tw-backdrop-grayscale: ;
        --tw-backdrop-hue-rotate: ;
        --tw-backdrop-invert: ;
        --tw-backdrop-opacity: ;
        --tw-backdrop-saturate: ;
        --tw-backdrop-sepia:
    }


    ::-webkit-backdrop {
        --tw-border-spacing-x: 0;
        --tw-border-spacing-y: 0;
        --tw-translate-x: 0;
        --tw-translate-y: 0;
        --tw-rotate: 0;
        --tw-skew-x: 0;
        --tw-skew-y: 0;
        --tw-scale-x: 1;
        --tw-scale-y: 1;
        --tw-pan-x: ;
        --tw-pan-y: ;
        --tw-pinch-zoom: ;
        --tw-scroll-snap-strictness: proximity;
        --tw-gradient-from-position: ;
        --tw-gradient-via-position: ;
        --tw-gradient-to-position: ;
        --tw-ordinal: ;
        --tw-slashed-zero: ;
        --tw-numeric-figure: ;
        --tw-numeric-spacing: ;
        --tw-numeric-fraction: ;
        --tw-ring-inset: ;
        --tw-ring-offset-width: 0px;
        --tw-ring-offset-color: #fff;
        --tw-ring-color: rgba(59, 130, 246, .5);
        --tw-ring-offset-shadow: 0 0 #0000;
        --tw-ring-shadow: 0 0 #0000;
        --tw-shadow: 0 0 #0000;
        --tw-shadow-colored: 0 0 #0000;
        --tw-blur: ;
        --tw-brightness: ;
        --tw-contrast: ;
        --tw-grayscale: ;
        --tw-hue-rotate: ;
        --tw-invert: ;
        --tw-saturate: ;
        --tw-sepia: ;
        --tw-drop-shadow: ;
        --tw-backdrop-blur: ;
        --tw-backdrop-brightness: ;
        --tw-backdrop-contrast: ;
        --tw-backdrop-grayscale: ;
        --tw-backdrop-hue-rotate: ;
        --tw-backdrop-invert: ;
        --tw-backdrop-opacity: ;
        --tw-backdrop-saturate: ;
        --tw-backdrop-sepia:
    }

    ::backdrop {
        --tw-border-spacing-x: 0;
        --tw-border-spacing-y: 0;
        --tw-translate-x: 0;
        --tw-translate-y: 0;
        --tw-rotate: 0;
        --tw-skew-x: 0;
        --tw-skew-y: 0;
        --tw-scale-x: 1;
        --tw-scale-y: 1;
        --tw-pan-x: ;
        --tw-pan-y: ;
        --tw-pinch-zoom: ;
        --tw-scroll-snap-strictness: proximity;
        --tw-gradient-from-position: ;
        --tw-gradient-via-position: ;
        --tw-gradient-to-position: ;
        --tw-ordinal: ;
        --tw-slashed-zero: ;
        --tw-numeric-figure: ;
        --tw-numeric-spacing: ;
        --tw-numeric-fraction: ;
        --tw-ring-inset: ;
        --tw-ring-offset-width: 0px;
        --tw-ring-offset-color: #fff;
        --tw-ring-color: rgba(59, 130, 246, .5);
        --tw-ring-offset-shadow: 0 0 #0000;
        --tw-ring-shadow: 0 0 #0000;
        --tw-shadow: 0 0 #0000;
        --tw-shadow-colored: 0 0 #0000;
        --tw-blur: ;
        --tw-brightness: ;
        --tw-contrast: ;
        --tw-grayscale: ;
        --tw-hue-rotate: ;
        --tw-invert: ;
        --tw-saturate: ;
        --tw-sepia: ;
        --tw-drop-shadow: ;
        --tw-backdrop-blur: ;
        --tw-backdrop-brightness: ;
        --tw-backdrop-contrast: ;
        --tw-backdrop-grayscale: ;
        --tw-backdrop-hue-rotate: ;
        --tw-backdrop-invert: ;
        --tw-backdrop-opacity: ;
        --tw-backdrop-saturate: ;
        --tw-backdrop-sepia:
    }

    .tabListing_tabListing__n5JBz {
        --tw-bg-opacity: 1;
        align-items: center;
        background-color: rgb(255 255 255/var(--tw-bg-opacity));
        display: flex;
        height: 44px;
        justify-content: space-around;
        position: fixed;
        /* width: 95%; */
        z-index: 10
    }

    @media only screen and (min-width: 600px) {
        .tabListing_tabListing__n5JBz {
            left: 50%;
            position: absolute;
            -webkit-transform: translateX(-50%);
            transform: translateX(-50%);
            width: calc(100% - 40px)
        }
    }

    .tabListing_tabListing__n5JBz>div {
        --tw-text-opacity: 1;
        border-radius: 4px;
        color: rgb(51 51 51/var(--tw-text-opacity));
        font-size: 14px;
        line-height: 28px;
        padding: 0 10px;
        white-space: nowrap;
    }

    .tabListing_tabListing__n5JBz>.tabListing_active__A9aYB {
        --tw-bg-opacity: 1;
        --tw-text-opacity: 1;
        background-color: rgb(var(--portal-wap-primary)/var(--tw-bg-opacity));
        color: rgb(255 255 255/var(--tw-text-opacity))
    }
</style>

<?php
function renderNotification($notifications, $filter)
{
    $htmlRender = "";
    $countItem = 0;
    foreach ($notifications as $message) {
        if (
            $filter == "all" || $filter == $message->type
            || (($message->type == 'supers' || $message->type == 'masters' || $message->type == 'agents' || $message->type == 'members') && $filter == "member")
        ) {
            $countItem++;
            // data-bs-toggle="modal" href="#full-width-modal-l-'.$message->id.'" 
            $htmlRender .= '<a  href="/notification/member-detail?id='.$message->id.'" class="list-group-item" style="color:black;border: none;">';
            $htmlRender .= '<div class="media">';
            $htmlRender .= '<div class="media-body" style="margin-left: 0px !important;">';
            $message_type = '';
            switch ($message->type) {
                case 'system':
                    $message_type = 'Hệ thống';
                    break;

                case 'supers':
                case 'masters':
                case 'agents':
                case 'members':
                    $message_type = 'Chung';
                    break;

                case 'personal':
                    $message_type = 'Cá nhân';
                    break;

                default:
                    break;
            }

            $htmlRender .= '<h5 class="media-heading" style="margin-bottom:10px;font-weight:600px;font-size: 14px;">' . $message_type;
            $htmlRender .= '<div class="pull-left p-r-10" bis_skin_checked="1" style="margin-right: 8px;">';
            if ($message->pin)
                $htmlRender .= '<em class="fa fa-bell-o fa-2x text-danger" style="font-size: 16px;"></em>';

            $htmlRender .= '</div>';
            $htmlRender .= '</h5>';
            $htmlRender .= '<p class="m-0" style="white-space: normal; font-style: normal !important; font-weight:200;text-transform: capitalize !important;font-size: 16px;word-wrap: break-word">';
            $htmlRender .= '<small>' . $message->message . '</small>';
            $htmlRender .= '</p>';
            $htmlRender .= '<p class="m-0" style="text-align: right; font-style: normal !important; font-weight:200;font-size: 12px;">';
            $htmlRender .= '<small>' . $message->created_at . '</small>';
            $htmlRender .= '</p></div></div></a>';


            $htmlRender .='<div id="full-width-modal-l-'.$message->id.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">';
            $htmlRender .='<div class="modal-dialog modal-full">';
            $htmlRender .='<div class="modal-content">';
            $htmlRender .='<div class="modal-header">';
            $htmlRender .='<h6 class="modal-title" style="color:white" id="exampleModalLabel">Thông báo chi tiết</h6>';
            $htmlRender .='<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>';
            $htmlRender .='</div>';
            $htmlRender .='<div class="modal-body">';
            $htmlRender .= '<p class="m-0" style="white-space: normal; font-style: normal !important; font-weight:200;text-transform: capitalize !important;font-size: 16px;word-wrap: break-word">';
            $htmlRender .= '<small>' . $message->message . '</small>';
            $htmlRender .= '</p>';
            $htmlRender .= '<p class="m-0" style="text-align: right; font-style: normal !important; font-weight:200;font-size: 12px;">';
            $htmlRender .= '<small>' . $message->created_at . '</small>';
            $htmlRender .= '</p>';
            $htmlRender .=$message->message2;
            $htmlRender .='</div>';
            $htmlRender .='</div>';
            // $htmlRender .='<div class="modal-footer">';
            // $htmlRender .='<button type="button" class="btn btn-default waves-effect" data-bs-dismiss="modal">Đóng</button>';
            // $htmlRender .='</div>';
            $htmlRender .='</div>';
            $htmlRender .='</div>';
        }
    }
    return [$htmlRender, $countItem];
}

$render[0] = renderNotification($notifications, "all");
$render[1] = renderNotification($notifications, "system");
$render[2] = renderNotification($notifications, "member");
$render[3] = renderNotification($notifications, "personal");
?>

<!-- <div class="w3-bar w3-black" style="border-color:#4C9EEA;">
	<button class="w3-bar-item w3-button" style="border: 1px solid #4C9EEA;padding: 10px;border-radius: 30px;" onclick="openCity('London')">Tất Cả (14)</button>
	<button class="w3-bar-item w3-button" onclick="openCity('Paris')">Hệ thống</button>
	<button class="w3-bar-item w3-button" onclick="openCity('Tokyo')">Cá nhân</button>
</div> -->


<div class="container" style="background-color:white; border: none;">
    @if($render[0][1] == 0 && $render[1][1] == 0 && $render[2][1] == 0 && $render[3][1] == 0)
        <label style="padding:20px;">Không có thông báo.</label>
    @else
        <div class="tabListing_tabListing__n5JBz" bis_skin_checked="1" style="margin-left:-25px;padding-bottom:50px;">
            @if($render[0][1] > 0)
            <div class="tabNoti tabListing_active__A9aYB" bis_skin_checked="1" id="AllTab" onclick="openCity('All')">Tất Cả ({{$render[0][1]}})</div>
            @endif
            @if($render[1][1] > 0)
            <div class="tabNoti" bis_skin_checked="1" id="SystemTab" onclick="openCity('System')">Hệ thống({{$render[1][1]}})</div>
            @endif
            @if($render[2][1] > 0)
            <div class="tabNoti" bis_skin_checked="1" id="MemberTab" onclick="openCity('Member')">Chung({{$render[2][1]}})</div>
            @endif
            @if($render[3][1] > 0)
            <div class="tabNoti" bis_skin_checked="1" id="PersonalTab" onclick="openCity('Personal')">Cá nhân({{$render[3][1]}})</div>
            @endif
        </div>
        <br>
        <br>
        <div id="All" class="notification">
            {!!$render[0][0]!!}
        </div>

        <div id="System" class="notification" style="display:none">
            {!!$render[1][0]!!}
        </div>

        <div id="Member" class="notification" style="display:none">
            {!!$render[2][0]!!}
        </div>

        <div id="Personal" class="notification" style="display:none">
            {!!$render[3][0]!!}
        </div>
    @endif
    
</div>

<script>
    function openCity(cityName) {
        var i;
        var x = document.getElementsByClassName("notification");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }

        var x = document.getElementsByClassName("tabNoti");
        for (i = 0; i < x.length; i++) {
            x[i].className = "tabNoti";
        }
        document.getElementById(cityName + "Tab").className = "tabNoti tabListing_active__A9aYB"
        document.getElementById(cityName).style.display = "block";
    }
</script>
@endsection