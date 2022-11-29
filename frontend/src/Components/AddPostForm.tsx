import React, {useState} from "react";
import {alpha, Button, IconButton, Snackbar, Stack, styled, Typography} from "@mui/material";
import InputBase from "@mui/material/InputBase";
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

type Props = {
    updateTemplate: () => void;
}

export default function AddPostForm({updateTemplate}: Props) {

    const [title, setTitle] = useState('');
    const [content, setContent] = useState('');
    const [image, setImage] = useState('');
    const navigate = useNavigate();

    const handleTitleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setTitle(event.target.value);
    };

    const handleContentChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setContent(event.target.value);
    };

    const handleImageChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setImage(event.target.value);
    };

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

    async function fetchAddPost() {
        var res = await fetch(`http://${BackAdress}/api/addPost`
            , {
                credentials: 'include',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                    'Access-Control-Allow-Credentials': 'true'
                },
                body: JSON.stringify({"title": title, "content": content, "image": image}),
            })
            .then((response) => {
                return response.json()
            })
            .then((data) => {
                return data
            });
        return res['auth'];
    }

    async function addPost() {
        if (content !== '' && image !== '' && title !== '') {
            const isAdded = Boolean(Number(await fetchAddPost()));
            if (isAdded) {
                updateTemplate();
                setContent('');
                setImage('');
                setTitle('');
            } else {
                navigate("/auth")
            }
        } else {
            handleClick();
        }
    }

    return (
        <div style={{
            width: '50%',
            borderRadius: '5px',
            backgroundColor: 'rgb(30, 30, 30)',
        }}>
            <Stack style={{
                margin: '2%',
                justifyContent: 'space-between',
                alignItems: 'center'
            }}
                   spacing={2}>
                <Typography variant="body1"> New Post </Typography>
                <InputDiv>
                    <InputBase onChange={handleTitleChange} multiline maxRows={2} placeholder={'Title'}
                               style={{width: '96%', marginLeft: '2%', marginRight: '2%'}}/>
                </InputDiv>
                <InputDiv>
                    <InputBase onChange={handleContentChange} multiline placeholder={'Content'}
                               style={{width: '96%', marginLeft: '2%', marginRight: '2%'}}/>
                </InputDiv>
                <InputDiv>
                    <InputBase onChange={handleImageChange} placeholder={'Image'}
                               style={{width: '96%', marginLeft: '2%', marginRight: '2%'}}/>
                </InputDiv>
                <Button onClick={addPost} style={{width: 'max-content'}}>
                    Publish
                </Button>
            </Stack>
            <Snackbar
                anchorOrigin={{
                    vertical: 'bottom',
                    horizontal: 'center',
                }}
                open={open}
                autoHideDuration={50}
                message="Input fields must be filled"
                action={
                    <React.Fragment>
                        <IconButton size="small" aria-label="close" color="inherit" onClick={handleClose}>
                            <CloseIcon></CloseIcon>
                        </IconButton>
                    </React.Fragment>
                }
            />
        </div>
    );
}






