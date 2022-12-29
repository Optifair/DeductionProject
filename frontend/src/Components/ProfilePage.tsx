import React, {useEffect, useState} from 'react';
import {Stack} from '@mui/system';
import {Button, Dialog, DialogContent, DialogProps, Typography} from "@mui/material";
import BackAdress from "../BackAdress";
import {useNavigate} from "react-router-dom";
import EditUserDataWindow from "./EditUserDataWindow";

export default function ProfilePage() {
    const [name, setName] = useState<string>('');
    const [login, setLogin] = useState<string>('');

    const navigate = useNavigate();

    const [open, setOpen] = React.useState(false);
    const [scroll, setScroll] = React.useState<DialogProps[ 'scroll' ]>('paper');
    const handleClickOpen = (scrollType: DialogProps[ 'scroll' ]) => () => {
        setOpen(true);
        setScroll(scrollType);
    };

    const handleClose = () => {
        setOpen(false);
    };

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

    async function fetchUserData() {
        let res = await fetch(`http://${BackAdress}/api/getUserData`
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
        return res;
    }

    async function redirectIfNotAuth() {
        const test: any = await fetchAuth();
        if (!test) {
            navigate("/auth")
        } else {
            let res: any = await fetchUserData();
            setName(res['name']);
            setLogin(res['login']);
        }
    }

    async function logOut() {
        let res = await fetch(`http://${BackAdress}/api/logout`
            , {
                credentials: 'include',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                    'Access-Control-Allow-Credentials': 'true'
                }
            })
        navigate("/");
    }

    async function goToMarks() {
        navigate("/marks");
    }

    useEffect(() => {
        redirectIfNotAuth();
    })

    return (
        <Stack spacing={3} alignItems={'center'} paddingBottom={'30px'}>
            <Stack spacing={2} className="s" alignItems={'center'} paddingTop={'90px'} minWidth={'100%'}>
                <Typography>Name: {name}</Typography>
                <Typography>Login: {login}</Typography>
                <Button onClick={goToMarks} variant="outlined"
                        style={{border: '1px solid ghostwhite', color: 'ghostwhite'}}>Marks</Button>
                <Button onClick={handleClickOpen('body')} variant="outlined"
                        style={{border: '1px solid ghostwhite', color: 'ghostwhite'}}>Edit</Button>
                <Button onClick={logOut} variant="outlined"
                        style={{border: '1px solid ghostwhite', color: 'ghostwhite'}}>Logout</Button>

                <Dialog
                    open={open}
                    onClose={handleClose}
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
                    <DialogContent dividers={scroll === 'paper'} style={{padding: '0px 0px 0px 0px'}}>
                        <EditUserDataWindow></EditUserDataWindow>
                    </DialogContent>
                </Dialog>
            </Stack>
        </Stack>
    );
}
