import React, {useState} from "react";
import {alpha, Avatar, IconButton, Snackbar, Stack, styled} from "@mui/material";
import InputBase from "@mui/material/InputBase";
import SendIcon from '@mui/icons-material/Send';
import BackAdress from "../BackAdress";
import {useNavigate} from "react-router-dom";
import CloseIcon from "@mui/icons-material/Close";

const InputDiv = styled('div')(({theme}) => ({
    position: 'relative',
    width: '100%',
    borderRadius: theme.shape.borderRadius,
    backgroundColor: alpha(theme.palette.common.white, 0.15),
    '&:hover': {
        backgroundColor: alpha(theme.palette.common.white, 0.25),
    }

}));

type Params = {
    post_id: string;
    userAvatar: string;
    userName: string;
    updateTemplate: () => void;
}

export default function AddCommentForm({post_id, userAvatar, userName, updateTemplate}: Params) {

    const [content, setContent] = useState('');
    const navigate = useNavigate();


    const handleContentChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setContent(event.target.value);
    };

    const [open, setOpen] = React.useState(false);

    const handleClick = () => {
        setOpen(true);
    };

    const handleClose = (event?: React.SyntheticEvent | Event, reason?: string) => {
        if (reason === 'clickaway') {
            return;
        }

        setOpen(false);
    };

    async function fetchAddComment() {
        var res = await fetch(`http://${BackAdress}/api/addComment`
            , {
                credentials: 'include',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                    'Access-Control-Allow-Credentials': 'true'
                },
                body: JSON.stringify({"post_id": post_id, "content": content}),
            })
            .then((response) => {
                return response.json()
            })
            .then((data) => {
                return data
            });
        return res['auth'];
    }

    async function addComment() {
        if (content !== '') {
            const isAdded = Boolean(Number(await fetchAddComment()));
            if (isAdded) {
                updateTemplate();
                setContent('');
            } else {
                navigate("/auth")
            }
        } else {
            handleClick();
        }
    }

    return (
        <Stack style={{width: '95%', justifyContent: 'space-between'}} spacing={2} direction={"row"}>
            {
                <Avatar src={userAvatar}> {userName[0]} </Avatar>
            }
            <InputDiv>
                <InputBase onChange={handleContentChange} multiline placeholder={'Comment'}
                           style={{width: '96%', marginLeft: '2%', marginRight: '2%'}}/>
            </InputDiv>
            <IconButton onClick={addComment}>
                <SendIcon fontSize='large'/>
            </IconButton>
            <Snackbar
                anchorOrigin={{
                    vertical: 'bottom',
                    horizontal: 'center',
                }}
                open={open}
                autoHideDuration={1500}
                onClose={handleClose}
                message="Input fields must be filled"
                action={
                    <React.Fragment>
                        <IconButton size="small" aria-label="close" color="inherit" onClick={handleClose}>
                            <CloseIcon></CloseIcon>
                        </IconButton>
                    </React.Fragment>
                }
            />
        </Stack>
    );
}






