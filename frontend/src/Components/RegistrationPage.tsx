import React, {useState} from 'react';
import {Stack} from '@mui/system';
import InputBase from "@mui/material/InputBase";
import {alpha, Box, Button, IconButton, Snackbar, styled, Typography} from "@mui/material";
import BackAdress from "../BackAdress";
import CloseIcon from "@mui/icons-material/Close";
import {useNavigate} from "react-router-dom";


const StyledDiv = styled('div')(({theme}) => ({
    position: 'relative',
    borderRadius: theme.shape.borderRadius,
    backgroundColor: alpha(theme.palette.common.white, 0.15),
    '&:hover': {
        backgroundColor: alpha(theme.palette.common.white, 0.25),
    }

}));

export default function RegistrationPage() {
    const [name, setName] = useState('');
    const [login, setLogin] = useState('');
    const [pass, setPass] = useState('');
    const [repeatedPass, setRepeatedPass] = useState('');

    const navigate = useNavigate();

    const [nameValid, setNameValid] = useState(true);
    const [loginValid, setLoginValid] = useState(true);
    const [passValid, setPassValid] = useState(true);
    const [repeatedPassValid, setRepeatedPassValid] = useState(true);

    const [nameIsFocusedYet, setNameIsFocusedYet] = useState(false);
    const [loginIsFocusedYet, setLoginIsFocusedYet] = useState(false);
    const [passIsFocusedYet, setPassIsFocusedYet] = useState(false);
    const [repeatedPassIsFocusedYet, setRepeatedPassIsFocusedYet] = useState(false);

    const [message, setMessage] = useState('');
    const EMAIL_REGEXP = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    const PASS_REGEXP = /(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]{6,}/g

    const handleNameChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setName(event.target.value);
        setNameIsFocusedYet(true);
        if (event.target.value.length >= 4) {
            setNameValid(true);
        } else {
            setNameValid(false);
        }
    };
    const handleLoginChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setLogin(event.target.value);
        setLoginIsFocusedYet(true);
        if (EMAIL_REGEXP.test(event.target.value)) {
            setLoginValid(true);
        } else {
            setLoginValid(false);
        }
    };
    const handlePassChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setPass(event.target.value);
        setPassIsFocusedYet(true);
        if (PASS_REGEXP.test(event.target.value)) {
            setPassValid(true);
        } else {
            setPassValid(false);
        }

        if (repeatedPass === event.target.value) {
            setRepeatedPassValid(true);
        } else {
            setRepeatedPassValid(false);
        }
    };
    const handleRepeatedPassChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setRepeatedPass(event.target.value);
        setRepeatedPassIsFocusedYet(true);
        if (pass === event.target.value) {
            setRepeatedPassValid(true);
        } else {
            setRepeatedPassValid(false);
        }
    };

    const [open, setOpen] = React.useState(false);
    const handleClick = () => {
        setOpen(true);
    };
    const handleClose = (event: React.SyntheticEvent | React.MouseEvent, reason?: string) => {
        if (reason === 'clickaway') {
            return;
        }
        setOpen(false);
    };


    async function fetchUser() {
        var res = await fetch(`http://${BackAdress}/api/registerUser`
            , {
                credentials: 'include',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                    'Access-Control-Allow-Credentials': 'true'
                },
                body: JSON.stringify({"name": name, "login": login, "password": pass}),
            })
            .then((response) => {
                return response.json()
            })
            .then((data) => {
                return data
            });
        return Boolean(Number(res['isRegister']));
    }

    async function checkData() {
        const dataValid = nameValid && loginValid && passValid && repeatedPassValid
            && nameIsFocusedYet && loginIsFocusedYet && passIsFocusedYet && repeatedPassIsFocusedYet
        return dataValid;
    }

    async function registerUser() {
        const dataValid = await checkData()
        if (dataValid) {
            const isRegister = await fetchUser();
            if (isRegister) {
                setMessage("You have successfully registered!");
                navigate("/auth");
            } else {
                setMessage("User with this login already exist");
            }
        } else {
            setMessage("You entered incorrect data");
        }
        handleClick();
    }


    return (
        <Stack spacing={3} alignItems={'center'} paddingBottom={'30px'}>
            <Stack spacing={1} className="s" alignItems={'center'} paddingTop={'90px'} minWidth={'100%'}>
                <Typography>Name</Typography>
                <Box border={2} borderColor={
                    nameIsFocusedYet
                        ?
                        nameValid ? 'green' : 'red'
                        :
                        'gray'
                }
                     borderRadius={'8px'}>
                    <StyledDiv>
                        <InputBase onChange={handleNameChange}
                                   style={{marginLeft: '5%', marginRight: '5%'}}/>
                    </StyledDiv>
                </Box>
                <Typography>Login</Typography>
                <Box border={2} borderColor={
                    loginIsFocusedYet
                        ?
                        loginValid ? 'green' : 'red'
                        :
                        'gray'
                }
                     borderRadius={'8px'}>
                    <StyledDiv>
                        <InputBase onChange={handleLoginChange}
                                   style={{marginLeft: '5%', marginRight: '5%'}}/>
                    </StyledDiv>
                </Box>
                <Typography>Password</Typography>
                <Box border={2} borderColor={
                    passIsFocusedYet
                        ?
                        passValid ? 'green' : 'red'
                        :
                        'gray'
                }
                     borderRadius={'8px'}>
                    <StyledDiv>
                        <InputBase onChange={handlePassChange} type='password'
                                   style={{marginLeft: '5%', marginRight: '5%'}}/>
                    </StyledDiv>
                </Box>
                <Typography variant='body2'>The password must contain at least one uppercase letter and one number and
                    be
                    more than 6 characters long</Typography>
                <Typography>Repeat password</Typography>
                <Box border={2} borderColor={
                    repeatedPassIsFocusedYet
                        ?
                        repeatedPassValid ? 'green' : 'red'
                        :
                        'gray'
                }
                     borderRadius={'8px'}>
                    <StyledDiv>
                        <InputBase onChange={handleRepeatedPassChange} type='password'
                                   style={{marginLeft: '5%', marginRight: '5%'}}/>
                    </StyledDiv>
                </Box>
                <Button onClick={registerUser} variant="outlined"
                        style={{border: '1px solid ghostwhite', color: 'ghostwhite'}}> Register </Button>
            </Stack>
            <Snackbar
                anchorOrigin={{
                    vertical: 'bottom',
                    horizontal: 'center',
                }}
                open={open}
                autoHideDuration={50}
                message={message}
                action={
                    <React.Fragment>
                        <IconButton size="small" aria-label="close" color="inherit" onClick={handleClose}>
                            <CloseIcon></CloseIcon>
                        </IconButton>
                    </React.Fragment>
                }
            />
        </Stack>
    );
}