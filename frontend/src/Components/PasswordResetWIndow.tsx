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

export default function PasswordResetWindow() {
    const [login, setLogin] = useState('');
    const [loginValid, setLoginValid] = useState(true);
    const [loginIsFocusedYet, setLoginIsFocusedYet] = useState(false);
    const EMAIL_REGEXP = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

    const handleLoginChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setLoginIsFocusedYet(true);
        setLogin(event.target.value);
        if (EMAIL_REGEXP.test(event.target.value)) {
            setLoginValid(true);
        } else {
            setLoginValid(false);
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

    async function fetchSendPasswordResetLink() {
        const formData = new FormData();
        formData.append("login", login);

        var res = await fetch(`http://${BackAdress}/api/sendPasswordResetLink`
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

    async function sendPasswordResetLink() {
        if (loginValid) {
            const res = await fetchSendPasswordResetLink();
            const isFound = Boolean(Number(res['isFound']));
            if (isFound) {
                setMessage("Reset password link sent to your email");
            } else {
                setMessage("No user with this login was found");
            }
        }
        handleClick()
    }

    return (
        <Card sx={{borderRadius: '25px'}}>
            <Stack spacing={2} className="s" alignItems={'center'} minWidth={'100%'} sx={{padding: '10%'}}>
                <Typography>Enter the login of the account you want to reset the password for</Typography>
                <Box border={2} borderColor={
                    loginIsFocusedYet
                        ?
                        loginValid ? 'green' : 'red'
                        :
                        'gray'
                }
                     borderRadius={'8px'}>
                    <InputDiv>
                        <InputBase onChange={handleLoginChange}
                                   style={{marginLeft: '5%', marginRight: '5%'}}/>
                    </InputDiv>
                </Box>

                <Button onClick={sendPasswordResetLink} variant="outlined"
                        style={{border: '1px solid ghostwhite', color: 'ghostwhite'}}> Send link</Button>
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
        </Card>
    );
}
