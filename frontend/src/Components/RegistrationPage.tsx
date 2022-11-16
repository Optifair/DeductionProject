import React, {useState} from 'react';
import {Stack} from '@mui/system';
import InputBase from "@mui/material/InputBase";
import {alpha, Button, styled, Typography} from "@mui/material";
import BackAdress from "../BackAdress";

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

    const handleNameChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setName(event.target.value);
    };
    const handleLoginChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setLogin(event.target.value);
    };
    const handlePassChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setPass(event.target.value);
    };

    async function fetchUser(name: any, login: any, pass: any) {
        var res = await fetch(
            `http://${BackAdress}/api/registerUser?name=${name}&login=${login}&pass=${pass}`
        );
    }

    async function registerUser() {
        await fetchUser(name, login, pass);
    }

    return (
        <Stack spacing={3} alignItems={'center'} paddingBottom={'30px'}>
            <Stack spacing={2} className="s" alignItems={'center'} paddingTop={'90px'} minWidth={'100%'}>
                <Typography>Name</Typography>
                <StyledDiv>
                    <InputBase onChange={handleNameChange}
                               style={{marginLeft: '5%', marginRight: '5%'}}/>
                </StyledDiv>
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
                <Button onClick={registerUser} variant="outlined"
                        style={{border: '1px solid ghostwhite', color: 'ghostwhite'}}> Register </Button>
            </Stack>
        </Stack>
    );
}