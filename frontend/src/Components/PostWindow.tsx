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
    Typography
} from '@mui/material';
import {red} from '@mui/material/colors';
import {Stack} from "@mui/system";
import React, {useEffect, useState} from "react";
import Comment from './Comment';
import BackAdress from "../BackAdress";
import {useNavigate} from "react-router-dom";


type Params = {
    id: string;
    title: string;
    content: string;
    userId: string;
    image: string;
    date: string;
}

export default function PostWindow({id, title, content, userId, image, date}: Params) {
    const [commentsTotal, setCommentsTotal] = useState(undefined);
    const [comments, setComments] = useState([]);
    const [commentsListSize, setCommentsListSize] = useState<number>(10);

    const navigate = useNavigate();

    const offset = 0;

    async function fetchComments(Size: any, offset: any) {
        const res = await fetch(
            `http://${BackAdress}/api/comments?post_id=${id}&limit=${Size}&offset=${offset}`
        );

        return await res.json();
    }

    useEffect(() => {
        fetchComments(commentsListSize, offset).then((comments) => {
            setCommentsTotal(comments.count);
            setComments(comments.comments);
            console.log(comments);
        });
    }, [commentsListSize, offset])

    async function loadNewComments() {
        setCommentsListSize((commentsListSize) => commentsListSize + 10);
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

    async function fetchMarkPost() {
        let res = await fetch(`http://${BackAdress}/api/MarkPost`
            , {
                credentials: 'include',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                    'Access-Control-Allow-Credentials': 'true'
                },
                body: JSON.stringify({"id": id}),
            })
        return res;
    }

    async function MarkPost() {
        const test: any = await fetchAuth();
        if (!test) {
            navigate("/auth")
        } else {
            await fetchMarkPost()
        }
    }

    return (
        <Card sx={{borderRadius: '25px'}}>
            <Box>
                <CardHeader sx={{paddingBottom: '0px', fontSize: 'large'}}
                            avatar={
                                <Avatar sx={{bgcolor: red[500], height: 60, width: 60}}
                                        src='https://bit.ly/broken-link'>
                                </Avatar>
                            }
                            action={
                                <IconButton aria-label="settings" onClick={MarkPost}>
                                    <SubscribeIcon fontSize='large'/>
                                </IconButton>
                            }
                            title={
                                <Stack>
                                    <Typography variant="h6">
                                        {userId}
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
                        {comments.map(function ({id, user_id, content, date}) {
                            return <Comment key={id} id={id} content={content} userId={user_id} date={date}/>
                        })}
                    </Stack>
                    <Stack spacing={4} alignItems={'center'} paddingTop={'20px'}>
                        <Button onClick={loadNewComments} variant="outlined"
                                style={{border: '1px solid ghostwhite', color: 'ghostwhite'}}>More comments</Button>
                    </Stack>

                </CardContent>
            </Box>
        </Card>
    );
}






