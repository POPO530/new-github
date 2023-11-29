<!DOCTYPE html>
<!-- HTML文書の開始を宣言します。DOCTYPE宣言はHTML文書であることを示します。 -->
<html>
<head>
    <meta charset="utf-8">
    <!-- 文書の文字コードをUTF-8に設定します。これにより、日本語を含む多様な文字が正しく表示されます。 -->
    <title>注文確認</title>
    <!-- ブラウザのタブに表示されるページのタイトルを設定します。 -->

    <!-- スタイルシートへのリンク -->
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <!-- 共通スタイルシートを読み込みます。asset関数はファイルパスを生成します。 -->
    <link rel="stylesheet" href="{{ asset('css/confirm.css') }}">
    <!-- 注文確認ページ専用のスタイルシートを読み込みます。 -->
</head>
<body>
    <h1 class="logo">イラスト・写真</h1>
    <!-- ページの主題を示す大きな見出しです。class="logo"はスタイル指定用です。 -->

    <div class="order">
        <h2>注文内容確認</h2>
        <!-- 注文内容を確認するセクションの見出しです。 -->

        <!-- 注文確認フォーム -->
        <form method="post" action="{{ route('items.store') }}">
            @csrf 
            <!-- このフォームの送信方法はPOSTで、送信先は'items.store'ルートです。@csrfはCSRF保護用トークンを生成します。 -->

            <ul>
                @foreach($items as $index => $item)
                    @php
                        $itemName = $item['name'];
                        $orderCount = $orderCounts[$itemName] ?? 0;
                        $totalPricePerItem = $item['price'] * $orderCount;
                    @endphp
                    @if($orderCounts[$itemName] > 0)
                        <!-- 各商品についての情報を処理します。商品名、注文数、その商品の合計価格を計算します。 -->
                        <li class="item-list">
                            {{ $itemName }} x 
                            <!-- 商品名を表示します。 -->
                            <input type="number" name="quantities[{{ $index }}]" value="{{ $orderCount }}" min="0" readonly> 枚
                            <!-- 注文数を表示するための数値入力フィールド。読み取り専用です。 -->
                            <input name="item-names[]" type="hidden" value="{{ $itemName }}">
                            <!-- 商品名をフォーム送信時に送るための隠れた入力フィールド。 -->
                            <input name="item-prices[]" type="hidden" value="{{ $item['price'] }}">
                            <!-- 商品の価格をフォーム送信時に送るための隠れた入力フィールド。 -->
                            {{ $totalPricePerItem }} 円
                            <!-- 商品の合計価格を表示します。 -->
                        </li>
                    @endif
                @endforeach
            </ul>
            <h3>合計金額: {{ $totalPayment }}円</h3>
            <!-- 注文全体の合計金額を表示します。 -->
            <input name="total-price" type="hidden" value="{{ $totalPayment }}">
            <!-- 合計金額をフォーム送信時に送るための隠れた入力フィールド。 -->

            @if($totalPayment > 0)
                <input class="btn" type="submit" value="注文決定">
                <!-- 合計金額が0より大きい場合、注文を確定するボタンを表示します。 -->
            @endif
        </form>
    </div>
    <input class="btn" type="button" onclick="history.back()" value="戻る">
    <!-- 「戻る」ボタン。クリックすると前のページに戻ります。 -->
</body>
</html>
