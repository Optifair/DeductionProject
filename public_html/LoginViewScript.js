class UserForm extends React.Component {
    constructor(props) {
        super(props);
        var Login = props.Login;
        var LoginIsValid = this.validateLogin(Login);
        var Password = props.Password;
        var PasswordIsValid = this.validatePassword(Password);
        this.state = {Login: Login, Password: Password, LoginValid: LoginIsValid, PasswordValid: PasswordIsValid};

        this.onLoginChange = this.onLoginChange.bind(this);
        this.onPasswordChange = this.onPasswordChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }
    validatePassword(Password){
        var valid = Password.length>5;
        return valid;
    }
    validateLogin(Login){
        var valid =Login.length>2;
        return valid;
    }
    onPasswordChange(e) {
        var val = e.target.value;
        var valid = this.validatePassword(val);
        this.setState({Password: val, PasswordValid: valid});
    }
    onLoginChange(e) {
        var val = e.target.value;
        console.log(val);
        var valid = this.validateLogin(val);
        this.setState({Login: val, LoginValid: valid});
    }

    handleSubmit(e) {
        e.preventDefault();
        if(this.state.LoginValid ===true && this.state.PasswordValid===true){
            alert(`Login: ${this.state.Login} Password: ${this.state.Password}`);
        }
        else if (this.state.LoginValid ===false )
        {
            if (this.state.PasswordValid===false)
            {
                alert("Login should be more than 2 symbols and Password should be more than 5 symbols.");
            }
            else
            {
                alert("Login should be more than 2 symbols.");
            }
        }
        else if (this.state.PasswordValid ===false )
        {
            alert("Password should be more than 5 symbols.");
        }
    }

    render() {
        // цвет границы для поля для ввода имени
        var LoginColor = this.state.LoginValid===true?"#7582f8fd":"red";
        // цвет границы для поля для ввода Passwordа
        var PasswordColor = this.state.PasswordValid===true?"#7582f8fd":"red";
        return (  
            <div>
                <form onSubmit={this.handleSubmit}>
                    <a href="#" class="close" >&#10006;</a>
                    <div class="columnGrid">
                        <label>Login:</label>
                        <input type="text" value={this.state.Login}
                               onChange={this.onLoginChange} style={{borderColor:LoginColor}} />

                        <label>Password:</label>
                        <input type="password" value={this.state.Password}
                               onChange={this.onPasswordChange}  style={{borderColor:PasswordColor}} />

                        <input type="submit" value="Log in"/>
                    
                    </div>

                </form>
                <div class="columnGrid">
                    <input type="submit" value="Forget Password?"/>
                    <input type="submit" value="Registration"/>
                </div>
            </div>                          

        );
    }
}
ReactDOM.createRoot(
    document.getElementById("app")
)
    .render(
        <UserForm Login="" Password="" />
    );