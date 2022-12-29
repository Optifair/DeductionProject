import {alpha, styled} from '@mui/material/styles';
import AppBar from '@mui/material/AppBar';
import Box from '@mui/material/Box';
import Toolbar from '@mui/material/Toolbar';
import IconButton from '@mui/material/IconButton';
import Typography from '@mui/material/Typography';
import InputBase from '@mui/material/InputBase';
import SearchIcon from '@mui/icons-material/Search';
import Link from '@mui/material/Link'
import React, {useEffect, useState} from 'react';
import {useNavigate} from "react-router-dom";
import BackAdress from "../BackAdress";
import {Avatar, Menu, MenuItem} from "@mui/material";


const Search = styled('div')(({theme}) => ({
    position: 'relative',
    borderRadius: theme.shape.borderRadius,
    backgroundColor: alpha(theme.palette.common.white, 0.15),
    '&:hover': {
        backgroundColor: alpha(theme.palette.common.white, 0.25),
    },
    marginLeft: 0,
    width: '100%',
    [theme.breakpoints.up('sm')]: {
        marginLeft: theme.spacing(1),
        width: 'auto',
    },
}));

const SearchIconWrapper = styled('div')(({theme}) => ({
    padding: theme.spacing(0, 2),
    height: '100%',
    position: 'absolute',
    pointerEvents: 'none',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
}));

const StyledInputBase = styled(InputBase)(({theme}) => ({
    color: 'inherit',
    '& .MuiInputBase-input': {
        padding: theme.spacing(1, 1, 1, 0),
        paddingLeft: `calc(1em + ${theme.spacing(4)})`,
        transition: theme.transitions.create('width'),
        width: '100%',
        [theme.breakpoints.up('sm')]: {
            width: '12ch',
            '&:focus': {
                width: '20ch',
            },
        },
    },
}));


export default function Header() {
    const [value, setValue] = React.useState('');
    const [rating, setRating] = React.useState('');
    const [avatar, setAvatar] = React.useState('');
    const [name, setName] = React.useState('');
    const [authState, setAuthState] = useState<boolean>(false);

    const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setValue(event.target.value);
    };

    const navigate = useNavigate();

    const [anchorEl, setAnchorEl] = React.useState<null | HTMLElement>(null);
    const isMenuOpen = Boolean(anchorEl);
    const handleMenuClose = () => {
        setAnchorEl(null);
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
        return Boolean(Number(res['auth']));
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

    async function setAuth() {
        const auth = await fetchAuth();
        setAuthState(auth);
    }

    async function setUserData() {
        const data = await fetchUserData();
        setRating(data['rating']);
        setAvatar(data['avatar']);
        setName(data['name']);
    }

    useEffect(() => {
        setAuth();
        if (authState) {
            setUserData();
        }
    })

    const renderMenu = (
        <Menu
            anchorEl={anchorEl}
            anchorOrigin={{vertical: 'top', horizontal: 'right'}}
            keepMounted
            transformOrigin={{vertical: 'top', horizontal: 'right'}}
            open={isMenuOpen}
            onClose={handleMenuClose}
        >
            <MenuItem href={'profile'}>Profile</MenuItem>
            <MenuItem href={'/marks'}>Marks</MenuItem>
            <MenuItem onClick={logOut}>LogOut</MenuItem>
        </Menu>
    );

    return (
        <Box sx={{flexGrow: 1}}>
            <AppBar position="fixed">
                <Toolbar>
                    <Typography
                        variant="h6"
                        noWrap
                        component="div"
                        sx={{flexGrow: 1, display: {xs: 'none', sm: 'block'}}}
                    >
                        <Link href='/' underline='none' variant="inherit" color={'whitesmoke'}>
                            Home
                        </Link>
                    </Typography>
                    {
                        authState
                        &&
                        <Typography
                            variant="h6"
                            noWrap
                            component="div"
                            maxWidth={'max-content'}
                            marginRight={'1%'}
                            sx={{flexGrow: 1, display: {xs: 'none', sm: 'block'}}}
                        >
                            Rating {rating}
                        </Typography>
                    }
                    <form action={'/search/' + value}>
                        <Search>
                            <SearchIconWrapper>
                                <SearchIcon/>
                            </SearchIconWrapper>
                            <StyledInputBase
                                onChange={handleChange}
                                placeholder="Search…"
                                inputProps={{'aria-label': 'search'}}
                            />
                        </Search>
                    </form>
                    <Box sx={{display: {xs: 'none', md: 'flex'}}}>
                        <Link href={'/profile'} underline='none' variant="inherit" color={'whitesmoke'}>
                            <IconButton
                                size="large"
                                edge="end"
                                aria-haspopup="true"
                                color="inherit"
                            >
                                {
                                    authState
                                        ?
                                        // avatar.length < 1
                                        //     ?
                                        //     <Avatar src={avatar}>
                                        //     </Avatar>
                                        //     :
                                        //     <Avatar> {name[0]} </Avatar>
                                        <Avatar src={avatar}> {name[0]} </Avatar>
                                        :
                                        <Typography>
                                            Log In
                                        </Typography>
                                }
                            </IconButton>
                        </Link>
                    </Box>
                    {renderMenu}
                </Toolbar>
            </AppBar>
        </Box>
    );
}