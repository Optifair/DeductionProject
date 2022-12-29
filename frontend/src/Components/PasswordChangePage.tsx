import React, {SyntheticEvent, useEffect, useState} from 'react';
import {Stack} from '@mui/system';
import {useNavigate, useParams} from 'react-router-dom';
import {alpha, Snackbar, SnackbarCloseReason, styled} from '@mui/material';
import BackAdress from "../BackAdress";

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
    const [message, setMessage] = useState('');
    const navigate = useNavigate();


    const [open, setOpen] = React.useState(false);
    const handleClick = () => {
        setOpen(true);
    };
    const handleClose = (event: Event | SyntheticEvent<any, Event>, reason: SnackbarCloseReason) => {
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
            navigate("/");
            setMessage("The link you clicked is out of date");
            handleClick();
        }
    }

    useEffect(() => {
        checkKey()
    })

    return (
        <Stack spacing={3} alignItems={'center'} paddingBottom={'30px'}>
            <Stack spacing={2} className="s" alignItems={'center'} paddingTop={'90px'} minWidth={'100%'}>

            </Stack>
            <Snackbar
                anchorOrigin={{
                    vertical: 'bottom',
                    horizontal: 'center',
                }}
                open={open}
                onClose={handleClose}
                autoHideDuration={50}
                message={message}
                action={
                    <React.Fragment>

                    </React.Fragment>
                }
            />
        </Stack>
    );
}
