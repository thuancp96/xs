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

    .custom-a {
        text-decoration: none;
        display: inline-block;
        padding: 8px 16px;
    }

    .custom-a:hover {
        background-color: #ddd;
        color: black;
    }

    .previous {
        background-color: #f1f1f1;
        color: black;
    }

    .next {
        background-color: #04AA6D;
        color: white;
    }

    .round {
        border-radius: 50%;
    }
</style>

<?php
function renderNotification($message, $filter)
{
    $htmlRender = "";
    $countItem = 0;
    $htmlRender .= '<p class="m-0" style="white-space: normal; font-style: normal !important; font-weight:200;text-transform: capitalize !important;font-size: 16px;word-wrap: break-word">';
    $htmlRender .= '<small style="font-weight:600;font-size:15px;">' . $message->message . '</small>';
    $htmlRender .= '</p>';
    $htmlRender .= '<p class="m-0" style="text-align: right; font-style: normal !important; font-weight:200;font-size: 12px;">';
    $htmlRender .= '<small style="font-weight:400;font-size:13px;">' . $message->updated_at . '</small>';
    $htmlRender .= '</p>';
    $htmlRender .= $message->message2;
    return [$htmlRender, $countItem];
}

$render = renderNotification($message, "all");
?>

<div class="panel-body" style="padding: 0 !important;width: 100%;" id="sideBetCate" bis_skin_checked="1">
    <a class="custom-a" href="/notification/member" style="color:white;background-color:gray;" class="previous">&laquo; Quay lại</a>
</div>

<div class="container" style="background-color:white; padding-bottom:50px;border: none;">
<br>
    {!!$render[0]!!}
</div>
@endsection