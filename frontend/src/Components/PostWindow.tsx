import SubscribeIcon from '@mui/icons-material/Bookmarks';
import {
    Avatar,
    Box,
    Button,
    Card,
    CardContent,
    CardHeader,
    CardMedia,
    Divider,
    IconButton,
    Snackbar,
    Typography
} from '@mui/material';
import {Stack} from "@mui/system";
import React, {useEffect, useState} from "react";
import Comment from './Comment';
import BackAdress from "../BackAdress";
import {useNavigate} from "react-router-dom";
import AddCommentForm from "./AddCommentForm";
import CloseIcon from "@mui/icons-material/Close";


type Params = {
    id: string;
    title: string;
    content: string;
    userId: string;
    image: string;
    date: string;
    isAuth: boolean;
    userAvatar: string;
    userName: string;
}

export default function PostWindow({id, title, content, userId, image, date, isAuth, userName, userAvatar}: Params) {
    const [commentsTotal, setCommentsTotal] = useState(undefined);
    const [comments, setComments] = useState([]);
    const [commentsListSize, setCommentsListSize] = useState<number>(10);
    const [authState, setAuthState] = useState<boolean>(Boolean(Number(isAuth)));

    const navigate = useNavigate();

    const offset = 0;

    const [openToast, setOpenToast] = React.useState(false);
    const handleToastClick = () => {
        setOpenToast(true);
    };
    const handleToastClose = (event: React.SyntheticEvent | React.MouseEvent, reason?: string) => {
        if (reason === 'clickaway') {
            return;
        }
        setOpenToast(false);
    };

    async function fetchComments(Size: any, offset: any) {
        const res = await fetch(
            `http://${BackAdress}/api/comments?post_id=${id}&limit=${Size}&offset=${offset}`
        );

        return await res.json();
    }

    function updateTemplate() {
        fetchComments(commentsListSize, offset).then((comments) => {
            setCommentsTotal(comments.count);
            setComments(comments.comments);
        });
    }

    useEffect(() => {
        updateTemplate();
    }, [commentsListSize, offset])

    async function loadNewComments() {
        setCommentsListSize((commentsListSize) => commentsListSize + 10);
    }

    async function fetchMarkPost() {
        let res = await fetch(`http://${BackAdress}/api/markPost`
            , {
                credentials: 'include',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                    'Access-Control-Allow-Credentials': 'true'
                },
                body: JSON.stringify({"id": id}),
            })
            .then((response) => {
                return response.json()
            })
            .then((data) => {
                return data
            });
        return res;
    }

    async function MarkPost() {
        const isAdded = Boolean(await fetchMarkPost());
        if (isAdded) {
            handleToastClick();
        } else {
            navigate("/auth")
        }
    }

    return (
        <Card sx={{borderRadius: '25px'}}>
            <Box>
                <CardHeader sx={{paddingBottom: '0px', fontSize: 'large'}}
                            avatar={
                                <Avatar src={userAvatar}> {userName[0]} </Avatar>
                            }
                            action={
                                authState
                                &&
                                <IconButton aria-label="settings" onClick={MarkPost}>
                                    <SubscribeIcon fontSize='large'/>
                                </IconButton>
                            }
                            title={
                                <Stack>
                                    <Typography variant="h6">
                                        {userName}
                                    </Typography>
                                    <Typography variant="h6" fontSize={'medium'} color={'gray'}>
                                        {date}
                                    </Typography>
                                </Stack>
                            }
                />
                <CardContent sx={{paddingTop: '5px'}}>
                    <Typography textAlign={'center'} variant="h5" paddingBottom={'10px'}>
                        {title}
                    </Typography>
                    <Typography variant="body1">
                        {content}
                    </Typography>
                </CardContent>
                <Box margin={'auto'} maxWidth="70%" paddingBottom={'20px'}>
                    <CardMedia
                        component="img"
                        image={image}
                    />
                </Box>
                <Divider/>
                <CardContent sx={{padding: '0'}}>
                    <Stack>
                        {comments.map(function ({id, user_id, content, date, liked, avatar, name}) {
                            return <Comment key={id} id={id} content={content} userId={user_id} liked={liked}
                                            date={date} isAuth={isAuth} userAvatar={avatar} userName={name}/>
                        })}
                    </Stack>
                    <Stack spacing={4} alignItems={'center'} paddingTop={'20px'}>
                        {
                            comments.length > 1
                            &&
                            <Button onClick={loadNewComments} variant="outlined"
                                    style={{border: '1px solid ghostwhite', color: 'ghostwhite'}}>More comments</Button>
                        }

                        {
                            authState
                            &&
                            <AddCommentForm updateTemplate={updateTemplate} post_id={id} userAvatar={userAvatar}
                                            userName={userName}></AddCommentForm>
                        }
                    </Stack>
                </CardContent>
                <Snackbar
                    anchorOrigin={{
                        vertical: 'bottom',
                        horizontal: 'center',
                    }}
                    open={openToast}
                    autoHideDuration={50}
                    message={'Marked'}
                    action={
                        <React.Fragment>
                            <IconButton size="small" aria-label="close" color="inherit" onClick={handleToastClose}>
                                <CloseIcon></CloseIcon>
                            </IconButton>
                        </React.Fragment>
                    }
                />
            </Box>
        </Card>
    );
}






