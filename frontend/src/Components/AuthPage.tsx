import React, {useEffect, useState} from 'react';
import {Stack} from '@mui/system';
import InputBase from "@mui/material/InputBase";
import {alpha, Button, styled, Typography} from "@mui/material";
import BackAdress from "../BackAdress";
import {useNavigate} from "react-router-dom";

const StyledDiv = styled('div')(({theme}) => ({
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

    async function fetchUser(login: any, pass: any) {
        var res = await fetch(`http://${BackAdress}/api/authUser`
            , {
                credentials: 'include',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                    'Access-Control-Allow-Credentials': 'true'
                },
                body: JSON.stringify({"login": login, "pass": pass}),
            }
        )
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
        const test: any = await fetchAuth();
        if (test) {
            navigate("/profile")
        }
    }

    useEffect(() => {
        redirectIfAuthYet();
    })

    async function authUser() {
        await fetchUser(login, pass);
        navigate("/profile");
    }

    return (
        <Stack spacing={3} alignItems={'center'} paddingBottom={'30px'}>
            <Stack spacing={2} className="s" alignItems={'center'} paddingTop={'90px'} minWidth={'100%'}>
                <Typography>Login</Typography>
                <StyledDiv>
                    <InputBase onChange={handleLoginChange}
                               style={{marginLeft: '5%', marginRight: '5%'}}/>
                </StyledDiv>
                <Typography>Password</Typography>
                <StyledDiv>
                    <InputBase onChange={handlePassChange} type='password'
                               style={{marginLeft: '5%', marginRight: '5%'}}/>
                </StyledDiv>

                <Button onClick={authUser} variant="outlined"
                        style={{border: '1px solid ghostwhite', color: 'ghostwhite'}}> Authentification </Button>
                <Typography>Not registered yet?</Typography>
                <form action={'/registration'}>
                    <Button type={"submit"} variant="outlined"
                            style={{border: '1px solid ghostwhite', color: 'ghostwhite'}}>Registration</Button>
                </form>
            </Stack>
        </Stack>
    );
}
