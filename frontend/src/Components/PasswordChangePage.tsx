import React, {useEffect, useState} from 'react';
import {Stack} from '@mui/system';
import {useNavigate, useParams} from 'react-router-dom';
import {alpha, Box, Button, IconButton, Snackbar, styled, Typography} from '@mui/material';
import BackAdress from "../BackAdress";
import CloseIcon from "@mui/icons-material/Close";
import InputBase from "@mui/material/InputBase";

const InputDiv = styled('div')(({theme}) => ({
    position: 'relative',
    borderRadius: theme.shape.borderRadius,
    backgroundColor: alpha(theme.palette.common.white, 0.15),
    '&:hover': {
        backgroundColor: alpha(theme.palette.common.white, 0.25),
    }

}));

export default function PasswordChangePage() {
    const params = useParams();
    const key = params.key;
    let login = "";
    const navigate = useNavigate();
    const [newPass, setNewPass] = useState('');
    const [repeatedPass, setRepeatedPass] = useState('');
    const [newPassIsFocusedYet, setNewPassIsFocusedYet] = useState(false);
    const [repeatedPassIsFocusedYet, setRepeatedPassIsFocusedYet] = useState(false);
    const PASS_REGEXP = /(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]{6,}/g

    const [newPassValid, setNewPassValid] = useState(true);
    const [repeatedPassValid, setRepeatedPassValid] = useState(true);

    const handleNewPassChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setNewPass(event.target.value);
        setNewPassIsFocusedYet(true);
        if (PASS_REGEXP.test(event.target.value)) {
            setNewPassValid(true);
        } else {
            setNewPassValid(false);
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
        if (newPass === event.target.value) {
            setRepeatedPassValid(true);
        } else {
            setRepeatedPassValid(false);
        }
    };
    const [message, setMessage] = useState('');
    const [open, setOpen] = React.useState(false);

    const handleClick = () => {
        setOpen(true);
    };

    const handleClose = (event?: React.SyntheticEvent | Event, reason?: string) => {
        if (reason === 'clickaway') {
            return;
        }

        setOpen(false);
    };

    async function fetchCheckKey(passwordResetKey: string) {
        const formData = new FormData();

        formData.append("key", passwordResetKey);

        var res = await fetch(`http://${BackAdress}/api/checkPasswordResetKey`
            , {
                credentials: 'include',
                method: 'POST',
                headers: {
                    'Access-Control-Allow-Credentials': 'true'
                },
                body: formData,
            })
            .then((response) => {
                return response.json()
            })
            .then((data) => {
                return data
            });
        return res;
    }

    async function fetchEditPassword() {
        const formData = new FormData();
        let passkey = "";
        if (typeof key != "undefined") {
            passkey = key;
        }
        formData.append("key", passkey);
        formData.append("login", login);
        formData.append("newPass", newPass);

        var res = await fetch(`http://${BackAdress}/api/editPassword`
            , {
                credentials: 'include',
                method: 'POST',
                headers: {
                    'Access-Control-Allow-Credentials': 'true'
                },
                body: formData,
            })
            .then((response) => {
                return response.json()
            })
            .then((data) => {
                return data
            });
        return res;
    }

    async function checkKey() {
        let checkSuccess = false;
        if (typeof key != "undefined") {
            const res = await fetchCheckKey(key);
            if (Boolean(Number(res['isFound']))) {
                checkSuccess = true;
                login = res['login'];
            }
        }
        if (!checkSuccess) {
            setTimeout(() => {
                navigate("/auth")
            }, 1500);
        }
    }

    async function editPassword() {
        if (newPassValid && repeatedPassValid && newPassIsFocusedYet && repeatedPassIsFocusedYet) {
            const res = await fetchEditPassword();
            const isEdit = Boolean(Number(res['isEdit']));
            if (isEdit) {
                setMessage("You have successfully edit your password!");
            } else {
                setMessage("This link is outdated!");
            }
            setTimeout(() => {
                navigate("/auth")
            }, 1500);
        } else {
            setMessage("You entered incorrect data");
        }
        handleClick()
    }


    useEffect(() => {
        checkKey()
    })

    return (
        <Stack spacing={3} alignItems={'center'} paddingBottom={'30px'}>
            <Stack spacing={2} className="s" alignItems={'center'} paddingTop={'90px'} minWidth={'100%'}>
                <Typography variant='body2'>The password must contain at least one uppercase letter and one number and
                    be
                    more than 6 characters long</Typography>
                <Typography>Enter the password</Typography>
                <Box border={2} borderColor={
                    newPassIsFocusedYet
                        ?
                        newPassValid ? 'green' : 'red'
                        :
                        'gray'
                }
                     borderRadius={'8px'}>
                    <InputDiv>
                        <InputBase onChange={handleNewPassChange} type='password'
                                   style={{marginLeft: '5%', marginRight: '5%'}}/>
                    </InputDiv>
                </Box>
                <Typography>Repeat password</Typography>
                <Box border={2} borderColor={
                    repeatedPassIsFocusedYet
                        ?
                        repeatedPassValid ? 'green' : 'red'
                        :
                        'gray'
                }
                     borderRadius={'8px'}>
                    <InputDiv>
                        <InputBase onChange={handleRepeatedPassChange} type='password'
                                   style={{marginLeft: '5%', marginRight: '5%'}}/>
                    </InputDiv>
                </Box>
                <Button onClick={editPassword} variant="outlined"
                        style={{border: '1px solid ghostwhite', color: 'ghostwhite'}}> Edit password</Button>

            </Stack>
            <Snackbar
                anchorOrigin={{
                    vertical: 'bottom',
                    horizontal: 'center',
                }}
                open={open}
                autoHideDuration={1500}
                onClose={handleClose}
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
