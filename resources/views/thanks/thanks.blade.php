<!DOCTYPE html>
<!-- HTML文書の開始を宣言します。DOCTYPE宣言はHTML文書であることを示します。 -->
<html>

<head>
    <meta charset="utf-8">
    <!-- 文書の文字コードをUTF-8に設定します。これにより、日本語を含む多様な文字が正しく表示されます。 -->
    <title>イラスト・写真注文フォーム</title>
    <!-- ブラウザのタブに表示されるページのタイトルを設定します。 -->

    <!-- CSSファイルのリンク -->
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <!-- 共通スタイルシートを読み込みます。asset関数はファイルパスを生成します。 -->
    <link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
    <!-- 注文完了ページ専用のスタイルシートを読み込みます。 -->
</head>

<body>
    <h1 class="logo">イラスト・写真</h1>
    <!-- ページの主題を示す大きな見出しです。class="logo"はスタイル指定用です。 -->
    <h2>注文を受け付けました！</h2>
    <!-- 注文完了のメッセージを表示する見出しです。 -->

    <!-- アイテムリストの表示 -->
    @if(count($itemNames) == count($itemPrices) && count($itemNames) > 0)
    <!-- アイテム名と価格のリストが同じ長さであり、かつ空でない場合にのみアイテムリストを表示します。 -->
    <p>注文した商品一覧：</p>
    <ul>
        @foreach($itemNames as $index => $itemName)
            @php
                $price = $itemPrices[$index];
                $quantity = $quantities[$index]; // 注文数量を取得
                $totalPrice = $price * $quantity; // 各アイテムの合計金額を計算
            @endphp
            <li class="item-list">
                <!-- 各アイテムの詳細情報をリストで表示します。 -->
                {{ $itemName }} - 単価: {{ $price }}円 - 数量: {{ $quantity }} - 合計金額: {{ $totalPrice }}円
            </li>
        @endforeach
    </ul>
    @endif

    <!-- 合計金額の表示 -->
    <p>合計金額：{{ $totalPrices }}円</p>
    <!-- 注文全体の合計金額を表示します。 -->

    <form method="get" action="{{ route('items.index') }}">
        <input class="btn" type="submit" value="TOPに戻る">
        <!-- トップページに戻るためのボタン。 -->
    </form>
</body>

</html>
