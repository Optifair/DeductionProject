import {Avatar, Card, CardContent, CardHeader, Divider, IconButton, Snackbar, Typography} from '@mui/material';
import {red} from '@mui/material/colors';
import React, {useState} from "react";
import LikeIcon from '@mui/icons-material/Favorite';
import EmptyLikeIcon from '@mui/icons-material/FavoriteBorder';
import BackAdress from "../BackAdress";
import {useNavigate} from "react-router-dom";
import CloseIcon from '@mui/icons-material/Close';

type Params = {
    id: string;
    content: string;
    userId: string;
    liked: boolean;
    date: string;
    isAuth: boolean;
    avatar: string;
    userName: string;
}

export default function CommentCard({id, content, userId, liked, date, isAuth, avatar, userName}: Params) {
    const [likedState, setLikedState] = useState<boolean>(Boolean(Number(liked)));
    const [authState, setAuthState] = useState<boolean>(Boolean(Number(isAuth)));
    const navigate = useNavigate();

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

    async function fetchLikeComment() {
        let res = await fetch(`http://${BackAdress}/api/likeComment`
            , {
                credentials: 'include',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                    'Access-Control-Allow-Credentials': 'true'
                },
                body: JSON.stringify({"id": id, 'liked': likedState}),
            })
            .then((response) => {
                return response.json()
            })
            .then((data) => {
                return data
            });
        return res;
    }

    async function like() {
        let likeResult = await fetchLikeComment();
        if (!Boolean(Number(likeResult['auth']))) {
            navigate("/auth")
        } else if (!Boolean(Number(likeResult['owner']))) {
            handleClick();
        } else {
            setLikedState(!likedState);
        }
    }

    return (
        <Card>
            <CardHeader sx={{paddingBottom: '0px', fontSize: 'large'}}
                        avatar={
                            <Avatar sx={{bgcolor: red[500], height: 45, width: 45}} src={avatar}>
                            </Avatar>
                        }
                        action={
                            authState
                            &&
                            <IconButton onClick={like}>
                                {likedState
                                    ? <LikeIcon fontSize='large'/>
                                    : <EmptyLikeIcon fontSize='large'/>
                                }
                            </IconButton>
                        }
                        title={
                            <Typography variant="h6">
                                {userName}
                            </Typography>
                        }
            />
            <CardContent sx={{paddingTop: '10px'}}>
                <Typography variant="body1">
                    {content}
                </Typography>
            </CardContent>
            <Divider/>

            <Snackbar
                anchorOrigin={{
                    vertical: 'bottom',
                    horizontal: 'center',
                }}
                open={open}
                autoHideDuration={50}
                message="Only the owner of the post can like."
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






