import React, {useEffect, useState} from 'react';
import {Stack} from '@mui/system';
import {Button, Typography} from "@mui/material";
import BackAdress from "../BackAdress";
import {useNavigate} from "react-router-dom";

export default function ProfilePage() {
    const [name, setName] = useState<string>('');
    const [login, setLogin] = useState<string>('');

    const navigate = useNavigate();

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
                <Button onClick={logOut} variant="outlined"
                        style={{border: '1px solid ghostwhite', color: 'ghostwhite'}}>Logout</Button>
                <Button onClick={goToMarks} variant="outlined"
                        style={{border: '1px solid ghostwhite', color: 'ghostwhite'}}>Marks</Button>
            </Stack>
        </Stack>
    );
}
