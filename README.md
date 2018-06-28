# あとでLINEでみる
## さくっとリンクをスマホに送るためのWeb app.(linede) 

http://linede.herokuapp.com/

## 使用スキル
- PHP7(Codeigniter3)
- MySQL
- Heroku
- React.js 

## 開発背景
### めっちゃシンプルにリンクを転送したい!!!
- 大学の演習室PCなど、使うたびにGoogleなどのクラウドサービスにログインするのが面倒くさい。
- LINEをインストールしていないPCで、ログインすらせずに手軽にLINEに送りたい。
- Googleドライブの共有リンクをスマホで送信する手間を減らしたい
    （スマホでの操作の流れ）
    Googleドライブのアプリを起動
    -> ファイル置いたところまでたどる 
    -> 共有リンクコピー
    -> LINEを開き、友だちに送信。
    
    共有リンクの取得は当然PCのほうが楽。
    
    （スマホでの操作の流れ）
    LINEでリンクを受信-> LINEメッセージを友だちに転送
    
    こうできる。

## 動作もシンプル

Web app. => LINE BOT => LINE

idを用いて、Web appからLINE BOTを操作し、LINEにメッセージが送信される動作をするだけ。
