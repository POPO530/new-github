<!DOCTYPE html>
<!-- HTML文書の開始を宣言します。DOCTYPE宣言はHTML文書であることを示します。 -->
<html lang="ja">
<!-- HTML要素の開始です。lang属性に"ja"を設定して、この文書が日本語で書かれていることを指定しています。 -->

<head>
    <meta charset="UTF-8">
    <!-- 文書の文字コードをUTF-8に設定します。これにより、日本語を含む多様な文字が正しく表示されます。 -->
    <title>イラスト・写真</title>
    <!-- ブラウザのタブに表示されるページのタイトルを設定します。 -->

    <!-- スタイルシートへのリンク -->
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <!-- 共通スタイルシートを読み込みます。asset関数はファイルパスを生成します。 -->
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <!-- このページ専用のスタイルシートを読み込みます。 -->
</head>

<body>

<h1 class="logo">イラスト・写真</h1>
<!-- ページの主題を示す大きな見出しです。class="logo"はスタイル指定用です。 -->
<h3>商品数: {{ count($items) }} 枚</h3>
<!-- 商品の総数を表示します。$itemsはサーバーから送られた商品データの配列で、count関数でその数を数えます。 -->

<!-- 日付と時刻の表示 -->
<p id="currentDate"></p>
<!-- 現在の日付を表示するための段落。JavaScriptで内容が埋められます。 -->
<p id="currentTime"></p>
<!-- 現在の時刻を表示するための段落。JavaScriptで内容が更新されます。 -->

<h1 class="logo">注文フォーム</h1>
<!-- 注文フォームの見出しです。 -->

<!-- 注文成功時のフィードバック表示 -->
@if(session("feedback.success"))
    <p>{{ session("feedback.success") }}</p>
    <!-- セッションに"feedback.success"というキーでメッセージがある場合、それを表示します。 -->
@endif

<!-- 注文フォーム -->
<form method="post" action="{{ route('items.confirm') }}">
    @csrf
    <!-- このフォームの送信方法はPOSTで、送信先は'items.confirm'ルートです。@csrfはCSRF保護用トークンを生成します。 -->

    <!-- 商品リスト表示 -->
    @foreach($items as $item)
        <div class="item">
            <h3 class="item-name">{{ $item->name }}</h3>
            <!-- 各商品の名前を表示します。 -->
            @if($item instanceof \App\Models\Illustration)
                <p class="item-type">イラストの種類: {{ $item->type }}</p>
                <!-- イラストの種類を表示します。$itemがIllustrationモデルのインスタンスの場合に限ります。 -->
            @elseif($item instanceof \App\Models\Photograph)
                <p class="item-type">被写体: {{ $item->subject }}</p>
                <!-- 写真の被写体を表示します。$itemがPhotographモデルのインスタンスの場合に限ります。 -->
            @endif
            <p class="item-price">¥{{ $item->price }}</p>
            <!-- 商品の価格を表示します。 -->
            <img src="{{ asset('img/items/' . $item->image) }}" class="item-image" alt="{{ $item->name }}" onclick="toggleImageSize(this);">
            <!-- 商品画像。クリックすると大きさが切り替わるJavaScript関数が呼び出されます。 -->
            <br/>
            <label class="quantity" for="quantity[{{ $item->name }}]">数量:</label>
            <input class="quantity" name="quantity[{{ $item->name }}]" type="number" value="0" min="0">
            <!-- 数量選択用の数値入力フィールド。 -->
            <span>枚</span>
        </div>
    @endforeach
    <input class="btn" type="submit" value="注文する">
    <!-- 注文確定ボタン -->
</form>

<div>
    <!-- 新しく追加した機能 -->
    <p>過去の注文</p>
    @if(count($dbItems) > 0)
        @foreach($dbItems as $dbItem)
            <p>{{ $dbItem->name }}</p>
            <p>{{ $dbItem->price }}</p>
            <p>{{ $dbItem->itemCount }}</p>
            <form action="{{ route('items.delete', ['itemId' => $dbItem->id]) }}" method="post">
                @method("DELETE")
                @csrf
                <input class="btn" type="submit" value="削除する">
            </form>
        @endforeach
    @endif
</div>

<!-- JavaScriptコード -->
<script>
    function toggleImageSize(image) {
        // この関数は、画像をクリックしたときにその大きさを切り替えます。
        if (image.classList.contains('item-image')) {
            image.classList.remove('item-image');
            image.style.cursor = "zoom-out";
        } else {
            image.classList.add('item-image');
            image.style.cursor = "zoom-in";
        }
    }

    function displayCurrentDate() {
        // 現在の日付を表示する関数
        var currentDate = new Date();
        document.getElementById("currentDate").textContent = "現在の日付: " + currentDate.toLocaleDateString();
    }

    function displayCurrentTime() {
        // 現在の時刻を表示する関数
        var currentTime = new Date();
        document.getElementById("currentTime").textContent = "現在の時刻: " + currentTime.toLocaleTimeString();
    }

    window.onload = function () {
        // ページが読み込まれたときに実行される関数
        displayCurrentDate();
        displayCurrentTime();
        setInterval(displayCurrentTime, 1000); // 毎秒現在時刻を更新します。
    };

</script>
</body>
</html>
