import React from 'react';
import Button from '@material-ui/core/Button';
import Snackbar from '@material-ui/core/Snackbar';
import TextField from '@material-ui/core/TextField';
import LinearProgress from '@material-ui/core/LinearProgress';
import Fade from '@material-ui/core/Fade';


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

    fetch('https://hook-marsjp.ssl-netowl.jp/sendlink', {
      body: JSON.stringify(data),
      cache: 'no-cache',
      method: 'POST',
    }).then(function (response) {
      if (response.ok) {
        return response.text();
      } else {
        throw new Error();
      }
    }).then(function (json) {
      console.log('json:', json);
    });
    setTimeout(() => {
      this.setState({
        snack: true, snack_text: '送信しました', sending: false
      });
    }, 1000);
  };

  handleClose = () => {
    this.setState({ snack: false });
  };
  ChangeUser = (e) => {
    this.setState({textValue_user: e.target.value})
  };
  ChangeText = (e) => {
    this.setState({textValue_text: e.target.value})
  };

  render() {
     const { sending, snack, snack_text, textValue_user,  } = this.state;
    return (
      <div>
        <Fade in={sending}>
          <LinearProgress />
        </Fade>
        <h1>あとでLINEでみる</h1>
        <h2>さくっとリンクをスマホに送るためのWeb app.</h2>
        <TextField
          required
          id="text"
          label="送信するテキスト"
          margin="normal"
          value={this.state.textValue_text}
          onChange={this.ChangeText}
        />

        <TextField
          required
          id="user"
          label="LINE ID"
          placeholder="Placeholder"
          margin="normal"
          value={this.state.textValue_user}
          onChange={this.ChangeUser}
        />
        <Button variant="contained" onClick={this.sendClick()}>
          LINEに送る
      </Button>


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
