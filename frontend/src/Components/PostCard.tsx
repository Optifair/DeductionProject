import React, {useState} from 'react';
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
    Snackbar,
    styled,
    Typography
} from '@mui/material';
import PostWindow from './PostWindow';
import {Stack} from "@mui/system";
import BackAdress from "../BackAdress";
import {useNavigate} from "react-router-dom";
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

export default function PostCard({id, title, content, userId, image, date, isAuth, userAvatar, userName}: Params) {
    const [open, setOpen] = React.useState(false);
    const [scroll, setScroll] = React.useState<DialogProps[ 'scroll' ]>('paper');
    const [authState, setAuthState] = useState<boolean>(Boolean(Number(isAuth)));

    const navigate = useNavigate();

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
        const isAdded = Boolean(await fetchMarkPost())
        if (isAdded) {
            handleToastClick();
        } else {
            navigate("/auth")
        }
    }


    return (
        <Card sx={{maxWidth: '50%', minWidth: 500, borderRadius: '25px', width: '100%'}}>
            <Box padding={'10px'}>
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
                <Dialog
                    open={open}
                    onClose={handleClose}
                    maxWidth={'md'}
                    PaperProps={{
                        style: {
                            backgroundImage: 'none',
                            background: 'transparent',
                            width: '80%'
                        },
                    }}
                >
                    <DialogContent dividers={scroll === 'paper'} style={{padding: '0px 0px 0px 0px'}}>
                        <PostWindow id={id} title={title} content={content} userId={userId}
                                    image={image} date={date} isAuth={isAuth} userAvatar={userAvatar}
                                    userName={userName}/>
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
                    <Box margin={'auto'} maxWidth="70%" paddingBottom={'20px'}>
                        <CardMedia
                            component="img"
                            image={image}
                        />
                    </Box>
                </Link>
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






