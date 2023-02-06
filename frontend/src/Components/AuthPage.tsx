import React, {useEffect, useState} from 'react';
import {Stack} from '@mui/system';
import InputBase from "@mui/material/InputBase";
import {
    alpha,
    Button,
    Dialog,
    DialogContent,
    DialogProps,
    IconButton,
    Link,
    Snackbar,
    styled,
    Typography
} from "@mui/material";
import BackAdress from "../BackAdress";
import {useNavigate} from "react-router-dom";
import CloseIcon from "@mui/icons-material/Close";
import PasswordResetWindow from "./PasswordResetWIndow";

const InputDiv = styled('div')(({theme}) => ({
    position: 'relative',
    borderRadius: theme.shape.borderRadius,
    backgroundColor: alpha(theme.palette.common.white, 0.15),
    '&:hover': {
        backgroundColor: alpha(theme.palette.common.white, 0.25),
    }

}));

export default function AuthPage() {
    const [login, setLogin] = useState('');
    const [pass, setPass] = useState('');

    const navigate = useNavigate();

    const handleLoginChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setLogin(event.target.value);
    };
    const handlePassChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setPass(event.target.value);
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

    const [openDialog, setOpenDialog] = React.useState(false);
    const [scrollDialog, setScrollDialog] = React.useState<DialogProps[ 'scroll' ]>('paper');
    const handleClickOpenDialog = (scrollType: DialogProps[ 'scroll' ]) => () => {
        setOpenDialog(true);
        setScrollDialog(scrollType);
    };

    const handleCloseDialog = () => {
        setOpenDialog(false);
    };

    async function fetchUser() {
        const formData = new FormData();

        formData.append("login", login);
        formData.append("pass", pass);

        var res = await fetch(`http://${BackAdress}/api/authUser`
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

    async function fetchAuth() {
        let res = await fetch(`http://${BackAdress}/api/cookieAuth`
            , {
                credentials: 'include',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                    'Access-Control-Allow-Credentials': 'true'
                }
            })
            .then((response) => {
                return response.json()
            })
            .then((data) => {
                return data
            });
        return res['auth'];
    }

    async function redirectIfAuthYet() {
        const isAuth = Boolean(Number(await fetchAuth()));
        if (isAuth) {
            navigate("/profile")
        }
    }

    useEffect(() => {
        redirectIfAuthYet();
    })

    async function authUser() {
        const res = await fetchUser();
        const isAuth = Boolean(Number(res['auth']));
        const isBan = Boolean(Number(res['ban']));
        if (isAuth) {
            if (login !== '' && pass !== '') {
                navigate("/profile")
                setMessage("You have successfully auth!");
            } else {
                setMessage("Input fields must be filled");
            }
        } else {
            if (isBan) {
                setMessage("User is banned. Check your email for more information");

            } else {
                setMessage("User not found");
            }
        }
        handleClick();
    }

    return (
        <Stack spacing={3} alignItems={'center'} paddingBottom={'30px'}>
            <Stack spacing={2} className="s" alignItems={'center'} paddingTop={'90px'} minWidth={'100%'}>
                <Typography>Login</Typography>
                <InputDiv>
                    <InputBase onChange={handleLoginChange}
                               style={{marginLeft: '5%', marginRight: '5%'}}/>
                </InputDiv>
                <Typography>Password</Typography>
                <InputDiv>
                    <InputBase onChange={handlePassChange} type='password'
                               style={{marginLeft: '5%', marginRight: '5%'}}/>
                </InputDiv>

                <Button onClick={authUser} variant="outlined"
                        style={{border: '1px solid ghostwhite', color: 'ghostwhite'}}> Log in </Button>
                <Typography>Not registered yet?</Typography>
                <Link href={'/registration'} underline='none' variant="inherit" color={'whitesmoke'}>
                    <Button type={"submit"} variant="outlined"
                            style={{border: '1px solid ghostwhite', color: 'ghostwhite'}}>Registration</Button>
                </Link>
                <Typography>Forgot your password?</Typography>
                <Button onClick={handleClickOpenDialog('body')} variant="outlined"
                        style={{border: '1px solid ghostwhite', color: 'ghostwhite'}}> Reset password </Button>
            </Stack>
            <Dialog
                open={openDialog}
                onClose={handleCloseDialog}
                maxWidth={'md'}
                PaperProps={{
                    style: {
                        backgroundImage: 'none',
                        background: 'transparent',
                        width: '25%',
                        height: 'max-content'
                    },
                }}
            >
                <DialogContent dividers={scrollDialog === 'paper'} style={{padding: '0px 0px 0px 0px'}}>
                    <PasswordResetWindow></PasswordResetWindow>
                </DialogContent>
            </Dialog>
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
