<?php

namespace App\Models;
// Laravelのモデル機能を使用するために、必要な名前空間を指定しています。

// LaravelのEloquentモデルクラスを使用するためのuse文です。
use Illuminate\Database\Eloquent\Model;

// PhotographクラスはItemクラスを継承しています。
// これにより、Itemクラスの属性やメソッドをPhotographクラスでも使用できます。
class Photograph extends Item
{
    // $fillableはMass Assignment（一括割り当て）で代入を許可する属性を定義します。
    // ここでは'name', 'price', 'itemCount', 'image', 'subject'の5つのカラムが代入可能であることを指定しています。
    protected $fillable = ['name', 'price', 'itemCount', 'image', 'subject'];
    
    // subjectカラムの値を取得するためのアクセサメソッドです。
    // Laravelのアクセサは、属性にアクセスする際に自動的に呼び出されるメソッドです。
    // このメソッドを使用すると、$photograph->subject として
    // subjectカラムのデータにアクセスできます。
    public function getSubjectAttribute() {
        // $this->attributes配列から'subject'キーに対応する値を返します。
        // $this->attributesは、モデルの各属性を保持する内部配列です。
        return $this->attributes['subject'];
    }
}
