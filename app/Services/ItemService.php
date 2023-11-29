<?php

namespace App\Services;
// App\Services 名前空間の宣言。
// これにより、このファイル内のクラスがこの名前空間に属することが示されます。

// 必要なモデル（IllustrationとPhotograph）をuse文でインポートしています。
// これにより、これらのモデルをクラス名のみで参照できるようになります。
use App\Models\Illustration;
use App\Models\Photograph;

// ItemServiceクラスの定義。
// このクラスは、アイテムに関連するサービスを提供します。
class ItemService
{
    // getItemsメソッドの定義。
    // このメソッドは、異なるタイプのアイテムオブジェクトを配列で返します。
    public function getItems()
    {
        return [
            // Illustrationモデルのインスタンスを作成し、初期値を配列で渡します。
            new Illustration(['name' => 'A', 'price' => 500, 'image' => '00004-910266984.png', 'type' => 'AIイラスト']),
            new Illustration(['name' => 'B', 'price' => 700, 'image' => '21_wx7wn5xy0l.jpg', 'type' => 'イラスト']),
            
            // Photographモデルのインスタンスを作成し、初期値を配列で渡します。
            new Photograph(['name' => 'C', 'price' => 900, 'image' => 'IMG20230716172629.jpg', 'subject' => 'フィギュア']),
            // 他のアイテムもこの配列に追加できます。
        ];
    }
}
