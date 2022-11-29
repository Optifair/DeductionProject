import React, {useState} from 'react';
import {Stack} from '@mui/system';
import InputBase from "@mui/material/InputBase";
import {alpha, Box, Button, Card, IconButton, Snackbar, styled, Typography} from "@mui/material";
import BackAdress from "../BackAdress";
import CloseIcon from "@mui/icons-material/Close";

const InputDiv = styled('div')(({theme}) => ({
    position: 'relative',
    borderRadius: theme.shape.borderRadius,
    backgroundColor: alpha(theme.palette.common.white, 0.15),
    '&:hover': {
        backgroundColor: alpha(theme.palette.common.white, 0.25),
    }

}));

export default function EditUserDataWindow() {
    const [newName, setNewName] = useState('');
    const [newLogin, setNewLogin] = useState('');
    const [newAvatar, setNewAvatar] = useState('');
    const [newPass, setNewPass] = useState('');
    const [pass, setPass] = useState('');

    const [newLoginValid, setNewLoginValid] = useState(true);
    const [newPassValid, setNewPassValid] = useState(true);
    const [newLoginIsFocusedYet, setNewLoginIsFocusedYet] = useState(false);
    const [newPassIsFocusedYet, setNewPassIsFocusedYet] = useState(false);

    const [message, setMessage] = useState('');
    const EMAIL_REGEXP = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    const PASS_REGEXP = /(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]{6,}/g

    const handleNewNameChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setNewName(event.target.value);
    };
    const handleNewLoginChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setNewLoginIsFocusedYet(true);
        setNewLogin(event.target.value);
        if (EMAIL_REGEXP.test(event.target.value)) {
            setNewLoginValid(true);
        } else {
            setNewLoginValid(false);
        }

    };
    const handleNewAvatarChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setNewAvatar(event.target.value);
    };
    const handleNewPassChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setNewPass(event.target.value);
        setNewPassIsFocusedYet(true);
        if (PASS_REGEXP.test(event.target.value)) {
            setNewPassValid(true);
        } else {
            setNewPassValid(false);
        }
    };
    const handlePassChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setPass(event.target.value);
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

    async function fetchEditUserData() {
        var res = await fetch(`http://${BackAdress}/api/editUserData`
            , {
                credentials: 'include',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                    'Access-Control-Allow-Credentials': 'true'
                },
                body: JSON.stringify({
                    "newName": newName, "newLogin": newLogin,
                    "newAvatar": newAvatar, "newPass": newPass, "pass": pass
                }),
            })
            .then((response) => {
                return response.json()
            })
            .then((data) => {
                return data
            });
        return res;
    }

    async function editUserData() {
        if (newPassValid && newLoginValid) {
            const res = await fetchEditUserData();
            const isEdit = Boolean(Number(res['isEdit']));
            const isAuth = Boolean(Number(res['isAuth']));
            const isPassCorrect = Boolean(Number(res['isPassCorrect']));
            const isLoginNotTaken = Boolean(Number(res['isNameTaken']));
            if (isEdit) {
                setMessage("You have successfully edit your data!");
            } else {
                if (!isAuth) {
                    setMessage("You entered incorrect data");
                } else if (!isPassCorrect) {
                    setMessage("Password is incorrect!");
                } else if (!isLoginNotTaken) {
                    setMessage("User with this login already exist");
                }
            }
        }
        handleClick()
    }

    return (
        <Card sx={{borderRadius: '25px'}}>
            <Stack spacing={2} className="s" alignItems={'center'} minWidth={'100%'} sx={{padding: '10%'}}>
                <Typography>New Name</Typography>
                <Box border={2} borderColor={'gray'}
                     borderRadius={'8px'}>
                    <InputDiv>
                        <InputBase onChange={handleNewNameChange}
                                   style={{marginLeft: '5%', marginRight: '5%'}}/>
                    </InputDiv>
                </Box>
                <Typography>New Login</Typography>
                <Box border={2} borderColor={
                    newLoginIsFocusedYet
                        ?
                        newLoginValid ? 'green' : 'red'
                        :
                        'gray'
                }
                     borderRadius={'8px'}>
                    <InputDiv>
                        <InputBase onChange={handleNewLoginChange}
                                   style={{marginLeft: '5%', marginRight: '5%'}}/>
                    </InputDiv>
                </Box>
                <Typography>New Avatar</Typography>
                <Box border={2} borderColor={'gray'}
                     borderRadius={'8px'}>
                    <InputDiv>
                        <InputBase onChange={handleNewAvatarChange}
                                   style={{marginLeft: '5%', marginRight: '5%'}}/>
                    </InputDiv>
                </Box>
                <Typography>New Password</Typography>
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
                <Typography variant='body2'>The password must contain at least one uppercase letter and one number and
                    be
                    more than 6 characters long</Typography>
                <Typography>Password</Typography>
                <Box border={2} borderColor={'gray'}
                     borderRadius={'8px'}>
                    <InputDiv>
                        <InputBase onChange={handlePassChange} type='password'
                                   style={{marginLeft: '5%', marginRight: '5%'}}/>
                    </InputDiv>
                </Box>
                <Button onClick={editUserData} variant="outlined"
                        style={{border: '1px solid ghostwhite', color: 'ghostwhite'}}> Edit</Button>
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
        </Card>
    );
}
