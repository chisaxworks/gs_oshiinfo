// ゆっくりトップに戻る
$('.logo').click(function () {
    $('body,html').animate({
        scrollTop: 0
    }, 500);
    return false;
});

// PageTop
function PageTopAnime() {
    var scroll = $(window).scrollTop();
    if (scroll >= 600){
        $('.pagetop').removeClass('pt-down');
        $('.pagetop').addClass('pt-up');
    }else{
        if($('.pagetop').hasClass('pt-up')){
        $('.pagetop').removeClass('pt-up');
        $('.pagetop').addClass('pt-down');
        }
    }
}

$('.pagetop').click(function () {
    $('body,html').animate({
        scrollTop: 0
    }, 500);
    return false;
});

$(window).scroll(function () {
    PageTopAnime();
});

$(window).on('load', function () {
    PageTopAnime();
});

// 設定アイコンtoggle
$('.setting').click(function () {

    if($('.setting').hasClass('active')){
        $('.setting_wrap').slideUp(300);
        $('.setting').removeClass('active');
    }else if($('.notify').hasClass('active')){
        $('.notify_wrap').slideUp(300);
        $('.notify').removeClass('active');
        $('.setting_wrap').slideDown(300);
        $('.setting').addClass('active');
    }else{
        $('.setting_wrap').slideDown(300);
        $('.setting').addClass('active');
    };

});

// 通知アイコンtoggle
$('.notify').click(function () {

    if($('.notify').hasClass('active')){
        $('.notify_wrap').slideUp(300);
        $('.notify').removeClass('active');
    }else if($('.setting').hasClass('active')){
        $('.setting_wrap').slideUp(300);
        $('.setting').removeClass('active');
        $('.notify_wrap').slideDown(300);
        $('.notify').addClass('active');
    }else{
        $('.notify_wrap').slideDown(300);
        $('.notify').addClass('active');
    };

});

// GET情報取得
let url = new URL(window.location.href);
let params = url.searchParams;

let evid = params.get('evid');
let pid = params.get('pid');
let favid = params.get('favid');

// 右側の子画面表示制御
if (window.matchMedia( "(min-width: 768px)" ).matches) {
    // PCサイズ
    if(evid != null){
        $('.data_area').css('width', '60%');
        $('.event_detail').css('display', 'block');
        $('.event_detail').css('width', '20%');
        $('.program_detail').css('display', 'none');
    }else if(pid != null){
        $('.data_area').css('width', '60%');
        $('.event_detail').css('display', 'none');
        $('.program_detail').css('display', 'block');
        $('.program_detail').css('width', '20%');
    }else{
        $('.event_detail').css('display', 'none');
        $('.program_detail').css('display', 'none');
        $('.data_area').css('width', '80%');
    }
}else{
    // スマホサイズ
    if(evid != null){
        $('.data_area').css('display', 'none');
        $('.event_detail').css('display', 'block');
        $('.event_detail').css('width', '100%');
        $('.program_detail').css('display', 'none');
        $('.filter_area').css('display', 'none');
        $('.sp_title').css('display', 'none');
    }else if(pid != null){
        $('.data_area').css('display', 'none');
        $('.event_detail').css('display', 'none');
        $('.program_detail').css('display', 'block');
        $('.program_detail').css('width', '100%');
        $('.filter_area').css('display', 'none');
        $('.sp_title').css('display', 'none');
    }else{
        $('.event_detail').css('display', 'none');
        $('.program_detail').css('display', 'none');
        $('.data_area').css('width', '100%');
        $('.filter_area').css('display', 'block');
        $('.sp_title').css('display', 'block');
    }
}

// スマホサイズ推し一覧エリア表示制御
$('.sp_filter').click(function () {
    $('.filter_area').slideToggle('active');
});

/*----------- アラート系 ----------*/

// ログアウト時アラート
$(".logout_btn").on("click", function () {

    if(!confirm("ログアウトしてもよろしいですか？")){
        return false;
    }
});

// 推し削除時アラート
$(".delete_btn").on("click", function () {

    if(!confirm("削除してもよろしいですか？")){
        return false;
    }
});

// 推し登録時アラート
$("#submit_add_fav").on("click", function(){
    if ($("#fav_name").val() === "") {
        alert("名前を入力してください");
        return false;
    }else if($("#fav_type").val() === ""){
        alert("職種を選んでください");
        return false;
    }
});

// 管理開始時アラート
$(".start_mg").on("click", function () {
    if(!confirm("管理対象にしてよろしいですか？")){
        return false;
    }
});

// 管理対象外アラート
$(".outof_mg").on("click", function () {
    if(!confirm("【注】以降管理することができなくなります。よろしいですか？")){
        return false;
    }
});


// 管理終了時アラート
$(".exit_mg").on("click", function () {

    if(!confirm("管理対象から外してもよろしいですか？")){
        return false;
    }
});

// アカウント削除時アラート
$("#deleteuser_btn").on("click", function () {

    if(!confirm("アカウントを削除してもよろしいですか？")){
        return false;
    }
});

/*--- ユーザ管理周り ---*/
// ログインボタンクリック時アラート
$("#login").on("click", function(){
    if ($("#email").val() === "") {
        alert("メールを入力してください");
        return false;
    }else if($("#password").val() === ""){
        alert("パスワードを入力してください");
        return false;
    }
});

// ユーザ登録ボタンクリック時アラート
$("#register").on("click", function(){
    if ($("#reg_name").val() === "") {
        alert("名前を入力してください");
        return false;
    }else if($("#reg_email").val() === ""){
        alert("メールを入力してください");
        return false;
    }else if($("#reg_password").val() === ""){
        alert("パスワードを入力してください");
        return false;
    }
});

// モバイル調整
let vh = window.innerHeight * 0.01;
document.documentElement.style.setProperty('--vh', `${vh}px`);