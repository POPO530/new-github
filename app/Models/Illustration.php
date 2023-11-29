<?php

namespace App\Models;
// 名前空間の宣言。App\Models名前空間に属することを示しています。

// Itemクラスを継承しています。
// Itemクラスの属性やメソッドをIllustrationクラスでも使用できるようになります。
class Illustration extends Item
{
    // $fillableはMass Assignment（一括割り当て）で代入を許可する属性を定義します。
    // ここでは'name', 'price', 'itemCount', 'image', 'type'の5つのカラムが代入可能であることを指定しています。
    protected $fillable = ['name', 'price', 'itemCount', 'image', 'type'];

    // getTypeAttributeメソッドは、type属性の値を取得するアクセサです。
    // Laravelでは、"get"と"Attribute"をメソッド名に含めることでアクセサとして機能します。
    // このメソッドを使うと、Illustrationモデルのインスタンスで$typeとして
    // typeカラムのデータにアクセスできます。
    public function getTypeAttribute() {
        // $this->attributes配列から'type'キーに対応する値を返します。
        // $this->attributesは、モデルの各属性を保持する内部配列です。
        return $this->attributes['type'];
    }

    // setTypeAttributeメソッドは、type属性に値を設定するミューテータです。
    // Laravelでは、"set"と"Attribute"をメソッド名に含めることでミューテータとして機能します。
    // このメソッドを使うと、Illustrationモデルのインスタンスに対して
    // $illustration->type = $value という形で値を設定できます。
    public function setTypeAttribute($value) {
        // $this->attributes配列の'type'キーに、引数で受け取った$valueを設定します。
        // これにより、typeカラムの値が更新されます。
        $this->attributes['type'] = $value;
    }
}
