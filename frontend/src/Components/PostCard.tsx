import React from 'react';
import SubscribeIcon from '@mui/icons-material/Bookmarks';
import {
    Avatar,
    Box,
    Card,
    CardContent,
    CardHeader,
    CardMedia,
    Dialog,
    DialogContent,
    DialogProps,
    IconButton,
    IconButtonProps,
    Link,
    styled,
    Typography
} from '@mui/material';
import {red} from '@mui/material/colors';
import PostWindow from './PostWindow';
import {Stack} from "@mui/system";
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


interface ExpandMoreProps extends IconButtonProps {
    expand: boolean;
}

const ExpandMore = styled((props: ExpandMoreProps) => {
    const {expand, ...other} = props;
    return <IconButton {...other} />;
})(({theme, expand}) => ({
    transform: !expand ? 'rotate(0deg)' : 'rotate(180deg)',
    marginLeft: 'auto',
    transition: theme.transitions.create('transform', {
        duration: theme.transitions.duration.shortest,
    }),
}));

export default function PostCard({id, title, content, userId, image, date}: Params) {
    const [open, setOpen] = React.useState(false);
    const [scroll, setScroll] = React.useState<DialogProps[ 'scroll' ]>('paper');

    const navigate = useNavigate();

    const handleClickOpen = (scrollType: DialogProps[ 'scroll' ]) => () => {
        setOpen(true);
        setScroll(scrollType);
    };

    const handleClose = () => {
        setOpen(false);
    };

    const [expanded, setExpanded] = React.useState(false);

    const handleExpandClick = () => {
        setExpanded(!expanded);
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
        <Card sx={{maxWidth: '50%', minWidth: 500, borderRadius: '25px'}}>
            <Box padding={'10px'}>
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
                <Dialog
                    open={open}
                    onClose={handleClose}
                    maxWidth={'md'}
                    PaperProps={{
                        style: {
                            backgroundImage: 'none',
                            background: 'transparent'
                        },
                    }}
                >
                    <DialogContent dividers={scroll === 'paper'} style={{padding: '0px 0px 0px 0px'}}
                    >
                        <PostWindow id={id} title={title} content={content} userId={userId} image={image} date={date}/>
                    </DialogContent>
                </Dialog>
                <Link onClick={handleClickOpen('body')} underline='none' color={'whitesmoke'}>
                    <CardContent sx={{paddingTop: '5px'}}>
                        <Typography textAlign={'center'} variant="h5" paddingBottom={'10px'}>
                            {title}
                        </Typography>
                        <Typography variant="body1">
                            {content}
                        </Typography>
                    </CardContent>
                </Link>
                <Box margin={'auto'} maxWidth="70%" paddingBottom={'20px'}>
                    <CardMedia
                        component="img"
                        image={image}
                    />
                </Box>
            </Box>
        </Card>
    );
}






