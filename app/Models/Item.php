<?php

namespace App\Models;
// Laravelのモデル機能を使うため、必要な名前空間を指定しています。

// LaravelのEloquentモデルクラスを使用するためのuse文です。
use Illuminate\Database\Eloquent\Model;

// ItemクラスはModelクラスを継承しており、データベースのitemsテーブルに対応します。
class Item extends Model
{
    // $fillableはMass Assignment（一括割り当て）で代入を許可する属性を定義します。
    // ここでは'name', 'price', 'itemCount'の3つのカラムが代入可能であることを指定しています。
    protected $fillable = ['name', 'price', 'itemCount'];
    
    /**
     * アイテムの総価格を計算して返します。
     * この属性はアイテムの単価（price）と個数（itemCount）を乗算して算出されます。
     * getTotalPriceAttributeという名前のメソッドは、Laravelのアクセサ機能により、
     * モデルのインスタンスで$totalPriceとしてアクセス可能になります。
     */
    public function getTotalPriceAttribute() {
        // 単価と個数を掛け合わせて、総価格を計算しています。
        return $this->price * $this->itemCount;
    }

    /**
     * アイテムの個数を設定します。
     * このメソッドはアイテムの個数を指定した値に更新するために使用されます。
     * setItemCountAttributeという名前のメソッドは、Laravelのミューテータ機能により、
     * $item->itemCount = $value という形で値を設定する際に自動的に呼び出されます。
     * 
     * @param int $count 設定するアイテムの個数
     */
    public function setItemCountAttribute($count) {
        // 引数で受け取った個数をitemCount属性にセットしています。
        $this->attributes['itemCount'] = $count;
    }
}
