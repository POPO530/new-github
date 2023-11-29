<?php
namespace App\Http\Controllers;
// Laravelのコントローラー機能を使うため、必要な名前空間を指定しています。

// 必要なクラスをインポートしています。
// ItemServiceはビジネスロジックを扱うクラスです。
// ItemはEloquentモデルクラスで、データベースのitemsテーブルに対応します。
// RequestはHTTPリクエストの情報を扱うクラスです。
use App\Services\ItemService;
use App\Models\Item;
use Illuminate\Http\Request;

// ItemControllerクラスはControllerクラスを継承しています。
// これにより、さまざまなHTTPリクエストを処理するためのメソッドを定義できます。
class ItemController extends Controller
{
    // ItemServiceのインスタンスを保持するプロパティです。
    // このプロパティはコントローラーの各メソッドで使われます。
    protected $itemService;

    /**
     * コンストラクタでItemServiceインスタンスを注入します。
     * このインスタンスはこのコントローラーの各メソッドで使用されます。
     * 
     * コンストラクタインジェクションを使用することで、
     * ItemControllerのインスタンスが作成される際に、
     * 自動的にItemServiceのインスタンスが生成され、
     * このコントローラーに注入されます。
     * 
     * @param ItemService $itemService アイテムに関するビジネスロジックを扱うサービス
     */
    public function __construct(ItemService $itemService)
    {
        // コンストラクタで受け取ったItemServiceのインスタンスをプロパティに代入します。
        $this->itemService = $itemService;
    }

    /**
     * アイテム一覧を表示するためのアクションです。
     * ItemServiceを使用してアイテムの一覧を取得し、ビューに渡します。
     * 
     * @return \Illuminate\View\View アイテム一覧ビュー
     * 
     * indexメソッドは、このコントローラーの主要な機能の一つです。
     * このメソッドは、Webページ上でアイテムの一覧をユーザーに表示するために呼び出されます。
     * ItemServiceを通じてデータベースからアイテムのデータを取得し、
     * そのデータをビューに渡して、ユーザーに対して表示します。
     */
    public function index()
    {
        // ItemServiceを通じてアイテムのデータを取得します。
        // ItemServiceは、ビジネスロジックやデータベースアクセスの処理を担います。
        $items = $this->itemService->getItems();

        $dbItems = Item::all();

        // 取得したアイテムのデータをビューに渡します。
        // 'items.index'は、アイテム一覧を表示するためのビューファイルを指します。
        // compact関数を使用して、'items'変数をビューに渡しています。
        // これにより、ビューファイル内で$items変数としてアイテムデータを利用できます。
        return view('items.index', compact('items', 'dbItems'));
    }

    /**
     * 注文確認ページを表示するためのアクションです。
     * リクエストからアイテムの数量を受け取り、合計金額を計算してビューに渡します。
     * 
     * @param Request $request HTTPリクエストオブジェクト
     * @return \Illuminate\View\View 注文確認ビューを返します。
     */
    public function confirm(Request $request)
    {
        // ItemServiceを使用して、すべてのアイテムを取得します。
        $items = $this->itemService->getItems();
        
        // アイテムごとの数量を格納する配列です。
        $orderCounts = [];
        
        // 合計金額を初期化します。
        $totalPayment = 0;

        // 取得した各アイテムに対してループを行います。
        foreach ($items as $item) {
            // リクエストから特定のアイテムの数量を取得します。
            // 'quantity.' . $item->name はリクエストの入力名を指しており、
            // ここでは各アイテム名に対応する数量を取得しています。
            $count = $request->input('quantity.' . $item->name, 0); 
            
            // 数量が負の値でないことを保証します。
            $count = max(0, $count); 

            // アイテムの個数を設定します。
            $item->itemCount = $count; 

            // アイテム名と数量のペアを$orderCounts配列に追加します。
            $orderCounts[$item->name] = $count;

            // 合計金額にアイテムの総価格を加算します。
            $totalPayment += $item->total_price; 
        }
        
        // view関数を使用して、'order.confirm'ビューを返します。
        // compact関数を使用して、ビューに変数を渡します。
        // 'items', 'orderCounts', 'totalPayment' がビューで利用可能になります。
        return view('order.confirm', compact('items', 'orderCounts', 'totalPayment'));
    }

    /**
     * アイテムの注文を受け付けて保存するアクションです。
     * リクエストデータをバリデーションし、Itemモデルを使用してデータベースに保存します。
     * 
     * @param Request $request HTTPリクエスト
     * @return \Illuminate\View\View 注文完了ビュー
     */
    public function store(Request $request)
    {
        // リクエストデータをバリデーションします。
        // ここではアイテム名、価格、数量、合計価格の各項目が適切な形式であることを確認しています。
        // 'item-names.*' は配列内の各アイテム名が文字列であることを要求します。
        // 'item-prices.*' は配列内の各アイテム価格が数値であることを要求します。
        // 'quantities.*' は配列内の各数量が数値で、かつ0以上であることを要求します。
        // 'total-price' は合計価格が数値であることを要求します。
        $data = $request->validate([
            'item-names.*' => 'required|string',
            'item-prices.*' => 'required|numeric',
            'quantities.*' => 'required|numeric|min:0', 
            'total-price' => 'required|numeric',
        ]);

        // バリデーションされたデータから、アイテム名、価格、数量、合計価格を取り出します。
        $itemNames = $data['item-names'];
        $itemPrices = $data['item-prices'];
        $quantities = $data['quantities']; 
        $totalPrice = $data['total-price'];

        // 各アイテムごとの合計価格を計算するための配列を初期化します。
        $totalPricesPerItem = []; 
        
        // 各アイテムについてループ処理を行います。
        foreach ($itemNames as $index => $itemName) {
            // 該当インデックスの価格と数量を取得し、存在しない場合はデフォルト値を0とします。
            $itemPrice = $itemPrices[$index] ?? 0;
            $itemCount = $quantities[$index] ?? 0; 
            // 各アイテムの価格と数量を掛け合わせて、そのアイテムの合計価格を計算し、配列に追加します。
            $totalPricesPerItem[] = $itemPrice * $itemCount; 

            // アイテムの数量が0より大きい場合のみ、データベースに登録します。
            // これにより、数量が0のアイテムは無視されます。
            if ($itemCount > 0) {
                Item::create([
                    'name' => $itemName,
                    'price' => $itemPrice,
                    'itemCount' => $itemCount,
                ]);
            }
        }
        
        // 注文完了ビューを表示します。
        // ここで各種データをビューに渡し、ユーザーに注文情報を表示させます。
        return view('thanks.thanks', [
            'itemNames' => $itemNames,
            'itemPrices' => $itemPrices,
            'quantities' => $quantities, 
            'totalPricesPerItem' => $totalPricesPerItem,
            'totalPrices' => $totalPrice,
        ]);
    }

    public function destroy($itemId)
    {
        try {
            // Eloquentを使用して指定されたidのアイテムを削除します
            $item = Item::findOrFail($itemId);
            $item->delete();

            // 削除処理の後、アイテム一覧ページにリダイレクトします
            return redirect()->route("items.index")->with("success", "アイテムを削除しました");
        } catch (\Exception $e) {
            // 例外が発生した場合、エラーメッセージをセッションに追加してリダイレクトします
            return redirect()->route("items.index")->with("error", "アイテムの削除中にエラーが発生しました");
        }
    }
}
