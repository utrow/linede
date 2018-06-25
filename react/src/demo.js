import React from 'react';
import Button from '@material-ui/core/Button';
import Snackbar from '@material-ui/core/Snackbar';
import TextField from '@material-ui/core/TextField';
import LinearProgress from '@material-ui/core/LinearProgress';
import Fade from '@material-ui/core/Fade';
import Grid from '@material-ui/core/Grid';
import AccountCircle from '@material-ui/icons/AccountCircle';
import line_qr from './uDPLWYF1Mu.png';
import ss_add from './ss_add.png';

require('./style.css');


class PositionedSnackbar extends React.Component {
  constructor() {
    super();
    this.state = {
      textValue_user: null,
      textValue_text: null,
      sending: false,
      snack: false,
      snack_text: '',
    };
  }

  sendClick = state => () => {
    this.setState({ sending: true });
    // POSTするデータ
    var data = {
      "user": this.state.textValue_user,
      "text": this.state.textValue_text
    };
    var _this = this;

    fetch('/send', {
      body: JSON.stringify(data),
      cache: 'no-cache',
      method: 'POST',
    }).then(function (response) {
      console.log(response.ok);
      if (response.ok) {
        return response.json();
      } else {
        throw new Error();
      }
    }).then(function (json) {
      var message = '';
      switch (json.code) {
        case -1:
          message = '入力されていない項目があります';
          break;
        case -2:
          message = 'IDが見つかりません';
          break;
        case 0:
          message = '送信に失敗しました';
          break;
        case 1:
          message = '送信しました';
          break;

        default:
          break;
      }
      setTimeout(() => {
        _this.setState({ snack: true, snack_text: message , sending: false });
      }, 1000);
    })
      .catch(function (error) {
        _this.setState({ snack: true, snack_text: '送信に失敗しました', sending: false });
      });



  };

  handleClose = () => {
    this.setState({ snack: false });
  };
  ChangeUser = (e) => {
    this.setState({ textValue_user: e.target.value })
  };
  ChangeText = (e) => {
    this.setState({ textValue_text: e.target.value })
  };

  render() {
    const { sending, snack, snack_text, } = this.state;
    return (
      <div>
        <div className="header">
          <h1>あとでLINEでみる</h1>
          <h2>さくっとリンクをスマホに送るためのWeb app.</h2>
        </div>
        <Fade in={sending}>
          <LinearProgress />
        </Fade>
        {/* Form */}
        <div className="form" >
          <Grid container spacing={12}>
            <Grid item xs={12} sm={12} className="center" >
              <TextField
                required
                id="text"
                label="送信するURLやテキスト"
                margin="normal"
                value={this.state.textValue_text}
                onChange={this.ChangeText}
                className="input-text"
              />

            </Grid>
            <Grid item xs={12} sm={12} alignItems="flex-end" >
              <Grid container spacing={8} alignItems="flex-end" className="center" >
                <Grid item>
                  <AccountCircle />
                </Grid>
                <Grid item>
                  <TextField
                    required
                    id="user"
                    label="ID"
                    margin="normal"
                    value={this.state.textValue_user}
                    onChange={this.ChangeUser}
                    className="input-text"
                  />
                </Grid>
                <Grid item>
                  <Button
                    variant="contained"
                    onClick={this.sendClick()}
                    size="large" >
                    LINEに送る</Button>
                </Grid>
              </Grid>
            </Grid>


          </Grid>
        </div>
        {/* Usage */}
        <div className="usage">
          <h3>使いかた</h3>
          <div className="center" >
            <p>友だち追加して、IDを決めるだけですぐ使える！</p>
            <h4>まずは、友だち追加</h4>
            <img src={line_qr} className="line-qr" alt="友だち追加のQRコード" />
            <a className="line-add" href="http://line.me/ti/p/uDPLWYF1Mu">友だち追加</a>
            <h4>あとは、IDの設定</h4>
            <img src={ss_add} className="screen" alt="ID設定のスクリーンショット" />
          </div>
        </div>




        <Snackbar
          anchorOrigin={{ vertical: 'bottom', horizontal: 'right' }}
          open={snack}
          onClose={this.handleClose}
          ContentProps={{
            'aria-describedby': 'message-id',
          }}
          message={<span id="message-id">{snack_text}</span>}
        />
      </div >
    );
  }
}

export default PositionedSnackbar;
