import React, {useState} from 'react';
import {IconButton, Snackbar} from "@mui/material";
import CloseIcon from "@mui/icons-material/Close";

class Toast extends React.Component {
}

export default function ToastBar() {

    const [message, setMessage] = useState('');
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

    return (
        <Snackbar
            anchorOrigin={{
                vertical: 'bottom',
                horizontal: 'center',
            }}
            open={open}
            autoHideDuration={50}
            onClose={handleClose}
            message={message}
            action={
                <React.Fragment>
                    <IconButton size="small" aria-label="close" color="inherit" onClick={handleClose}>
                        <CloseIcon></CloseIcon>
                    </IconButton>
                </React.Fragment>
            }
        />
    );
}
