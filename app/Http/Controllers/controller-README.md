## controllerの仕組みについて
役割：**値はモデルで加工し、コントローラではビューに渡すだけ**
### viewをreturnする
- view()ヘルパ関数の引数に、移動先のビューを指定する。
- resources/viewsまでは暗黙的に示されているのでそれ以下のパスを指定してあげる。
- 最後の.以降の名前.blade.php にわたっていることに注意
以下の例ならresources/views/tests/component-test1.blade.php
```
public function showComponent1(){
        $message = "メッセージ１";
        return view("tests.component-test1", compact("message"));
        //tests/component-test1.blade.phpの「:message="$message"」の部分に渡す
        //compact("message")の"messageは"「$message」と同じことを意味する。()内には
        //「渡したい変数から$を取り除き、""で囲ったもの」を入れることで、うまく値が渡ってくれるようになる。
  }
```
#### 「return view()」と「->redirect()->route()」の違い
>return view()の場合は、POSTでリクエストで渡されたパラメータをそのままPOSTリクエストで渡して画面遷移します。それに対し、redirect()はPOSTのリクエストに対しても、必ずGETのリクエスト（正しくはルーティングで指定されたメソッド）で画面遷移するため、前の画面から渡されたパラメータは遷移先のビューには渡さないということが分かりました。

>注意したいのが、RedirectはGET判定されることです。そのためredirect()が実行されると、web.phpのRoute::get()が読み込まれるため注意しましょう。
redirect()->route(名前付きルート)としてあげる事でルートを指定してあげることができるらしい

>view() 和 redirect() 的異同
>1. 使用 return view() 不會改變當前訪問的 url ， return redirect() 會改變改變當前訪問的 url
>2. 使用 return view() 不會使當前 Session 的 Flash 失效 ，但是 return redirect() 會使 Flash 失效
>3. 在 RESTful 架構中，訪問 Get 方法時推薦使用 return view() ，訪問其他方法推薦使用 return redirect()
1. return view() を使用しても現在アクセスしている URL は変更されませんが、return redirect() を使用すると現在アクセスしている URL が変更されます。
2. return view() を使用しても現在のセッションのフラッシュは無効になりませんが、return redirect() を使用するとフラッシュが無効になります。
3. RESTful アーキテクチャでは、Get メソッドにアクセスする場合は return view() を使用し、他のメソッドにアクセスする場合は return redirect() を使用することをお勧めします。

とのこと。

### できるだけ軽量化しよう
- DB関連の処理はできるだけModelにやらせる
- View関連の処理はできるだけコンポーネントにやらせる